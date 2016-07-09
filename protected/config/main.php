<?php

/*
require_once( dirname(__FILE__) . '/../helpers/Orders.php');
require_once( dirname(__FILE__) . '/../helpers/GCM.php');
require_once( dirname(__FILE__) . '/../helpers/QrCodes.php');
require_once( dirname(__FILE__) . '/../helpers/Cipher.php');
require_once( dirname(__FILE__) . '/../helpers/Login.php');*/

// require_once( dirname(__FILE__) . '/../lib/check_out/Twocheckout.php');

//require_once(Yii::getPathOfAlias('application.components.PayPal') . '/PPBootStrap.php');

define("PRIVATE_KEY" , "2AA5AAFB-343F-47C6-886C-7D187459FB25");
define("SELLER_ID" , "901262532");
define("TRANS_PATH" , "/var/www/html/kinjo/protected/locale");
//require_once ('PHPUnit/Framework/TestCase.php');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');


//require_once( Yii::getPathOfAlias('system.test.CTestCase').'.php' );
//-----------------------------------------------------------------

define("AUTHORIZENET_API_LOGIN_ID","78W75pYUqs");    // Add your API LOGIN ID
define("AUTHORIZENET_TRANSACTION_KEY","2jJ3xEcx88w6fS9t"); // Add your API transaction key
define("AUTHORIZENET_SANDBOX",true);       // Set to false to test against production
define("TEST_REQUEST", "FALSE");           // You may want to set to true if testing against production


// You only need to adjust the two variables below if testing DPM
define("AUTHORIZENET_MD5_SETTING","");                // Add your MD5 Setting.
//$site_root = "http://YOURDOMAIN/samples/your_store/"; // Add the URL to your site
//-----------------------------------------------------------------
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Kinjo',
	 'defaultController' => 'Home/Stores',
	// 'languages'=>array('lang'),
	// preloading 'log' component
	'preload'=>array('log'),
	'language' => 'en',
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.easyimage.EasyImage',
		'application.helpers.*',
		'application.lib.*',
		
		'application.helpers.Facebook.*',
		//'application.helpers.PayPalSDK.*',
		//'application.helpers.anet_php_sdk.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
	
		'clientScript' => array('scriptMap' => array('jquery.js' => false, )),
		// 'language' => 'lt',
		// 'messages' => array(
	        // 'class' => 'CGettextMessageSource',
	    // ),
		'easyImage' => array(
		    'class' => 'application.extensions.easyimage.EasyImage',
		    //'driver' => 'GD',
		    //'quality' => 100,
		    //'cachePath' => '/assets/easyimage/',
		    //'cacheTime' => 2592000,
		    //'retinaSupport' => false,
		  ),
	
	
	
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=kinjo',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'password',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		// 'lang'=>array()
		// 'Facebook'=>array(  
	        // 'appId' => '742477709179739',
	        // 'secret' => '3a54dd1fce8ffd83d4a901dddb4e23a3',
	        // 'cookie' => true,
	    // ),
	),
);
