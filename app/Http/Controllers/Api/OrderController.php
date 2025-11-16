<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);

        return response()->json($orders);
        // return response()->json([
        //     'data' => $products
        // ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        Log::info('Order store request received.', ['payload' => $request->all()]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.sku' => 'nullable|string',
            'items.*.variant' => 'nullable',   // ✔️ MUST ADD THIS
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0', // ← ADD THIS

        ]);

        \DB::beginTransaction();

        try {
            $uuid = Str::uuid()->toString();

            $validated['id'] = $uuid;
            $validated['order_number'] = 'ORD-' . $uuid;
            $validated['status'] = 'processing';

            Log::info('Validated order data prepared.', [
                'order_id' => $uuid,
                'order_number' => $validated['order_number']
            ]);

            $itemsData = $validated['items'];
            unset($validated['items']);

            $order = Order::create($validated);

            Log::info('Order created successfully in database.', ['order_id' => $order->id]);

            foreach ($itemsData as $item) {
                Log::info('Creating order item.', ['product_id' => $item['product_id']]);

                $variant = null;
                if (!empty($item['variant'])) {
                    $variant = json_decode($item['variant'], true);
                }


                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'sku' => $item['sku'] ?? null,
                    'variant' => $variant,   // ✅ FIXED
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    // 'subtotal' => $item['subtotal'],
                ]);
            }

            Log::info('All order items saved.', ['order_id' => $order->id]);

            \DB::commit();

            Log::info('Order transaction committed.', ['order_id' => $order->id]);

            return response()->json([
                'message' => 'Order created successfully.',
                'order' => $order->load('items'),
            ], 201);
        } catch (\Exception $e) {

            \DB::rollBack();

            Log::error('Error while creating order.', [
                'error_message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch order with related items
        $order = Order::with('items')->where('id', $id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // ✅ Calculate totals dynamically
        $subtotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = 0; // You can apply logic here later
        $tax = 0;      // Example: no tax for now
        $total = $subtotal - $discount + $tax;

        // ✅ Merge calculated values into order data
        $orderData = $order->toArray();
        $orderData['subtotal'] = $subtotal;
        $orderData['discount'] = $discount;
        $orderData['tax'] = $tax;
        $orderData['total_amount'] = $total;

        return response()->json([
            'success' => true,
            'data' => $orderData
        ]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'subtotal_amount' => 'sometimes|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'grand_total' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order updated successfully.',
            'order' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $order = Order::findOrFail($id);

        // ✅ Delete related order items first
        $order->items()->delete();

        // ✅ Delete the order itself
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
            'data' => $order
        ]);
    }
}
