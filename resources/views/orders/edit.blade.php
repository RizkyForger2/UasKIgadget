@extends('layouts.app')

@section('title', 'Edit Pesanan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            <i class="fas fa-edit text-blue-600"></i> Edit Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </h2>

        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Status -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Status Pesanan *</label>
                <select name="status" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror" 
                        required>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                        Diproses
                    </option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                        Selesai
                    </option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-info-circle"></i> Jika status diubah menjadi "Dibatalkan", stok produk akan dikembalikan.
                </p>
            </div>

            <!-- Data Pelanggan -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                    <i class="fas fa-user"></i> Data Pelanggan
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_name') border-red-500 @enderror" 
                               required>
                        @error('customer_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_email') border-red-500 @enderror" 
                               required>
                        @error('customer_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">No. Telepon *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_phone') border-red-500 @enderror" 
                               required>
                        @error('customer_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Alamat *</label>
                        <textarea name="customer_address" rows="3" 
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_address') border-red-500 @enderror" 
                                  required>{{ old('customer_address', $order->customer_address) }}</textarea>
                        @error('customer_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Detail Produk (Read Only) -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">
                    <i class="fas fa-shopping-bag"></i> Produk yang Dipesan
                </h3>
                <div class="space-y-2">
                    @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded border-2 border-blue-200">
                        <p class="font-bold text-gray-800">Total:</p>
                        <p class="text-xl font-bold text-blue-600">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-save"></i> Update Pesanan
                </button>
                <a href="{{ route('orders.show', $order) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection