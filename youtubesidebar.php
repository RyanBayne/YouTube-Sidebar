<?php         
/*
Plugin Name: YouTube Sidebar
Version: 3.0.0
Plugin URI: http://www.webtechglobal.co.uk/
Description: YouTube Sidebar does a little bit of everything.
Author: WebTechGlobal
Author URI: http://www.webtechglobal.co.uk/
Last Updated: June 2015
Text Domain: youtubesidebar
Domain Path: /languages

GPL v3 

This program is free software downloaded from WordPress.org: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. This means
it can be provided for the sole purpose of being developed further
and we do not promise it is ready for any one persons specific needs.
See the GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.
*/           
  
// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'Direct script access is not allowed!' );

// exit early if YouTube Sidebar doesn't have to be loaded
if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) ) // Login screen
    || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
    || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
    return;
}

// Load the modern bootstrap
require_once __DIR__ . '/bootstrap.php';
\YouTubeSidebar\Bootstrap\PluginBootstrap::boot( __FILE__ );
