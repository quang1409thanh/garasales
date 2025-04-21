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
        $query = Product::with(['category', 'unit']);

        if (auth()->user()->username !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        // Lọc theo checkbox "Còn hàng"
        $query->when($this->inStock, function ($query) {
            return $query->where('quantity', '>', 0);
        });

        return view('livewire.tables.product-table', [
            'products' => $query
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }

}
