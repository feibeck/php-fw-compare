<?php
/**
 * Global Configuration Override
 */

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'charset'  => 'utf8',
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'USERNAME',
                    'password' => 'PASSWORD',
                    'dbname'   => 'DATABASE',
                )
            )
        )
    )
);
