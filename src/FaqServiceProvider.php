<?php 

namespace Gwaps4nlp\FaqManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class FaqServiceProvider extends ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $viewPath = __DIR__.'/../resources/views';
        $this->loadViewsFrom($viewPath, 'faq-manager');
        $this->publishes([
            $viewPath => base_path('resources/views/vendor/faq-manager'),
        ], 'views');

        $migrationPath = __DIR__.'/../database/migrations';
        $this->loadMigrationsFrom($migrationPath); 
              
        $this->publishes([
            $migrationPath => base_path('database/migrations'),
        ], 'migrations');

        $config =  [
            'prefix' => 'faq',
            'middleware' => ['web'],
        ];
        $config['namespace'] = 'Gwaps4nlp\FaqManager';

        $config = $this->app['config']->get('faq-manager.front-route', []);
        $config['namespace'] = 'Gwaps4nlp\FaqManager';

        $router->group($config, function($router)
        {
            $router->get('/', 'FaqController@getIndex')->name('faq');
        });

        $config = $this->app['config']->get('faq-manager.back-route', []);
        $config['namespace'] = 'Gwaps4nlp\FaqManager';

        $router->group($config, function($router)
        {
            $router->get('/admin-index', 'FaqController@getAdminIndex');
            $router->post('/create-question-answer', 'FaqController@postCreateQuestionAnswer');
            $router->post('/delete-question-answer', 'FaqController@postDeleteQuestionAnswer');
            $router->post('/create-section-faq', 'FaqController@postCreateSectionFaq');
            $router->post('/delete-section-faq', 'FaqController@postDeleteSectionFaq');
            $router->post('/update-order-sections', 'FaqController@postUpdateOrderSections');
            $router->post('/update-order-question-answer', 'FaqController@postUpdateOrderQuestionAnswer');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/faq-manager.php';
        $this->mergeConfigFrom($configPath, 'faq-manager');
        $this->publishes([$configPath => config_path('faq-manager.php')], 'config');
    }

}
