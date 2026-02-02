@extends('layouts.customer')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="mb-6 text-sm text-gray-600">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('shop') }}" class="hover:text-blue-600">Shop</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">{{ $product->name }}</span>
    </div>

    <!-- Product Detail -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <!-- Product Image -->
            <div>
                <div class="bg-gray-100 rounded-lg overflow-hidden h-96 flex items-center justify-center sticky top-24">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-contain">
                    @else
                        <i class="fas fa-mobile-alt text-9xl text-gray-300"></i>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div>
                <!-- Badges -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        {{ $product->brand }}
                    </span>
                    @if($product->category)
                        <span class="bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">
                            <i class="fas {{ $product->category->icon ?? 'fa-tag' }}"></i> {{ $product->category->name }}
                        </span>
                    @endif
                    @if($product->stock < 5 && $product->stock > 0)
                        <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                            <i class="fas fa-exclamation-triangle"></i> Stok Terbatas!
                        </span>
                    @elseif($product->stock == 0)
                        <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full">
                            <i class="fas fa-times-circle"></i> Stok Habis
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                
                <!-- Rating -->
                <div class="flex items-center gap-2 mb-6 p-3 bg-gray-50 rounded-lg">
                    <div class="flex text-yellow-400 text-xl">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($product->averageRating()) ? '' : ' opacity-30' }}"></i>
                        @endfor
                    </div>
                    <span class="font-bold text-gray-800">{{ number_format($product->averageRating(), 1) }}</span>
                    <span class="text-sm text-gray-600">({{ $product->approvedReviews->count() }} review)</span>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <p class="text-4xl font-bold text-blue-600 mb-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-box"></i> 
                        Stok tersedia: 
                        <span class="font-semibold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock }} unit
                        </span>
                    </p>
                </div>

                <!-- Specifications -->
                <div class="border-t border-b py-4 mb-6">
                    <h3 class="font-bold text-gray-800 mb-3 text-lg">Spesifikasi:</h3>
                    <div class="space-y-2">
                        @if($product->processor)
                            <div class="flex items-center">
                                <i class="fas fa-microchip w-8 text-blue-600"></i>
                                <span class="text-gray-600 w-32">Processor:</span>
                                <span class="font-semibold text-gray-800">{{ $product->processor }}</span>
                            </div>
                        @endif
                        @if($product->ram)
                            <div class="flex items-center">
                                <i class="fas fa-memory w-8 text-blue-600"></i>
                                <span class="text-gray-600 w-32">RAM:</span>
                                <span class="font-semibold text-gray-800">{{ $product->ram }}</span>
                            </div>
                        @endif
                        @if($product->storage)
                            <div class="flex items-center">
                                <i class="fas fa-hdd w-8 text-blue-600"></i>
                                <span class="text-gray-600 w-32">Storage:</span>
                                <span class="font-semibold text-gray-800">{{ $product->storage }}</span>
                            </div>
                        @endif
                        @if($product->camera)
                            <div class="flex items-center">
                                <i class="fas fa-camera w-8 text-blue-600"></i>
                                <span class="text-gray-600 w-32">Kamera:</span>
                                <span class="font-semibold text-gray-800">{{ $product->camera }}</span>
                            </div>
                        @endif
                        @if($product->battery)
                            <div class="flex items-center">
                                <i class="fas fa-battery-full w-8 text-blue-600"></i>
                                <span class="text-gray-600 w-32">Baterai:</span>
                                <span class="font-semibold text-gray-800">{{ $product->battery }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-2 text-lg">Deskripsi:</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Form -->
                @auth
                    @if(auth()->user()->isCustomer())
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="flex items-center gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah:</label>
                                        <input type="number" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}"
                                               class="w-24 px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg text-lg font-semibold transition flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart"></i>
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <i class="fas fa-times-circle text-3xl text-red-500 mb-2"></i>
                                <p class="text-red-700 font-semibold">Produk Sedang Habis</p>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <p class="text-gray-700 mb-3">Silakan login untuk membeli produk ini</p>
                        <div class="flex gap-2">
                            <a href="{{ route('login') }}" 
                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-center transition">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-center transition">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                    </div>
                @endauth

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="{{ route('shop') }}" 
                       class="text-blue-600 hover:text-blue-800 font-semibold">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Shop
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-star text-yellow-500"></i> Review & Rating
        </h3>

        <!-- Average Rating Summary -->
        <div class="flex items-center gap-6 mb-8 p-6 bg-gray-50 rounded-lg">
            <div class="text-center">
                <p class="text-5xl font-bold text-gray-800">{{ number_format($product->averageRating(), 1) }}</p>
                <div class="flex text-yellow-400 text-2xl mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($product->averageRating()) ? '' : ' opacity-30' }}"></i>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ $product->approvedReviews->count() }} review</p>
            </div>
            
            <div class="flex-1">
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $product->approvedReviews->where('rating', $i)->count();
                        $percentage = $product->approvedReviews->count() > 0 
                            ? ($count / $product->approvedReviews->count()) * 100 
                            : 0;
                    @endphp
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm w-12">{{ $i }} <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                        <div class="flex-1 bg-gray-200 rounded-full h-3">
                            <div class="bg-yellow-400 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Customer Reviews -->
        <div class="space-y-6 mb-8">
            <h4 class="text-xl font-bold text-gray-800">Review Customer</h4>
            @forelse($product->approvedReviews->take(5) as $review)
                <div class="border-b pb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $review->customer_name }}</p>
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : ' opacity-30' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700">{{ $review->comment }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-comments text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada review untuk produk ini</p>
                    <p class="text-sm text-gray-400">Jadilah yang pertama memberikan review!</p>
                </div>
            @endforelse
        </div>

        <!-- Add Review Form -->
        <div class="border-t pt-8">
            <h4 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-pen"></i> Tulis Review Anda
            </h4>
            
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama *</label>
                        <input type="text" 
                               name="customer_name" 
                               value="{{ old('customer_name', auth()->user()->name ?? '') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_name') border-red-500 @enderror" 
                               placeholder="Nama lengkap Anda" 
                               required>
                        @error('customer_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                        <input type="email" 
                               name="customer_email" 
                               value="{{ old('customer_email', auth()->user()->email ?? '') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_email') border-red-500 @enderror" 
                               placeholder="email@example.com" 
                               required>
                        @error('customer_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Rating *</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" 
                                       name="rating" 
                                       value="{{ $i }}" 
                                       class="hidden rating-input" 
                                       {{ old('rating') == $i ? 'checked' : '' }} 
                                       required>
                                <i class="fas fa-star text-4xl text-gray-300 rating-star hover:text-yellow-400 transition"></i>
                            </label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Komentar * (min. 10 karakter)</label>
                    <textarea name="comment" 
                              rows="4" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('comment') border-red-500 @enderror" 
                              placeholder="Bagikan pengalaman Anda dengan produk ini..." 
                              required>{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-paper-plane"></i> Kirim Review
                </button>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Produk Terkait</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <a href="{{ route('shop.show', $related) }}">
                        <div class="h-40 bg-gray-200 flex items-center justify-center">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" 
                                     alt="{{ $related->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-mobile-alt text-4xl text-gray-400"></i>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-2">{{ $related->name }}</h4>
                        <p class="text-blue-600 font-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                        <a href="{{ route('shop.show', $related) }}" 
                           class="block mt-3 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-center transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Rating Stars Interactive
document.querySelectorAll('.rating-input').forEach((input, index) => {
    input.addEventListener('change', function() {
        document.querySelectorAll('.rating-star').forEach((star, i) => {
            if (i <= index) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    });
});

// Hover effect
document.querySelectorAll('.rating-star').forEach((star, index) => {
    star.parentElement.addEventListener('mouseenter', function() {
        document.querySelectorAll('.rating-star').forEach((s, i) => {
            if (i <= index) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300');
            }
        });
    });
    
    star.parentElement.parentElement.addEventListener('mouseleave', function() {
        const checked = document.querySelector('.rating-input:checked');
        const checkedIndex = checked ? Array.from(document.querySelectorAll('.rating-input')).indexOf(checked) : -1;
        
        document.querySelectorAll('.rating-star').forEach((s, i) => {
            if (i <= checkedIndex) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    });
});

// Set initial state
const checkedRating = document.querySelector('.rating-input:checked');
if (checkedRating) {
    const index = Array.from(document.querySelectorAll('.rating-input')).indexOf(checkedRating);
    document.querySelectorAll('.rating-star').forEach((star, i) => {
        if (i <= index) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        }
    });
}
</script>
@endsection