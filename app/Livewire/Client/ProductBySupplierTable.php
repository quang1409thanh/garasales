<?php

namespace App\Livewire\Client;

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
    public $inStock = false; // Thêm biến để theo dõi trạng thái checkbox

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function mount($supplier)
    {
        $this->supplier = $supplier;
    }

    public function render()
    {
        return view('livewire.client.product-by-supplier-table', [
            'products' => Product::where('supplier_id', $this->supplier->id)
                ->search($this->search) // Đảm bảo có phương thức search trong model Product
                // Thêm điều kiện lọc khi checkbox "In Stock" được chọn
                ->when($this->inStock, function ($query) {
                    return $query->where('quantity', '>', 0); // Lọc sản phẩm còn hàng
                })
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }

}

