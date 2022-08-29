<?php
    namespace Patienceman\Deper;

    class ClassWrapper {
        /**
         * Initialize class to be wrapped
         */
        protected static $object;

        /**
         * Initialize classWrapper instance
         *
         * @param Object
         * @return ClassWrapper
         */
        public function __construct($obj) {
            self::$object = $obj;

            return $this;
        }

        /**
         * Call inside the object when missed
         * @param string $name
         * @param string|array $arguments
         * @return mixed
         */
        public function __call($name, $arguments) {
            $func = self::$object->$name;

            return $func(...$arguments);
        }

        /**
         * Call inside the object when missed
         * @param string $name
         * @param string|array $arguments
         * @return mixed
         */
        public static function __callStatic($name, $arguments) {
            $func = self::$object->$name;

            return $func(...$arguments);
        }

        /**
         * Get single property from wrapped class
         *
         * @param string $name
         * @return string|Object
         */
        public function __get($name) {
            return self::$object->$name;
        }
    }
