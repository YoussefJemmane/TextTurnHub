<?php

namespace App\Http\Controllers;

use App\Models\TextileWaste;
use App\Models\WasteExchange;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WasteExchangeController extends Controller
{
    /**
     * Display a listing of exchange requests received by the user's company.
     */
        
    public function receivedRequests()
    {
        $user = Auth::user();
        
        // Build base query with common relationships
        $query = WasteExchange::query()
            ->with(['textileWaste', 'receiverCompany', 'receiverArtisan.user'])
            ->latest();
    
        // Filter based on user role
        if ($user->hasRole('company')) {
            
            $query->where('supplier_company_id', $user->companyProfile->id)
                 ->where('status', 'requested');
        } elseif ($user->hasRole('artisan')) {
             $query->where('supplier_company_id', $user->artisanProfile->id)
                  ->where('status', 'requested');
        }
    
        $exchangeRequests = $query->paginate(5);
    
        return view('waste-exchanges.received', [
            'exchangeRequests' => $exchangeRequests,
            'userRole' => $user->roles->first()->name
        ]);
    }

    /**
     * Display a listing of exchange requests sent by the user's company.
     */

public function sentRequests()
{
    $user = Auth::user();

    // Build the base query
    $query = WasteExchange::whereIn('status', ['requested', 'accepted', 'completed', 'cancelled'])
        ->with(['textileWaste', 'supplierCompany'])
        ->latest();

    // Filter based on user role
    if ($user->hasRole('company')) {
        $query->where('receiver_company_id', $user->companyProfile->id);
    } elseif ($user->hasRole('artisan')) {
        $query->where('receiver_artisan_id', $user->artisanProfile->id);
    }

    $exchangeRequests = $query->paginate(5);

    return view('waste-exchanges.sent', compact('exchangeRequests'));
}
    /**
     * Show the form for creating a new exchange request.
     */

public function create(TextileWaste $textileWaste)
{
    $user = Auth::user();

    // Check if the textile waste is available
    if (!$textileWaste->isAvailable()) {
        return redirect()->route('marketplace.index')
            ->with('error', 'This textile waste is no longer available for exchange.');
    }

    // Check ownership based on user role
    if ($user->hasRole('company')) {
        if ($textileWaste->company_profiles_id === $user->companyProfile->id) {
            return redirect()->route('marketplace.index')
                ->with('error', 'You cannot request your own textile waste.');
        }
    }

    return view('waste-exchanges.create', [
        'textileWaste' => $textileWaste,
        'userRole' => $user->roles->first()->name
    ]);
}
    /**
     * Store a newly created exchange request.
     */

    public function store(Request $request, TextileWaste $textileWaste)
    {
        // Validate the request data
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01|max:' . $textileWaste->quantity,
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if the textile waste is available
        if (!$textileWaste->isAvailable()) {
            return redirect()->route('marketplace.index')
                ->with('error', 'This textile waste is no longer available.');
        }

        // Create the exchange request
        $exchange = new WasteExchange();
        $exchange->textile_waste_id = $textileWaste->id;
        $exchange->supplier_company_id = $textileWaste->company_profiles_id;

        // Set receiver based on user role
        if (Auth::user()->hasRole('artisan')) {
            $exchange->receiver_artisan_id = Auth::user()->artisanProfile->id;
            $exchange->receiver_company_id = null;
        } else {
            $exchange->receiver_company_id = Auth::user()->companyProfile->id;
            $exchange->receiver_artisan_id = null;
        }

        $exchange->quantity = $validated['quantity'];
        $exchange->status = 'requested';
        $exchange->notes = $validated['notes'] ?? null;

        // For artisans, always use the price
        if (Auth::user()->hasRole('artisan')) {
            $exchange->final_price = $textileWaste->price_per_unit * $validated['quantity'];
        } elseif ($textileWaste->price_per_unit) {
            $exchange->final_price = $textileWaste->price_per_unit * $validated['quantity'];
        }

        $exchange->save();

        return redirect()->route('waste-exchanges.sent')
            ->with('success', 'Exchange request sent successfully.');
    }

    // Modify the complete method to handle artisan completion
    public function complete(WasteExchange $wasteExchange)
    {
        // Authorization check
        $user = Auth::user();
        $isAuthorized = false;

        if ($user->hasRole('company')) {
            $companyId = $user->companyProfile->id;
            $isAuthorized = $wasteExchange->supplier_company_id === $companyId;
        } elseif ($user->hasRole('artisan')) {
            $artisanId = $user->artisanProfile->id;
            $isAuthorized = $wasteExchange->receiver_artisan_id === $artisanId;
        }

        if (!$isAuthorized) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the request is in the 'accepted' state
        if ($wasteExchange->status !== 'accepted') {
            return redirect()->back()
                ->with('error', 'This exchange request cannot be completed in its current state.');
        }

        // Update the exchange status
        $wasteExchange->status = 'completed';
        $wasteExchange->exchange_date = now();
        $wasteExchange->save();

        return redirect()->back()
            ->with('success', 'Exchange marked as completed successfully.');
    }

    /**
     * Display the specified exchange.
     */

public function show(WasteExchange $wasteExchange)
{
    $user = Auth::user();
    $isAuthorized = false;

    if ($user->hasRole('company')) {
        $companyId = $user->companyProfile->id;
        $isAuthorized = $wasteExchange->supplier_company_id === $companyId ||
                        $wasteExchange->receiver_company_id === $companyId;
    } elseif ($user->hasRole('artisan')) {
        $artisanId = $user->artisanProfile->id;
        $isAuthorized = $wasteExchange->receiver_artisan_id === $artisanId;
    }

    if (!$isAuthorized) {
        abort(403, 'Unauthorized action.');
    }

    return view('waste-exchanges.show', [
        'wasteExchange' => $wasteExchange,
        'userRole' => $user->roles->first()->name
    ]);
}

    /**
     * Accept an exchange request.
     */
    public function accept(WasteExchange $wasteExchange)
    {
        // Authorization check - only the supplier can accept the request
        $companyId = Auth::user()->companyProfile->id;
        if ($wasteExchange->supplier_company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the request is in the 'requested' state
        if ($wasteExchange->status !== 'requested') {
            return redirect()->route('waste-exchanges.received')
                ->with('error', 'This exchange request cannot be accepted in its current state.');
        }

        // Update the exchange status
        $wasteExchange->status = 'accepted';
        $wasteExchange->save();

        // Update the textile waste availability status
        $textileWaste = $wasteExchange->textileWaste;

        // If the requested quantity equals the available quantity,
        // mark the waste as 'exchanged', otherwise keep it as 'available'
        if ($wasteExchange->quantity >= $textileWaste->quantity) {
            $textileWaste->availability_status = 'exchanged';
            $textileWaste->save();
        } else {
            // Reduce the available quantity
            $textileWaste->quantity -= $wasteExchange->quantity;
            $textileWaste->save();
        }

        return redirect()->route('waste-exchanges.received')
            ->with('success', 'Exchange request accepted successfully.');
    }

    /**
     * Reject an exchange request.
     */
    public function reject(WasteExchange $wasteExchange)
    {
        // Authorization check - only the supplier can reject the request
        $companyId = Auth::user()->companyProfile->id;
        if ($wasteExchange->supplier_company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the request is in the 'requested' state
        if ($wasteExchange->status !== 'requested') {
            return redirect()->route('waste-exchanges.received')
                ->with('error', 'This exchange request cannot be rejected in its current state.');
        }

        // Update the exchange status
        $wasteExchange->status = 'cancelled';
        $wasteExchange->save();

        return redirect()->route('waste-exchanges.received')
            ->with('success', 'Exchange request rejected successfully.');
    }


    /**
     * Cancel an exchange request.
     */
    public function cancel(WasteExchange $wasteExchange)
    {
        // Authorization check - only the receiver can cancel their request
        $companyId = Auth::user()->companyProfile->id;
        if ($wasteExchange->receiver_company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the request is in the 'requested' or 'accepted' state
        if (!in_array($wasteExchange->status, ['requested', 'accepted'])) {
            return redirect()->route('waste-exchanges.sent')
                ->with('error', 'This exchange request cannot be cancelled in its current state.');
        }

        // Update the exchange status
        $wasteExchange->status = 'cancelled';
        $wasteExchange->save();

        // If the exchange was accepted, make the textile waste available again
        if ($wasteExchange->status === 'accepted') {
            $textileWaste = $wasteExchange->textileWaste;

            // If the waste was marked as exchanged, change it back to available
            if ($textileWaste->availability_status === 'exchanged') {
                $textileWaste->availability_status = 'available';
            }

            // Add the quantity back
            $textileWaste->quantity += $wasteExchange->quantity;
            $textileWaste->save();
        }

        return redirect()->route('waste-exchanges.sent')
            ->with('success', 'Exchange request cancelled successfully.');
    }

    /**
     * Display a history of all exchanges.
     */
    public function history(Request $request)
{

    return view('waste-exchanges.history');
}

}

