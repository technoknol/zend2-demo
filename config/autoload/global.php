<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        'driver' => 'Pdo',
        // 'dsn'    => sprintf('sqlite:%s/data/zftutorial.db', realpath(getcwd())),
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),

//        'dsn'    => 'mysql:dbname=s_zf2-demo;host=localhost',
//        'username'=>  'root',
//        'password' => 'root'
    ],
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
            => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        /* Moved to Auth module to allow to be replaced by Doctrine or other.
        // added for Authentication and Authorization. Without this each time we have to create a new instance.
        // This code should be moved to a module to allow Doctrine to overwrite it
        'aliases' => array( // !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'my_auth_service',
        ),
        'invokables' => array(
            'my_auth_service' => 'Zend\Authentication\AuthenticationService',
        ),
        */
    ),
];
