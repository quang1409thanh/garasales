<?php

namespace App\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'invoice_no';

    public $sortAsc = false;
    public $totalOrdersAmount ;
    public function mount($totalOrdersAmount)
    {
        $this->totalOrdersAmount = $totalOrdersAmount;
    }


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

    public function render()
    {
        $query = Order::with(['customer', 'details']);

        if (auth()->user()->username !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        return view('livewire.tables.order-table', [
            'orders' => $query
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }

}
