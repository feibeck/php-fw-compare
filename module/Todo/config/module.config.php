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
                                'action'        => 'edit',
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
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Todo\Controller\Index' => 'Todo\Controller\IndexController'
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
     )

);