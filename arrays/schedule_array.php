<?php
/** 
 * Default schedule array for YouTube Sidebar plugin 
 * 
 * @package YouTube Sidebar
 * @author Ryan Bayne   
 * @since 3.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$youtubesidebar_schedule_array = array();
// history
$youtubesidebar_schedule_array['history']['lastreturnreason'] = __( 'None', 'youtubesidebar' );
$youtubesidebar_schedule_array['history']['lasteventtime'] = time();
$youtubesidebar_schedule_array['history']['lasteventtype'] = __( 'None', 'youtubesidebar' );
$youtubesidebar_schedule_array['history']['day_lastreset'] = time();
$youtubesidebar_schedule_array['history']['hour_lastreset'] = time();
$youtubesidebar_schedule_array['history']['hourcounter'] = 1;
$youtubesidebar_schedule_array['history']['daycounter'] = 1;
$youtubesidebar_schedule_array['history']['lasteventaction'] = __( 'None', 'youtubesidebar' );
// times/days
$youtubesidebar_schedule_array['days']['monday'] = true;
$youtubesidebar_schedule_array['days']['tuesday'] = true;
$youtubesidebar_schedule_array['days']['wednesday'] = true;
$youtubesidebar_schedule_array['days']['thursday'] = true;
$youtubesidebar_schedule_array['days']['friday'] = true;
$youtubesidebar_schedule_array['days']['saturday'] = true;
$youtubesidebar_schedule_array['days']['sunday'] = true;
// times/hours
$youtubesidebar_schedule_array['hours'][0] = true;
$youtubesidebar_schedule_array['hours'][1] = true;
$youtubesidebar_schedule_array['hours'][2] = true;
$youtubesidebar_schedule_array['hours'][3] = true;
$youtubesidebar_schedule_array['hours'][4] = true;
$youtubesidebar_schedule_array['hours'][5] = true;
$youtubesidebar_schedule_array['hours'][6] = true;
$youtubesidebar_schedule_array['hours'][7] = true;
$youtubesidebar_schedule_array['hours'][8] = true;
$youtubesidebar_schedule_array['hours'][9] = true;
$youtubesidebar_schedule_array['hours'][10] = true;
$youtubesidebar_schedule_array['hours'][11] = true;
$youtubesidebar_schedule_array['hours'][12] = true;
$youtubesidebar_schedule_array['hours'][13] = true;
$youtubesidebar_schedule_array['hours'][14] = true;
$youtubesidebar_schedule_array['hours'][15] = true;
$youtubesidebar_schedule_array['hours'][16] = true;
$youtubesidebar_schedule_array['hours'][17] = true;
$youtubesidebar_schedule_array['hours'][18] = true;
$youtubesidebar_schedule_array['hours'][19] = true;
$youtubesidebar_schedule_array['hours'][20] = true;
$youtubesidebar_schedule_array['hours'][21] = true;
$youtubesidebar_schedule_array['hours'][22] = true;
$youtubesidebar_schedule_array['hours'][23] = true;
// limits
$youtubesidebar_schedule_array['limits']['hour'] = '1000';
$youtubesidebar_schedule_array['limits']['day'] = '5000';
$youtubesidebar_schedule_array['limits']['session'] = '300';
// event types (update event_action() if adding more eventtypes)
// deleteuserswaiting - this is the auto deletion of new users who have not yet activated their account 
$youtubesidebar_schedule_array['eventtypes']['deleteuserswaiting']['name'] = __( 'Delete Users Waiting', 'youtubesidebar' ); 
$youtubesidebar_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'disabled';
// send emails - rows are stored in wp_c2pmailing table for mass email campaigns 
$youtubesidebar_schedule_array['eventtypes']['sendemails']['name'] = __( 'Send Emails', 'youtubesidebar' ); 
$youtubesidebar_schedule_array['eventtypes']['sendemails']['switch'] = 'disabled';    
?>