<?php
/**
*
* Sample Laminas-Console-Symfony Application Configuration File
* Copy this file into the /config directory
*/
return [
    // Symfony Console Application configuration
    'console_application_options' => [
        'name' => 'Test',
        'version' => '0.1',
    ],

    // List of Symfony Console Commands to create and add to the console application
    'commands' => require __DIR__ . '/commands.config.php',

    // List of ZF modules to be loaded by zend-modulemanager
    'modules' => require __DIR__ . '/modules.config.php',

    // Laminas Module Manager Config Listener Options (see laminas-modulemanager documentation)
    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor',
        ],
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
    ],

];
