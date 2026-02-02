@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-credit-card text-blue-600"></i> Checkout
    </h1>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Checkout -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-user"></i> Informasi Pengiriman
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                            <input type="text" 
                                   name="customer_name" 
                                   value="{{ old('customer_name', auth()->user()->name) }}" 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_name') border-red-500 @enderror"
                                   required>
                            @error('customer_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                            <input type="email" 
                                   name="customer_email" 
                                   value="{{ old('customer_email', auth()->user()->email) }}" 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_email') border-red-500 @enderror"
                                   required>
                            @error('customer_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">No. Telepon *</label>
                            <input type="text" 
                                   name="customer_phone" 
                                   value="{{ old('customer_phone') }}" 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_phone') border-red-500 @enderror"
                                   placeholder="08123456789"
                                   required>
                            @error('customer_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Alamat Lengkap *</label>
                            <textarea name="customer_address" 
                                      rows="4" 
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_address') border-red-500 @enderror"
                                      placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"
                                      required>{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-shopping-bag"></i> Produk yang Dibeli
                    </h2>

                    <div class="space-y-4">
                        @foreach($cart as $item)
                            <div class="flex items-center gap-4 border-b pb-4">
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             alt="{{ $item['name'] }}"
                                             class="w-full h-full object-cover rounded">
                                    @else
                                        <i class="fas fa-mobile-alt text-gray-400"></i>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item['brand'] }}</p>
                                    <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-blue-600 font-bold">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
<div class="bg-blue-50 rounded-lg p-4 mb-4">
        <h4 class="font-bold text-gray-800 mb-2 flex items-center">
            <i class="fas fa-qrcode text-blue-600 mr-2"></i>
            Metode Pembayaran
        </h4>
        <p class="text-sm text-gray-700">
            Setelah checkout, Anda akan mendapatkan <strong>QR Code QRIS</strong> untuk pembayaran.
        </p>
        <div class="flex items-center gap-2 mt-3">
            <i class="fas fa-mobile-alt text-blue-600"></i>
            <span class="text-xs text-gray-600">GoPay, OVO, DANA, ShopeePay, Mobile Banking</span>
        </div>
    </div>

    <button type="submit" 
            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
        <i class="fas fa-qrcode mr-2"></i> Lanjut ke Pembayaran
    </button>

    <a href="{{ route('cart.index') }}" 
       class="block mt-3 text-center text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
    </a>

    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
        <p class="text-xs text-gray-600 text-center">
            <i class="fas fa-shield-alt text-green-600"></i>
            Transaksi Anda <strong>aman & terpercaya</strong>
        </p>
    </div>
</div>

                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold transition">
                        <i class="fas fa-check-circle"></i> Buat Pesanan
                    </button>

                    <a href="{{ route('cart.index') }}" 
                       class="block mt-3 text-center text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                    </a>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            Dengan melanjutkan, Anda menyetujui syarat dan ketentuan yang berlaku.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection