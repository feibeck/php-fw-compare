<?php

namespace Todo;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;

use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;

use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleUsageProviderInterface, ConsoleBannerProviderInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->em = $e->getApplication()->getServiceManager()->get('doctrine.entitymanager.orm_default');

        $events = $e->getApplication()->getEventManager()->getSharedManager();
        $events->attach(
            'ZfcUser\Service\User',
            "register.post",
            array($this, 'onNewUser')
        );

    }

    public function onNewUser(Event $e)
    {
        $parameters = $e->getParams();

        $user = $parameters['user'];
        $conn = $this->em->getConnection();

        $conn->insert(
            'user_role_linker',
            array(
                'user_id' => $user->getId(),
                'role_id' => 'user'
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConsoleUsage(Console $console){
        return array(
            'emailreminder' => 'Send E-Mails for all reminders',
        );
    }

    public function getConsoleBanner(Console $console)
    {
        return
            "==------------------------------------------------------==\n" .
            "        Welcome to TODO app                               \n" .
            "==------------------------------------------------------==\n" .
            "Version 0.0.1\n"
        ;
    }

}
