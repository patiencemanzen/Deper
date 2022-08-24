# Deper from Patienceman

Deper is a dependency injector, which is able to inject even more than 4 classes in a simple way and is reliable,
So now we are able to use multiple classes functions at once, :100: :fireworks: 

## Installation

Installing the package doesn't require much requirement except to paste the following command in the laravel terminal,  and you're good to go.

```bash
composer require patienceman/deper
```

## Usage
It is easy to deal with this package, however, when you start to inject heavy classes, like thousand functions, it will take
a while to find your function, and adds up to API / execution time.
So once the installation is done! you need to create a new class file! for example
```PHP 
namespace App\Dependencies;

use Patienceman\Deper\Dependencies;

class PatientDependencies extends Dependencies {
    /**
     * Class constructor.
     */
    public function __construct() {
        return $this->boot();
    }

    /**
     * Resources to be injected
     *
     * @return array
     */
    public function injections(): array {
        return [
            \App\Services\Mood::class, // or new Mood()
            \App\Services\Energy::class, // or new Energy()
            \App\Actions\Move::class // or new Move()
        ];
    }
}
```
You can apply even to many classes to be injected, Just put it inside the injection return array.
So now on let's use our dependencies anywhere we want!
```PHP 
namespace App\Http\Controllers;

use App\Dependencies\PatientDependencies;
use Illuminate\Http\Request;

class TestController extends Controller {
    /**
     * Store a newly created resource in storage.
     *
     * @param PatientDependencies $dependency
     * @return \Illuminate\Http\Response
     */
    public function store(PatientDependencies $dependency) {
       return $dependency->mood(); // or $dependency->energy();
    }
}
```
Woow :smile: :star: :star2: , that amazing step forward we made!
See!, easy-peasy!

## class inheritance
So now that was the easy step to use!, let see how the inheritance work for dependencies!, Note: we will use the above example
```PHP 
namespace App\Services;

use App\Dependencies\PatientDependencies;

class Human extends PatientDependencies {
    /**
     * Class constructor.
     */
    public function __construct() {
        parent::__construct();
    }

   /**
     * Possess a new spirit
     *
     * @return string
     */
    public function Spirit () {
        $this->mood();
        $this->energy();
        return "spirit created and possessed successfully";
    }
}
```
That was awesome right? :+1: :call_me_hand: :ambulance: 

## Let use trait
So now on let's use traits to make it easier, Note: with current example
```PHP 
namespace App\Services;

use Patienceman\Deper\Injectable;

class Human {
    use Injectable;

    protected $dependencies = [
         \App\Services\Mood::class, // or new Mood()
         \App\Services\Energy::class, // or new Energy()
         \App\Actions\Move::class // or new Move()
    ];

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->inject($this->dependencies);
    }

   /**
     * Possess a new spirit
     *
     * @return string
     */
    public function Spirit () {
        $this->mood();
        $this->energy();
        return "spirit created and possessed successfully";
    }
}
```
This is so amazing! :100: :smile: 



## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)
