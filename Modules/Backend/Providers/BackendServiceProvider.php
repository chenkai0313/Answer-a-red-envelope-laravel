<?php

namespace Modules\Backend\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Backend\Services\AdminService;
use Modules\Backend\Services\AdService;
use Modules\Backend\Services\ConfigService;
use Modules\Backend\Services\MerchantsService;
use Modules\Backend\Services\NotesService;
use Modules\Backend\Services\PackRecordService;
use Modules\Backend\Services\QuestionService;
use Modules\Backend\Services\RandQuestionService;
use Modules\Backend\Services\AdminLogService;
use Modules\Backend\Services\UserService;
use Modules\Backend\Services\OrderService;
use Modules\Backend\Services\DfService;


class BackendServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->addFacade();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
    }

    /**
     * 门面注册
     */
    public function addFacade()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('AdminService', \Modules\Backend\Facades\AdminFacade::class);
        $loader->alias('AdminLogService', \Modules\Backend\Facades\AdminLogFacade::class);
        $loader->alias('UserService', \Modules\Backend\Facades\UserFacade::class);
        $loader->alias('OrderService', \Modules\Backend\Facades\OrderFacade::class);
        $loader->alias('DfService', \Modules\Backend\Facades\DfFacade::class);
        $loader->alias('PackRecordService', \Modules\Backend\Facades\PackRecordFacade::class);
        $loader->alias('RandQuestionService', \Modules\Backend\Facades\RandQuestionFacade::class);
        $loader->alias('AdService', \Modules\Backend\Facades\AdFacade::class);
        $loader->alias('NotesService', \Modules\Backend\Facades\NotesFacade::class);
        $loader->alias('ConfigService', \Modules\Backend\Facades\ConfigFacade::class);
        $loader->alias('QuestionService', \Modules\Backend\Facades\QuestionFacade::class);
        $loader->alias('MerchantsService', \Modules\Backend\Facades\MerchantsFacade::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('AdminService', function () {
            return new AdminService();
        });
        $this->app->singleton('AdminLogService', function () {
            return new AdminLogService();
        });
        $this->app->singleton('UserService', function () {
            return new UserService();
        });
        $this->app->singleton('OrderService', function () {
            return new OrderService();
        });
        $this->app->singleton('DfService', function () {
            return new DfService();
        });
        $this->app->singleton('PackRecordService', function () {
            return new PackRecordService();
        });
        $this->app->singleton('RandQuestionService', function () {
            return new RandQuestionService();
        });
        $this->app->singleton('AdService', function () {
            return new AdService();
        });
        $this->app->singleton('MerchantsService', function () {
            return new MerchantsService();
        });
        $this->app->singleton('NotesService', function () {
            return new NotesService();
        });
        $this->app->singleton('ConfigService', function () {
            return new ConfigService();
        });
        $this->app->singleton('QuestionService', function () {
            return new QuestionService();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('backend.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'backend'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/backend');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/backend';
        }, \Config::get('view.paths')), [$sourcePath]), 'backend');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/backend');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'backend');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'backend');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
