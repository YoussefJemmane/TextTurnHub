<?php

namespace App\Http\Controllers;

use App\Models\TextileWaste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class TextileWasteController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of textile wastes.
     */
    public function index()
    {
        $textileWastes = TextileWaste::where('company_profiles_id', Auth::user()->companyProfile->id)
            ->latest()
            ->paginate(10);

        return view('textile-wastes.index', compact('textileWastes'));
    }

    /**
     * Show the form for creating a new textile waste.
     */
    public function create()
    {
        return view('textile-wastes.create');
    }

    /**
     * Store a newly created textile waste.
     */
    public function store(Request $request)
{
    // Combine all validation rules into one call
    $validationRules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'waste_type' => 'required|in:fabric,yarn,offcuts,scraps,other',
        'material_type' => 'required|string|max:255',
        'quantity' => 'required|numeric|min:0',
        'unit' => 'required|in:kg,meters,pieces',
        'condition' => 'required|string|max:255',
        'color' => 'nullable|string|max:255',
        'composition' => 'nullable|string|max:255',
        'minimum_order_quantity' => 'nullable|numeric|min:0',
        'price_per_unit' => 'nullable|numeric|min:0',
        'location' => 'required|string|max:255',
        'sustainability_metrics' => 'nullable|array',
        'availability_status' => 'nullable|in:available',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    // Validate all inputs at once
    $validated = $request->validate($validationRules);

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $imagePath = $request->file('image')->store('textile-wastes', 'public');
    }

    // Store the image path
    $validated['images'] = $imagePath;
    $validated['availability_status'] = 'available';

    $validated['company_profiles_id'] = Auth::user()->companyProfile->id;

    $textileWaste = TextileWaste::create($validated);

    return redirect()->route('textile-waste.index')
        ->with('success', 'Textile waste listed successfully.');
}


    /**
     * Display the specified textile waste.
     */
    public function show(TextileWaste $textileWaste)
    {
        return view('textile-wastes.show', compact('textileWaste'));
    }

    /**
     * Show the form for editing the specified textile waste.
     */
    public function edit(TextileWaste $textileWaste)
    {
        $this->authorize('update', $textileWaste);
        return view('textile-wastes.edit', compact('textileWaste'));
    }

    /**
     * Update the specified textile waste.
     */
    public function update(Request $request, TextileWaste $textileWaste)
    {
        $this->authorize('update', $textileWaste);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'waste_type' => 'required|in:fabric,yarn,offcuts,scraps,other',
            'material_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:kg,meters,pieces',
            'condition' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'composition' => 'nullable|string|max:255',
            'minimum_order_quantity' => 'nullable|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:255',
            'images.*' => 'nullable|image|max:2048',
            'sustainability_metrics' => 'nullable|array'
        ]);

        if ($request->hasFile('images')) {
            // Delete old images
            if ($textileWaste->images) {
                foreach ($textileWaste->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Store new images
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('textile-wastes', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $textileWaste->update($validated);

        return redirect()->route('textile-wastes.index')
            ->with('success', 'Textile waste updated successfully.');
    }

    /**
     * Remove the specified textile waste.
     */
    public function destroy(TextileWaste $textileWaste)
    {



        $textileWaste->delete();

        return redirect()->route('textile-waste.index')
            ->with('success', 'Textile waste deleted successfully.');
    }

    /**
     * Display a listing of available textile wastes for exchange.
     */
     
    public function marketplace(Request $request)
    {
        $user = Auth::user();
        $query = TextileWaste::available();
    
        // If user is a company, exclude their own listings
        if ($user->hasRole('company')) {
            $query->where('company_profiles_id', '!=', $user->companyProfile->id);
        }
    
        // Apply filters if provided
        if ($request->filled('waste_type')) {
            $query->where('waste_type', $request->waste_type);
        }
    
        if ($request->filled('material_type')) {
            $query->where('material_type', 'like', '%' . $request->material_type . '%');
        }
    
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
    
        $textileWastes = $query->latest()->paginate(5);
        $textileWastes->appends($request->all());
    
        // Pass user role to view to show different actions (exchange vs buy)
        return view('textile-wastes.marketplace', [
            'textileWastes' => $textileWastes,
            'userRole' => $user->roles->first()->name
        ]);
    }
}

