<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * List all reviews (admin purpose)
     */

    public function form(Request $request)
    {
        $review = null;

        if ($request->id) {
            $review = ProductReview::find($request->id);
        }

        return view('admin.review.add-review', compact('review'));
    }


    public function index()
    {
        $reviews = ProductReview::with('product:id,name')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
    /**
     * Store a new product review
     */
    public function store(Request $request)
    {
        \Log::info('Review store request received.', [
            'payload' => $request->all()
        ]);

        try {
            \Log::info('Validating review request...');

            $validated = $request->validate([
                'product_id'    => 'required|string',
                'customer_name' => 'required|string|max:255',
                'rating'        => 'required|integer|min:1|max:5',
                'review_title'  => 'nullable|string|max:255',
                'review_text'   => 'nullable|string',
                'images'        => 'nullable|array',
                'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            \Log::info('Review request validated successfully.', [
                'validated_data' => $validated
            ]);

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $imagePaths[] = $path;
                }
            }

            $validated['images'] = $imagePaths;
            $validated['ip_address'] = $request->ip();
            $validated['verified_purchase'] = false;

            \Log::info('Creating review entry...', [
                'final_data' => $validated
            ]);

            $review = ProductReview::create($validated);

            \Log::info('Review created successfully.', [
                'review_id' => $review->id
            ]);

            // ✔ Redirect to review list page
            return redirect()
                ->route('reviews.all')
                ->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {

            \Log::error('Error while creating review.', [
                'error_message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to submit review.')
                ->withInput();
        }
    }



    /**
     * Show a specific review
     */
    public function show($id)
    {
        $review = ProductReview::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }

    /**
     * Update review (admin – approve/reject)
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,approved,rejected',
            'review_title' => 'nullable|string|max:255',
            'review_text' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'images' => 'nullable|array',
        ]);

        $review = ProductReview::findOrFail($id);
        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully!',
            'data' => $review
        ]);
    }

    /**
     * Delete review
     */
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!'
        ]);
    }


    public function reviewWithCount(Request $request)
    {
        try {
            // Get product_id from query: ?product_id=xxx
            $productId = $request->query('product_id');

            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'product_id is required'
                ], 422);
            }

            // Fetch approved reviews only
            $reviews = ProductReview::where('product_id', $productId)
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->get();

            // dd($reviews);
            $totalReviews = $reviews->count();

            // ⭐ Rating Distribution (1–5)
            $ratingCounts = $reviews->groupBy('rating')->map->count();

            $distribution = [];
            for ($star = 5; $star >= 1; $star--) {
                $count = $ratingCounts[$star] ?? 0;
                $percentage = $totalReviews > 0
                    ? round(($count / $totalReviews) * 100)
                    : 0;

                $distribution[] = [
                    'stars' => $star,
                    'count' => $count,
                    'percentage' => $percentage,
                ];
            }

            // ⭐ Average Rating
            $averageRating = $totalReviews > 0
                ? round($reviews->avg('rating'), 1)
                : 0;

            // ⭐ Format each customer review
            $formattedReviews = $reviews->map(function ($r) {
                return [
                    'id'            => $r->id,
                    'userName'      => $r->customer_name,
                    'rating'        => $r->rating,
                    'date'          => $r->created_at ? $r->created_at->format('d M Y') : null,
                    'title'         => $r->review_title,
                    'comment'       => $r->review_text,
                    'verified'      => (bool) $r->verified_purchase,
                    'helpful'       => $r->helpful_count,
                    'images'        => is_string($r->images)
                        ? json_decode($r->images, true)
                        : ($r->images ?? []),
                    'customer_id'   => $r->customer_id,
                    'ip_address'    => $r->ip_address,
                ];
            });


            return response()->json([
                'success' => true,
                'message' => 'Product review details',
                'data' => [
                    'reviews'       => $formattedReviews,
                    'distribution'  => $distribution,
                    'averageRating' => $averageRating,
                    'totalReviews'  => $totalReviews,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
