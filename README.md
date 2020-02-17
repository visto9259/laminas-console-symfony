# Laminas-Console-Symfony: A Laminas MVC console app using Symfony Console


## Introduction

`laminas-console-symfony` provides a replacement for `zf-console` with a wrapper for the `symfony/console` component.

For developers familiar with Laminas MVC application, this package is inspired by `laminas-mvc` in its usage of modules and dependency injection using the Service Manager.

## Requirements

Please see the [composer.json](composer.json) file.

## Installation

Run the following `composer` command:

    $ composer require visto9259/laminas-console-symfony
    
Or, alternatively, manually add the following to your `composer.json`, in the `require` section:

    "require" : {
        "visto9259/laminas-console-symfony" : "^1.0.0"
    }

And then run `composer update` to ensure the module is installed.

## Creating a console application

The console application skeleton is based on the Laminas Framework MVC Application skeleton.  It follows a similar directory structure:

    MyApp/
       config/
          autoload/
             global.php
             local.php
             ... other config files
          application.config.php
          modules.config.php
          commands.config.php
          development.config.php (optional)
       module/
          myModule/
            config/
               module.config.php (optional)
            src/
               Module.php
       vendor/
          composer/
          ...
          autoload.php (created by Composer)
       myapp.php (entry point of the application)
       
The `myapp.php` file contains the console application code and can be as simple as:
```php
require __DIR__.'/vendor/autoload.php';
     
use Laminas\Stdlib\ArrayUtils;
use LaminasSymfonyConsole\Application;
     
// Setup the application
$appConfig = require __DIR__ . '/config/application.config.php_dist';
if (file_exists(__DIR__ . '/config/development.config.php')) {
   $appConfig = ArrayUtils::merge($appConfig, require __DIR__ . '/config/development.config.php');
}
$application = new Application($appConfig);
     
$exit = $application->run();
```
### Configuring the application

The file `application_config.php` provides the configuration for the console application and for the loading of the modules using the Laminas Framework Module Manager.  It contains the following:

```php
return [
    'console_application_options' => [
        'name' => 'Test',
        'version' => '0.1',
    ],
    'commands' => require __DIR__ . '/commands.config.php_dist',
    'modules' => require __DIR__ . '/modules.config.php',
    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor',
        ],
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],
    'service_manager' => [
        // typical Service Manager configation
    ],   
];
```
`console_application_options` contains the `$name` and `$version` arguments passed to `Symfony\Component\Console\Application` constructor.

`commands` is an array of command configurations.  See [Defining Commands](#DefiningCommands) for details.

`modules` and `module_listener_options` are the Module Manager configuration parameters.  

`service_manager` contains the typical Laminas Framework Service Manager configuration which allows for services, factories and so on to be defined.
 
 #### Differences with `laminas-mvc` applications
 
 This application is inspired by the `laminas-mvc` application but it is not intended to implement a full-fledge MVC application.
 It is a wrapper around the Symfony Console Application that provides a Service Manager for dependency injection and uses the Module Manager to load modules.
 
 The intent is that if one wants to use an existing or a custom Laminas module, it can be added to the list of modules to be loaded.
 For example, if a module defines services to access a database, it will be loaded by the Module Manager and its services added to the Service Manager.
 

 
 #### Limitations in Module Configuration
 The LaminasConsoleSymfony application uses the Module Manager `loadModules()` method to load modules but will only service modules that implement the `ServiceProviderInterface` and the `ConfigProviderInterface`interfaces.  All other provider interfaces are ignored.
 
 **Important:** The Module class **SHOULD NOT** implement the `BootstrapListenerInterface` and `InitProviderInterface` for the time being.  These will make the application crash.

### <a name="DefiningCommands"></a>Defining commands

Symfony Console commands can be defined in the `config/commands.config.php`.  They will be instantiated and added to Console application when it is created.

A typical `commands.config.php` would contain the following:

```php
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LaminasSymfonyConsole\Factory\AbstractCommandFactory;

return [
    'commands' => [
        [
            'name' => 'test',
            'description' => 'Test command',
            'help' => 'Test help message',
            'class' => Test::class,
            'arguments' => [
                [
                    'name' => 'arg1',
                    'mode' => InputArgument::OPTIONAL,
                    'description' => 'Argument 1',
                ],
                [
                    'name' => 'arg2',
                    'mode' => InputArgument::OPTIONAL,
                    'description' => 'Argument 2',
                    'default' => 'some default',
                ],
            ],
            'options' => [
                [
                    'name' => 'yell',
                    'shortcut' => 'y',
                    'mode' => InputOption::VALUE_REQUIRED,
                    'description' => 'yell option',
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Test::class => AbstractCommandFactory::class,
        ]
    ]
];
```

`commands` is an array of Symfony Command definitions.  The `name`, `description` and `help` usage is defined in the Symfony Command documentation.
The`arguments` and `options` arrays contains lists of Symfony `InputArgument`and `InputOptions` definitions.

The `class` element of a command definition refers to the name of the class that implements the Symfony Command.  It is mandatory that the class extends the `Symfony\Component\Console\Command\Command` class.
`class`can either by a class name or a string.  The application will use the Laminas Framework Service Manager to instantiate the class which allows for the use of factories to create the command and inject dependencies.
`class` can also be an instance of a `Symfony\Component\Console\Command\Command` class.

`service_manager` contains the typical Service Manager configuration that will be added to the application Service Manager.  
If a Command does not need a specific factory to create it, the `class` element of the Command definition can simply provide the class name of the Command and the Service Manager will use `AbstractCommandFactory` to create the command. 

## Feedback

This package is work-in-progress.  It has not been thoroughly tested for all thinkable cases.  If you find a problem, please log an issue.

I am also very opened to feedback and suggestions for improvements.

###### &copy; 2019 Eric Richer (eric.richer@vistoconsulting.com)
