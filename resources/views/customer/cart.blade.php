@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-shopping-cart text-blue-600"></i> Keranjang Belanja
    </h1>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    @foreach($cart as $id => $item)
                        <div class="flex items-center gap-4 border-b pb-4 mb-4">
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
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
                                <p class="text-blue-600 font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <form action="{{ route('cart.update', $id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item['quantity'] }}" 
                                           min="1" 
                                           max="{{ $item['stock'] }}"
                                           class="w-20 px-2 py-1 border rounded"
                                           onchange="this.form.submit()">
                                </form>
                            </div>

                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-4">Ringkasan Belanja</h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total</span>
                            <span class="text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout') }}" 
                       class="block bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg text-center font-semibold transition">
                        <i class="fas fa-credit-card"></i> Checkout
                    </a>
                    <a href="{{ route('shop') }}" 
                       class="block mt-2 text-center text-blue-600 hover:text-blue-800">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Keranjang Anda kosong</p>
            <a href="{{ route('shop') }}" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection