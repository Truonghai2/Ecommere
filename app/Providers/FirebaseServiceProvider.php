<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('firebase.firestore', function ($app) {
            $serviceAccountPath = storage_path('firebase/credentials.json');
            $googleCloud = new ServiceBuilder([
                'keyFilePath' => $serviceAccountPath,
            ]);

            return $googleCloud->firestore();
        });

        $this->app->singleton('firebase.auth', function ($app) {
            $serviceAccountPath = storage_path('firebase/credentials.json');
            $googleCloud = new ServiceBuilder([
                'keyFilePath' => $serviceAccountPath,
            ]);

            return $googleCloud->auth();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
