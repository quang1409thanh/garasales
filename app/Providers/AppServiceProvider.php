<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Breadcrumbs\Breadcrumbs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        // Clear cache khi có thay đổi sản phẩm
        Event::listen([
            'eloquent.created: ' . Product::class,
            'eloquent.updated: ' . Product::class,
            'eloquent.deleted: ' . Product::class,
        ], function() {
            $userId = auth()->id();
            Cache::forget("user_{$userId}_products_*"); // Xóa tất cả cache phân trang
        });

        Request::macro('breadcrumbs', function (){
            return new Breadcrumbs($this);
        });
    }
}
