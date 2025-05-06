<?php

namespace App\Livewire;

use App\Models\TextileWaste;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TextileWasteTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'title';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = TextileWaste::pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteSelected()
    {
        TextileWaste::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', 'Selected wastes deleted successfully!');
    }

    public function render()
    {
        $textileWastes = TextileWaste::query()
            ->where('company_profiles_id', Auth::user()->companyProfile->id)
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('waste_type', 'like', '%' . $this->search . '%')
                    ->orWhere('material_type', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            

            ->paginate(5);

        return view('livewire.textile-waste-table', [
            'textileWastes' => $textileWastes,
        ]);
    }
}
