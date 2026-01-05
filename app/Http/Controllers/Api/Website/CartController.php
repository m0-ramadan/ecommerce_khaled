<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\DesignService;
use App\Models\PrintLocation;
use App\Models\PrintingMethod;
use App\Models\ProductOptions;
use App\Traits\ApiResponseTrait;
use App\Models\EmbroiderLocation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\CartResource;
use App\Http\Requests\Website\AddToCartRequest;
use App\Http\Resources\Website\CartItemResource;
use App\Http\Requests\Website\UpdateCartItemRequest;

class CartController extends Controller
{
    use ApiResponseTrait;

    // جلب السلة الحالية أو إنشاء واحدة جديدة (مستخدم مسجل أو session visitor)
    private function getCurrentCart(): Cart
    {
        $user = auth()->user();
        $sessionId = request()->header('X-Session-Id') ?: session()->getId();

        return Cart::firstOrCreate(
            $user ? ['user_id' => $user->id] : ['session_id' => $sessionId],
            ['subtotal' => 0, 'total' => 0]
        );
    }

    // عرض السلة
    public function index(Request $request)
    {
        $cart = $this->getCurrentCart()->load('items.product', 'items.size', 'items.color', 'items.printingMethod', 'items.designService');
        return $this->success(new CartResource($cart), 'تم جلب السلة بنجاح');
    }

    // إضافة منتج للسلة
    public function add(AddToCartRequest $request)
    {
        $cart = $this->getCurrentCart();

        return DB::transaction(function () use ($request, $cart) {
            // حساب السعر بناء على الطلب
            $priceData = $this->calculateItemPrice($request);

            // تجهيز الـ JSON fields واستحداث hash_key لمنع التكرار
            $printLocationsJson = $request->filled('print_locations') ? json_encode($request->print_locations, JSON_UNESCAPED_UNICODE) : null;
            $embroiderLocationsJson = $request->filled('embroider_locations') ? json_encode($request->embroider_locations, JSON_UNESCAPED_UNICODE) : null;
            $selectedOptionsJson = $request->filled('selected_options') ? json_encode($request->selected_options, JSON_UNESCAPED_UNICODE) : null;

            $hashAttributes = [
                'product_id' => (int) $request->product_id,
                'size_id' => $request->size_id ? (int) $request->size_id : null,
                'color_id' => $request->color_id ? (int) $request->color_id : null,
                'material_id' => $request->material_id ? (int) $request->material_id : null,
                'printing_method_id' => $request->printing_method_id ? (int) $request->printing_method_id : null,
                'print_locations' => $request->print_locations ?? [],
                'embroider_locations' => $request->embroider_locations ?? [],
                'selected_options' => $request->selected_options ?? [],
                'design_service_id' => $request->design_service_id ?? null,
                'is_sample' => (bool) $request->boolean('is_sample', false),
            ];
            $hashKey = md5(json_encode($hashAttributes, JSON_UNESCAPED_UNICODE));

            // حاول الحصول على العنصر بنفس hash_key
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('hash_key', $hashKey)
                ->first();

            $printingMethodId = null;
            if ($request->filled('printing_method_id')) {
                $valid = Product::whereKey($request->product_id)
                    ->whereHas('printingMethods', function ($q) use ($request) {
                        $q->where('printing_methods.id', $request->printing_method_id);
                    })
                    ->exists();
                if ($valid) {
                    $printingMethodId = (int) $request->printing_method_id;
                }
            }

            if ($cartItem) {
                // لو العنصر موجود — نزود الكمية بالكمية الواردة
                $cartItem->increment('quantity', $request->quantity);
                $cartItem->price_per_unit = $priceData['price_per_unit'];
                $cartItem->line_total = $cartItem->quantity * $cartItem->price_per_unit;
                $cartItem->save();
            } else {
                // انشئ عنصر جديد
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'size_id' => $request->size_id,
                    'color_id' => $request->color_id,
                    'printing_method_id' => $printingMethodId,
                    'print_locations' => $printLocationsJson,
                    'embroider_locations' => $embroiderLocationsJson,
                    'selected_options' => $selectedOptionsJson,
                    'design_service_id' => $request->design_service_id,
                    'note' => $request->input('note'),
                    'quantity_id' => $request->input('quantity_id'),
                    'image_design' => $request->input('image_design'),
                    'quantity' => $request->quantity,
                    'price_per_unit' => $priceData['price_per_unit'],
                    'line_total' => $priceData['line_total'],
                    'is_sample' => $request->boolean('is_sample', false),
                    'hash_key' => $hashKey,
                    'material_id' => $request->input('material_id') ? (int) $request->input('material_id') : null,
                ]);
            }

            $this->recalculateCart($cart);

            return $this->success(new CartItemResource($cartItem), 'تمت الإضافة إلى السلة');
        });
    }

    // تحديث عنصر في السلة
    public function update(CartItem $cartItem, UpdateCartItemRequest $request)
    {
        $this->authorizeCartItem($cartItem);

        return DB::transaction(function () use ($cartItem, $request) {
            // نحضر البيانات الجديدة مع الاحتفاظ بالقيم القديمة إن لم تُقدم
            $data = $request->only([
                'quantity',
                'size_id',
                'color_id',
                'printing_method_id',
                'print_locations',
                'embroider_locations',
                'selected_options',
                'design_service_id',
                'is_sample',
                'note',
                'quantity_id',
                'image_design',
                'material_id'
            ]);

            // نحسب السعر بناء على التخصيص الجديد — نمرر المنتج الحالي كـ param اختياري
            $priceRequest = new Request(array_merge($request->all(), [
                'product_id' => $cartItem->product_id,
                'quantity' => $data['quantity'] ?? $cartItem->quantity,
            ]));

            $priceData = $this->calculateItemPrice($priceRequest, $cartItem->product);

            // تأكد من تحويل الحقول إلى JSON عند الحفظ
            $printLocationsJson = isset($data['print_locations']) ? json_encode($data['print_locations'], JSON_UNESCAPED_UNICODE) : $cartItem->print_locations;
            $embroiderLocationsJson = isset($data['embroider_locations']) ? json_encode($data['embroider_locations'], JSON_UNESCAPED_UNICODE) : $cartItem->embroider_locations;
            $selectedOptionsJson = isset($data['selected_options']) ? json_encode($data['selected_options'], JSON_UNESCAPED_UNICODE) : $cartItem->selected_options;
            $printingMethodId = null;

            $printingMethodId = $cartItem->printing_method_id;

            if ($request->filled('printing_method_id')) {
                $valid = $cartItem->product
                    ->printingMethods()
                    ->where('printing_methods.id', $request->printing_method_id)
                    ->exists();

                if ($valid) {
                    $printingMethodId = (int) $request->printing_method_id;
                }
            }

            // إعادة توليد hash_key لأن التخصيصات قد تتغير
            $hashAttributes = [
                'product_id' => $cartItem->product_id,
                'size_id' => $data['size_id'] ?? $cartItem->size_id,
                'color_id' => $data['color_id'] ?? $cartItem->color_id,
                'printing_method_id' => $printingMethodId ?? $cartItem->printing_method_id,
                'print_locations' => isset($data['print_locations']) ? $data['print_locations'] : json_decode($cartItem->print_locations, true) ?? [],
                'embroider_locations' => isset($data['embroider_locations']) ? $data['embroider_locations'] : json_decode($cartItem->embroider_locations, true) ?? [],
                'selected_options' => isset($data['selected_options']) ? $data['selected_options'] : json_decode($cartItem->selected_options, true) ?? [],
                'design_service_id' => $data['design_service_id'] ?? $cartItem->design_service_id,
                'material_id' => $data['material_id'] ?? $cartItem->material_id,
                'is_sample' => isset($data['is_sample']) ? (bool)$data['is_sample'] : (bool)$cartItem->is_sample,
            ];
            $newHashKey = md5(json_encode($hashAttributes, JSON_UNESCAPED_UNICODE));

            // قم بالتحديث
            $cartItem->update([
                'quantity' => $data['quantity'] ?? $cartItem->quantity,
                'size_id' => $data['size_id'] ?? $cartItem->size_id,
                'color_id' => $data['color_id'] ?? $cartItem->color_id,
                'printing_method_id' => $data['printing_method_id'] ?? $cartItem->printing_method_id,
                'print_locations' => $printLocationsJson,
                'embroider_locations' => $embroiderLocationsJson,
                'selected_options' => $selectedOptionsJson,
                'design_service_id' => $data['design_service_id'] ?? $cartItem->design_service_id,
                'note' => $data['note'] ?? $cartItem->note,
                'quantity_id' => $data['quantity_id'] ?? $cartItem->quantity_id,
                'image_design' => $data['image_design'] ?? $cartItem->image_design,
                'is_sample' => isset($data['is_sample']) ? (bool)$data['is_sample'] : $cartItem->is_sample,
                'price_per_unit' => $priceData['price_per_unit'],
                'line_total' => $priceData['line_total'],
                'hash_key' => $newHashKey,
                'material_id' => $data['material_id'] ?? $cartItem->material_id,
            ]);

            // إعادة حساب السلة
            $this->recalculateCart($cartItem->cart);

            return $this->success(new CartResource($cartItem->cart->fresh(['items.product', 'items.size', 'items.color', 'items.printingMethod', 'items.designService'])), 'تم تحديث العنصر بنجاح');
        });
    }

    // حذف عنصر من السلة
    public function remove(CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);

        return DB::transaction(function () use ($cartItem) {
            $cart = $cartItem->cart;
            $cartItem->delete();
            $this->recalculateCart($cart);
            return $this->success(new CartResource($cart->fresh(['items'])), 'تم حذف العنصر من السلة');
        });
    }

    // تفريغ السلة
    public function clear()
    {
        $cart = $this->getCurrentCart();
        $cart->items()->delete();
        $cart->update(['subtotal' => 0, 'total' => 0]);
        return $this->success(null, 'تم تفريغ السلة');
    }

    // رفع صورة للعنصر في السلة
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->input('cart_item_id'));
        $this->authorizeCartItem($cartItem);

        return DB::transaction(function () use ($request, $cartItem) {
            $imagePath = $request->file('image')->store('cart_items', 'public');
            $cartItem->update(['image_design' => $imagePath]);

            return $this->success(new CartItemResource($cartItem), 'تم رفع الصورة بنجاح');
        });
    }

    // --- Helpers ---

    private function authorizeCartItem(CartItem $cartItem)
    {
        $currentCart = $this->getCurrentCart();
        if ($cartItem->cart_id !== $currentCart->id) {
            abort(403, 'هذا العنصر لا يخص سلتك');
        }
    }

    /**
     * حساب السعر لعُنصر السلة.
     * يقبل Request و product اختياري (مفيد عند التحديث).
     *
     * @param Request $request
     * @param Product|null $product
     * @return array ['price_per_unit' => float, 'line_total' => float]
     */


    // private function calculateItemPrice(Request $request, Product $product = null): array
    // {
    //     $product = $product ?? Product::findOrFail($request->product_id);
    //     $quantity = (int) ($request->quantity ?? 1);

    //     // ============================
    //     // 🟢 base unit price
    //     // ============================
    //     $tier = $product->pricingTiers()
    //         ->where('quantity', '<=', $quantity)
    //         ->orderByDesc('quantity')
    //         ->first();

    //     $unitPrice = $tier?->price_per_unit ?? $product->base_price ?? 0;

    //     // ============================
    //     // 🟢 per-unit additions
    //     // ============================

    //     // selected options
    //     if ($request->filled('selected_options')) {
    //         foreach ($request->selected_options as $option) {
    //             $unitPrice += (float) ($option['option_additional_price'] ?? 0);
    //         }
    //     }

    //     // color
    //     if ($request->filled('color_id')) {
    //         $color = $product->colors()->find($request->color_id);
    //         $unitPrice += $color?->additional_price ?? 0;
    //     }

    //     // size (per unit)
    //     if ($request->filled('size_id')) {
    //         $size = $product->sizes()->find($request->size_id);

    //         $sizeTier = $size?->productTiers()
    //             ->where('quantity', '<=', $quantity)
    //             ->orderByDesc('quantity')
    //             ->first();

    //         $unitPrice += $sizeTier?->price_per_unit ?? 0;
    //     }

    //     // printing method
    //     if ($request->filled('printing_method_id')) {
    //         $method = PrintingMethod::find($request->printing_method_id);
    //         $unitPrice += $method?->base_price ?? 0;
    //     }

    //     // ============================
    //     // 🟡 one-time additions
    //     // ============================
    //     $oneTimePrice = 0;

    //     if ($request->filled('print_locations')) {
    //         $oneTimePrice += PrintLocation::whereIn('id', $request->print_locations)
    //             ->sum('additional_price');
    //     }

    //     if ($request->filled('embroider_locations')) {
    //         $oneTimePrice += EmbroiderLocation::whereIn('id', $request->embroider_locations)
    //             ->sum('additional_price');
    //     }

    //     if ($request->filled('design_service_id')) {
    //         $service = DesignService::find($request->design_service_id);
    //         $oneTimePrice += $service?->price ?? 0;
    //     }

    //     // ============================
    //     // 🧮 totals
    //     // ============================
    //     $lineTotal = ($unitPrice * $quantity) + $oneTimePrice;

    //     return [
    //         'price_per_unit' => round($unitPrice, 2),
    //         'line_total'     => round($lineTotal, 2),
    //     ];
    // }
    private function calculateItemPrice(Request $request, Product $product = null): array
    {
        $product  = $product ?? Product::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);

        // ============================
        // 🟢 base unit price
        // ============================
        $tier = $product->sizeTiers()
            ->where('quantity', '<=', $quantity)->where('size_id', $request->size_id)
            ->orderByDesc('quantity')
            ->first();
        $unitPrice = $tier?->price_per_unit ?? $product->base_price ?? 0;

        // ============================
        // 🟢 per-unit additions
        // ============================
        $oneTimePrice = 0;

        if ($request->filled('selected_options')) {
            foreach ($request->selected_options as $optionData) {

                $optionName = trim($optionData['option_name'] ?? '');

                // 🟡 خدمة التصميم → زي ما هي
                if (mb_strpos($optionName, 'خدمة تصميم') !== false) {
                    $oneTimePrice += (float) ($optionData['additional_price'] ?? 0);
                    continue;
                }

                // 🟢 باقي الأوبشنز → بحث مرن بالاسم
                $option = ProductOptions::where('product_id', $product->id)
                    ->where('option_name', 'LIKE', '%' . $optionName . '%')
                    ->first();

                if (!$option) {
                    continue;
                }

                $unitPrice += $option->additional_price;
            }
        }



        // color
        if ($request->filled('color_id')) {
            $color = $product->colors()->find($request->color_id);
            $unitPrice += $color?->additional_price ?? 0;
        }
        // // size (per unit)
        // if ($request->filled('size_id')) {
        //     $size = $product->sizes()->find($request->size_id);

        //     $sizeTier = $size?->productTiers()
        //         ->where('quantity', '<=', $quantity)
        //         ->orderByDesc('quantity')
        //         ->first();

        //     $unitPrice += $sizeTier?->price_per_unit ?? 0;
        // }

        // printing method (per unit)
        if ($request->filled('printing_method_id')) {
            $method = PrintingMethod::find($request->printing_method_id);
            $unitPrice += $method?->base_price ?? 0;
        }

        // ============================
        // 🟡 one-time additions
        // ============================
        if ($request->filled('print_locations')) {
            $oneTimePrice += PrintLocation::whereIn('id', $request->print_locations)
                ->sum('additional_price');
        }

        if ($request->filled('embroider_locations')) {
            $oneTimePrice += EmbroiderLocation::whereIn('id', $request->embroider_locations)
                ->sum('additional_price');
        }

        if ($request->filled('material_id')) {
            $material = DB::table('material_product')->where('product_id', $product->id)->where('material_id', $request->material_id)->first();
            $unitPrice += $material?->additional_price ?? 0;
        }

        // ============================
        // 🧮 totals
        // ============================
        $lineTotal = ($unitPrice * $quantity) + $oneTimePrice;

        return [
            'price_per_unit' => round($unitPrice, 2),
            'line_total'     => round($lineTotal, 2),
        ];
    }

    private function recalculateCart(Cart $cart)
    {
        $subtotal = $cart->items()->sum('line_total');
        $cart->update([
            'subtotal' => $subtotal,
            'total' => $subtotal, // مستقبلًا: + شحن - خصم
        ]);
    }
}
