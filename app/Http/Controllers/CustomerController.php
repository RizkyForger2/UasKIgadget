<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Landing Page (Public)
    public function index()
    {
        $featuredProducts = Product::where('stock', '>', 0)
            ->with(['category', 'approvedReviews'])
            ->latest()
            ->take(8)
            ->get();
        
        $categories = Category::withCount('products')->get();
        
        return view('customer.index', compact('featuredProducts', 'categories'));
    }

    // Shop Page (Public)
    public function shop(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        
        $products = Product::query()
            ->with(['category', 'approvedReviews'])
            ->when($search, function($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('brand', 'LIKE', "%{$search}%");
            })
            ->when($category, function($query, $category) {
                return $query->where('category_id', $category);
            })
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();
        
        $categories = Category::all();
        
        return view('customer.shop', compact('products', 'categories', 'search', 'category'));
    }

    // Product Detail (Public)
    public function show(Product $product)
    {
        $product->load(['category', 'approvedReviews']);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();
        
        return view('customer.show', compact('product', 'relatedProducts'));
    }

    // Customer Dashboard
    public function dashboard()
    {
        $user = auth()->user();
        $recentOrders = Order::where('customer_email', $user->email)
            ->latest()
            ->take(5)
            ->get();
        
        return view('customer.dashboard', compact('recentOrders'));
    }

    // Cart
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.cart', compact('cart', 'total'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        
        $quantity = $request->input('quantity', 1);
        
        if ($quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
                'stock' => $product->stock,
            ];
        }
        
        session()->put('cart', $cart);
        
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    // Checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong!');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|max:20',
            'customer_address' => 'required',
        ]);
        
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong!');
        }
        
        DB::beginTransaction();
        
        try {
            $totalAmount = 0;
            
            // Validasi stok
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi!");
                }
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            // Buat order
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);
            
            // Buat order items dan kurangi stok
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                
                $product->decrement('stock', $item['quantity']);
            }
            
            // Generate QR Code
            $qrData = "ORDER-" . $order->id . "|" . $totalAmount . "|" . $validated['customer_email'];
            
            $qrCode = QrCode::size(300)
                ->margin(2)
                ->generate($qrData);
            
            $qrPath = 'qrcodes/order-' . $order->id . '.svg';
            Storage::disk('public')->put($qrPath, $qrCode);
            
            $order->update([
                'qr_code' => $qrPath
            ]);
            
            DB::commit();
            
            // Kosongkan cart
            session()->forget('cart');
            
            return redirect()->route('customer.payment', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan scan QR Code untuk pembayaran.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // Payment
    public function payment(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->customer_email !== auth()->user()->email) {
            abort(403, 'Unauthorized action.');
        }
        
        // Jika sudah dibayar, redirect ke order detail
        if ($order->isPaid()) {
            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Pesanan sudah dibayar!');
        }
        
        return view('customer.payment', compact('order'));
        dd([
        'order_id' => $order->id,
        'user_email' => auth()->user()->email,
        'order_email' => $order->customer_email,
        'qr_code' => $order->qr_code
    ]);
    }

    public function confirmPayment(Request $request, Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->customer_email !== auth()->user()->email) {
            abort(403, 'Unauthorized action.');
        }
        
        // Update status pembayaran
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now()
        ]);
        
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.');
    }

    // My Orders
    public function myOrders()
    {
        $user = auth()->user();
        $orders = Order::where('customer_email', $user->email)
            ->latest()
            ->paginate(10);
        
        return view('customer.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->customer_email !== auth()->user()->email) {
            abort(403, 'Unauthorized action.');
        }
        
        $order->load('orderItems.product');
        return view('customer.order-detail', compact('order'));
    }
}