<?php

return array(
    'router' => array(
        'routes' => array(
            'todo' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/todo',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Todo\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'form',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'form',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
            'feed' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/feed/:userhash',
                    'constraints' => array(
                        'userhash' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Todo\Controller',
                        'controller'    => 'Feed',
                        'action'        => 'feed',
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'user-reset-password' => array(
                    'options' => array(
                        'route'    => 'emailreminder',
                        'defaults' => array(
                            'controller' => 'Todo\Controller\Cli',
                            'action'     => 'email-reminder'
                        )
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'factories' => array(
            'Todo\Controller\Index' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                return new \Todo\Controller\IndexController(
                    $sm->get("doctrine.entitymanager.orm_default"),
                    $sm->get("translator")
                );
            },
            'Todo\Controller\Cli' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                $em = $sm->get("doctrine.entitymanager.orm_default");
                return new \Todo\Controller\CliController(
                    $em->getRepository('\Todo\Entity\Todo'),
                    $sm->get("MailTransport")
                );
            },
            'Todo\Controller\Feed' => function(Zend\Mvc\Controller\ControllerManager $cm) {
                $sm = $cm->getServiceLocator();
                $em = $sm->get("doctrine.entitymanager.orm_default");
                return new \Todo\Controller\FeedController(
                    $em->getRepository('\Todo\Entity\Todo'),
                    $em->getRepository('\Todo\Entity\User')
                );
            }
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
         'driver' => array(
             'Todo' . '_driver' => array(
                 'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                 'cache' => 'array',
                 'paths' => array(__DIR__ . '/../src/' . 'Todo' . '/Entity')
             ),
             'orm_default' => array(
                 'drivers' => array(
                     'Todo' . '\Entity' => 'Todo' . '_driver'
                 )
             )
         )
     ),
    'service_manager' => array(
        'factories' => array(
            'Todo\Authorize\IdentityProvider' => function ($sm) {
                $provider = new \Todo\Authorize\IdentityProvider();
                $provider->setUserService($sm->get('zfcuser_user_service'));
                return $provider;
            },
            'MailTransport' => function($sm) {
                $options   = new Zend\Mail\Transport\FileOptions(array(
                    'path'              => 'data/mail/',
                    'callback'  => function (\Zend\Mail\Transport\File $transport) {
                        return 'Message_' . microtime(true) . '_' . mt_rand() . '.txt';
                    },
                ));
                $transport = new \Zend\Mail\Transport\File($options);
                return $transport;
            }
        ),
    ),
    'bjyauthorize' => array(
        'identity_provider' => 'Todo\Authorize\IdentityProvider',
    ),
);