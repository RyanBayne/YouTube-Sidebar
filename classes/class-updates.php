<?php
/** 
* Plugin update procedure.
* 
* If an update needs to modify the existing installation we use this. Create a new function as shown in example
* and increment the digit on the end i.e. the version is added to the end without zeros or periods.
* 
* @package YouTube Sidebar
* @author Ryan Bayne   
* @since 3.0.0
* @version 1.0 
*/

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
                                                                        
class YOUTUBESIDEBAR_UpdatePlugin {  
    public function patch_800( $action_requested = 'update' ){
        $update_array = array();
        $update_array['info']['modificationrequired'] = true;
        $update_array['info']['intro'] = __( 'Version 0.0.1 is not compatible with older installations of YouTube Sidebar.', 'youtubesidebar' );
        $update_array['info']['urgency'] = 'high';
        #############################
        #          CHANGES          #
        #############################
        // new features
        //$update_array['changes']['added'][0] = array( 'title' => __( 'Mailing System', 'youtubesidebar' ), 'about' => __( 'Can now perform mass email campaigns with emails being sent in small batches using the plugins schedule procedures.', 'youtubesidebar' ) );
        //$update_array['changes']['added'][1] = array( 'title' => __( 'Dashboard Widgets', 'youtubesidebar' ), 'about' => __( 'Section dashboard widgets added with switches to activate. Most are empty but ready to be customized.', 'youtubesidebar' ) );
        // bug fixes        
        //$update_array['changes']['fixes'][0] = array( 'title' => __( 'Download Failure', 'youtubesidebar' ), 'about' => __( 'Premium download package download attempts should no longer fail and display a notice regarding a user ID being required.', 'youtubesidebar' ) );
        // general changes        
        //$update_array['changes']['general'][0] = array( 'title' => __( 'Files', 'youtubesidebar' ), 'about' => __( 'The plugin package has totally changed, please delete the existing plugin folder.', 'youtubesidebar' ) );
        //$update_array['changes']['general'][1] = array( 'title' => __( 'Download Statistics', 'youtubesidebar' ), 'about' => __( 'Download statistics are being collected and used in graphs.', 'youtubesidebar' ) );
        //$update_array['changes']['general'][2] = array( 'title' => __( 'Updating', 'youtubesidebar' ), 'about' => __( 'The functions and approach to updating the plugin has been improved.', 'youtubesidebar' ) );
        // configuration changes        
        //$update_array['changes']['config'][0] = array( 'title' => __( 'Various Options', 'youtubesidebar' ), 'about' => __( 'At this stage development is rapid, with many new settings being added. Please always check all options screens and ensure nothing is configured incorrectly for your needs.', 'youtubesidebar' ) );
        ############################
        #     REQUEST RESULT       #
        ############################   
        $update_array['failed'] = false;
        $update_array['failedreason'] = __( 'No Applicable', 'youtubesidebar' );
                    
        if( $action_requested == 'update' )
        {             
            /* no installation changes required for version 0.0.3 */
            return $update_array;
        }   
        elseif( $action_requested == 'info' )
        {
            return $update_array['info'];    
        }
        elseif( $action_requested == 'changes' )
        {
            return $update_array['changes'];    
        }
    }    
    public function nextversion_clean() {
        global $youtubesidebar_currentversion;
        
        $installed_version = YOUTUBESIDEBAR::get_installed_version();
        
        $installed_version_cleaned = str_replace( '.', '', $installed_version);
        
        return $installed_version_cleaned + 1;       
    }
    public function changelist( $scope = 'next' ){
        global $youtubesidebar_currentversion;
        
        // standard messages for change types
        $added = __( 'New features added to the plugin, be sure to configure them to suit your needs.', 'youtubesidebar' );
        $fixes = __( 'Bug fixes, remember a fix may instantly change how the plugin operates on your site.', 'youtubesidebar' );
        $general = __( 'General improvements to how features operate, the interface and procedures.', 'youtubesidebar' );
        $config = __( 'These changes involve changing the plugins installation, database updates involved.', 'youtubesidebar' );
                                                                            
        $next_version = $this->nextversion_clean();

        eval( '$method_exists = method_exists ( $this , "patch_' . $next_version .'" );' );

        if(!$method_exists){
            return false;
        }
        
        eval( '$changes_array = $this->patch_' . $next_version .'( "changes");' );
        
        foreach( $changes_array as $key => $group){
            $test = '<ol>';
            foreach( $group as $item){
                $test .= '<li><strong>'.$item['title'].': </strong>'.$item['about'].'</li>';
            }
            $test .= '</ol>';
            
            echo $this->UI->notice_return( 'info', 'Small',ucfirst( $key ), $$key . $test);                
        }    
    } 
}
?>
