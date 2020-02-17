<?php
/**
 * @see       https://github.com/visto9259/zf-console-symfony for the canonical source repository
 * @copyright Copyright (c) 2019 Eric Richer (eric.richer@vistoconsulting.com)
 * @license   https://github.com/visto9259/zf-console-symfony/LICENSE GNU GENERAL PUBLIC LICENSE
 */


namespace LaminasSymfonyConsole;


use Laminas\ServiceManager\Config;
use LaminasSymfonyConsole\Factory\ModuleManagerFactory;

class ServiceManagerConfig extends Config
{

    protected $config = [
        'abstract_factories' => [],
        'aliases'            => [],
        'delegators' => [],
        'factories'  => [
            'ModuleManager' => ModuleManagerFactory::class,
        ],
        'lazy_services' => [],
        'initializers'  => [],
        'invokables'    => [],
        'services'      => [],
        'shared'        => [],

    ];

    /**
     * ServiceManagerConfig constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config['factories']['ServiceManager'] = function ($container) {
            return $container;
        };
        parent::__construct($config);
    }
}