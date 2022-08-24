<?php
    namespace Patienceman\Deper\Facades;

    use Illuminate\Support\Facades\Facade;

    class Deper extends Facade {
        protected static function getFacadeAccessor(){
            return 'Deper';
        }
    }
