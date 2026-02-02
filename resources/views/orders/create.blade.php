@extends('layouts.app')

@section('title', 'Buat Pesanan Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            <i class="fas fa-shopping-cart text-blue-600"></i> Buat Pesanan Baru
        </h2>

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
            @csrf
            
            <!-- Data Pelanggan -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                    <i class="fas fa-user"></i> Data Pelanggan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                               placeholder="John Doe" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                               placeholder="john@example.com" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">No. Telepon *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                               placeholder="08123456789" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Alamat *</label>
                        <textarea name="customer_address" rows="3" 
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                                  placeholder="Alamat lengkap pengiriman" required>{{ old('customer_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Produk -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                    <i class="fas fa-mobile-alt"></i> Pilih Produk
                </h3>

                <div id="productList">
                    <div class="product-item mb-4 p-4 border rounded-lg bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2">Produk</label>
                                <select name="products[0][product_id]" 
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 product-select" 
                                        required onchange="updatePrice(this, 0)">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->price }}"
                                                data-stock="{{ $product->stock }}">
                                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Jumlah</label>
                                <input type="number" 
                                       name="products[0][quantity]" 
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 quantity-input" 
                                       placeholder="1" 
                                       min="1" 
                                       value="1" 
                                       required
                                       onchange="calculateTotal()">
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            Subtotal: <span class="font-bold text-blue-600 subtotal">Rp 0</span>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addProduct()" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>

            <!-- Total -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-700">Total Pembayaran:</span>
                    <span class="text-2xl font-bold text-blue-600" id="totalAmount">Rp 0</span>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-check-circle"></i> Buat Pesanan
                </button>
                <a href="{{ route('orders.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let productIndex = 1;

function addProduct() {
    const productList = document.getElementById('productList');
    const newProduct = `
        <div class="product-item mb-4 p-4 border rounded-lg bg-gray-50 relative">
            <button type="button" onclick="removeProduct(this)" 
                    class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                <i class="fas fa-times-circle"></i>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Produk</label>
                    <select name="products[${productIndex}][product_id]" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 product-select" 
                            required onchange="updatePrice(this, ${productIndex})">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}">
                                {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jumlah</label>
                    <input type="number" 
                           name="products[${productIndex}][quantity]" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 quantity-input" 
                           placeholder="1" 
                           min="1" 
                           value="1" 
                           required
                           onchange="calculateTotal()">
                </div>
            </div>
            <div class="mt-2 text-sm text-gray-600">
                Subtotal: <span class="font-bold text-blue-600 subtotal">Rp 0</span>
            </div>
        </div>
    `;
    productList.insertAdjacentHTML('beforeend', newProduct);
    productIndex++;
}

function removeProduct(button) {
    button.closest('.product-item').remove();
    calculateTotal();
}

function updatePrice(select, index) {
    const option = select.options[select.selectedIndex];
    const price = parseFloat(option.dataset.price) || 0;
    const productItem = select.closest('.product-item');
    const quantityInput = productItem.querySelector('.quantity-input');
    const quantity = parseInt(quantityInput.value) || 1;
    const subtotal = price * quantity;
    
    productItem.querySelector('.subtotal').textContent = 
        'Rp ' + subtotal.toLocaleString('id-ID');
    
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.product-item').forEach(item => {
        const select = item.querySelector('.product-select');
        const option = select.options[select.selectedIndex];
        const price = parseFloat(option.dataset.price) || 0;
        const quantity = parseInt(item.querySelector('.quantity-input').value) || 0;
        const subtotal = price * quantity;
        
        item.querySelector('.subtotal').textContent = 
            'Rp ' + subtotal.toLocaleString('id-ID');
        total += subtotal;
    });
    
    document.getElementById('totalAmount').textContent = 
        'Rp ' + total.toLocaleString('id-ID');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection