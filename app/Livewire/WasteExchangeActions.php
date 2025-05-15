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

    private function isSupplier(): bool
    {
        return Auth::user()->companyProfile->id === $this->wasteExchange->supplier_company_id;
    }

    private function isReceiver(): bool
    {
        return Auth::user()->companyProfile->id === $this->wasteExchange->receiver_company_id;
    }

    private function unauthorizedAction(): void
    {
        abort(403, 'Unauthorized action.');
    }

    private function handleInvalidState(string $expectedStatus, string $action)
    {
        if ($this->wasteExchange->status !== $expectedStatus) {
            session()->flash('error', "This exchange request cannot be {$action} in its current state.");
            return true;
        }
        return false;
    }

    private function flashAndDispatch(string $message)
    {
        session()->flash('success', $message);
        $this->dispatch('exchangeStatusUpdated');
    }

    public function acceptRequest()
    {
        if (!$this->isSupplier() || $this->handleInvalidState('requested', 'accepted')) return;

        $this->wasteExchange->update(['status' => 'accepted']);

        $textileWaste = $this->wasteExchange->textileWaste;
        $this->wasteExchange->quantity >= $textileWaste->quantity
            ? $textileWaste->update(['availability_status' => 'exchanged'])
            : $textileWaste->decrement('quantity', $this->wasteExchange->quantity);

        $this->flashAndDispatch('Exchange request accepted successfully.');
    }

    public function rejectRequest()
    {
        if (!$this->isSupplier() || $this->handleInvalidState('requested', 'rejected')) return;

        $this->wasteExchange->update(['status' => 'cancelled']);
        $this->flashAndDispatch('Exchange request rejected successfully.');
    }

    public function cancelRequest()
    {
        if (!$this->isReceiver()) return;

        if (!in_array($this->wasteExchange->status, ['requested', 'accepted'])) {
            session()->flash('error', 'This exchange request cannot be cancelled in its current state.');
            return;
        }

        $wasAccepted = $this->wasteExchange->status === 'accepted';

        $this->wasteExchange->update(['status' => 'cancelled']);

        if ($wasAccepted) {
            $textileWaste = $this->wasteExchange->textileWaste;

            if ($textileWaste->availability_status === 'exchanged') {
                $textileWaste->availability_status = 'available';
            }

            $textileWaste->quantity += $this->wasteExchange->quantity;
            $textileWaste->save();
        }

        $this->flashAndDispatch('Exchange request cancelled successfully.');
    }

    
    public function completeExchange()
    {
        $user = Auth::user();
        $isAuthorized = false;
    
        if ($user->hasRole('company')) {
            $companyId = $user->companyProfile->id;
            $isAuthorized = in_array($companyId, [
                $this->wasteExchange->supplier_company_id,
                $this->wasteExchange->receiver_company_id
            ]);
        } elseif ($user->hasRole('artisan')) {
            $artisanId = $user->artisanProfile->id;
            $isAuthorized = $this->wasteExchange->receiver_artisan_id === $artisanId;
        }
    
        if (!$isAuthorized || $this->handleInvalidState('accepted', 'completed')) {
            return;
        }
    
        $this->wasteExchange->update([
            'status' => 'completed',
            'exchange_date' => now()
        ]);
    
        $message = $user->hasRole('artisan') 
            ? 'Purchase marked as completed successfully.' 
            : 'Exchange marked as completed successfully.';
    
        $this->flashAndDispatch($message);
    }

        
    public function downloadReceipt(WasteExchange $wasteExchange)
    {
        $user = Auth::user();
        $isAuthorized = false;
    
        if ($user->hasRole('company')) {
            $companyId = $user->companyProfile->id;
            $isAuthorized = in_array($companyId, [
                $wasteExchange->supplier_company_id, 
                $wasteExchange->receiver_company_id
            ]);
        } elseif ($user->hasRole('artisan')) {
            $artisanId = $user->artisanProfile->id;
            $isAuthorized = $wasteExchange->receiver_artisan_id === $artisanId;
        }
    
        if (!$isAuthorized) {
            $this->unauthorizedAction();
        }
    
        if ($wasteExchange->status !== 'completed') {
            return redirect()->back()->with('error', 'Receipt is only available for completed exchanges.');
        }
    
        $filename = $user->hasRole('artisan') ? 'purchase-receipt-' : 'exchange-receipt-';
        $filename .= $wasteExchange->id . '.pdf';
    
        return response()->stream(function () use ($wasteExchange) {
            $pdf = PDF::loadView('pdf.exchange-receipt', ['exchange' => $wasteExchange]);
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function render()
    {
        return view('livewire.waste-exchange-actions');
    }
}
