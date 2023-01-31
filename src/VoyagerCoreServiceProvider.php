<?php

declare(strict_types=1);

namespace Joy\VoyagerCore;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Joy\VoyagerCore\Events\FormFieldsRegistered;
use Joy\VoyagerCore\Http\Middleware\VoyagerAdminMiddleware;
use Joy\VoyagerCore\Facades\Voyager as VoyagerFacade;
use Joy\VoyagerCore\FormFields\After\DescriptionHandler;
use Joy\VoyagerCore\Models\Via;
use TCG\Voyager\Facades\Voyager as TcgVoyager;

/**
 * Class VoyagerCoreServiceProvider
 *
 * @category  Package
 * @package   JoyVoyagerCore
 * @author    Ramakant Gangwar <gangwar.ramakant@gmail.com>
 * @copyright 2021 Copyright (c) Ramakant Gangwar (https://github.com/rxcod9)
 * @license   http://github.com/rxcod9/joy-voyager-core/blob/main/LICENSE New BSD License
 * @link      https://github.com/rxcod9/joy-voyager-core
 */
class VoyagerCoreServiceProvider extends ServiceProvider
{
    /**
     * Boot
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $event)
    {
        TcgVoyager::useModel('Via', Via::class);

        $this->registerPublishables();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'joy-voyager');

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        if (config('joy-voyager.database.autoload_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'joy-voyager');

        $router->aliasMiddleware('admin.user', VoyagerAdminMiddleware::class);
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix(config('joy-voyager.route_prefix', 'api'))
            ->middleware('api')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('JoyVoyager', VoyagerFacade::class);

        $this->app->singleton('joy-voyager', function () {
            return new Voyager();
        });

        $this->registerFormFields();

        $this->mergeConfigFrom(__DIR__ . '/../config/voyager.php', 'joy-voyager');

        $this->registerCommands();
    }

    /**
     * Register publishables.
     *
     * @return void
     */
    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../config/voyager.php' => config_path('joy-voyager.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/joy-voyager'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../resources/views/formfields' => resource_path('views/vendor/voyager/formfields'),
        ], 'overwrite-voyager-views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/joy-voyager'),
        ], 'translations');
    }

    protected function registerCommands(): void
    {
        //
    }

    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'multiple_checkbox',
            'color',
            'date',
            'file',
            'image',
            'multiple_images',
            'media_picker',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'code_editor',
            'markdown_editor',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'time',
            'timestamp',
            'hidden',
            'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = Str::studly("{$formField}_handler");

            VoyagerFacade::addFormField("Joy\\VoyagerCore\\FormFields\\{$class}");
        }

        VoyagerFacade::addAfterFormField(DescriptionHandler::class);

        event(new FormFieldsRegistered($formFields));
    }
}
