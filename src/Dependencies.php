<?php
    namespace Patienceman\Deper;

    class Dependencies {
        use Injectable;

        /**
         * Execute injection and aliases inside the class
         *
         * @return void
         */
        public function boot() {
            $this->inject($this->injections());
            $this->mapClassAliases($this->aliases());
        }
    }
