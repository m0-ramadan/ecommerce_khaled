<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    use TranslatableTrait;

    public function __construct()
    {
        $locale = request()->header('lang', 'ar');
        App::setLocale($locale);
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:products,code',
                'description' => 'nullable|string',
                'category' => 'required|string|max:255',
                'brand' => 'nullable|string|max:255',
                'in_stock_quantity' => 'required|integer|min:0',
                'reorder_limit' => 'required|integer|min:0',
                'minimum_stock' => 'required|integer|min:0',
                'location_in_stock' => 'nullable|string|max:255',
                'product_details' => 'nullable|string',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:0',
                'discounts' => 'nullable|numeric|min:0',
                'expected_profit_margin' => 'nullable|numeric|min:0',
                'supplier_name' => 'nullable|string|max:255',
                'supplier_contact_information' => 'nullable|string|max:255',
                'expected_delivery_time' => 'nullable|string|max:255',
                'status' => 'required|in:1,2,3,4',
                'date_added_to_stock' => 'required|date',
                'date_last_updated_to_stock' => 'nullable|date',
                'expiry_date' => 'nullable|date',
                'unit_type' => 'required|string|max:255',
                'product_size' => 'nullable|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = $request->user();
            $data = $validator->validated();
            $data['person_id'] = $user->id;
            $data['person_type'] = get_class($user);

            $product = Product::create($data);

            return response()->json([
                'status' => true,
                'message' => $this->translate('product_created'),
                'data' => new ProductResource($product),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $this->translate('validation_failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_creating_product'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $products = Product::where('person_id', auth()->user()->id)->with('images', 'person')->get();

            return response()->json([
                'status' => true,
                'message' => $this->translate('products_retrieved'),
                'data' => ProductResource::collection($products),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error retrieving products: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_retrieving_products'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $product = Product::with('images', 'person')->find($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => $this->translate('product_not_found'),
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => $this->translate('product_retrieved'),
                'data' => new ProductResource($product),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error retrieving product: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_retrieving_product'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => $this->translate('product_not_found'),
                ], 404);
            }

            $rules = [
                'name' => 'sometimes|string|max:255',
                'code' => 'sometimes|string|max:255|unique:products,code,' . $product->id,
                'description' => 'nullable|string',
                'category' => 'sometimes|string|max:255',
                'brand' => 'nullable|string|max:255',
                'in_stock_quantity' => 'sometimes|integer|min:0',
                'reorder_limit' => 'sometimes|integer|min:0',
                'minimum_stock' => 'sometimes|integer|min:0',
                'location_in_stock' => 'nullable|string|max:255',
                'product_details' => 'nullable|string',
                'purchase_price' => 'sometimes|numeric|min:0',
                'sale_price' => 'sometimes|numeric|min:0',
                'discounts' => 'nullable|numeric|min:0',
                'expected_profit_margin' => 'nullable|numeric|min:0',
                'supplier_name' => 'nullable|string|max:255',
                'supplier_contact_information' => 'nullable|string|max:255',
                'expected_delivery_time' => 'nullable|string|max:255',
                'status' => 'sometimes|in:In Stock,Out of Stock,Pending,Returned',
                'date_added_to_stock' => 'sometimes|date',
                'date_last_updated_to_stock' => 'nullable|date',
                'expiry_date' => 'nullable|date',
                'unit_type' => 'sometimes|string|max:255',
                'product_size' => 'nullable|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $product->update($validator->validated());

            return response()->json([
                'status' => true,
                'message' => $this->translate('product_updated'),
                'data' => new ProductResource($product),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $this->translate('validation_failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_updating_product'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateQuantityAndPrices(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => $this->translate('product_not_found'),
                ], 404);
            }

            $rules = [
                // 'in_stock_quantity' => 'sometimes|integer|min:0',
                // 'purchase_price' => 'sometimes|numeric|min:0',
                'sale_price' => 'sometimes|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $product->update($validator->validated());

            return response()->json([
                'status' => true,
                'message' => $this->translate('product_updated'),
                'data' => new ProductResource($product),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => $this->translate('validation_failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating product quantity and prices: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_updating_product'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => $this->translate('product_not_found'),
                ], 404);
            }

            $product->delete();

            return response()->json([
                'status' => true,
                'message' => $this->translate('product_deleted'),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_deleting_product'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}