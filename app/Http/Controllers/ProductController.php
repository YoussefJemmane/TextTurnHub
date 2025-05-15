<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{

    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'category'    => 'required|string|max:255',
        'price'       => 'required|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'unit'        => 'required|string|max:50',
        'color'       => 'nullable|string|max:50',
        'material'    => 'nullable|string|max:255',
        'image'       => 'nullable|image|max:2048',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    // Attach artisan_profile_id from authenticated user
    $validated['artisan_profile_id'] =  Auth::user()->artisanProfile->id;

    Product::create($validated);

    return redirect()->route('products.index')->with('success', 'Product created successfully.');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function marketplace()
    {
        $products = Product::all();
        return view('products.marketplace', compact('products'));
    }
}
