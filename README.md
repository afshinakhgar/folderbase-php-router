
Folderbase PHP Router!
===================
[![AUR](https://img.shields.io/aur/license/yaourt.svg?maxAge=2592000?style=plastic)]()

A lightweight and simple object oriented PHP Router.
Built by Afshin Akhgar- [http://www.akhgar.net](http://www.akhgar.net)
---------
### Features
- Static Route
- Dynamic Route
- Folder and file and subfolders routing
- Subrouting
- Parameter sending over url and query String Support
- Custom 404 handling
- Works fine in subfolders
> **Note:**
> - This Library is under development

#### <i class="icon-file"></i>  Demo
- First of all get the lib
- Include it in your php index file 
- Call The router go Method
```
GLOBAL $includePath;
GLOBAL $assetsUrl;
$includePath = '/';
				
require_once './lib/router.php';
$router = new Router();
return $router->go(array(
	'afshin'=>'examples/afshin.php',
	'/'=>'examples/afshin.php'
));

```
