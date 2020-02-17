<?php

/**
 * Sample Symfony Command configuration
 *
 * Copy this file into the /config directory
 */


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LaminasSymfonyConsole\Factory\AbstractCommandFactory;

return [
    // Array of commands to be configured (see Symfony Command documentation)
    'commands' => [
        [
            'name' => 'test',
            'description' => 'Test command',
            'help' => 'Test help message',

            /**
             * The object class to instantiate. This can either be a class name, a string alias or an object that
             * extends the Symfony\Component\Console\Command\Command class.
             * If this is not an object, the Service Manager will be used to create the Command object
             *
             */
            'class' => Test::class,

            /**
             * Array of Symfony InputArgument definition to be added to the command.
             * See Symfony Command documentation on how to create Input Arguments
             */
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

            /**
             * Array of Symfony InputOptions definition to be added to the command.
             * See Symfony Command documentation on how to create Input Options
             */
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
    /**
     * Service Manager configuration for commands.
     * Refer to laminas-servicemanager documentation for details
     */
    'service_manager' => [
        'factories' => [
            Test::class => AbstractCommandFactory::class,
        ]
    ]
];
