<?php
/** 
 * Database tables information for past and new versions.
 * 
 * This file is not fully in use yet. The intention is to migrate it to the
 * installation class and rather than an array I will simply store every version
 * of each tables query. Each query can be broken down to compare against existing 
 * tables. I find this array approach too hard to maintain over many plugins.
 * 
 * @todo move this to installation class but also reduce the array to actual queries per version
 * 
 * @package YouTube Sidebar
 * @author Ryan Bayne   
 * @since 3.0.0
 * @version 8.1.2
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
 
 
/*   Column Array Example Returned From "mysql_query( "SHOW COLUMNS FROM..."
        
          array(6) {
            [0]=>
            string(5) "row_id"
            [1]=>
            string(7) "int(11)"
            [2]=>
            string(2) "NO"
            [3]=>
            string(3) "PRI"
            [4]=>
            NULL
            [5]=>
            string(14) "auto_increment"
          }
                  
    +------------+----------+------+-----+---------+----------------+
    | Field      | Type     | Null | Key | Default | Extra          |
    +------------+----------+------+-----+---------+----------------+
    | Id         | int(11)  | NO   | PRI | NULL    | auto_increment |
    | Name       | char(35) | NO   |     |         |                |
    | Country    | char(3)  | NO   | UNI |         |                |
    | District   | char(20) | YES  | MUL |         |                |
    | Population | int(11)  | NO   |     | 0       |                |
    +------------+----------+------+-----+---------+----------------+            
*/
   
global $wpdb;   
$youtubesidebar_tables_array =  array();
##################################################################################
#                                 webtechglobal_log                                         #
##################################################################################        
$youtubesidebar_tables_array['tables']['webtechglobal_log']['name'] = $wpdb->prefix . 'webtechglobal_log';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['required'] = false;// required for all installations or not (boolean)
$youtubesidebar_tables_array['tables']['webtechglobal_log']['pluginversion'] = '0.0.1';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$youtubesidebar_tables_array['tables']['webtechglobal_log']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$youtubesidebar_tables_array['tables']['webtechglobal_log']['primarykey'] = 'row_id';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['uniquekey'] = 'row_id';
// webtechglobal_log - row_id
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['row_id']['type'] = 'bigint(20)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['row_id']['null'] = 'NOT NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['row_id']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['row_id']['default'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['row_id']['extra'] = 'AUTO_INCREMENT';
// webtechglobal_log - outcome
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['outcome']['type'] = 'tinyint(1)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['outcome']['null'] = 'NOT NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['outcome']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['outcome']['default'] = '1';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['outcome']['extra'] = '';
// webtechglobal_log - timestamp
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['type'] = 'timestamp';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['null'] = 'NOT NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['default'] = 'CURRENT_TIMESTAMP';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['timestamp']['extra'] = '';
// webtechglobal_log - line
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['line']['type'] = 'int(11)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['line']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['line']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['line']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['line']['extra'] = '';
// webtechglobal_log - file
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['file']['type'] = 'varchar(250)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['file']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['file']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['file']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['file']['extra'] = '';
// webtechglobal_log - function
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['function']['type'] = 'varchar(250)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['function']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['function']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['function']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['function']['extra'] = '';
// webtechglobal_log - sqlresult
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['type'] = 'blob';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlresult']['extra'] = '';
// webtechglobal_log - sqlquery
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlquery']['extra'] = '';
// webtechglobal_log - sqlerror
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['type'] = 'mediumtext';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['sqlerror']['extra'] = '';
// webtechglobal_log - wordpresserror
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['type'] = 'mediumtext';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['wordpresserror']['extra'] = '';
// webtechglobal_log - screenshoturl
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['type'] = 'varchar(500)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['screenshoturl']['extra'] = '';
// webtechglobal_log - userscomment
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['type'] = 'mediumtext';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userscomment']['extra'] = '';
// webtechglobal_log - page
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['page']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['page']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['page']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['page']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['page']['extra'] = '';
// webtechglobal_log - version
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['version']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['version']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['version']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['version']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['version']['extra'] = '';
// webtechglobal_log - panelid
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelid']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelid']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelid']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelid']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelid']['extra'] = '';
// webtechglobal_log - panelname
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelname']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelname']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelname']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelname']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['panelname']['extra'] = '';
// webtechglobal_log - tabscreenid
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenid']['extra'] = '';
// webtechglobal_log - tabscreenname
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['tabscreenname']['extra'] = '';
// webtechglobal_log - dump
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['dump']['type'] = 'longblob';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['dump']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['dump']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['dump']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['dump']['extra'] = '';
// webtechglobal_log - ipaddress
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['ipaddress']['extra'] = '';
// webtechglobal_log - userid
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userid']['type'] = 'int(11)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userid']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userid']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userid']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['userid']['extra'] = '';
// webtechglobal_log - comment
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['comment']['type'] = 'mediumtext';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['comment']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['comment']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['comment']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['comment']['extra'] = '';
// webtechglobal_log - type
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['type']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['type']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['type']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['type']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['type']['extra'] = '';
// webtechglobal_log - category
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['category']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['category']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['category']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['category']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['category']['extra'] = '';
// webtechglobal_log - action
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['action']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['action']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['action']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['action']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['action']['extra'] = '';
// webtechglobal_log - priority
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['priority']['type'] = 'varchar(45)';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['priority']['null'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['priority']['key'] = '';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['priority']['default'] = 'NULL';
$youtubesidebar_tables_array['tables']['webtechglobal_log']['columns']['priority']['extra'] = '';              
?>