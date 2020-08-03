<?php
/**
 * Plugin Name: Facebook simple login
 */

function plugin_init(){
  require_once dirname( __FILE__ ) . '/class-facebook-simple-login.php';
  global $fb_login;
  $fb_login = new Facebook_Simple_Login;
  //register_activation_hook( __FILE__, array($GLOBALS['fb_login'],$GLOBALS['fb_login']->__construct ));
  //register_activation_hook( __FILE__, array($GLOBALS['fb_login']));
  }

function write_login_link($fb_api_id,$fb_app_secret,$fb_login_page,$fb_login_page_old = null){
    $link_exist = false;
    $all_menus = wp_get_nav_menus();
    foreach($all_menus as $menu){
        $menu_items = wp_get_nav_menu_items($menu);
        //$GLOBALS['fb_login']->write_ref_log($menu->term_id);
        //$GLOBALS['fb_login']->write_ref_log($menu->name);
        foreach($menu_items as $item){
            //$GLOBALS['fb_login']->write_ref_log($item->title);
            //$GLOBALS['fb_login']->write_ref_log($item->ID);
            if(isset($fb_login_page_old) && $item->title == $fb_login_page_old) {
                $link_exist = true;
                $callback_url =$GLOBALS['fb_login']->set_fb_callback($fb_api_id, $fb_app_secret,$fb_login_page);
                $menu_object_args = [
                  'menu-item-title' => $fb_login_page,
                  'menu-item-url' => $callback_url,
                  'menu-item-status' => 'publish',
                  'menu-item-type' => 'custom'
                ];
                wp_update_nav_menu_item($menu->term_id,$item->ID,$menu_object_args);
                //$GLOBALS['fb_login']->write_ref_log('item exist: '. $link_exist);
            }
        }
    
        if(!$link_exist){
            
        $callback_url =$GLOBALS['fb_login']->set_fb_callback($fb_api_id, $fb_app_secret,$fb_login_page);
                $menu_object_args = [
                  'menu-item-title' => $fb_login_page,
                  'menu-item-url' => $callback_url,
                  'menu-item-status' => 'publish',
                  'menu-item-type' => 'custom'
                ];
                wp_update_nav_menu_item($menu->term_id,0,$menu_object_args);
        
    
            }
        }
    
}

function load_login_link_in_header(){
    
    write_login_link(get_option('fb_api_id'),get_option('fb_app_secret'),get_option('fb_login_page'),get_option('fb_login_page'));
}


function set_options($option_name,$old_value,$value){

    $options_set = [
        'fb_api_id' => '',
        'fb_app_secret' => '',
        'fb_login_page' =>'',
        'fb_signup_page' => '',
        'fb_my_account' => '',
        'fb_install_composer' => 0,
        'fb_install_fb_graph' => 0
      ];

    //$GLOBALS['fb_login']->write_ref_log($options_set['fb_login_page']);
  if(array_key_exists($option_name, $options_set)){

    foreach($options_set as $option => $option_value){
        if($option_name == $option){
          $options_set[$option] = strval($value);
        } else{
          $options_set[$option] = strval(get_option($option));
        }
      //$GLOBALS['fb_login']->write_ref_log('key: '. $option . '; value: '. $option_value);
    }
    //$GLOBALS['fb_login']->write_ref_log($options_set['fb_login_page']);
    if($options_set['fb_api_id'] !='' && $options_set['fb_app_secret'] != '' && $options_set['fb_login_page'] !='') {
   
   /* 
   **Viejo codigo del link
    -------------------------------------
    
   $callback_url =$GLOBALS['fb_login']->set_fb_callback($options_set['fb_api_id'], $options_set['fb_app_secret'] ,$options_set['fb_login_page']);
    $all_menus = wp_get_nav_menus();
    $menu_object_args = [
      'menu-item-title' => $options_set['fb_login_page'],
      'menu-item-url' => $callback_url,
      'menu-item-status' => 'publish',
      'menu-item-type' => 'custom', // optional
    ];
    //wp_update_nav_menu_item($header_menu,0,$menu_object_args);
    foreach ($all_menus as $menu) {
      wp_update_nav_menu_item($menu->term_id,0,$menu_object_args);
      //$GLOBALS['fb_login']->write_ref_log($menu->term_id);
    }
    
    ---------------------------------------
    */
    
    if ($option_name == 'fb_login_page'){
        
        write_login_link($options_set['fb_api_id'],$options_set['fb_app_secret'],$value,$old_value);
    }
    
    //write_login_link($options_set['fb_api_id'],$options_set['fb_app_secret'],$options_set['fb_login_page']);
   
      if($option_name == 'fb_signup_page'|| $option_name == 'fb_my_account' ||$option_name == 'fb_login_page'){
          $origen = dirname(__FILE__).'/'.str_replace('_','-',$option_name).'.php';
          $destino = get_stylesheet_directory().'/page-'.$value.'.php';
          //$GLOBALS['fb_login']->write_ref_log($origen);
          //$GLOBALS['fb_login']->write_ref_log($destino);
          copy($origen,$destino);
          //copy(dirname(__FILE__).'/'.$option.'.php',get_stylesheet_directory().'/page-'.$option_value.'.php');
          //$GLOBALS['fb_login']->write_ref_log('page found: '.is_page($value));
          
        $old_page = get_page_by_title($old_value);
        //$GLOBALS['fb_login']->write_ref_log(is_page($old_page->ID));
          
          if(!$old_page->ID){
            wp_insert_post(array('post_title' => $value,'post_content' => '', 'post_type' => 'page', 'post_status' => 'publish'),true);

          } else{
              
              //$GLOBALS['fb_login']->write_ref_log('old page: '.$old_page->ID);
            wp_update_post([
                  'ID' => $old_page->ID,
                  'post_title' => $value,
                  'post_name' => $value
                  ]);
              
          }
        } 
        
        /*elseif ($option_name == 'fb_login_page'){
             $origen = dirname(__FILE__).'/'.str_replace('_','-',$option_name).'.php';
             $destino = get_home_path().'/'.$value.'.php';
             copy($origen,$destino);
        }*/

    //}
  }

  if ($option_name == 'fb_install_composer' || $option_name == 'fb_install_fb_graph') {

    if($options_set['fb_install_composer'] == 1){

      $GLOBALS['fb_login']->find_composer();
    }

    if($options_set['fb_install_fb_graph']  == 1){

      $GLOBALS['fb_login']->find_facebook_graph();
    }
  }
  //$GLOBALS['fb_login']->write_ref_log(dirname(__FILE__).'/fb-login-page.php');
  //$GLOBALS['fb_login']->write_ref_log(get_stylesheet_directory().'/page-'.get_option(fb_login_page).'.php');
  }
}


try{
    
  add_action( 'plugins_loaded', 'plugin_init' );
  add_action('updated_option','set_options',10,3);
  add_action('get_header','load_login_link_in_header');

}



catch (Exception $e) {

  $GLOBALS['fb_login']->write_error_log($e->getMessage());
}

?>
