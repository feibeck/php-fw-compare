<?php
return array(
    'modules' => array(
        'Application',
        'DoctrineModule',
        'DoctrineORMModule',
        'Todo',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
//    'doctrine' => array(
//        'driver' => array(
//            'default_driver' => array(
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => array(__DIR__ . '/../module/Todo/src/Todo/Entity')
//            ),
//            'orm_default' => array(
//                'drivers' => array(
//                    'Todo\Entity' => 'default_driver'
//                )
//            )
//        ),
//        'connection' => array(
//            'orm_default' => array()
//        )
//    )
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '23306',
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'zf2',
                )
            )
        )
    ),



);
