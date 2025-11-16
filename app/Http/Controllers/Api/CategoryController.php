<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::with('parent')->paginate(10);
            Log::info('Category list fetched successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Category list fetched successfully.',
                'data' => $categories
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch category list: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 🧩 Step 1: Validate Input
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'slug'        => 'nullable|string|unique:categories,slug',
                'attachment'  => 'nullable|file|mimes:jpg,png,jpeg,webp|max:2048',
                // 'parent_id'   => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'status'      => 'required|in:active,inactive',
            ]);

            DB::beginTransaction();

            // 🧩 Step 2: Slug auto-generate if empty
            $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

            // 🧩 Step 3: Handle file upload
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('categories', 'public');
                $validated['attachment'] = $path;
            }

            // 🧩 Step 4: Create category
            $category = Category::create($validated);

            DB::commit();

            // 🧩 Step 5: Log success
            Log::info('✅ Category created successfully', [
                'category_id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'data' => $category
            ], 201);
        } catch (ValidationException $e) {
            // ❌ Log validation errors separately
            Log::warning('⚠️ Category validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            // ❌ Log general failure
            Log::error('❌ Failed to create category', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = Category::with('children')->findOrFail($id);

            Log::info('Category fetched successfully', ['category_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Category fetched successfully.',
                'data' => $category
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to fetch category: ' . $e->getMessage(), ['category_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);

            $request->validate([
                'title' => 'sometimes|string|max:255',
                'slug' => 'nullable|string|unique:categories,slug,' . $id,
                'attachment' => 'nullable|file|mimes:jpg,png,jpeg,webp',
                'description' => 'nullable',
                'status' => 'nullable|in:active,inactive',
            ]);

            $data = $request->all();
            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('categories', 'public');
            }

            $category->update($data);

            Log::info('Category updated successfully', ['category_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'data' => $category
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to update category: ' . $e->getMessage(), ['category_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            Log::info('Category deleted successfully', ['category_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to delete category: ' . $e->getMessage(), ['category_id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
