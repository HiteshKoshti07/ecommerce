<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $customers = Customer::orderBy('created_at', 'desc')->paginate($perPage);

            Log::info('Fetched customer list', ['count' => $customers->total()]);

            return response()->json([
                'success' => true,
                'message' => 'Customers fetched successfully',
                'data' => $customers->items(),
                'meta' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'per_page' => $customers->perPage(),
                    'total' => $customers->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customers', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'mobile_no' => 'nullable|string|max:15',
                'address_line1' => 'nullable|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'alternate_contact' => 'nullable|string|max:15',
                'landmark' => 'nullable|string|max:255',
            ]);

            $customer = Customer::create($validated);

            Log::info('Customer created successfully', ['id' => $customer->id]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer
            ], 201);
        } catch (ValidationException $e) {
            Log::warning('Customer validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            Log::error('Database error while creating customer', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Database error while creating customer',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected error while creating customer', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                Log::notice('Customer not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            Log::info('Customer details fetched', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Customer fetched successfully',
                'data' => $customer
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching customer', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch customer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                Log::notice('Customer not found for update', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
                'mobile_no' => 'sometimes|string|max:15',
                'address_line1' => 'nullable|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'country' => 'nullable|string|max:100',
                'alternate_contact' => 'nullable|string|max:15',
                'landmark' => 'nullable|string|max:255',
            ]);

            $customer->update($validated);

            Log::info('Customer updated successfully', ['id' => $customer->id]);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer
            ]);
        } catch (ValidationException $e) {
            Log::warning('Customer validation failed on update', ['id' => $id, 'errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating customer', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified customer (soft delete).
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::find($id);

            if (!$customer) {
                Log::notice('Customer not found for delete', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $customer->delete();

            Log::info('Customer deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting customer', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
