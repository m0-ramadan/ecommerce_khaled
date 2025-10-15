<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

use App\Models\Category;

use App\Models\SubCategory;


class ProductsController extends Controller
{
public function products()
{
    $products = Product::active()->paginate(12);
    $subCategories = SubCategory::all();
    $categories = Category::all();
    $ids = $subCategories->first()->id ?? null; // Getting the first ID or null if not available

    return view('Front.pages.products', compact('products', 'subCategories', 'categories', 'ids'));
}


}
