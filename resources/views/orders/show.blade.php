@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-gray-600 text-sm">{{ $order->created_at->format('d F Y, H:i') }}</p>
            </div>
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

        <!-- Data Pelanggan -->
        <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-3">
                <i class="fas fa-user text-blue-600"></i> Data Pelanggan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Nama:</p>
                    <p class="font-semibold">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Email:</p>
                    <p class="font-semibold">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Telepon:</p>
                    <p class="font-semibold">{{ $order->customer_phone }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Alamat:</p>
                    <p class="font-semibold">{{ $order->customer_address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4">
            <i class="fas fa-shopping-bag text-blue-600"></i> Detail Produk
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-12 h-12 object-cover rounded">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $item->product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->product->brand }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-right font-semibold">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-bold">Total:</td>
                        <td class="px-4 py-3 text-right text-xl font-bold text-blue-600">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Aksi -->
    <div class="flex gap-4">
        <a href="{{ route('orders.edit', $order) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-edit"></i> Edit Status
        </a>
        <form action="{{ route('orders.destroy', $order) }}" 
              method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')"
              class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-trash"></i> Hapus Pesanan
            </button>
        </form>
        <a href="{{ route('orders.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection