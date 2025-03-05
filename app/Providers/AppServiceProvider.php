<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Observers\CategoryObserver;
use App\Observers\ProductObserver;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->singleton(GoogleDriveService::class, function($app){
        //     return new GoogleDriveService();
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // convert http to https
        // URL::forceScheme('https');
        if(env('APP_ENV') == 'production'){
            URL::forceScheme('https');
        }else{
            URL::forceScheme('http');
        }

        // create var $addresses return to page 
        View::composer('product', function ($view) {
            $user = auth()->user();
            $addresses = $user->getAddress->filter(fn($address) => $address->active == 1);
            $view->with('addresses', $addresses);
        });

        View::composer('components.ProductComponent', function ($view) {
            $user = auth()->user();
            $addresses = $user->getAddress->filter(fn($address) => $address->active == 1);
            $view->with('addresses', $addresses);
        });

        View::composer('layout.Pay', function ($view) {
            $user = auth()->user();
            $addresses = $user->getAddress->filter(fn($address) => $address->active == 1);
            $view->with('addresses', $addresses);
        });

        

        // $materials = [
        //     'ceramic' => 'Gốm, Sứ',
        //     ''
        // ];

        // Cache::remember('material', 525600, function () use ($materials) {
        //     return $materials;
        // });

        //  register Observer 
        Category::observe(CategoryObserver::class);

        Product::observe(ProductObserver::class);
        // end container 


        // register blade 

        Blade::directive('datetime', function ($expression) {
            return "<?php echo App\Client\config::handleTime($expression); ?>";
        });

        Blade::directive('handlePrice', function ($expression) {
            return "<?php echo App\Client\config::handlePrice($expression); ?>";
        });


        Blade::directive('formatPrice', function ($expression) {
            return "<?php echo App\Client\config::formatPrice($expression); ?>";
        });


        Blade::directive('HandlePriceShip', function ($expression) {
            return "<?php echo App\Client\config::HandlePriceShip($expression); ?>";
        });


        Blade::directive('HandlePriceShipListProduct', function($expression){
            return "<?php echo App\Client\config::HandlePriceShipListProduct($expression); ?>";
        });

        Blade::directive('getService', function($expression){
            return "<?php echo App\Client\config::getService($expression); ?>";
        });

        Blade::directive('calculateTimeship', function($expression){
            return "<?php echo App\Client\config::calculateTimeship($expression); ?>";
        });
    }
}
