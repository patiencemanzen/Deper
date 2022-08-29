<?php
    namespace Patienceman\Deper;

    use Throwable;
    use Closure;
    use Exception;
    use Illuminate\Support\Str;

    trait Injectable {
        /**
         * Stardand class scope identifier
         * @var
         */
        protected $classScope = "to";

        /**
         * All mapped class properties
         */
        protected static $mappedObjects = [];

        /**
         * Deper utility class
         */
        protected static DeperUtils $deperUtils;

        /**
         * Deper utility class
         */
        protected static ClassInstances $classInstance;

        /**
         * Call function in current class
         *
         * @param string $method
         * @param string|array $parameters
         * @return Exception|Object
         */
        public function __call($name, $arguments) {
            if(isset($this->$name))
                return call_user_func_array($this->$name, $arguments);

            return self::$deperUtils->findAndCallMethod($name, $arguments);
        }


        /**
         * Call function in current class
         *
         * @param string|static $name
         * @param string|array $parameters
         * @return Exception|Object
         */
        public static function __callStatic($name, $arguments) {
            if(isset(self::$name))
                return call_user_func_array(self::$name, $arguments);

            return self::$deperUtils->findAndCallMethod($name, $arguments);
        }

        /**
         * Call class property
         *
         * @param string $name
         * @return Exception|Object|mixed
         */
        public function __get(string $name) {
            if(Str::contains($name, $this->classScope))
                return $this->depFrom(Str::after($name, $this->classScope));

            if(isset($this->$name)) return $this->$name;

            return self::$deperUtils->findAndCallProperty($name);
        }

        /**
         * Instatiate the classes
         *
         * @param array $classes
         * @return Dependencies|Object
         */
        public static function inject(array $classes) {
            if(empty($classes)) return;

            self::$deperUtils = new DeperUtils();

            self::$classInstance = new ClassInstances();

            self::$deperUtils->cleanAndRegisterObject($classes);

            return new self;
        }

        /**
         * Map classes and aliases
         *
         * @param array $aliases
         * @return void
         */
        public function mapClassAliases(array $aliases){
            if(empty($aliases)) return;

            foreach(self::$deperUtils->getObjects() as $key => $class){
                self::$mappedObjects[$aliases[$key]] = $class;
            }
        }

        /**
         * Call class with aliase by from function
         *
         * @param string $aliase
         * @return Exception|Object
         */
        public function depFrom(string $alias){
            self::$deperUtils->catchIf(function() use ($alias) {
                    self::$mappedObjects[$alias];
                }, function($e) use ($alias) {
                    throw new Exception("Undefined class scope, register \"{$alias}\" inside Dependency aliases");
                });

            return self::$classInstance->accessLockedProperty(app(self::$mappedObjects[$alias]));
        }
    }
