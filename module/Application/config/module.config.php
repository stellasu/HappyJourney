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
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
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
        		
        	'listArea' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/listarea',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\Index',
        							'action'        => 'listArea',
        					),
        			),
        	),  
        		
        	'customizedTravel' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/customizedtravel',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\CustomizedTravel',
        							'action'        => 'index',
        					),
        			),
        	),
        		
        	'submitCustomizedTravelMessage' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/customizedtravel/submit',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\CustomizedTravel',
        							'action'        => 'submitcustomizedtravelmessage',
        					),
        			),
        	),

        	'areaDetail' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/area/detail/:id',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\CustomizedTravel',
        							'action'        => 'detail',
        					),
        			),
        	),
        		
        	'shuttleservice' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/shuttleservice',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\ShuttleService',
        							'action'        => 'index',
        					),
        			),
        	),
        		
        	'listitinerary' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/shuttleservice/listitinerary',
        					'defaults' => array(
        							'controller'    => 'Application\Controller\ShuttleService',
        							'action'        => 'listItinerary',
        					),
        			),
        	),
        		
        		'submitCustomerItinerary' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route'    => '/shuttleservice/submit',
        						'defaults' => array(
        								'controller'    => 'Application\Controller\ShuttleService',
        								'action'        => 'submitCustomerItinerary',
        						),
        				),
        		),
        		
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        	'database' => 'Application\Service\Factory\Database',
        	'entity-manager' => 'Application\Service\Factory\EntityManager',
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
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        	'Application\Controller\CustomizedTravel' => 'Application\Controller\CustomizedTravelController',
        	'Application\Controller\ShuttleService' => 'Application\Controller\ShuttleServiceController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'application/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'application' => __DIR__ . '/../view',
        ),
    	'strategies' => array(
    		'ViewJsonStrategy',
    	),
    ),
	'doctrine' => array( 
		'entity_path' => array(
			__DIR__ . '/../src/Application/Model/Entity/',
		),
	)
		
		
);
