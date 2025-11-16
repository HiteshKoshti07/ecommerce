<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class StoreSettingController extends Controller
{
    public function index()
    {
        $stores = StoreSetting::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_slug' => 'required|string|max:255|unique:store_settings,store_slug',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string',
            'short_description' => 'nullable|string',
            'about_store' => 'nullable|string',
            'is_active' => 'boolean',
            'store_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // ✅ Create store record first
        $store = StoreSetting::create($validated);

        // ✅ Handle logo upload
        if ($request->hasFile('store_logo')) {
            $path = $request->file('store_logo')->store('store_logos', 'public');
            $store->store_logo = $path;
        }

        // ✅ Handle cover photo upload
        if ($request->hasFile('cover_banner')) {
            $path = $request->file('cover_banner')->store('cover_banner', 'public');
            $store->cover_banner = $path;
        }

        $store->save();

        return response()->json([
            'message' => 'Store created successfully',
            'data' => $store,
        ], 201);
    }

    public function show($id)
    {
        $store = StoreSetting::findOrFail($id);
        return response()->json($store);
    }
    public function update(Request $request, $id)
    {
        $store = StoreSetting::findOrFail($id);

        Log::info('Incoming Store Update', [
            'fields' => $request->except(['store_logo', 'store_cover']),
            'has_logo' => $request->hasFile('store_logo'),
            'has_cover' => $request->hasFile('cover_banner'),
        ]);

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_slug' => 'required|string|max:255|unique:store_settings,store_slug,' . $id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string',
            'short_description' => 'nullable|string',
            'about_store' => 'nullable|string',
            'is_active' => 'boolean',
            'store_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // ✅ Handle store logo upload
        if ($request->hasFile('store_logo')) {
            if ($store->store_logo && Storage::disk('public')->exists($store->store_logo)) {
                Storage::disk('public')->delete($store->store_logo);
            }

            $path = $request->file('store_logo')->store('store_logos', 'public');
            $validated['store_logo'] = $path;
        }

        // ✅ Handle store cover upload
        if ($request->hasFile('cover_banner')) {
            if ($store->store_cover && Storage::disk('public')->exists($store->store_cover)) {
                Storage::disk('public')->delete($store->store_cover);
            }

            $coverPath = $request->file('cover_banner')->store('cover_banner', 'public');
            $validated['cover_banner'] = $coverPath;
        }

        // ✅ Update store data
        $store->update($validated);

        return response()->json([
            'message' => 'Store updated successfully',
            'data' => $store,
        ]);
    }

    public function destroy($id)
    {
        $store = StoreSetting::findOrFail($id);
        $store->delete();

        return response()->json(['message' => 'Store deleted successfully']);
    }

    public function findStore(Request $request)
    {
        $store = StoreSetting::where('store_slug', $request->store_slug)->first();

        if ($store) {
            return response()->json([
                'exists' => true,
                'data' => $store
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }
}
