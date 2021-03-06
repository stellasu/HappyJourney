<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/administration',
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'administration' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/administration',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
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
                ),
            ),
        		
        	'login' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/login',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\Auth',
        							'action'        => 'login',
        					),
        			),
        	),  
        		
        	'logout' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/logout',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\Auth',
        							'action'        => 'logout',
        					),
        			),
        	),
        		
        	'auth' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/authenticate',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\Auth',
        							'action'        => 'authenticate',
        					),
        			),
        	),
        		
        	'updatetext' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/updatetext',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\Index',
        							'action'        => 'updatetext',
        					),
        			),
        	),
        		
        	'managecustomizedtravel' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/customizedtravel[/:page]',
        					'constraints'   => array(
        							'page'        => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\CustomizedTravel',
        							'action'        => 'listcustomermessage',
        					),
        			),
        	),
        		
        	'deletecustomizedtravelmessage' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/customizedtravel/deletemessage',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\CustomizedTravel',
        							'action'        => 'deleteMessage',
        					),
        			),
        	),
        		
        	'editarea' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/customizedtravel/editarea',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\CustomizedTravel',
        							'action'        => 'editarea',
        					),
        			),
        	),
        		
        	'addarea' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/customizedtravel/addarea',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\CustomizedTravel',
        							'action'        => 'addarea',
        					),
        			),
        	),
        		
        	'manageshuttleservice' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/shuttleservice[/:page]',
        					'constraints'   => array(
        							'page'        => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\ShuttleService',
        							'action'        => 'index',
        					),
        			),
        	),
        		
        	'manageitinerary' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/shuttleservice/manageitinerary[/:page]',
        					'constraints'   => array(
        							'page'        => '[0-9]+',
        					),
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\ShuttleService',
        							'action'        => 'manageitinerary',
        					),
        			),
        	),
        		
        	'additinerary' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/shuttleservice/additinerary',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\ShuttleService',
        							'action'        => 'additinerary',
        					),
        			),
        	),
        		
        	'closecustomeritinerary' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/shuttleservice/closecustomeritinerary',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\ShuttleService',
        							'action'        => 'closeCustomerItinerary',
        					),
        			),
        	),
        		
        	'edititinerary' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/administration/shuttleservice/edititinerary',
        					'defaults' => array(
        							'controller'    => 'Administration\Controller\ShuttleService',
        							'action'        => 'editItinerary',
        					),
        			),
        	),
        		
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        	'database' => 'Administration\Service\Factory\Database',
        	'entity-manager' => 'Administration\Service\Factory\EntityManager',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Administration\Controller\Index' => 'Administration\Controller\IndexController',
        	'Administration\Controller\Login' => 'Administration\Controller\LoginController',
        	'Administration\Controller\Auth' => 'Administration\Controller\AuthController',
        	'Administration\Controller\CustomizedTravel' => 'Administration\Controller\CustomizedTravelController',
        	'Administration\Controller\ShuttleService' => 'Administration\Controller\ShuttleServiceController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'admin/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'administration/index/index' => __DIR__ . '/../view/administration/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    	'strategies' => array(
    		'ViewJsonStrategy',
    	),
    ),
	'doctrine' => array( 
		'entity_path' => array(
			__DIR__ . '/../src/Administration/Model/Entity/',
		),
	),
		
);
