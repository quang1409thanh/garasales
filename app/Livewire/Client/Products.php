<?php

namespace App\Livewire\Client;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'id';

    public $sortAsc = false;

    public $inStock = false; // Thêm biến để theo dõi checkbox

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
        return view('livewire.client.products', [
            'products' => Product::where("user_id", 1)
                ->with(['category', 'unit'])
                ->search($this->search) // Hàm search để tìm kiếm sản phẩm
                // Thêm điều kiện lọc khi checkbox được chọn
                ->when($this->inStock, function ($query) {
                    return $query->where('quantity', '>', 0); // Chỉ lấy sản phẩm có quantity > 0
                })
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
