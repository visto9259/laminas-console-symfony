<?php
/**
 * @see       https://github.com/visto9259/laminas-console-symfony for the canonical source repository
 * @copyright Copyright (c) 2019 Eric Richer (eric.richer@vistoconsulting.com)
 * @license   https://github.com/visto9259/laminas-console-symfony/LICENSE GNU GENERAL PUBLIC LICENSE
 */


namespace LaminasSymfonyConsole\Factory;


use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractCommandFactory implements AbstractFactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName();
    }

    /**
     * @inheritDoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return in_array(Command::class, class_parents($requestedName));
    }
}