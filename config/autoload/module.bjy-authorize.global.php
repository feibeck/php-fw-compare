<?php

return array(
    'bjyauthorize' => array(
        'default_role' => 'guest',
        'unauthorized_strategy' => 'BjyAuthorize\View\UnauthorizedStrategy',
        'role_providers' => array(
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array(),
                'user'  => array(),
            ),
        ),
        'resource_providers' => array(
        ),
        'rule_providers' => array(
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'zfcuser', 'roles' => array()),
                array('controller' => 'Todo\Controller\Index', 'roles' => array('user')),
                array('controller' => 'Todo\Controller\Cli', 'roles' => array('guest', 'user')),
                array('controller' => 'Todo\Controller\Feed', 'roles' => array('guest', 'user')),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'error/403' => __DIR__ . '/../../module/Application/view/error/403.phtml',
        ),
    ),
);
