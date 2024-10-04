<?php

namespace App\Livewire\Tables;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductBySupplierTable extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $sortField = 'name';
    public $sortAsc = true;
    public $supplier = null;
    public $totalSellingPrice;
    public $totalBuyingPrice;

    public function mount($supplier, $totalSellingPrice, $totalBuyingPrice)
    {
        $this->supplier = $supplier;
        $this->totalSellingPrice = $totalSellingPrice;
        $this->totalBuyingPrice = $totalBuyingPrice;
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.tables.product-by-supplier-table', [
            'products' => Product::where('supplier_id', $this->supplier->id)
                ->search($this->search) // Đảm bảo có phương thức search trong model Product
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
