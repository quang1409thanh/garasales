<?php

namespace App\Livewire\Tables;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductByCategoryTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'name';

    public $sortAsc = true;

    public $category = null;
    public $inStock = false; // Thêm biến để theo dõi trạng thái checkbox

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

    public function mount($category)
    {
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.tables.product-by-category-table',[
            'products' => Product::where('category_id', $this->category->id)
                ->search($this->search)
                ->when($this->inStock, function ($query) {
                    return $query->where('quantity', '>', 0); // Lọc sản phẩm còn hàng
                })
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
