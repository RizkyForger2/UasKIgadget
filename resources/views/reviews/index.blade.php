@extends('layouts.app')

@section('title', 'Kelola Review')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">
        <i class="fas fa-star text-yellow-500"></i> Kelola Review & Rating
    </h1>
    <p class="text-gray-600">Setujui atau hapus review dari customer</p>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <div class="flex gap-4">
        <a href="?status=all" 
           class="px-4 py-2 rounded-lg {{ request('status', 'all') == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
            <i class="fas fa-list"></i> Semua ({{ $reviews->total() }})
        </a>
        <a href="?status=pending" 
           class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
            <i class="fas fa-clock"></i> Pending
        </a>
        <a href="?status=approved" 
           class="px-4 py-2 rounded-lg {{ request('status') == 'approved' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
            <i class="fas fa-check-circle"></i> Disetujui
        </a>
    </div>
</div>

<div class="space-y-4">
    @forelse($reviews as $review)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <!-- Customer Info -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $review->customer_name }}</p>
                            <p class="text-sm text-gray-500">{{ $review->customer_email }}</p>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : ' opacity-30' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600">({{ $review->rating }}/5)</span>
                    </div>

                    <!-- Comment -->
                    <p class="text-gray-700 mb-3">{{ $review->comment }}</p>

                    <!-- Product Info -->
                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                        @if($review->product->image)
                            <img src="{{ asset('storage/' . $review->product->image) }}" 
                                 alt="{{ $review->product->name }}"
                                 class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-gray-400"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-sm">{{ $review->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $review->product->brand }}</p>
                        </div>
                    </div>

                    <!-- Date -->
                    <p class="text-xs text-gray-500 mt-3">
                        <i class="fas fa-clock"></i> {{ $review->created_at->diffForHumans() }}
                    </p>
                </div>
                <!-- Actions -->
                <div class="flex flex-col gap-2 ml-4">
                    @if(!$review->is_approved)
                        <form action="{{ route('reviews.approve', $review) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition whitespace-nowrap">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                        </form>
                    @else
                        <span class="bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm font-semibold">
                            <i class="fas fa-check-circle"></i> Disetujui
                        </span>
                    @endif
                    
                    <form action="{{ route('reviews.destroy', $review) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition whitespace-nowrap">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada review</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $reviews->links() }}
</div>
@endsection