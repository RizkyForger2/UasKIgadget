<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Menampilkan daftar semua pesanan
    public function index()
    {
        $orders = Order::with('orderItems.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Menampilkan form pembuatan pesanan baru
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    // Menyimpan pesanan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|max:20',
            'customer_address' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        
        try {
            $totalAmount = 0;
            
            // Hitung total amount
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Cek stok
                if ($product->stock < $item['quantity']) {
                    return back()->withErrors([
                        'error' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}"
                    ])->withInput();
                }
                
                $totalAmount += $product->price * $item['quantity'];
            }
            
            // Buat order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);
            
            // Buat order items dan kurangi stok
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);
                
                // Kurangi stok
                $product->decrement('stock', $item['quantity']);
            }
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Menampilkan detail pesanan
    public function show(Order $order)
    {
        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }

    // Menampilkan form edit pesanan
    public function edit(Order $order)
    {
        $order->load('orderItems.product');
        return view('orders.edit', compact('order'));
    }

    // Update status pesanan
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'customer_name' => 'sometimes|required|max:255',
            'customer_email' => 'sometimes|required|email|max:255',
            'customer_phone' => 'sometimes|required|max:20',
            'customer_address' => 'sometimes|required',
        ]);

        // Jika status berubah menjadi cancelled, kembalikan stok
        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil diupdate!');
    }

    // Hapus pesanan
    public function destroy(Order $order)
    {
        // Kembalikan stok jika pesanan belum completed
        if ($order->status !== 'completed') {
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}