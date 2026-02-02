@extends('layouts.customer')

@section('title', 'Pembayaran QRIS')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-credit-card text-blue-600"></i> Pembayaran
                </h1>
                <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-clock"></i> Menunggu Pembayaran
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm text-gray-600">Order ID</p>
                    <p class="font-bold text-lg">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Scan QR Code untuk Pembayaran</h2>
            <p class="text-gray-600 mb-6">Gunakan aplikasi mobile banking atau e-wallet Anda</p>

            <!-- QR Code Display -->
            <div class="flex justify-center mb-6">
                @if($order->qr_code)
                    <div class="bg-white p-6 rounded-lg shadow-lg border-4 border-blue-500">
                        <img src="{{ $order->qr_code }}" 
                             alt="QR Code Pembayaran" 
                             class="w-80 h-80 mx-auto"
                             onerror="this.src='https://via.placeholder.com/300?text=QR+Code+Error'">
                    </div>
                @else
                    <div class="bg-gray-100 p-6 rounded-lg border-4 border-gray-300">
                        <div class="w-80 h-80 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle text-6xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600">QR Code tidak tersedia</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Payment Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Cara Pembayaran:
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li>Buka aplikasi mobile banking atau e-wallet (GoPay, OVO, DANA, ShopeePay, dll)</li>
                    <li>Pilih menu <strong>Scan QR</strong> atau <strong>QRIS</strong></li>
                    <li>Scan QR Code di atas</li>
                    <li>Pastikan nominal: <strong class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></li>
                    <li>Konfirmasi pembayaran</li>
                    <li>Klik tombol "Saya Sudah Bayar" setelah pembayaran berhasil</li>
                </ol>
            </div>

            <!-- Supported Payment Methods -->
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-3">Metode pembayaran yang didukung:</p>
                <div class="flex justify-center items-center gap-4 flex-wrap">
                    <div class="bg-white p-3 rounded-lg shadow">
                        <i class="fas fa-wallet text-3xl text-purple-600"></i>
                        <p class="text-xs mt-1">GoPay</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow">
                        <i class="fas fa-mobile-alt text-3xl text-blue-600"></i>
                        <p class="text-xs mt-1">OVO</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow">
                        <i class="fas fa-mobile-alt text-3xl text-cyan-600"></i>
                        <p class="text-xs mt-1">DANA</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow">
                        <i class="fas fa-shopping-bag text-3xl text-orange-600"></i>
                        <p class="text-xs mt-1">ShopeePay</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow">
                        <i class="fas fa-university text-3xl text-gray-700"></i>
                        <p class="text-xs mt-1">Bank</p>
                    </div>
                </div>
            </div>

            <!-- Confirm Payment Button -->
            <form action="{{ route('customer.payment.confirm', $order) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-lg text-lg font-semibold transition shadow-lg hover:shadow-xl mb-3"
                        onclick="return confirm('Apakah Anda sudah menyelesaikan pembayaran?')">
                    <i class="fas fa-check-circle mr-2"></i> Saya Sudah Bayar
                </button>
            </form>

            <a href="{{ route('customer.orders.show', $order) }}" 
               class="block text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Pesanan
            </a>
        </div>

        <!-- Warning -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Penting:</strong> Silakan selesaikan pembayaran dalam <strong>24 jam</strong> 
                agar pesanan tidak dibatalkan otomatis.
            </p>
        </div>

        <!-- Debug Info (Hapus setelah berhasil) -->
        <div class="mt-4 p-4 bg-gray-100 rounded text-left">
            <p class="text-xs font-mono text-gray-600">Debug Info:</p>
            <p class="text-xs font-mono">Order ID: {{ $order->id }}</p>
            <p class="text-xs font-mono">QR Code: {{ $order->qr_code ? 'Ada' : 'Tidak Ada' }}</p>
            @if($order->qr_code)
                <p class="text-xs font-mono break-all">URL: {{ Str::limit($order->qr_code, 50) }}</p>
            @endif
        </div>
    </div>
</div>
@endsection