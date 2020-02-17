<?php

/**
 * @see       https://github.com/visto9259/laminas-console-symfony for the canonical source repository
 * @copyright Copyright (c) 2019 Eric Richer (eric.richer@vistoconsulting.com)
 * @license   https://github.com/visto9259/laminas-console-symfony/LICENSE GNU GENERAL PUBLIC LICENSE
 */


namespace LaminasSymfonyConsole;

use Symfony\Component\Console\Command\Command;
use Laminas\ServiceManager\ServiceManager;
use Exception;

class CommandConfig
{
    protected $name;
    protected $class;
    protected $arguments = [];
    protected $options = [];

    protected $command;

    /**
     * CommandConfig constructor.
     * @param $config
     * @param ServiceManager $container
     * @throws Exception
     */
    public function __construct($config, ServiceManager $container)
    {
        if (!is_array($config) || !isset($config['name']) || !isset($config['class'])) {
            throw new Exception('Invalid command configuration');
        }
        // Create the command
        $this->name = $config['name'];
        $this->class = $config['class'];
        if ($this->class instanceof Command) {
            $this->command = $this->class;
        } elseif (is_string($this->class)) {
            $this->command = $container->get($this->class);
        }
        $this->command->setName($config['name']);
        if (isset($config['description'])) $this->command->setDescription($config['description']);
        if (isset($config['help'])) $this->command->setHelp($config['help']);
        // process arguments
        if (isset($config['arguments'])) {
            if (is_array($config['arguments'])) {
                foreach ($config['arguments'] as $argument) {
                    if (is_array($argument)) {
                        $this->command->addArgument($argument['name'], $argument['mode'], $argument['description'],
                            isset($argument['default']) ?$argument['default'] : null);
                    }
                }
            }
        }
        // process the options
        if (isset($config['options'])) {
            if (is_array($config['options'])) {
                foreach ($config['options'] as $option) {
                    if (is_array($option)) {
                        $this->command->addOption($option['name'], $option['shortcut'],$option['mode'], $option['description'],
                            isset($option['default']) ?$option['default'] : null);
                    }
                }
            }
        }

    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }
}