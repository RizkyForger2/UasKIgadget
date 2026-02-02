<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|min:10',
        ]);

        $validated['product_id'] = $product->id;
        $validated['is_approved'] = false; // Pending approval

        Review::create($validated);

        return back()->with('success', 'Review Anda berhasil dikirim dan menunggu persetujuan!');
    }

   public function index(Request $request)
{
    $query = Review::with('product')->latest();
    
    // Filter by status
    if ($request->status == 'pending') {
        $query->where('is_approved', false);
    } elseif ($request->status == 'approved') {
        $query->where('is_approved', true);
    }
    
    $reviews = $query->paginate(20);
    
    return view('reviews.index', compact('reviews'));
}

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review berhasil disetujui!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review berhasil dihapus!');
    }

}