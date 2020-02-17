<?php
/**
 * @see       https://github.com/visto9259/laminas-console-symfony for the canonical source repository
 * @copyright Copyright (c) 2019 Eric Richer (eric.richer@vistoconsulting.com)
 * @license   https://github.com/visto9259/laminas-console-symfony/LICENSE GNU GENERAL PUBLIC LICENSE
 */

namespace LaminasSymfonyConsole;


use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

class Application
{

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var CommandsConfig
     */
    protected $commandsConfig;

    /**
     * @var Application
     */
    protected $consoleApp;

    public function __construct( $appConfig = [])
    {
        // Create a service manager
        $smConfig = isset($appConfig['service_manager']) ? $appConfig['service_manager'] : [];
        $smConfig = new ServiceManagerConfig($smConfig);
        $sm = new ServiceManager();
        $this->serviceManager = $smConfig->configureServiceManager($sm);
        $sm->setService('ApplicationConfig', $appConfig);

        // Create a module manager
        $moduleManager = $sm->get('ModuleManager');
        $moduleManager->loadModules();

        // Create a Symfony Console Application
        if (!isset($appConfig['console_application_options'])) {
            $this->consoleApp = new SymfonyConsoleApplication();
        } else {
            $appName = isset($appConfig['console_application_options']['name']) ? $appConfig['console_application_options']['name'] : 'Console';
            $appVersion = isset($appConfig['console_application_options']['version']) ? $appConfig['console_application_options']['version'] : '1.0';
            $this->consoleApp = new SymfonyConsoleApplication($appName, $appVersion);
        }

        // Get the commands config
        $this->commandsConfig = new CommandsConfig($appConfig['commands'], $this->serviceManager);
        foreach ($this->commandsConfig->getCommands() as $command) {
            $this->consoleApp->add($command);
        }
    }

    /**
     * @return Application
     */
    public function getConsoleApplication()
    {
        return $this->consoleApp;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        return $this->consoleApp->run();
    }

}

