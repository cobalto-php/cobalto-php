<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Libraries
| 2. Helper files
| 3. Plugins
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

require_once BASEPATH.'vendor/autoload'.EXT;
// require_once BASEPATH.'libraries/CobaltoTrait'.EXT;

class ClassLoader
{

	private static $search_paths = [
		"autenticacao", "dashboard", "exemplo", "gerenciador", "util"
   	];

   public static function loader($class_name)
   {
       if (substr($class_name, (strlen($class_name)-3), 3) == 'ORM'){
           require_once self::files_mapper()[$class_name];
       }
   }

   private static function files_mapper()
   {
       $files_mapper = [];
       $base_path = str_replace(['system', '../system'], '', BASEPATH);
       foreach (self::$search_paths as $search_path){
           foreach (glob($base_path.$search_path.'/models/*/*ORM'.EXT) as $file){
               $files_mapper[basename($file, EXT)] = $file;
           }
           foreach (glob($base_path.$search_path.'/models/*ORM'.EXT) as $file){
               $files_mapper[basename($file, EXT)] = $file;
           }
       }
       return $files_mapper;
   }
}

spl_autoload_register('ClassLoader::loader');

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your system/application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/

$autoload['libraries'] = array('database', 'ajax', 'encrypt', 'validate', 'tabpanel', 'gridpanel', 'jqgridpanel', 'jasperreportgenerate', 'twitter', 'unit_test', 'auth', 'session');

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/

$autoload['helper'] = array('url', 'lang', 'formaux', 'form', 'util', 'toolbar', 'tabpanel', 'gridpanel', 'jqgridpanel', 'editorgridpanel', 'download', 'inflector', 'string', 'file');

/*
| -------------------------------------------------------------------
|  Auto-load Plugins
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['plugin'] = array('captcha', 'js_calendar');
*/

$autoload['plugin'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

$autoload['language'] = array('system');

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1', 'model2');
|
*/

$autoload['model'] = array();

/* End of file autoload.php */
/* Location: ./system/application/config/autoload.php */