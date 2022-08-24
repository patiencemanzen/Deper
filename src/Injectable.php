<?php
    namespace Patienceman\Deper;

    use Throwable;
    use Closure;

    trait Injectable {
        /**
         * centralized classes
         * @var array
         */
        protected static $centralizedProperties = [];

        /**
         * Call function in current class
         *
         * @param string $method
         * @param string|array $parameters
         */
        public function __call($name, $arguments) {
            if(isset($this->$name)) {
                array_unshift($arguments, $this);

                return call_user_func_array($this->$name, $arguments);
            }

            return self::callMethod($name, $arguments);
        }


        /**
         * Call function in current class
         *
         * @param string $method
         * @param string|array $parameters
         */
        public static function __callStatic($name, $arguments) {
            if(isset(self::$name)) {
                array_unshift($arguments, new self);

                return call_user_func_array(self::$name, $arguments);
            }

            return self::callMethod($name, $arguments);
        }

        /**
         * Instatiate the classes
         *
         * @return Clossure
         */
        public static function inject(array $clossures) {
            self::centeralizeProperties($clossures);

            return new self;
        }

        /**
         * Call a specified method if not exist in current class
         *
         * @param string $method
         * @param string|array $parameters
         */
        public static function callMethod($name, $arguments) {
            foreach(self::$centralizedProperties as $class) {
                if(method_exists(app($class), $name)){
                    return app($class)->$name(...$arguments);
                }
            }
        }

        /**
         * Dispatch the classes to the same level
         *
         * @param array $classes
         * @return array
         */
        public static function centeralizeProperties(array $classess): array {
            foreach($classess as $class) {
                self::catchIf(function() use ($class) {
                    self::$centralizedProperties = array_merge(
                        self::$centralizedProperties,
                        [get_class(app($class))]
                    );
                }, function() use ($class) {
                    self::catchIf(function() use ($class) {
                        self::$centralizedProperties = array_merge(
                            self::$centralizedProperties,
                            [get_class($class)]
                        );
                    }, function() use ($class) {
                        self::$centralizedProperties = array_merge(self::$centralizedProperties, [$class]);
                    });
                });
            }

            return self::$centralizedProperties;
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
    }
