<?php
/**
 * Main
 * 
 * @package YouTube Sidebar
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 3.0.0
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class YOUTUBESIDEBAR_Main_View extends YOUTUBESIDEBAR_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 3.0.0
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'main';
    
    public $purpose = 'normal';// normal, dashboard

    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function meta_box_array() {
        global $youtubesidebar_settings;

        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( 'main-welcome', __( 'Support WordPress Plugin Development', 'youtubesidebar' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'welcome' ), true, 'activate_plugins' ),
            
            // package
            array( 'main-assignvideotopost', __( 'Assign Video to Post', 'wtgportalmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'assignvideotopost' ), true, 'activate_plugins' ),          
            array( 'main-videooptions', __( 'Video Options', 'wtgportalmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'videooptions' ), true, 'activate_plugins' ),          
            array( 'main-newadsnippet', __( 'New Ad Snippet', 'wtgportalmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'newadsnippet' ), true, 'activate_plugins' ),          
            array( 'main-editads', __( 'Edit Ads', 'wtgportalmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'editads' ), true, 'activate_plugins' ),          
            array( 'main-adoptions', __( 'Ad Options', 'wtgportalmanager' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'adoptions' ), true, 'activate_plugins' ),                     
            
            // framework settings
            array( 'main-globalswitches', __( 'Global Switches', 'youtubesidebar' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'globalswitches' ), true, 'activate_plugins' ),
            array( 'main-logsettings', __( 'Log Settings', 'youtubesidebar' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'logsettings' ), true, 'activate_plugins' ),
            array( 'main-pagecapabilitysettings', __( 'Page Capability Settings', 'youtubesidebar' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'pagecapabilitysettings' ), true, 'activate_plugins' ),

            // side boxes
            array( 'main-twitterupdates', __( 'Twitter Updates', 'youtubesidebar' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'twitterupdates' ), true, 'activate_plugins' ),
            array( 'main-facebook', __( 'Facebook', 'youtubesidebar' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'facebook' ), true, 'activate_plugins' ),
            array( 'main-support', __( 'Support', 'youtubesidebar' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'support' ), true, 'activate_plugins' ),            
        );
        
        // add meta boxes that have conditions i.e. a global switch
        if( isset( $youtubesidebar_settings['widgetsettings']['dashboardwidgetsswitch'] ) && $youtubesidebar_settings['widgetsettings']['dashboardwidgetsswitch'] == 'enabled' ) {
            $this->meta_boxes_array[] = array( 'main-dashboardwidgetsettings', __( 'Dashboard Widget Settings', 'youtubesidebar' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'dashboardwidgetsettings' ), true, 'activate_plugins' );   
        }
        
        return $this->meta_boxes_array;                
    }
          
    /**
     * Set up the view with data and do things that are specific for this view
     *
     * @since 3.0.0
     *
     * @param string $action Action for this view
     * @param array $data Data for this view
     */
    public function setup( $action, array $data ) {
        global $youtubesidebar_settings;
        
        // create constant for view name
        if(!defined( "YOUTUBESIDEBAR_VIEWNAME") ){define( "YOUTUBESIDEBAR_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->YOUTUBESIDEBAR = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR', 'class-youtubesidebar.php', 'classes' );
        $this->UI = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR_UI', 'class-ui.php', 'classes' );  
        $this->DB = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR_PHP', 'class-phplibrary.php', 'classes' );
        $this->TabMenu = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR_TabMenu', 'class-pluginmenu.php', 'classes' );
        $this->Forms = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR_Formbuilder', 'class-forms.php', 'classes' );
        
        parent::setup( $action, $data );
        
        // only output meta boxes
        if( $this->purpose == 'normal' ) {
            self::metaboxes();// register meta boxes for the current view
        } elseif( $this->purpose == 'dashboard' ) {
            // do nothing - add_dashboard_widgets() in class-ui.php calls dashboard_widgets() from this class
        } elseif( $this->purpose == 'customdashboard' ) {
            return self::meta_box_array();// return meta box array
        } else {
            // do nothing 
        }       
    } 
    
     /**
     * Outputs the meta boxes
     * 
     * @author Ryan R. Bayne
     * @package YouTube Sidebar
     * @since 3.0.0
     * @version 1.0
     */
     public function metaboxes() {
        parent::register_metaboxes( self::meta_box_array() );     
     }

    /**
    * This function is called when on WP core dashboard and it adds widgets to the dashboard using
    * the meta box functions in this class. 
    * 
    * @uses dashboard_widgets() in parent class YOUTUBESIDEBAR_View which loops through meta boxes and registeres widgets
    * 
    * @author Ryan R. Bayne
    * @package YOUTUBESIDEBAR
    * @since 3.0.0
    * @version 1.0
    */
    public function dashboard() { 
        parent::dashboard_widgets( self::meta_box_array() );  
    }                 
    
    /**
    * All add_meta_box() callback to this function to keep the add_meta_box() call simple.
    * 
    * This function also offers a place to apply more security or arguments.
    * 
    * @author Ryan R. Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    function parent( $data, $box ) {
        eval( 'self::postbox_' . $this->view_name . '_' . $box['args']['formid'] . '( $data, $box );' );
    }
         
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_welcome( $data, $box ) {    
        echo '<p>' . __( "Most users I talk to own more than I do. Please take that into consideration if 
        my plugin saves you time and helps you to make money. If you donate or hire me it will help me to continue
        my work. Both supporting this plugin and creating more great plugins.", 'youtubesidebar' ) . '</p>';
    }       

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_schedulerestrictions( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This is a less of a specific day and time schedule. More of a system that allows systematic triggering of events within permitted hours. A new schedule system is in development though and will offer even more control with specific timing of events capable.', 'youtubesidebar' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
 
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Days', 'youtubesidebar' ); ?></th>
                    <td>
                        <?php 
                        $days_array = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
                        $days_counter = 1;
                        
                        foreach( $days_array as $key => $day ){
                            
                            // set checked status
                            if( isset( $this->schedule['days'][$day] ) ){
                                $day_checked = 'checked';
                            }else{
                                $day_checked = '';            
                            }
                                 
                            echo '<input type="checkbox" name="youtubesidebar_scheduleday_list[]" id="daycheck'.$days_counter.'" value="'.$day.'" '.$day_checked.' />
                            <label for="daycheck'.$days_counter.'">'.ucfirst( $day ).'</label><br />';    
                            ++$days_counter;
                        }?>
                    </td>
                </tr>
                <!-- Option End -->                          

                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Hours', 'youtubesidebar' ); ?></th>
                    <td>
                    <?php
                    // loop 24 times and create a checkbox for each hour
                    for( $i=0;$i<24;$i++){
                        
                        // check if the current hour exists in array, if it exists then it is permitted, if it does not exist it is not permitted
                        if( isset( $this->schedule['hours'][$i] ) ){
                            $hour_checked = ' checked'; 
                        }else{
                            $hour_checked = '';
                        }
                        
                        echo '<input type="checkbox" name="youtubesidebar_schedulehour_list[]" id="hourcheck'.$i.'"  value="'.$i.'" '.$hour_checked.' />
                        <label for="hourcheck'.$i.'">'.$i.'</label><br>';    
                    }
                    ?>
                    </td>
                </tr>
                <!-- Option End -->          
         
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Daily Limit', 'youtubesidebar' );?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Daily Limit</span></legend>
                            <input type="radio" id="youtubesidebar_radio1_dripfeedrate_maximumperday" name="day" value="1" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 1){echo 'checked';} ?> /><label for="youtubesidebar_radio1_dripfeedrate_maximumperday"> <?php _e( '1', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio2_dripfeedrate_maximumperday" name="day" value="5" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 5){echo 'checked';} ?> /><label for="youtubesidebar_radio2_dripfeedrate_maximumperday"> <?php _e( '5', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio3_dripfeedrate_maximumperday" name="day" value="10" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 10){echo 'checked';} ?> /><label for="youtubesidebar_radio3_dripfeedrate_maximumperday"> <?php _e( '10', 'youtubesidebar' );?> </label><br> 
                            <input type="radio" id="youtubesidebar_radio9_dripfeedrate_maximumperday" name="day" value="24" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 24){echo 'checked';} ?> /><label for="youtubesidebar_radio9_dripfeedrate_maximumperday"> <?php _e( '24', 'youtubesidebar' );?> </label><br>                    
                            <input type="radio" id="youtubesidebar_radio4_dripfeedrate_maximumperday" name="day" value="50" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 50){echo 'checked';} ?> /><label for="youtubesidebar_radio4_dripfeedrate_maximumperday"> <?php _e( '50', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio5_dripfeedrate_maximumperday" name="day" value="250" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 250){echo 'checked';} ?> /><label for="youtubesidebar_radio5_dripfeedrate_maximumperday"> <?php _e( '250', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio6_dripfeedrate_maximumperday" name="day" value="1000" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 1000){echo 'checked';} ?> /><label for="youtubesidebar_radio6_dripfeedrate_maximumperday"> <?php _e( '1000', 'youtubesidebar' );?> </label><br>                                                                                                                       
                            <input type="radio" id="youtubesidebar_radio7_dripfeedrate_maximumperday" name="day" value="2000" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 2000){echo 'checked';} ?> /><label for="youtubesidebar_radio7_dripfeedrate_maximumperday"> <?php _e( '2000', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio8_dripfeedrate_maximumperday" name="day" value="5000" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 5000){echo 'checked';} ?> /><label for="youtubesidebar_radio8_dripfeedrate_maximumperday"> <?php _e( '5000', 'youtubesidebar' );?> </label>                   
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->   
                         
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Hourly Limit', 'youtubesidebar' );?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Hourly Limit</span></legend>
                            <input type="radio" id="youtubesidebar_radio1_dripfeedrate_maximumperhour" name="hour" value="1" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 1){echo 'checked';} ?> /><label for="youtubesidebar_radio1_dripfeedrate_maximumperhour"> <?php _e( '1', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio2_dripfeedrate_maximumperhour" name="hour" value="5" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 5){echo 'checked';} ?> /><label for="youtubesidebar_radio2_dripfeedrate_maximumperhour"> <?php _e( '5', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio3_dripfeedrate_maximumperhour" name="hour" value="10" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 10){echo 'checked';} ?> /><label for="youtubesidebar_radio3_dripfeedrate_maximumperhour"> <?php _e( '10', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio9_dripfeedrate_maximumperhour" name="hour" value="24" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 24){echo 'checked';} ?> /><label for="youtubesidebar_radio9_dripfeedrate_maximumperhour"> <?php _e( '24', 'youtubesidebar' );?> </label><br>                    
                            <input type="radio" id="youtubesidebar_radio4_dripfeedrate_maximumperhour" name="hour" value="50" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 50){echo 'checked';} ?> /><label for="youtubesidebar_radio4_dripfeedrate_maximumperhour"> <?php _e( '50', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio5_dripfeedrate_maximumperhour" name="hour" value="100" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 100){echo 'checked';} ?> /><label for="youtubesidebar_radio5_dripfeedrate_maximumperhour"> <?php _e( '100', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio6_dripfeedrate_maximumperhour" name="hour" value="250" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 250){echo 'checked';} ?> /><label for="youtubesidebar_radio6_dripfeedrate_maximumperhour"> <?php _e( '250', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio7_dripfeedrate_maximumperhour" name="hour" value="500" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 500){echo 'checked';} ?> /><label for="youtubesidebar_radio7_dripfeedrate_maximumperhour"> <?php _e( '500', 'youtubesidebar' );?> </label><br>       
                            <input type="radio" id="youtubesidebar_radio8_dripfeedrate_maximumperhour" name="hour" value="1000" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 1000){echo 'checked';} ?> /><label for="youtubesidebar_radio8_dripfeedrate_maximumperhour"> <?php _e( '1000', 'youtubesidebar' );?> </label><br>                                                                                                                           
                       </fieldset>
                    </td>
                </tr>
                <!-- Option End -->   

                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Event Limit', 'youtubesidebar' );?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Event Limit</span></legend>
                            <input type="radio" id="youtubesidebar_radio1_dripfeedrate_maximumpersession" name="session" value="1" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 1){echo 'checked';} ?> /><label for="youtubesidebar_radio1_dripfeedrate_maximumpersession"> <?php _e( '1', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio2_dripfeedrate_maximumpersession" name="session" value="5" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 5){echo 'checked';} ?> /><label for="youtubesidebar_radio2_dripfeedrate_maximumpersession"> <?php _e( '5', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio3_dripfeedrate_maximumpersession" name="session" value="10" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 10){echo 'checked';} ?> /><label for="youtubesidebar_radio3_dripfeedrate_maximumpersession"> <?php _e( '10', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio9_dripfeedrate_maximumpersession" name="session" value="25" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 25){echo 'checked';} ?> /><label for="youtubesidebar_radio9_dripfeedrate_maximumpersession"> <?php _e( '25', 'youtubesidebar' );?> </label><br>                    
                            <input type="radio" id="youtubesidebar_radio4_dripfeedrate_maximumpersession" name="session" value="50" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 50){echo 'checked';} ?> /><label for="youtubesidebar_radio4_dripfeedrate_maximumpersession"> <?php _e( '50', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio5_dripfeedrate_maximumpersession" name="session" value="100" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 100){echo 'checked';} ?> /><label for="youtubesidebar_radio5_dripfeedrate_maximumpersession"> <?php _e( '100', 'youtubesidebar' );?> </label><br>
                            <input type="radio" id="youtubesidebar_radio6_dripfeedrate_maximumpersession" name="session" value="200" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 200){echo 'checked';} ?> /><label for="youtubesidebar_radio6_dripfeedrate_maximumpersession"> <?php _e( '200', 'youtubesidebar' );?> </label><br>                                                                                                                        
                            <input type="radio" id="youtubesidebar_radio7_dripfeedrate_maximumpersession" name="session" value="300" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 300){echo 'checked';} ?> /><label for="youtubesidebar_radio7_dripfeedrate_maximumpersession"> <?php _e( '300', 'youtubesidebar' );?> </label><br>          
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->     
                
            </table>
             
        <?php 
        $this->UI->postbox_content_footer();
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_scheduleinformation( $data, $box ) {  ?>
            <h4><?php _e( 'Last Schedule Finish Reason', 'youtubesidebar' );?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lastreturnreason'] ) ){
                echo $this->schedule['history']['lastreturnreason']; 
            }else{
                _e( 'No event refusal reason has been set yet', 'youtubesidebar' );    
            }?>
            </p>
            
            <h4><?php _e( 'Events Counter - 60 Minute Period', 'youtubesidebar' );?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['hourcounter'] ) ){
                echo $this->schedule['history']['hourcounter']; 
            }else{
                _e( 'No events have been done during the current 60 minute period', 'youtubesidebar' );    
            }?>
            </p> 

            <h4><?php _e( 'Events Counter - 24 Hour Period', 'youtubesidebar' );?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['daycounter'] ) ){
                echo $this->schedule['history']['daycounter']; 
            }else{
                _e( 'No events have been done during the current 24 hour period', 'youtubesidebar' );    
            }?>
            </p>

            <h4><?php _e( 'Last Event Type', 'youtubesidebar' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lasteventtype'] ) ){
                
                if( $this->schedule['history']['lasteventtype'] == 'dataimport' ){
                    echo 'Data Import';            
                }elseif( $this->schedule['history']['lasteventtype'] == 'dataupdate' ){
                    echo 'Data Update';
                }elseif( $this->schedule['history']['lasteventtype'] == 'postcreation' ){
                    echo 'Post Creation';
                }elseif( $this->schedule['history']['lasteventtype'] == 'postupdate' ){
                    echo 'Post Update';
                }elseif( $this->schedule['history']['lasteventtype'] == 'twittersend' ){
                    echo 'Twitter: New Tweet';
                }elseif( $this->schedule['history']['lasteventtype'] == 'twitterupdate' ){
                    echo 'Twitter: Send Update';
                }elseif( $this->schedule['history']['lasteventtype'] == 'twitterget' ){
                    echo 'Twitter: Get Reply';
                }
                 
            }else{
                _e( 'No events have been carried out yet', 'youtubesidebar' );    
            }?>
            </p>

            <h4><?php _e( 'Last Event Action', 'youtubesidebar' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lasteventaction'] ) ){
                echo $this->schedule['history']['lasteventaction']; 
            }else{
                _e( 'No event actions have been carried out yet', 'youtubesidebar' );    
            }?>
            </p>
                
            <h4><?php _e( 'Last Event Time', 'youtubesidebar' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lasteventtime'] ) ){
                echo date( "F j, Y, g:i a", $this->schedule['history']['lasteventtime'] ); 
            }else{
                _e( 'No schedule events have ran on this server yet', 'youtubesidebar' );    
            }?>
            </p>
            
            <h4><?php _e( 'Last Hourly Reset', 'youtubesidebar' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['hour_lastreset'] ) ){
                echo date( "F j, Y, g:i a", $this->schedule['history']['hour_lastreset'] ); 
            }else{
                _e( 'No hourly reset has been done yet', 'youtubesidebar' );    
            }?>
            </p>   
                
            <h4><?php _e( 'Last 24 Hour Period Reset', 'youtubesidebar' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['day_lastreset'] ) ){
                echo date( "F j, Y, g:i a", $this->schedule['history']['day_lastreset'] ); 
            }else{
                _e( 'No 24 hour reset has been done yet', 'youtubesidebar' );    
            }?>
            </p> 
               
            <h4><?php _e( 'Your Servers Current Data and Time', 'youtubesidebar' ); ?></h4>
            <p><?php echo date( "F j, Y, g:i a",time() );?></p>     
            
        <?php                       
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_globalswitches( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'These switches disable or enable systems. Disabling systems you do not require will improve the plugins performance.', 'youtubesidebar' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $youtubesidebar_settings;
        ?>  

            <table class="form-table">
            <?php        
            $this->UI->option_switch( __( 'WordPress Notice Styles', 'youtubesidebar' ), 'uinoticestyle', 'uinoticestyle', $youtubesidebar_settings['noticesettings']['wpcorestyle'] );
            $this->UI->option_switch( __( 'WTG Flag System', 'youtubesidebar' ), 'flagsystemstatus', 'flagsystemstatus', $youtubesidebar_settings['posttypes']['wtgflags']['status'] );
            $this->UI->option_switch( __( 'Dashboard Widgets Switch', 'youtubesidebar' ), 'dashboardwidgetsswitch', 'dashboardwidgetsswitch', $youtubesidebar_settings['widgetsettings']['dashboardwidgetsswitch'], 'Enabled', 'Disabled', 'disabled' );      
            ?>
            </table> 
            
        <?php 
        $this->UI->postbox_content_footer();
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_logsettings( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'The plugin has its own log system with multi-purpose use. Not everything is logged for the sake of performance so please request increased log use if required.', 'youtubesidebar' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $youtubesidebar_settings;
        ?>  

            <table class="form-table">
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row">Log</th>
                    <td>
                        <?php 
                        // if is not set ['admintriggers']['newcsvfiles']['status'] then it is enabled by default
                        if(!isset( $youtubesidebar_settings['globalsettings']['uselog'] ) ){
                            $radio1_uselog_enabled = 'checked'; 
                            $radio2_uselog_disabled = '';                    
                        }else{
                            if( $youtubesidebar_settings['globalsettings']['uselog'] == 1){
                                $radio1_uselog_enabled = 'checked'; 
                                $radio2_uselog_disabled = '';    
                            }elseif( $youtubesidebar_settings['globalsettings']['uselog'] == 0){
                                $radio1_uselog_enabled = ''; 
                                $radio2_uselog_disabled = 'checked';    
                            }
                        }?>
                        <fieldset><legend class="screen-reader-text"><span>Log</span></legend>
                            <input type="radio" id="logstatus_enabled" name="youtubesidebar_radiogroup_logstatus" value="1" <?php echo $radio1_uselog_enabled;?> />
                            <label for="logstatus_enabled"> <?php _e( 'Enable', 'youtubesidebar' ); ?></label>
                            <br />
                            <input type="radio" id="logstatus_disabled" name="youtubesidebar_radiogroup_logstatus" value="0" <?php echo $radio2_uselog_disabled;?> />
                            <label for="logstatus_disabled"> <?php _e( 'Disable', 'youtubesidebar' ); ?></label>
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->
      
                <?php       
                // log rows limit
                if(!isset( $youtubesidebar_settings['globalsettings']['loglimit'] ) || !is_numeric( $youtubesidebar_settings['globalsettings']['loglimit'] ) ){$youtubesidebar_settings['globalsettings']['loglimit'] = 1000;}
                $this->UI->option_text( 'Log Entries Limit', 'youtubesidebar_loglimit', 'loglimit', $youtubesidebar_settings['globalsettings']['loglimit'] );
                ?>
            </table> 
            
                    
            <h4>Outcomes</h4>
            <label for="youtubesidebar_log_outcomes_success"><input type="checkbox" name="youtubesidebar_log_outcome[]" id="youtubesidebar_log_outcomes_success" value="1" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['outcomecriteria']['1'] ) ){echo 'checked';} ?>> Success</label>
            <br> 
            <label for="youtubesidebar_log_outcomes_fail"><input type="checkbox" name="youtubesidebar_log_outcome[]" id="youtubesidebar_log_outcomes_fail" value="0" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['outcomecriteria']['0'] ) ){echo 'checked';} ?>> Fail/Rejected</label>

            <h4>Type</h4>
            <label for="youtubesidebar_log_type_general"><input type="checkbox" name="youtubesidebar_log_type[]" id="youtubesidebar_log_type_general" value="general" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['typecriteria']['general'] ) ){echo 'checked';} ?>> General</label>
            <br>
            <label for="youtubesidebar_log_type_error"><input type="checkbox" name="youtubesidebar_log_type[]" id="youtubesidebar_log_type_error" value="error" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['typecriteria']['error'] ) ){echo 'checked';} ?>> Errors</label>
            <br>
            <label for="youtubesidebar_log_type_trace"><input type="checkbox" name="youtubesidebar_log_type[]" id="youtubesidebar_log_type_trace" value="flag" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['typecriteria']['flag'] ) ){echo 'checked';} ?>> Trace</label>

            <h4>Priority</h4>
            <label for="youtubesidebar_log_priority_low"><input type="checkbox" name="youtubesidebar_log_priority[]" id="youtubesidebar_log_priority_low" value="low" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['prioritycriteria']['low'] ) ){echo 'checked';} ?>> Low</label>
            <br>
            <label for="youtubesidebar_log_priority_normal"><input type="checkbox" name="youtubesidebar_log_priority[]" id="youtubesidebar_log_priority_normal" value="normal" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['prioritycriteria']['normal'] ) ){echo 'checked';} ?>> Normal</label>
            <br>
            <label for="youtubesidebar_log_priority_high"><input type="checkbox" name="youtubesidebar_log_priority[]" id="youtubesidebar_log_priority_high" value="high" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['prioritycriteria']['high'] ) ){echo 'checked';} ?>> High</label>
            
            <h1>Custom Search</h1>
            <p>This search criteria is not currently stored, it will be used on the submission of this form only.</p>
         
            <h4>Page</h4>
            <select name="youtubesidebar_pluginpages_logsearch" id="youtubesidebar_pluginpages_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php
                $current = '';
                if( isset( $youtubesidebar_settings['logsettings']['logscreen']['page'] ) && $youtubesidebar_settings['logsettings']['logscreen']['page'] != 'notselected' ){
                    $current = $youtubesidebar_settings['logsettings']['logscreen']['page'];
                } 
                $this->UI->page_menuoptions( $current);?> 
            </select>
            
            <h4>Action</h4> 
            <select name="csv2pos_logactions_logsearch" id="csv2pos_logactions_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php 
                $current = '';
                if( isset( $youtubesidebar_settings['logsettings']['logscreen']['action'] ) && $youtubesidebar_settings['logsettings']['logscreen']['action'] != 'notselected' ){
                    $current = $youtubesidebar_settings['logsettings']['logscreen']['action'];
                }
                $action_results = $this->DB->log_queryactions( $current);
                if( $action_results){
                    foreach( $action_results as $key => $action){
                        $selected = '';
                        if( $action['action'] == $current){
                            $selected = 'selected="selected"';
                        }
                        echo '<option value="'.$action['action'].'" '.$selected.'>'.$action['action'].'</option>'; 
                    }   
                }?> 
            </select>
            
            <h4>Screen Name</h4>
            <select name="youtubesidebar_pluginscreens_logsearch" id="youtubesidebar_pluginscreens_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php 
                $current = '';
                if( isset( $youtubesidebar_settings['logsettings']['logscreen']['screen'] ) && $youtubesidebar_settings['logsettings']['logscreen']['screen'] != 'notselected' ){
                    $current = $youtubesidebar_settings['logsettings']['logscreen']['screen'];
                }
                $this->UI->screens_menuoptions( $current);?> 
            </select>
                  
            <h4>PHP Line</h4>
            <input type="text" name="youtubesidebar_logcriteria_phpline" value="<?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['line'] ) ){echo $youtubesidebar_settings['logsettings']['logscreen']['line'];} ?>">
            
            <h4>PHP File</h4>
            <input type="text" name="youtubesidebar_logcriteria_phpfile" value="<?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['file'] ) ){echo $youtubesidebar_settings['logsettings']['logscreen']['file'];} ?>">
            
            <h4>PHP Function</h4>
            <input type="text" name="youtubesidebar_logcriteria_phpfunction" value="<?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['function'] ) ){echo $youtubesidebar_settings['logsettings']['logscreen']['function'];} ?>">
            
            <h4>Panel Name</h4>
            <input type="text" name="youtubesidebar_logcriteria_panelname" value="<?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['panelname'] ) ){echo $youtubesidebar_settings['logsettings']['logscreen']['panelname'];} ?>">

            <h4>IP Address</h4>
            <input type="text" name="youtubesidebar_logcriteria_ipaddress" value="<?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['ipaddress'] ) ){echo $youtubesidebar_settings['logsettings']['logscreen']['ipaddress'];} ?>">
           
            <h4>User ID</h4>
            <input type="text" name="youtubesidebar_logcriteria_userid" value="<?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['userid'] ) ){echo $youtubesidebar_settings['logsettings']['logscreen']['userid'];} ?>">    
          
            <h4>Display Fields</h4>                                                                                                                                        
            <label for="youtubesidebar_logfields_outcome"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_outcome" value="outcome" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] ) ){echo 'checked';} ?>> <?php _e( 'Outcome', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_line"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_line" value="line" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['line'] ) ){echo 'checked';} ?>> <?php _e( 'Line', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_file"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_file" value="file" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['file'] ) ){echo 'checked';} ?>> <?php _e( 'File', 'youtubesidebar' );?></label> 
            <br>
            <label for="youtubesidebar_logfields_function"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_function" value="function" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['function'] ) ){echo 'checked';} ?>> <?php _e( 'Function', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_sqlresult"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_sqlresult" value="sqlresult" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['sqlresult'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Result', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_sqlquery"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_sqlquery" value="sqlquery" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['sqlquery'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Query', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_sqlerror"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_sqlerror" value="sqlerror" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['sqlerror'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Error', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_wordpresserror"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_wordpresserror" value="wordpresserror" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['wordpresserror'] ) ){echo 'checked';} ?>> <?php _e( 'WordPress Erro', 'youtubesidebar' );?>r</label>
            <br>
            <label for="youtubesidebar_logfields_screenshoturl"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_screenshoturl" value="screenshoturl" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['screenshoturl'] ) ){echo 'checked';} ?>> <?php _e( 'Screenshot URL', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_userscomment"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_userscomment" value="userscomment" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['userscomment'] ) ){echo 'checked';} ?>> <?php _e( 'Users Comment', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_page"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_page" value="page" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['page'] ) ){echo 'checked';} ?>> <?php _e( 'Page', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_version"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_version" value="version" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['version'] ) ){echo 'checked';} ?>> <?php _e( 'Plugin Version', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_panelname"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_panelname" value="panelname" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['panelname'] ) ){echo 'checked';} ?>> <?php _e( 'Panel Name', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_tabscreenname"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_tabscreenname" value="tabscreenname" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] ) ){echo 'checked';} ?>> <?php _e( 'Screen Name *', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_dump"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_dump" value="dump" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['dump'] ) ){echo 'checked';} ?>> <?php _e( 'Dump', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_ipaddress"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_ipaddress" value="ipaddress" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['ipaddress'] ) ){echo 'checked';} ?>> <?php _e( 'IP Address', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_userid"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_userid" value="userid" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['userid'] ) ){echo 'checked';} ?>> <?php _e( 'User ID', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_comment"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_comment" value="comment" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['comment'] ) ){echo 'checked';} ?>> <?php _e( 'Developers Comment', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_type"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_type" value="type" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['type'] ) ){echo 'checked';} ?>> <?php _e( 'Entry Type', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_category"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_category" value="category" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['category'] ) ){echo 'checked';} ?>> <?php _e( 'Category', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_action"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_action" value="action" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['action'] ) ){echo 'checked';} ?>> <?php _e( 'Action', 'youtubesidebar' );?></label>
            <br>
            <label for="youtubesidebar_logfields_priority"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_priority" value="priority" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['priority'] ) ){echo 'checked';} ?>> <?php _e( 'Priority', 'youtubesidebar' );?></label> 
            <br>
            <label for="youtubesidebar_logfields_thetrigger"><input type="checkbox" name="youtubesidebar_logfields[]" id="youtubesidebar_logfields_thetrigger" value="thetrigger" <?php if( isset( $youtubesidebar_settings['logsettings']['logscreen']['displayedcolumns']['thetrigger'] ) ){echo 'checked';} ?>> <?php _e( 'Trigger', 'youtubesidebar' );?></label> 

    
        <?php 
        $this->UI->postbox_content_footer();
    }    
        
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_iconsexplained( $data, $box ) {    
        ?>  
        <p class="about-description"><?php _e( 'The plugin has icons on the UI offering different types of help...' ); ?></p>
        
        <h3>Help Icon<?php echo $this->UI->helpicon( 'http://www.webtechglobal.co.uk/youtubesidebar' )?></h3>
        <p><?php _e( 'The help icon offers a tutorial or indepth description on the WebTechGlobal website. Clicking these may open
        take a key page in the plugins portal or post in the plugins blog. On a rare occasion you will be taking to another users 
        website who has published a great tutorial or technical documentation.' )?></p>        
        
        <h3>Discussion Icon<?php echo $this->UI->discussicon( 'http://www.webtechglobal.co.uk/youtubesidebar' )?></h3>
        <p><?php _e( 'The discussion icon open an active forum discussion or chat on the WebTechGlobal domain in a new tab. If you see this icon
        it means you are looking at a feature or area of the plugin that is a hot topic. It could also indicate the
        plugin author would like to hear from you regarding a specific feature. Occasionally these icons may take you to a discussion
        on other websites such as a Google circles, an official page on Facebook or a good forum thread on a users domain.' )?></p>
                          
        <h3>Info Icon<img src="<?php echo YOUTUBESIDEBAR_IMAGES_URL;?>info-icon.png" alt="<?php _e( 'Icon with an i click it to read more information in a popup.' );?>"></h3>
        <p><?php _e( 'The information icon will not open another page. It will display a pop-up with extra information. This is mostly used within
        panels to explain forms and the status of the panel.' )?></p>        
        
        <h3>Video Icon<?php echo $this->UI->videoicon( 'http://www.webtechglobal.co.uk/youtubesidebar' )?></h3>
        <p><?php _e( 'clicking on the video icon will open a new tab to a YouTube video. Occasionally it may open a video on another
        website. Occasionally a video may even belong to a user who has created a good tutorial.' )?></p> 
               
        <h3>Trash Icon<?php echo $this->UI->trashicon( 'http://www.webtechglobal.co.uk/youtubesidebar' )?></h3>
        <p><?php _e( 'The trash icon will be shown beside items that can be deleted or objects that can be hidden.
        Sometimes you can hide a panel as part of the plugins configuration. Eventually I hope to be able to hide
        notices, especially the larger ones..' )?></p>      
      <?php     
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_twitterupdates( $data, $box ) {    
        ?>
        <p class="about-description"><?php _e( 'Thank this plugins developers with a Tweet...', 'youtubesidebar' ); ?></p>    
        <a class="twitter-timeline" href="https://twitter.com/WebTechGlobal" data-widget-id="511630591142268928">Tweets by @WebTechGlobal</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id) ){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>                                                   
        <?php     
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0.4
    */
    public function postbox_main_support( $data, $box ) {    
        ?>      
        <p><?php _e( 'All users (free and pro editions) are supported. Please register on the <a href="http://www.webtechglobal.co.uk/register/" title="WebTechGlobal Registration" target="_blank">WebTechGlobal</a> site for free support.', 'youtubesidebar' ); ?></p>                     
        <?php     
    }   
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_facebook( $data, $box ) {    
        ?>      
        <p class="about-description"><?php _e( 'Please show your appreciation for this plugin I made for you by clicking Like...', 'youtubesidebar' ); ?></p>
        <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FWebTechGlobal1&amp;width=350&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true" scrolling="no" frameborder="0" style="padding: 10px 0 0 0;border:none; overflow:hidden; width:100%; height:290px;" allowTransparency="true"></iframe>                                                                             
        <?php     
    }

    /**
    * Form for setting which captability is required to view the page
    * 
    * By default there is no settings data for this because most people will never use it.
    * However when it is used, a new option record is created so that the settings are
    * independent and can be accessed easier.  
    * 
    * @author Ryan R. Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_pagecapabilitysettings( $data, $box ) {
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Set the capability a user requires to view any of the plugins pages. This works independently of role plugins such as Role Scoper.', 'youtubesidebar' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        // get the tab menu 
        $pluginmenu = $this->TabMenu->menu_array();
        ?>
        
        <table class="form-table">
        
        <?php 
        // get stored capability settings 
        $saved_capability_array = get_option( 'youtubesidebar_capabilities' );
        
        // add a menu for each page for the user selecting the required capability 
        foreach( $pluginmenu as $key => $page_array ) {
            
            // do not add the main page to the list as a strict security measure
            if( $page_array['name'] !== 'main' ) {
                $current = null;
                if( isset( $saved_capability_array['pagecaps'][ $page_array['name'] ] ) && is_string( $saved_capability_array['pagecaps'][ $page_array['name'] ] ) ) {
                    $current = $saved_capability_array['pagecaps'][ $page_array['name'] ];
                }
                
                $this->UI->option_menu_capabilities( $page_array['menu'], 'pagecap' . $page_array['name'], 'pagecap' . $page_array['name'], $current );
            }
        }?>
        
        </table>
        
        <?php 
        $this->UI->postbox_content_footer();        
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package YouTube Sidebar
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_dashboardwidgetsettings( $data, $box ) { 
        global $youtubesidebar_settings;
           
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This panel is new and is advanced.   
        Please seek my advice before using it.
        You must be sure and confident that it operates in the way you expect.
        It will add widgets to your dashboard. 
        The capability menu allows you to set a global role/capability requirements for the group of wigets from any giving page. 
        The capability options in the "Page Capability Settings" panel are regarding access to the admin page specifically.', 'youtubesidebar' ), false );   
             
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );

        echo '<table class="form-table">';

        // now loop through views, building settings per box (display or not, permitted role/capability  
        $YOUTUBESIDEBAR_TabMenu = YOUTUBESIDEBAR::load_class( 'YOUTUBESIDEBAR_TabMenu', 'class-pluginmenu.php', 'classes' );
        $menu_array = $YOUTUBESIDEBAR_TabMenu->menu_array();
        foreach( $menu_array as $key => $section_array ) {

            /*
                'groupname' => string 'main' (length=4)
                'slug' => string 'youtubesidebar_generalsettings' (length=24)
                'menu' => string 'General Settings' (length=16)
                'pluginmenu' => string 'General Settings' (length=16)
                'name' => string 'generalsettings' (length=15)
                'title' => string 'General Settings' (length=16)
                'parent' => string 'main' (length=4)
            */
            
            // get dashboard activation status for the current page
            $current_for_page = '123nocurrentvalue';
            if( isset( $youtubesidebar_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'] ) ) {
                $current_for_page = $youtubesidebar_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'];   
            }
            
            // display switch for current page
            $this->UI->option_switch( $section_array['menu'], $section_array['name'] . 'dashboardwidgetsswitch', $section_array['name'] . 'dashboardwidgetsswitch', $current_for_page, 'Enabled', 'Disabled', 'disabled' );
            
            // get current pages minimum dashboard widget capability
            $current_capability = '123nocapability';
            if( isset( $youtubesidebar_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'] ) ) {
                $current_capability = $youtubesidebar_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'];   
            }
                            
            // capabilities menu for each page (rather than individual boxes, the boxes will have capabilities applied in code)
            $this->UI->option_menu_capabilities( __( 'Capability Required', 'youtubesidebar' ), $section_array['name'] . 'widgetscapability', $section_array['name'] . 'widgetscapability', $current_capability );
        }

        echo '</table>';
                    
        $this->UI->postbox_content_footer();
    }    

    /**
    * Assign videos to posts.
    * 
    * @author Ryan Bayne
    * @package WTG Portal Manager
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_assignvideotopost( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Enter the ID from video URL only. Try this sample ID: PR99KwLB4b4.', 'wtgportalmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );

        $this->YOUTUBESIDEBAR->postlist_addvideotosidebar( 500 );

        $this->UI->postbox_content_footer();                        
    } 

    /**
    * EMPTY.
    * 
    * @author Ryan Bayne
    * @package WTG Portal Manager
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_videooptions( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Set the default configuration for videos.', 'wtgportalmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $youtubesidebar_settings;
        ?>                                                   
        
        <table class="form-table">

            <!-- Option Start -->        
            <tr valign="top">
                <th scope="row"> <label for="yts_videooptions_maximum"><span class="yts-tips" title="Posts can have more than one video, we can cycle through them, pick them at random or just display many at once with a limit.">Maximum Videos</span></label></th>
                <td>
                    <input type="text" name="yts_videooptions_maximum" id="yts_videooptions_maximum" value="<?php echo $youtubesidebar_settings['videooptions']['maximumvideos'];?>">
                </td>
            </tr>
            <!-- Option End -->     
        
            <!-- Option Start -->          
            <tr valign="top">
                <th scope="row"> <label for="yts_videooptions_color"> <span class="yts-tips" title="TODO">Color</span> </label> </th>
                <td>  

                    <select name="yts_videooptions_color" size="1">
                        <option value="notselected" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'' ); ?>>Default</option>
                        <option value="&color1=0x3a3a3a&color2=0x999999" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0x3a3a3a&color2=0x999999' ); ?>>Dark Grey &amp; Medium Grey</option>
                        <option value="&color1=0x2b405b&color2=0x6b8ab6" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0x2b405b&color2=0x6b8ab6' ); ?>>Dark Blue &amp; Light Blue</option>
                        <option value="&color1=0x006699&color2=0x54abd6" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0x006699&color2=0x54abd6' ); ?>>Dark Torq &amp; Light Torq</option>
                        <option value="&color1=0x234900&color2=0x4e9e00" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0x234900&color2=0x4e9e00' ); ?>>Dark Green &amp; Light Green</option>
                        <option value="&color1=0xe1600f&color2=0xfebd01" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0xe1600f&color2=0xfebd01' ); ?>>Dark Orange &amp; Light Orange</option>
                        <option value="&color1=0xcc2550&color2=0xe87a9f" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0xcc2550&color2=0xe87a9f' ); ?>>Dark Pink &amp; Light Pink</option>
                        <option value="&color1=0x402061&color2=0x9461ca" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0x402061&color2=0x9461ca' ); ?>>Dark Purple &amp; Light Purple</option>
                        <option value="&color1=0x5d1719&color2=0xcd311b" <?php $this->UI->echoselected( $youtubesidebar_settings['videooptions']['color'],'&color1=0x5d1719&color2=0xcd311b' ); ?>>Dark Red &amp; Light Red</option>
                    </select> 
                                   
                </td>
            </tr>
            <!-- Option End -->

            <!-- Option Start -->
            <tr valign="top">
                <th scope="row"><span class="yts-tips" title="TODO">Border</span></th>
                <td>
     
                    <div id="yts_videooptions_border_div">
                        <input type="radio" id="yts_videooptions_border_enable" name="yts_videooptions_border" value="enable" <?php if($youtubesidebar_settings['videooptions']['border'] == 'enable'){echo 'checked';}?> />
                        <label for="yts_videooptions_border_enable"> Enable</label>
                        <input type="radio" id="yts_videooptions_border_disable" name="yts_videooptions_border" value="disable" <?php if($youtubesidebar_settings['videooptions']['border'] == 'disable'){echo 'checked';}?> />
                        <label for="yts_videooptions_border_disable"> Disable</label>
                    </div>    
      
                </td>
            </tr>
            <!-- Option End -->
            
            <!-- Option Start -->
            <tr valign="top">
                <th scope="row"><span class="yts-tips" title="TODO">Auto-play</span></th>
                <td>  
                
                   <div id="yts_videooptions_autoplay_div">
                        <input type="radio" id="yts_videooptions_autoplay_enable" name="yts_videooptions_autoplay" value="enable" <?php if($youtubesidebar_settings['videooptions']['autoplay'] == 'enable'){echo 'checked';}?> />
                        <label for="yts_videooptions_autoplay_enable"> Enable</label>
                        <input type="radio" id="yts_videooptions_autoplay_disable" name="yts_videooptions_autoplay" value="disable" <?php if($youtubesidebar_settings['videooptions']['autoplay'] == 'disable'){echo 'checked';}?> />
                        <label for="yts_videooptions_autoplay_disable"> Disable</label>
                    </div>    
      
                </td>
            </tr>
            <!-- Option End -->
            
            <!-- Option Start -->
            <tr valign="top">
                <th scope="row"><span class="yts-tips" title="TODO">Full-screen</span></th>
                <td>
                
                    <script>
                        $(function() {
                            $( "#yts_videooptions_fullscreen_div" ).buttonset();
                        });
                    </script>
     
                    <div id="yts_videooptions_fullscreen_div">
                        <input type="radio" id="yts_videooptions_fullscreen_enable" name="yts_videooptions_fullscreen" value="enable" <?php if($youtubesidebar_settings['videooptions']['fullscreen'] == 'enable'){echo 'checked';}?> />
                        <label for="yts_videooptions_fullscreen_enable"> Enable</label>
                        <input type="radio" id="yts_videooptions_fullscreen_disable" name="yts_videooptions_fullscreen" value="disable" <?php if($youtubesidebar_settings['videooptions']['fullscreen'] == 'disable'){echo 'checked';}?> />
                        <label for="yts_videooptions_fullscreen_disable"> Disable</label>
                    </div>    
      
                </td>
            </tr>
            <!-- Option End -->
         
            <!-- Option Start -->
            <tr valign="top">
                <th scope="row"><span class="yts-tips" title="TODO">Script Access</span></th>
                <td>
                
                    <script>
                        $(function() {
                            $( "#yts_videooptions_scriptaccess_div" ).buttonset();
                        });
                    </script>
     
                    <div id="yts_videooptions_scriptaccess_div">
                        <input type="radio" id="yts_videooptions_scriptaccess_enable" name="yts_videooptions_scriptaccess" value="enable" <?php if($youtubesidebar_settings['videooptions']['scriptaccess'] == 'enable'){echo 'checked';}?> />
                        <label for="yts_videooptions_scriptaccess_enable"> Enable</label>
                        <input type="radio" id="yts_videooptions_scriptaccess_disable" name="yts_videooptions_scriptaccess" value="disable" <?php if($youtubesidebar_settings['videooptions']['scriptaccess'] == 'disable'){echo 'checked';}?> />
                        <label for="yts_videooptions_scriptaccess_disable"> Disable</label>
                    </div>    
      
                </td>
            </tr>
            <!-- Option End -->
               
        </table>

        <?php 

        $this->UI->postbox_content_footer();                        
    } 
    
    /**
    * EMPTY.
    * 
    * @author Ryan Bayne
    * @package WTG Portal Manager
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_newadsnippet( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Copy and paste ad snippets here then submit, they will become available for using in your widget when a post does not have a video to display.', 'wtgportalmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $youtubesidebar_settings;
        ?>
      
            <textarea name="yts_submitad_snippet" id="yts_submitad_snippet" cols="100" rows="10"></textarea>
                   
        <?php 
        $this->UI->postbox_content_footer();                        
    }
    
    /**
    * EMPTY.
    * 
    * @author Ryan Bayne
    * @package WTG Portal Manager
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_editads( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Remove this post-box 1.', 'wtgportalmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $youtubesidebar_settings;
        ?>

            <?php  
                $this->UI->GUI_tablestart();
                
                // head
                echo '<thead>';
                echo '<tr>';
                echo '<th width="75">Delete</th>';
                echo '<th width="75">Array ID</th>'; 
                echo '<th width="200">Ad Provider</th>';
                echo '<th>Snippet</th>';
                echo '<th>Preview</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</thead>';
                
                // foot
                echo '<tfoot>';          
                echo '<tr>';
                echo '<th>Delete</th>';
                echo '<th>Array ID</th>'; 
                echo '<th>Ad Provider</th>';
                echo '<th>Snippet</th>';
                echo '<th>Preview</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</tfoot>';
            
                    // body start
                echo '<tbody>';

                if( isset($youtubesidebar_settings['adsnippets'])) {
                    foreach($youtubesidebar_settings['adsnippets'] as $arrayID => $snippet_array){
                        
                        echo '<tr>';
                        echo '<th><input type="checkbox" name="yts_ad_delete_'.$arrayID.'"></th>';
                        echo '<th>'.$arrayID.'</th>'; 
                        echo '<th>'.$snippet_array['source'].'</th>';
                        ### TODO:LOWPRIORITY, if we create an actual Ad ID value we should replace $arrayID in this line with that instead
                        echo '<th><textarea name="yts_adsnippet_'.$arrayID.'">'.$snippet_array['snippet'].'</textarea></th>';
                        echo '<th>'.$snippet_array['snippet'].'</th>';
                        echo '<th></th>';# delete, view stats
                        echo '</tr>';
                
                    }
                }
                          
                // body finish
                echo '</tbody></table>';
                
            echo '<p>PPC services have limits to the number of ads displayed per page. Some ads may not show a preview.</p>';
                       
        $this->UI->postbox_content_footer();                        
    }
    
    /**
    * EMPTY.
    * 
    * @author Ryan Bayne
    * @package WTG Portal Manager
    * @since 3.0.0
    * @version 1.0
    */
    public function postbox_main_adoptions( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( "Make use of your widget space when a post does not have a video by diplaying Google AdSense. Here you can configure what types of pages AdSense is displayed on. (Beta Note: the original plugin offered full range of settings but after re-developing the plugin we're releasing it with basic settings and will gradually improve it).", 'wtgportalmanager' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $youtubesidebar_settings;
        ?>

            <table class="form-table">    
            
                <!-- Option Start -->          
                <tr valign="top">
                    <th scope="row"> <label for="yts_adsenseoptions_maximum"> <span class="yts-tips" title="Us">Maximum Ads</span> </label> </th>
                    <td>  

                        <select name="yts_adsenseoptions_maximum" id="yts_adsenseoptions_maximum" size="1">
                        
                            <?php $current = '';
                            if( isset( $youtubesidebar_settings['adoptions']['maximumads'] ) ) {
                                $current = $youtubesidebar_settings['adoptions']['maximumads'];
                            } ?>
                            <option value="1" <?php $this->UI->echoselected( $current ,'1' ); ?>>1</option>
                            
                            <?php $current = '';
                            if( isset( $youtubesidebar_settings['adoptions']['maximumads'] ) ) {
                                $current = $youtubesidebar_settings['adoptions']['maximumads'];
                            } ?>                            
                            <option value="2" <?php $this->UI->echoselected( $current ,'2' ); ?>>2</option>
                            
                            <?php $current = '';
                            if( isset( $youtubesidebar_settings['adoptions']['maximumads'] ) ) {
                                $current = $youtubesidebar_settings['adoptions']['maximumads'];
                            } ?>                            
                            <option value="3" <?php $this->UI->echoselected( $current ,'3' ); ?>>3</option>
                        
                        </select> 
                                       
                    </td>
                </tr>
                <!-- Option End -->

            </table>                
            
        <?php 
        $this->UI->postbox_content_footer();                        
    }
                                                                  
}?>