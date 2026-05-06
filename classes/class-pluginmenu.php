<?php
/**
* Beta testing only (check if in use yet) - phasing array files into classes of their own then calling into the main class
* 
* @package YouTube Sidebar
* @author Ryan Bayne   
* @since 3.0.0
* @version 1.2 
*/
class YOUTUBESIDEBAR_TabMenu {
    public function menu_array() {
        $menu_array = array();
        
        ######################################################
        #                                                    #
        #                        MAIN                        #
        #                                                    #
        ######################################################
        // can only have one view in main right now until WP allows pages to be hidden from showing in
        // plugin menus. This may provide benefit of bringing user to the latest news and social activity
        // main page
        $menu_array['main']['groupname'] = 'main';        
        $menu_array['main']['slug'] = 'youtubesidebar';// home page slug set in main file
        $menu_array['main']['menu'] = __( 'Plugins Dashboard', 'youtubesidebar' );// plugin admin menu
        $menu_array['main']['pluginmenu'] = __( 'Plugins Dashboard' ,'youtubesidebar' );// for tabbed menu
        $menu_array['main']['name'] = "main";// name of page (slug) and unique
        $menu_array['main']['title'] = 'YouTube Sidebar Dashboard';// title at the top of the admin page
        $menu_array['main']['parent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu         
        $menu_array['main']['tabmenu'] = false;// boolean - true indicates multiple pages in section, false will hide tab menu and show one page 

        /*
        ######################################################
        #                                                    #
        #                   POSTS SECTION                    #
        #                                                    #
        ###################################################### 
  
        // posts section 
        $menu_array['postssection']['groupname'] = 'poststools';
        $menu_array['postssection']['slug'] = 'youtubesidebar_postssection'; 
        $menu_array['postssection']['menu'] = __( 'Posts', 'youtubesidebar' );
        $menu_array['postssection']['pluginmenu'] = __( 'Posts', 'youtubesidebar' );
        $menu_array['postssection']['name'] = "postssection";
        $menu_array['postssection']['title'] = __( 'Posts', 'youtubesidebar' ); 
        $menu_array['postssection']['parent'] = 'parent'; 
        $menu_array['postssection']['tabmenu'] = true;     
        */
                     
        return $menu_array;
    }
} 
?>
