@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Product Detail -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Gambar Produk -->
            <div>
                <div class="bg-gray-100 rounded-lg overflow-hidden h-96 flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-contain">
                    @else
                        <i class="fas fa-mobile-alt text-9xl text-gray-300"></i>
                    @endif
                </div>
            </div>

            <!-- Info Produk -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        {{ $product->brand }}
                    </span>
                    @if($product->category)
                        <span class="inline-block bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">
                            <i class="fas {{ $product->category->icon ?? 'fa-tag' }}"></i> {{ $product->category->name }}
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                
                <div class="mb-6">
                    <p class="text-4xl font-bold text-blue-600 mb-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <p class="text-sm">
                        <i class="fas fa-box"></i> 
                        Stok: 
                        <span class="font-semibold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock }} unit
                        </span>
                    </p>
                </div>

                <!-- Average Rating Display -->
                <div class="mb-6 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="flex text-yellow-400 text-xl">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($product->averageRating()) ? '' : ' opacity-30' }}"></i>
                            @endfor
                        </div>
                        <span class="font-bold text-gray-800">{{ number_format($product->averageRating(), 1) }}</span>
                        <span class="text-sm text-gray-600">({{ $product->approvedReviews->count() }} review)</span>
                    </div>
                </div>

                <!-- Spesifikasi -->
                <div class="border-t border-b py-4 mb-6 space-y-2">
                    <h3 class="font-bold text-gray-800 mb-3">Spesifikasi:</h3>
                    @if($product->processor)
                        <div class="flex">
                            <i class="fas fa-microchip w-6 text-blue-600"></i>
                            <span class="text-gray-600 w-24">Processor:</span>
                            <span class="font-semibold">{{ $product->processor }}</span>
                        </div>
                    @endif
                    @if($product->ram)
                        <div class="flex">
                            <i class="fas fa-memory w-6 text-blue-600"></i>
                            <span class="text-gray-600 w-24">RAM:</span>
                            <span class="font-semibold">{{ $product->ram }}</span>
                        </div>
                    @endif
                    @if($product->storage)
                        <div class="flex">
                            <i class="fas fa-hdd w-6 text-blue-600"></i>
                            <span class="text-gray-600 w-24">Storage:</span>
                            <span class="font-semibold">{{ $product->storage }}</span>
                        </div>
                    @endif
                    @if($product->camera)
                        <div class="flex">
                            <i class="fas fa-camera w-6 text-blue-600"></i>
                            <span class="text-gray-600 w-24">Kamera:</span>
                            <span class="font-semibold">{{ $product->camera }}</span>
                        </div>
                    @endif
                    @if($product->battery)
                        <div class="flex">
                            <i class="fas fa-battery-full w-6 text-blue-600"></i>
                            <span class="text-gray-600 w-24">Baterai:</span>
                            <span class="font-semibold">{{ $product->battery }}</span>
                        </div>
                    @endif
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-2">Deskripsi:</h3>
                    <p class="text-gray-600 text-sm">{{ $product->description }}</p>
                </div>

                <!-- Tombol Aksi -->
                <div class="space-y-3">
                    <a href="{{ route('products.edit', $product) }}" 
                       class="block bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-4 rounded-lg text-center">
                        <i class="fas fa-edit"></i> Edit Produk
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-lg">
                            <i class="fas fa-trash"></i> Hapus Produk
                        </button>
                    </form>
                    <a href="{{ route('products.index') }}" 
                       class="block bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-lg text-center">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- TAMBAHAN BARU: REVIEWS SECTION DI BAWAH INI -->
    <!-- ========================================= -->
    
    <!-- Reviews Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">
            <i class="fas fa-star text-yellow-500"></i> Review & Rating
        </h3>

        <!-- Average Rating -->
        <div class="flex items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="text-center">
                <p class="text-4xl font-bold text-gray-800">{{ number_format($product->averageRating(), 1) }}</p>
                <div class="flex text-yellow-400 text-xl">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($product->averageRating()) ? '' : ' opacity-30' }}"></i>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ $product->approvedReviews->count() }} review</p>
            </div>
            <div class="flex-1">
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $product->approvedReviews->where('rating', $i)->count();
                        $percentage = $product->approvedReviews->count() > 0 
                            ? ($count / $product->approvedReviews->count()) * 100 
                            : 0;
                    @endphp
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm w-8">{{ $i }} <i class="fas fa-star text-yellow-400 text-xs"></i></span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Review List -->
        <div class="space-y-4 mb-6">
            <h4 class="text-xl font-bold text-gray-800 mb-3">Semua Review</h4>
            @forelse($product->approvedReviews as $review)
                <div class="border-b pb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $review->customer_name }}</p>
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : ' opacity-30' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm ml-12">{{ $review->comment }}</p>
                    <p class="text-xs text-gray-500 mt-2 ml-12">
                        <i class="fas fa-clock"></i> {{ $review->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-comments text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">Belum ada review untuk produk ini. Jadilah yang pertama!</p>
                </div>
            @endforelse
        </div>

        <!-- Add Review Form -->
        <div class="border-t pt-6">
            <h4 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-pen"></i> Tulis Review Anda
            </h4>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_name') border-red-500 @enderror" 
                               placeholder="Nama lengkap Anda" required>
                        @error('customer_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('customer_email') border-red-500 @enderror" 
                               placeholder="email@example.com" required>
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
                                <input type="radio" name="rating" value="{{ $i }}" 
                                       class="hidden rating-input" 
                                       {{ old('rating') == $i ? 'checked' : '' }} required>
                                <i class="fas fa-star text-3xl text-gray-300 rating-star hover:text-yellow-400 transition"></i>
                            </label>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Komentar * (min. 10 karakter)</label>
                    <textarea name="comment" rows="4" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 @error('comment') border-red-500 @enderror" 
                              placeholder="Bagikan pengalaman Anda dengan produk ini..." 
                              required>{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-paper-plane"></i> Kirim Review
                </button>
            </form>
        </div>
    </div>
    <!-- ========================================= -->
    <!-- AKHIR TAMBAHAN REVIEWS SECTION -->
    <!-- ========================================= -->

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

// Hover effect for rating stars
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

// Set initial state if old rating exists
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