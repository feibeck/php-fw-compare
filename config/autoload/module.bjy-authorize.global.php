<?php

return array(
    'bjyauthorize' => array(
        'default_role' => 'guest',
        'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserDoctrine',
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
            ),
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'error/403' => __DIR__ . '/../../module/Application/view/error/403.phtml',
        ),
    ),
);