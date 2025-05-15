<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::latest()->take(8)->get();
     $categories = Product::distinct()->pluck('category');
    
        return view('home', compact('featuredProducts', 'categories'));
    }

    public function index()
    {
        $products = Product::with('category')->paginate(12);
        return view('shop.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::search($query)->paginate(12);
        return view('shop.search', compact('products', 'query'));
    }
}