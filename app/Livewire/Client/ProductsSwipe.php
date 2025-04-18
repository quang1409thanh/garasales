<?php

namespace App\Livewire\Client;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsSwipe extends Component
{
    use WithPagination;

    public $perPage = 5; // Số lượng sản phẩm tải mỗi lần
    public $currentIndex = 0;
    public $showDetails = false;
    public $currentProduct = null;

    protected $listeners = [
        'loadMore' => 'loadMoreProducts',
        'viewProductDetails' => 'viewProductDetails',
        'closeProductDetails' => 'closeProductDetails'
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function loadMoreProducts()
    {
        $this->perPage += 5;
    }

    public function viewProductDetails($productUuid)
    {
        $this->currentProduct = Product::where('uuid', $productUuid)->first();
        $this->showDetails = true;
    }

    public function closeProductDetails()
    {
        $this->showDetails = false;
        $this->currentProduct = null;
    }

    public function setCurrentIndex($index)
    {
        $this->currentIndex = $index;
        // Khi người dùng đã lướt đến phần cuối, tải thêm sản phẩm
        if ($index >= $this->perPage - 2) {
            $this->loadMoreProducts();
        }
    }

    public function render()
    {
        // Lấy danh sách sản phẩm với hình ảnh và thông tin cần thiết
        $products = Product::with(['category', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.client.products-swipe', [
            'products' => $products,
        ]);
    }
}
