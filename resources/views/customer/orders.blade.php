@extends('layouts.customer')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-shopping-bag text-blue-600"></i> Pesanan Saya
    </h1>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Order Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Order ID</p>
                                <p class="font-bold text-lg">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal</p>
                                <p class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total</p>
                                <p class="font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
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
                        </div>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-box text-gray-400"></i>
                                <span class="text-gray-600">{{ $order->orderItems->count() }} item</span>
                            </div>
                            <a href="{{ route('customer.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-800 font-semibold">
                                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Anda belum memiliki pesanan</p>
            <a href="{{ route('shop') }}" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@foreach($orders as $order)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Order Header -->
        <div class="bg-gray-50 px-6 py-4 border-b">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-600">Order ID</p>
                    <p class="font-bold text-lg">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <!-- Payment Status Badge -->
                    @if($order->payment_status === 'pending')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 block mb-2">
                            <i class="fas fa-clock"></i> Belum Bayar
                        </span>
                    @else
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800 block mb-2">
                            <i class="fas fa-check-circle"></i> Lunas
                        </span>
                    @endif
                    
                    <!-- Order Status Badge -->
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
            </div>
        </div>

        <!-- Order Items Preview -->
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-box text-gray-400"></i>
                    <span class="text-gray-600">{{ $order->orderItems->count() }} item</span>
                </div>
                <div class="flex gap-2">
                    @if($order->payment_status === 'pending')
                        <a href="{{ route('customer.payment', $order) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold transition text-sm">
                            <i class="fas fa-qrcode"></i> Bayar
                        </a>
                    @endif
                    <a href="{{ route('customer.orders.show', $order) }}" 
                       class="text-blue-600 hover:text-blue-800 font-semibold">
                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection