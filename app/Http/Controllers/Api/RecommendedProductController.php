<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Traits\ApiTrait;

class RecommendedProductController extends Controller
{
    use ApiTrait;

    public function index()
    {
        if (auth('clients')
            ->user()
        ) {

            $products = auth('clients')
                ->user()
                ->clientCategories()
                ->with([
                    'category' => function ($query) {
                        $query->whereHas('products');
                    },
                    'category.products' => function ($query) {
                        $query->inRandomOrder();
                    },
                ])
                ->get()
                ->pluck('category.products')
                ->flatten();
        } else {
            $products = [];
        }
        $products = ProductResource::collection($products);

        return $this->apiResponse(data: $products);
    }
}
