<?php
    namespace Patienceman\Deper;

    class Dependencies {
        use Injectable;

        /**
         * Execute injection inside the class
         *
         * @return mixed|Injectable|Dependencies
         */
        public function boot() {
            return $this->inject($this->injections());
        }
    }
