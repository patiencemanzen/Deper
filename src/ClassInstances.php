<?php
    namespace Patienceman\Deper;

    use ReflectionClass;
    use ReflectionMethod;
    use ReflectionProperty;
    use stdClass;

    class ClassInstances {
        /**
         * Create new class object
         */
        protected $newObject;

        /**
         * Remove all accessibility block from the class
         */
        public function accessLockedProperty($obj) {
            $obj = new $obj;

            $this->newObject = new stdClass();

            $this->getProperties($obj);

            $this->getMethods($obj);

            return (new ClassWrapper($this->newObject));
        }

        /**
         * Get and register all class properties
         *
         * @param Object $obj
         * @return void
         */
        public function getProperties($obj) {
            $reflection = new ReflectionClass($obj);

            $props = $reflection->getProperties(
                ReflectionProperty::IS_PROTECTED |
                ReflectionProperty::IS_PUBLIC |
                ReflectionProperty::IS_STATIC
            );

            foreach($props as $prop){
                $propName = $prop->name;

                $this->newObject->$propName = $prop->getValue($obj);
            }
        }

        /**
         * Get and register all class methods
         *
         * @param Object $obj
         * @return void
         */
        public function getMethods($obj) {
            $reflection = new ReflectionClass($obj);

            $methods = $reflection->getMethods(
                ReflectionProperty::IS_PROTECTED |
                ReflectionProperty::IS_PUBLIC |
                ReflectionProperty::IS_STATIC
            );

            foreach($methods as $method){
                $method = $method->name;
                $this->newObject->$method = function (...$parameters) use ($method, $obj) {
                    $reflectedMethod = new ReflectionMethod($obj, $method);
                    $reflectedMethod->setAccessible(true);
                    return $reflectedMethod->invoke(new $obj, ...$parameters);
                };
            };
        }
    }
