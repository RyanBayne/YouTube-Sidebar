<?php
/** 
 * Default administration settings for YouTube Sidebar plugin. These settings are installed to the 
 * wp_options table and are used from there by default. 
 * 
 * @package YouTube Sidebar
 * @author Ryan Bayne   
 * @since 3.0.0
 * @version 1.0.7
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// install main admin settings option record
$youtubesidebar_settings = array();
// encoding
$youtubesidebar_settings['standardsettings']['encoding']['type'] = 'utf8';
// admin user interface settings start
$youtubesidebar_settings['standardsettings']['ui_advancedinfo'] = false;// hide advanced user interface information by default
// other
$youtubesidebar_settings['standardsettings']['ecq'] = array();
$youtubesidebar_settings['standardsettings']['chmod'] = '0750';
$youtubesidebar_settings['standardsettings']['systematicpostupdating'] = 'enabled';
// testing and development
$youtubesidebar_settings['standardsettings']['developementinsight'] = 'disabled';
// global switches
$youtubesidebar_settings['standardsettings']['textspinrespinning'] = 'enabled';// disabled stops all text spin re-spinning and sticks to the last spin

##########################################################################################
#                                                                                        #
#                           SETTINGS WITH NO UI OPTION                                   #
#              array key should be the method/function the setting is used in            #
##########################################################################################
$youtubesidebar_settings['create_localmedia_fromlocalimages']['destinationdirectory'] = 'wp-content/uploads/importedmedia/';
 
##########################################################################################
#                                                                                        #
#                            DATA IMPORT AND MANAGEMENT SETTINGS                         #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['datasettings']['insertlimit'] = 100;

##########################################################################################
#                                                                                        #
#                                    WIDGET SETTINGS                                     #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['widgetsettings']['dashboardwidgetsswitch'] = 'disabled';

##########################################################################################
#                                                                                        #
#                               CUSTOM POST TYPE SETTINGS                                #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['posttypes']['wtgflags']['status'] = 'disabled'; 
$youtubesidebar_settings['posttypes']['posts']['status'] = 'disabled';

##########################################################################################
#                                                                                        #
#                                    NOTICE SETTINGS                                     #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['noticesettings']['wpcorestyle'] = 'enabled';

##########################################################################################
#                                                                                        #
#                           YOUTUBE RELATED SETTINGS                                     #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['videooptions']['maximumvideos'] = 1;
$youtubesidebar_settings['videooptions']['color'] = '&color1=0x2b405b&color2=0x6b8ab6';
$youtubesidebar_settings['videooptions']['border'] = 'enable';
$youtubesidebar_settings['videooptions']['autoplay'] = 'enable';
$youtubesidebar_settings['videooptions']['fullscreen'] = 'enable';
$youtubesidebar_settings['videooptions']['scriptaccess'] = 'always';

##########################################################################################
#                                                                                        #
#                                  AD SETTINGS                                          #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['adoptions']['maximumads'] = 1;
// other
$youtubesidebar_settings['ecq'] = array();
$youtubesidebar_settings['chmod'] = '0750';
// ads - 250 x 250 example
$youtubesidebar_settings['adsnippets'][0]['time'] = time();
$youtubesidebar_settings['adsnippets'][0]['source'] = 'google';
$youtubesidebar_settings['adsnippets'][0]['snippet'] = '<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* YouTubeSidebar250250 */
google_ad_slot = "5814814678";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
// ads - 200 x 200 example
$youtubesidebar_settings['adsnippets'][1]['time'] = time();
$youtubesidebar_settings['adsnippets'][1]['source'] = 'google';
$youtubesidebar_settings['adsnippets'][1]['snippet'] = '<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* YouTubeSidebar200200 */
google_ad_slot = "7291547875";
google_ad_width = 200;
google_ad_height = 200;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
// ads - 125 x 125 example
$youtubesidebar_settings['adsnippets'][2]['time'] = time();
$youtubesidebar_settings['adsnippets'][2]['source'] = 'google';
$youtubesidebar_settings['adsnippets'][2]['snippet'] = '<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* YouTubeSidebar125125 */
google_ad_slot = "8768281070";
google_ad_width = 125;
google_ad_height = 125;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';

##########################################################################################
#                                                                                        #
#                                  LOG SETTINGS                                          #
#                                                                                        #
##########################################################################################
$youtubesidebar_settings['logsettings']['uselog'] = 1;
$youtubesidebar_settings['logsettings']['loglimit'] = 1000;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['timestamp'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['line'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['function'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['page'] = true; 
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['panelname'] = true;   
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['userid'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['type'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['category'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['action'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['priority'] = true;
$youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['comment'] = true;
?>