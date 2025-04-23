<?php

namespace App\Livewire\Tables;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    public $search = '';

    public $sortField = 'name';

    public $sortAsc = false;

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
        $query = Customer::with(['orders' => function ($q) {
            $q->where('order_status', 1); // Chỉ lấy đơn hàng trạng thái 1
        }, 'quotations']);

        if (auth()->user()->username !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        $customers = $query
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);


        // Tính toán cho từng khách hàng
        $customers->getCollection()->transform(function ($customer) {
            $orders = $customer->orders;

            $customer->totalAmount = $orders->sum('total');
            $customer->cashAmount = $orders->where('payment_type', 'Tiền mặt')->sum('total');
            $customer->bankAmount = $orders->where('payment_type', 'Chuyển khoản')->sum('total');

            return $customer;
        });

        // Tính tổng tiền của tất cả khách hàng
        $totalAmount = $customers->sum('totalAmount');
        $cashAmount = $customers->sum('cashAmount');
        $bankAmount = $customers->sum('bankAmount');

        return view('livewire.tables.customer-table', [
            'customers' => $customers,
            'totalAmount' => $totalAmount,
            'cashAmount' => $cashAmount,
            'bankAmount' => $bankAmount,
        ]);
    }

}
