<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Exception;


class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        try {
            $query = Product::latest();

            // Exclude product in edit mode
            if ($request->has('exclude') && !empty($request->exclude)) {
                $query->where('id', '!=', $request->exclude);
            }

            $products = $query->get();

            Log::info('Fetched product list successfully.', [
                'count' => $products->count(),
                'exclude' => $request->exclude ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product list fetched successfully.',
                'data' => $products
            ], 200);
        } catch (Exception $e) {

            Log::error('Failed to fetch products: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        return $this->saveProduct($request);
    }

    /**
     * Display the specified product.
     */

    public function show(string $id)
    {
        try {
            // Check: if $id looks like a UUID, fetch by id; else by slug
            if (Str::isUuid($id)) {
                $product = Product::findOrFail($id);
            } else {
                $product = Product::where('slug', $id)->firstOrFail();
            }

            Log::info('Product fetched successfully.', ['identifier' => $id]);

            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch product: ' . $e->getMessage(), ['identifier' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            return $this->saveProduct($request, $product);
        } catch (Exception $e) {
            Log::error('Failed to update product: ' . $e->getMessage(), ['product_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            Log::info('Product deleted successfully.', ['product_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Failed to delete product: ' . $e->getMessage(), ['product_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update product logic.
     */
    private function saveProduct(Request $request, ?Product $product = null)
    {
        try {
            // ✅ Validation Rules
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('products', 'sku')->ignore($product?->id),
                ],
                'base_price' => 'required|numeric|min:1',
                'discount_price' => 'nullable|numeric|min:0|lt:base_price',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'nullable|exists:categories,id',
                'status' => 'required|in:active,inactive,draft',
                'collection' => 'required',
                'tags' => 'nullable|string|max:255',
                'variants' => 'nullable|json',
                // 'product_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
                'description' => 'nullable|string'
            ]);

            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;

            while (Product::where('slug', $slug)->when($product, fn($q) => $q->where('id', '!=', $product->id))->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }


            // ✅ Prepare data
            $data = [
                'name' => $validated['name'],
                'slug' => $slug, // <-- use unique slug here
                'sku' => $validated['sku'],
                'barcode' => $request->barcode,
                'description' => $request->description,
                'base_price' => $validated['base_price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'category_id' => $validated['category_id'] ?? null,
                'collection' => $validated['collection'] ?? null,
                'status' => $validated['status'],
                'tags' => $validated['tags'] ?? null,
                'variants' => $validated['variants'] ?? null,
            ];

            if ($request->has('upsell_products')) {
                $data['upsell_products'] = json_encode($request->upsell_products);
            }

            if (env('IMAGE_FROM')) {
                $data['product_image'] = $request->product_image;
                $data['product_images'] = $request->product_images; // always array now
            } else {

                // ✅ Local file upload (existing logic)
                if ($request->hasFile('product_image')) {
                    $image = $request->file('product_image');
                    $filename = uniqid('prod_', true) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('products', $filename, 'public');
                    $data['product_image'] = $path;
                }

                $productImages = [];
                if ($request->hasFile('product_images')) {
                    foreach ($request->file('product_images') as $image) {
                        $filename = uniqid('gallery_', true) . '.' . $image->getClientOriginalExtension();
                        $path = $image->storeAs('products/gallery', $filename, 'public');
                        $productImages[] = $path;
                    }
                }

                $data['product_images'] = json_encode($productImages);
            }

            $data['meta_title'] = $request->name;
            $data['meta_description'] = $request->meta_description;
            $data['meta_keywords'] = $request->meta_keywords;
            $data['product_video']  = $request->product_video;
            $data['product_fabric'] = $request->product_fabric;
            $data['product_work']   = $request->product_work;
            $data['product_length'] = $request->product_length;
            $data['product_care']   = $request->product_care;

            // ✅ Create or Update
            if ($product) {
                $product->update($data);
                Log::info('Product updated successfully.', ['product_id' => $product->id]);
                $message = 'Product updated successfully.';
            } else {
                $product = Product::create($data);
                Log::info('Product created successfully.', ['product_id' => $product->id]);
                $message = 'Product created successfully.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $product
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // 🧩 Handle Validation Exception
            Log::warning('Product validation failed.', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // ❌ Catch all other errors
            Log::error('Unexpected error while saving product: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCollection(Request $request)
    {
        $collection = $request->query('collection');

        // If no collection query string provided, return all with selected fields
        if (!$collection) {
            $products = Product::select('id', 'name', 'slug', 'sku', 'base_price', 'product_image', 'discount_price')->get();

            return response()->json([
                'success' => true,
                'count' => $products->count(),
                'data' => $products
            ]);
        }

        // Filter products by collection
        $products = Product::select('name', 'slug', 'sku', 'base_price', 'discount_price', 'product_image')
            ->where('collection', $collection)
            ->get();

        // If no products found
        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No products found for this collection.'
            ], 404);
        }

        // Success response
        return response()->json([
            'success' => true,
            'collection' => $collection,
            'count' => $products->count(),
            'data' => $products
        ]);
    }


    public function upsellProducts(Request $request)
    {
        $ids = $request->ids; // array of IDs

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or empty IDs'
            ], 400);
        }

        $products = Product::whereIn('id', $ids)->get();

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products
        ]);
    }
}
