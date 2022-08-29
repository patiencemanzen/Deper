<?php
    namespace Patienceman\Deper;

    use Closure;
    use Throwable;

    class DeperUtils {
        /**
         * centralized classes
         * @var array
         */
        protected static $objects = [];

        /**
         * Call a specified method if not exist in current class
         *
         * @param string $method
         * @param string|array $parameters
         * @return Exception|Object
         */
        public static function findAndCallMethod($name, $arguments) {
            foreach(self::$objects as $class) {
                if(method_exists(app($class), $name))
                    return app($class)->$name(...$arguments);
            }
        }

        /**
         * Call propert when don't exist in class
         *
         * @param string $name
         * @return Exception|Object
         */
        public function findAndCallProperty($name) {
            foreach(self::$objects as $class) {
                if(app($class)->$name)
                    return app($class)->$name;
            }
        }

        /**
         * Clean and then register the recieved objectes
         *
         * @param array $classes
         * @return array
         */
        public static function cleanAndRegisterObject(array $classes): array {
            foreach($classes as $class) {
                self::catchIf(function() use ($class) {
                        self::mergeProperty(get_class(app($class)));
                    }, function() use ($class) {
                        self::catchIf(function() use ($class) {
                                self::mergeProperty(get_class($class));
                            }, function() use ($class) {
                                self::mergeProperty($class);
                            });
                    });
            }

            return self::$objects;
        }

        /**
         * Add new cleaned property into center properties
         *
         * @param string|Object $class
         * @return void
         */
        protected static function mergeProperty($class) {
            self::$objects = array_merge(self::$objects, [$class]);
        }

        /**
         * Catch a potential exception and return a default value.
         *
         * @param  callable  $callback
         * @param  mixed  $rescue
         * @param  bool  $report
         * @return mixed
         */
        public static function catchIf(callable $callback, $execute = null, $report = true) {
            try {
                return $callback();
            } catch (Throwable $e) {
                if($report) report($e);

                return $execute instanceof Closure ? $execute($e) : $execute;
            }
        }

        /**
         * Get all Objects
         *
         * @return static array
         */
        public function getObjects() {
            return self::$objects;
        }
    }
