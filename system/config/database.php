<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "default";
$active_record = TRUE;

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "cobalto-desenv";
$db['default']['password'] = "cobalto-desenv";
$db['default']['database'] = "cobalto-desenv";
$db['default']['dbdriver'] = "postgre";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

ActiveRecord\Config::initialize(function($cfg) use ($db, $active_group)
{
    $cfg->set_connections([$active_group => "pgsql://{$db[$active_group]['username']}:{$db[$active_group]['password']}@{$db[$active_group]['hostname']}/{$db[$active_group]['database']}".(isset($db[$active_group]['port']) ? ";port={$db[$active_group]['port']}" : "") ]);
    $cfg->set_default_connection($active_group);
    $cfg->set_logging(true);
    $cfg->set_logger(new CI_Log());
});

ActiveRecord\DateTime::$DEFAULT_FORMAT = 'db';
ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';

/* End of file database.php */
/* Location: ./system/application/config/database.php */
