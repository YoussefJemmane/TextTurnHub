<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class ProductTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectAll = false;

    protected $paginationTheme = 'tailwind';

    // Reset page when searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Sorting logic
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // Select all logic
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = Product::where(function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('category', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->pluck('id')
            ->toArray();
        } else {
            $this->selected = [];
        }
    }

    // Delete selected products
    public function deleteSelected()
    {
        Product::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->selectAll = false;
        session()->flash('success', 'Selected products deleted successfully.');
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::where(function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('category', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.product-table', [
            'products' => $products,
        ]);
    }
}
