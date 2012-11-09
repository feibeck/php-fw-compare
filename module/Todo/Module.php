<?php

namespace Todo;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;

class Module
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
}
