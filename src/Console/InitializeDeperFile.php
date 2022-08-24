<?php

    namespace Patienceman\Deper\Console;

    use Illuminate\Console\Command;
    use Illuminate\Support\Str;

    class InitializeDeperFile extends Command {
         /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'make:deper {name}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Create your cutom dependecies wrapper';

        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         * Execute the console command.
         *
         * @return int
         */
        public function handle() {
            $name = $this->argument('name');
            $dir = "./app/Dependencies";
            $filename = $dir."/{$name}.php";

            if (!file_exists(dirname($filename)))
                mkdir(dirname($filename), 0777, true);

            fopen($filename, "w");

            $namespace = str_replace("/", "\\", Str::studly(
                dirname(str_replace(array("./"), "", $filename)
            )));

            file_put_contents(
                $filename,
                $this->getFileInitialContents($namespace, basename($filename, ".php"))
            );

            $this->info("{$namespace} create successfully");
        }

/**
 * Get file inital contents
 */
public function getFileInitialContents($namespace, $className) {
    $initFunction = '$this->boot()';

    return "<?php
    namespace $namespace;

    use Patienceman\Deper\Dependencies;

    class {$className} extends Dependencies {
        /**
         * Class constructor.
         */
        public function __construct() {
            return $initFunction;
        }

        /**
         * Resources to injected into SiteService
         *
         * @return array
         */
        public function injections(): array {
            return [
                // \App\Spaces\Human\SpaceMan::class,
            ];
        }
    }
    ";
}
    }
