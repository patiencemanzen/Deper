<?php

    namespace Patienceman\Deper;

    use Illuminate\Support\ServiceProvider;
    use Patienceman\Deper\Console\InitializeDeperFile;

    class DeperServiceProvider extends ServiceProvider {
        /**
         * Register services.
         *
         * @return void
         */
        public function register() {

        }

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot() {
            if ($this->app->runningInConsole()) {
                $this->commands([
                    InitializeDeperFile::class
                ]);
            }
        }
    }
