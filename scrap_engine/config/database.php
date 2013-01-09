<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
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
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// ----------------------------------------------------------------------- SETTINGS HANDLING
/*
 -------------------------------------------------------------------------------------------------
Used exclusivly to get the database information so that it only needs to be changed in one place.
-------------------------------------------------------------------------------------------------
*/
$settings = simplexml_load_file('scrap_engine/settings/scrap_settings.xml');
// ----------------------------------------------------------------------- CONNECTION
// Chosen connection
$chosen_conn 				= trim($settings->application->current_conn);

//  Local connection settings
$db_name_local 				= $settings->local_conn->database_name;
$db_path_local 				= $settings->local_conn->database_path;
$db_username_local 			= $settings->local_conn->database_username;
$db_password_local 			= $settings->local_conn->database_password;

// Production connection settings
$db_name_prod 				= $settings->production_conn->database_name;
$db_path_prod 				= $settings->production_conn->database_path;
$db_username_prod 			= $settings->production_conn->database_username;
$db_password_prod 			= $settings->production_conn->database_password;
/*
 END OF SETTINGS CODE
*/

$active_group 				= $chosen_conn;
$active_record 				= TRUE;

$db['local']['hostname'] 	= $db_path_local;
$db['local']['username'] 	= $db_username_local;
$db['local']['password'] 	= $db_password_local;
$db['local']['database'] 	= $db_name_local;
$db['local']['dbdriver'] 	= 'mysql';
$db['local']['dbprefix'] 	= '';
$db['local']['pconnect'] 	= FALSE;
$db['local']['db_debug'] 	= TRUE;
$db['local']['cache_on'] 	= FALSE;
$db['local']['cachedir'] 	= '';
$db['local']['char_set'] 	= 'utf8';
$db['local']['dbcollat'] 	= 'utf8_general_ci';
$db['local']['swap_pre'] 	= '';
$db['local']['autoinit'] 	= TRUE;
$db['local']['stricton'] 	= FALSE;

$db['production']['hostname'] 	= $db_path_prod;
$db['production']['username'] 	= $db_username_prod;
$db['production']['password'] 	= $db_password_prod;
$db['production']['database'] 	= $db_name_prod;
$db['production']['dbdriver'] 	= 'mysql';
$db['production']['dbprefix'] 	= '';
$db['production']['pconnect'] 	= FALSE;
$db['production']['db_debug'] 	= TRUE;
$db['production']['cache_on'] 	= FALSE;
$db['production']['cachedir'] 	= '';
$db['production']['char_set'] 	= 'utf8';
$db['production']['dbcollat'] 	= 'utf8_general_ci';
$db['production']['swap_pre'] 	= '';
$db['production']['autoinit'] 	= TRUE;
$db['production']['stricton'] 	= FALSE;


/* End of file database.php */
/* Location: ./scrap_engine/config/database.php */