@extends('layouts.customer')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('customer.orders') }}" class="hover:text-blue-600">Pesanan Saya</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </h1>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'processing' => 'Diproses',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $statusColors[$order->status] }}">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Tanggal Pemesanan</p>
                        <p class="font-semibold">{{ $order->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total Pembayaran</p>
                        <p class="font-bold text-blue-600 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            </div>
<!-- END Order Info -->

<!-- Payment Status Section (TAMBAHKAN INI) -->
@if($order->payment_status === 'pending')
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-lg p-6 shadow-md">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-3xl text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-yellow-900 text-lg mb-1">
                        Menunggu Pembayaran
                    </h3>
                    <p class="text-sm text-yellow-800 mb-2">
                        Pesanan Anda belum dibayar. Silakan scan QR Code untuk menyelesaikan pembayaran.
                    </p>
                    <p class="text-xs text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Selesaikan pembayaran dalam <strong>24 jam</strong>
                    </p>
                </div>
            </div>
            <a href="{{ route('customer.payment', $order) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg whitespace-nowrap">
                <i class="fas fa-qrcode mr-2"></i> Bayar Sekarang
            </a>
        </div>
    </div>
@elseif($order->payment_status === 'paid')
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-lg p-6 shadow-md">
        <div class="flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-3xl text-green-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-green-900 text-lg">Pembayaran Berhasil</h3>
                <p class="text-sm text-green-700">
                    Dibayar pada: <strong>{{ $order->paid_at->format('d F Y, H:i') }}</strong>
                </p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-truck mr-1"></i> Pesanan Anda sedang diproses
                </p>
            </div>
        </div>
    </div>
@endif

            <!-- Shipping Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-truck text-blue-600"></i> Informasi Pengiriman
                </h2>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Penerima</p>
                        <p class="font-semibold">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-semibold">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Alamat Pengiriman</p>
                        <p class="font-semibold">{{ $order->customer_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-shopping-bag text-blue-600"></i> Produk yang Dipesan
                </h2>

                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center gap-4 border-b pb-4">
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover rounded">
                                @else
                                    <i class="fas fa-mobile-alt text-2xl text-gray-400"></i>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h3 class="font-bold text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->product->brand }}</p>
                                <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            </div>

                            <div class="text-right">
                                <p class="text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                <p class="font-bold text-blue-600">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal ({{ $order->orderItems->count() }} item)</span>
                        <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span class="font-semibold text-green-600">GRATIS</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('customer.orders') }}" 
                       class="block bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    @if($order->status === 'pending' || $order->status === 'processing')
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle text-blue-600"></i>
                                Pesanan Anda sedang dalam proses. Kami akan mengirimkan konfirmasi via email.
                            </p>
                        </div>
                    @elseif($order->status === 'completed')
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-green-700">
                                <i class="fas fa-check-circle"></i>
                                Pesanan telah selesai. Terima kasih sudah berbelanja!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection