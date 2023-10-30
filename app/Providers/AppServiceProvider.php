<?php

namespace App\Providers;

use Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Schema::defaultStringLength(191);
    Paginator::useBootstrap();

    Menus::creating(function ($model) {
      Cache::forget('menu_' . $model->id);
    });
    Menus::updated(function ($model) {
      Cache::forget('menu_' . $model->id);
    });
    Menus::deleted(function ($model) {
      Cache::forget('menu_' . $model->id);
    });

    MenuItems::creating(function ($model) {
      Cache::forget('menu_' . $model->menu);
    });
    MenuItems::updated(function ($model) {
      Cache::forget('menu_' . $model->menu);
    });
    MenuItems::deleted(function ($model) {
      Cache::forget('menu_' . $model->menu);
    });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    
  }
}
