<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WasteExchange;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class WasteExchangeActions extends Component
{
    public WasteExchange $wasteExchange;

    public function mount(WasteExchange $wasteExchange)
    {
        $this->wasteExchange = $wasteExchange;
    }

    public function acceptRequest()
    {
        if (Auth::user()->companyProfile->id !== $this->wasteExchange->supplier_company_id) {
            return;
        }

        if ($this->wasteExchange->status !== 'requested') {
            session()->flash('error', 'This exchange request cannot be accepted in its current state.');
            return;
        }

        $this->wasteExchange->status = 'accepted';
        $this->wasteExchange->save();

        $textileWaste = $this->wasteExchange->textileWaste;
        if ($this->wasteExchange->quantity >= $textileWaste->quantity) {
            $textileWaste->availability_status = 'exchanged';
        } else {
            $textileWaste->quantity -= $this->wasteExchange->quantity;
        }
        $textileWaste->save();

        session()->flash('success', 'Exchange request accepted successfully.');
        $this->dispatch('exchangeStatusUpdated');
    }

    public function rejectRequest()
    {
        if (Auth::user()->companyProfile->id !== $this->wasteExchange->supplier_company_id) {
            return;
        }

        if ($this->wasteExchange->status !== 'requested') {
            session()->flash('error', 'This exchange request cannot be rejected in its current state.');
            return;
        }

        $this->wasteExchange->status = 'cancelled';
        $this->wasteExchange->save();

        session()->flash('success', 'Exchange request rejected successfully.');
        $this->dispatch('exchangeStatusUpdated');
    }

    public function cancelRequest()
    {
        if (Auth::user()->companyProfile->id !== $this->wasteExchange->receiver_company_id) {
            return;
        }

        if (!in_array($this->wasteExchange->status, ['requested', 'accepted'])) {
            session()->flash('error', 'This exchange request cannot be cancelled in its current state.');
            return;
        }

        $this->wasteExchange->status = 'cancelled';
        $this->wasteExchange->save();

        if ($this->wasteExchange->status === 'accepted') {
            $textileWaste = $this->wasteExchange->textileWaste;
            if ($textileWaste->availability_status === 'exchanged') {
                $textileWaste->availability_status = 'available';
            }
            $textileWaste->quantity += $this->wasteExchange->quantity;
            $textileWaste->save();
        }

        session()->flash('success', 'Exchange request cancelled successfully.');
        $this->dispatch('exchangeStatusUpdated');
    }

    public function completeExchange()
    {
        if (!in_array(Auth::user()->companyProfile->id, [
            $this->wasteExchange->supplier_company_id,
            $this->wasteExchange->receiver_company_id
        ])) {
            return;
        }

        if ($this->wasteExchange->status !== 'accepted') {
            session()->flash('error', 'This exchange request cannot be completed in its current state.');
            return;
        }

        $this->wasteExchange->status = 'completed';
        $this->wasteExchange->exchange_date = now();
        $this->wasteExchange->save();

        session()->flash('success', 'Exchange marked as completed successfully.');
        $this->dispatch('exchangeStatusUpdated');
    }

   public function downloadReceipt(WasteExchange $wasteExchange)
{
    // Authorization check - only the supplier or receiver can download the receipt
    $companyId = Auth::user()->companyProfile->id;
    if ($wasteExchange->supplier_company_id !== $companyId && $wasteExchange->receiver_company_id !== $companyId) {
        abort(403, 'Unauthorized action.');
    }

    // Only allow downloading receipts for completed exchanges
    if ($wasteExchange->status !== 'completed') {
        return redirect()->back()->with('error', 'Receipt is only available for completed exchanges.');
    }

    return response()->stream(function () use ($wasteExchange) {
        $pdf = PDF::loadView('pdf.exchange-receipt', ['exchange' => $wasteExchange]);
        echo $pdf->output();
    }, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="exchange-receipt-' . $wasteExchange->id . '.pdf"',
    ]);
    // $pdf = PDF::loadView('pdf.exchange-receipt', ['exchange' => $wasteExchange]);
    // return $pdf->download('exchange-receipt-' . $wasteExchange->id . '.pdf');
}

    public function render()
    {
        return view('livewire.waste-exchange-actions');
    }
}
