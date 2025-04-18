<div class="products-swipe-container">
    <!-- Xử lý loading -->
    <div wire:loading class="products-swipe-loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Container chính cho trải nghiệm lướt sản phẩm -->
    <div
        x-data="{
            currentIndex: @entangle('currentIndex'),
            productCount: {{ count($products) }},
            isScrolling: false,
            lastScrollTop: 0,
            init() {
                this.setupObserver();
                this.setupScrollBehavior();
            },
            setupObserver() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const index = parseInt(entry.target.dataset.index);
                            if (index >= this.productCount - 2) {
                                $wire.loadMoreProducts();
                            }
                            $wire.setCurrentIndex(index);
                        }
                    });
                }, {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.7
                });

                // Theo dõi tất cả các sản phẩm
                document.querySelectorAll('.product-swipe-item').forEach(item => {
                    observer.observe(item);
                });
            },
            setupScrollBehavior() {
                // Tối ưu hóa cho trải nghiệm cuộn mượt mà
                document.querySelector('.products-swipe-scroll').addEventListener('scroll', () => {
                    const scrollContainer = document.querySelector('.products-swipe-scroll');
                    const scrollTop = scrollContainer.scrollTop;
                    const direction = scrollTop > this.lastScrollTop ? 'down' : 'up';
                    this.lastScrollTop = scrollTop;

                    // Phát hiện chạm tới cuối để tải thêm dữ liệu
                    if (direction === 'down' &&
                        scrollContainer.scrollHeight - scrollContainer.scrollTop <= scrollContainer.clientHeight + 100) {
                        $wire.loadMoreProducts();
                    }
                });
            }
        }"
        x-init="init"
        class="products-swipe-wrapper"
        wire:key="products-swipe-container"
    >
        <!-- Scroll container chính -->
        <div class="products-swipe-scroll">
            @forelse($products as $index => $product)
                <div
                    class="product-swipe-item"
                    data-index="{{ $index }}"
                    wire:key="product-{{ $product->uuid }}"
                >
                    <div class="product-swipe-content">
                        <img
                            class="product-swipe-image"
                            src="{{ $product->product_image ? $product->product_image : 'https://storage.googleapis.com/garasales/thumbnails/default.png' }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                        >

                        <div class="product-swipe-info">
                            <h3 class="product-swipe-name">{{ $product->name }}</h3>
                            <div class="product-swipe-price">{{ $product->selling_price }}</div>

                            <div class="product-swipe-category">
                                <span class="badge bg-blue-lt">
                                    {{ $product->category ? $product->category->name : '--' }}
                                </span>
                            </div>

                            <div class="product-swipe-actions">
                                <button
                                    class="btn btn-primary btn-sm"
                                    wire:click="viewProductDetails('{{ $product->uuid }}')"
                                >
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="products-swipe-empty">
                    <p>Không tìm thấy sản phẩm nào</p>
                </div>
            @endforelse
        </div>

        <!-- Các nút điều hướng -->
        <div class="product-swipe-navigation">
            <button
                class="btn btn-icon btn-outline-primary swipe-nav-up"
                @click="document.querySelector('.products-swipe-scroll').scrollBy({top: -window.innerHeight, behavior: 'smooth'})"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M6 15l6 -6l6 6"></path>
                </svg>
            </button>
            <button
                class="btn btn-icon btn-outline-primary swipe-nav-down"
                @click="document.querySelector('.products-swipe-scroll').scrollBy({top: window.innerHeight, behavior: 'smooth'})"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-down" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M6 9l6 6l6 -6"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Modal xem chi tiết sản phẩm -->
    @if($showDetails && $currentProduct)
        <div
            class="product-detail-modal"
            x-data="{}"
            x-init="$el.classList.add('show')"
        >
            <div class="product-detail-content">
                <div class="product-detail-header">
                    <h3>{{ $currentProduct->name }}</h3>
                    <button class="btn btn-icon btn-sm" wire:click="closeProductDetails">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="product-detail-body">
                    <div class="product-detail-image">
                        <img
                            src="{{ $currentProduct->product_image ? $currentProduct->product_image : 'https://storage.googleapis.com/garasales/thumbnails/default.png' }}"
                            alt="{{ $currentProduct->name }}"
                        >
                    </div>

                    <div class="product-detail-info">
                        <div class="product-detail-price">{{ $currentProduct->selling_price }}</div>

                        <div class="product-detail-category">
                        <span class="badge bg-blue-lt">
                            {{ $currentProduct->category ? $currentProduct->category->name : '--' }}
                        </span>
                        </div>

                        <div class="product-detail-code">
                            <strong>Mã sản phẩm:</strong> {{ $currentProduct->code }}
                        </div>

                        <div class="product-detail-supplier">
                            <strong>Nhà cung cấp:</strong>
                            @if($currentProduct->supplier)
                                <a class="badge bg-green-lt" href="{{ route('supplier_client.show', $currentProduct->supplier->uuid) }}">
                                    {{ $currentProduct->supplier->name }}
                                </a>
                            @else
                                --
                            @endif
                        </div>

                        <div class="product-detail-actions">
                            <a href="{{ route('product_client.show', $currentProduct->uuid) }}" class="btn btn-primary">
                                Xem trang sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .products-swipe-container {
            position: relative;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            background-color: #f5f5f5;
        }

        .products-swipe-wrapper {
            height: 100%;
            position: relative;
        }

        .products-swipe-scroll {
            height: 100%;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
        }

        /*// Đồng thời cập nhật CSS trong component Livewire:*/
        /*// Trong .product-swipe-item*/
           .product-swipe-item {
               height: 100vh;
               width: 100%;
               display: flex;
               align-items: center;
               justify-content: center;
               scroll-snap-align: start;
               position: relative;
           }

        .product-swipe-content {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-swipe-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .product-swipe-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            z-index: 2;
        }

        .product-swipe-name {
            font-size: 1.5rem;
            margin-bottom: 8px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }

        .product-swipe-price {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-swipe-category {
            margin-bottom: 15px;
        }

        .product-swipe-actions {
            display: flex;
            gap: 10px;
        }

        .product-swipe-navigation {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 10;
        }

        .products-swipe-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 1000;
        }

        .products-swipe-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        /* Modal chi tiết sản phẩm */
        .product-detail-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .product-detail-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .product-detail-content {
            width: 90%;
            max-width: 800px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .product-detail-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-detail-body {
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        @media (min-width: 768px) {
            .product-detail-body {
                flex-direction: row;
            }

            .product-detail-image {
                width: 50%;
            }

            .product-detail-info {
                width: 50%;
                padding-left: 20px;
            }
        }

        .product-detail-image img {
            width: 100%;
            border-radius: 5px;
            object-fit: cover;
        }

        .product-detail-price {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .product-detail-category,
        .product-detail-code,
        .product-detail-supplier {
            margin-bottom: 10px;
        }

        .product-detail-actions {
            margin-top: 20px;
        }

        /* Hiệu ứng thêm */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .product-swipe-item {
            animation: fadeIn 0.5s ease;
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .product-swipe-navigation {
                right: 10px;
            }

            .product-swipe-name {
                font-size: 1.2rem;
            }

            .product-swipe-price {
                font-size: 1rem;
            }
        }
    </style>
</div>
