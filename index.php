<?php
/**
 * Created by PhpStorm.
 * User: akhgar-af
 * Date: 8/9/2016
 * Time: 10:46 AM
 */

GLOBAL $includePath;
GLOBAL $assetsUrl;
$includePath = '/';
				
require_once './lib/router.php';
$router = new Router();
return $router->go(array(
	'afshin'=>'examples/afshin.php',
	'/'=>'examples/afshin.php'
));
