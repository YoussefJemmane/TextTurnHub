<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WasteExchange;

class WasteExchangeHistory extends Component
{
    use WithPagination;

    public $status = '';
    public $dateFrom = null;
    public $dateTo = null;
    public $search = '';

    protected $updatesQueryString = ['status', 'dateFrom', 'dateTo', 'search'];

    public function render()
    {
        $query = WasteExchange::with(['textileWaste', 'supplierCompany', 'receiverCompany'])
            ->whereIn('status', ['completed', 'cancelled']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->dateFrom) {
            $query->whereDate('updated_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('updated_at', '<=', $this->dateTo);
        }

        if ($this->search) {
            $query->whereHas('textileWaste', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }

        $exchanges = $query->orderByDesc('updated_at')->paginate(10);

        return view('livewire.waste-exchange-history', [
            'exchanges' => $exchanges,
        ]);
    }

    public function resetFilters()
{
    $this->reset(['status', 'dateFrom', 'dateTo', 'search']);
    $this->resetPage();
    $this->dispatch('reset-filters');
}
}
