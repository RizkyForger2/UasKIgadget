<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // PASTIKAN INI ADA!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::query()
            ->when($search, function($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('brand', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
        
        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Category::all(); // TAMBAHKAN INI!
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'brand' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id', // TAMBAHKAN INI!
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'processor' => 'nullable|max:255',
            'ram' => 'nullable|max:255',
            'storage' => 'nullable|max:255',
            'camera' => 'nullable|max:255',
            'battery' => 'nullable|max:255',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        // Load relationships
        $product->load(['category', 'approvedReviews']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all(); // TAMBAHKAN INI!
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'brand' => 'required|max:255',
            'category_id' => 'nullable|exists:categories,id', // TAMBAHKAN INI!
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'processor' => 'nullable|max:255',
            'ram' => 'nullable|max:255',
            'storage' => 'nullable|max:255',
            'camera' => 'nullable|max:255',
            'battery' => 'nullable|max:255',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}