<?php

namespace App\Livewire\Tables;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'id';

    public $sortAsc = false;
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

    public function render()
    {
        return view('livewire.tables.product-table', [
            'products' => Product::where("user_id", auth()->id())
                ->with(['category', 'unit'])
                ->search($this->search)
                // Thêm điều kiện lọc khi checkbox được chọn
                ->when($this->inStock, function ($query) {
                    return $query->where('quantity', '>', 0); // Chỉ lấy sản phẩm có quantity > 0
                })
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
