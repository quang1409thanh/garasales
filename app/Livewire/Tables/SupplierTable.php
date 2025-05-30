<?php

namespace App\Livewire\Tables;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'name';

    public $sortAsc = false;
    public $totalSellingPrice;
    public $totalBuyingPrice;


    public function sortBy($field): void
    {
        if($this->sortField === $field)
        {
            $this->sortAsc = ! $this->sortAsc;

        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
    public function mount( $totalSellingPrice, $totalBuyingPrice)
    {
        $this->totalSellingPrice = $totalSellingPrice;
        $this->totalBuyingPrice = $totalBuyingPrice;
    }
    public function render()
    {
        return view('livewire.tables.supplier-table', [
            'suppliers' => Supplier::where("user_id", auth()->id())
                ->with(['purchases'])
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
