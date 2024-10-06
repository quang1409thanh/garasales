<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class BankSelector extends Component
{
    public $search = '';  // Biến để giữ giá trị tìm kiếm
    public $banks = [];   // Biến để lưu danh sách ngân hàng phù hợp

    // Danh sách ngân hàng cố định
    protected $allBanks = [
        'Vietcombank', 'Techcombank', 'BIDV', 'Agribank', 'ACB', 'VPBank',
        'Sacombank', 'VietinBank', 'Eximbank', 'MB Bank', 'SHB',
        'OceanBank', 'HDBank', 'Kiên Long Bank', 'NAB Bank', 'TPBank'
    ];

    protected $listeners = ['searchUpdated'];

    // Hàm này được gọi khi sự kiện 'searchUpdated' từ JavaScript
    public function searchUpdated($value)
    {
        Log::info('searchUpdated được gọi. Từ khóa tìm kiếm: ' . $value);
        $this->search = $value;

        // Thực hiện lọc danh sách ngân hàng theo từ khóa tìm kiếm
        $this->banks = collect($this->allBanks)
            ->filter(function ($bank) {
                return stripos($bank, $this->search) !== false;
            })
            ->take(10)  // Giới hạn số lượng kết quả trả về (tối đa 10)
            ->values()
            ->all();

        Log::info('Kết quả lọc: ' . json_encode($this->banks));
    }

    // Hàm chọn ngân hàng
    public function selectBank($bank)
    {
        $this->search = $bank;
        $this->banks = [];  // Xóa danh sách ngân hàng sau khi chọn
    }

    public function render()
    {
        Log::info('Render component với từ khóa: ' . $this->search);
        return view('livewire.bank-selector');
    }
}
