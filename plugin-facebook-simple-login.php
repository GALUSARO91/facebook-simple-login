<?php
/**
 * Plugin Name:       Facebook Simple Login
 * Plugin URI:        Pending
 * Description:       Use it to create a passwordless simple alternative for user to login to wordpress. Make user log in with just a click.
 * Version:           1.0.0
 * Requires at least: Pending
 * Requires PHP:      Pending
 * Author:            Luis Gabriel Rodriguez Sandino.
 * Author URI:        Pending
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       facebook-simple-login
 * Domain Path:       Pending
**/
function plugin_init(){
  require_once dirname( __FILE__ ) . '/class-facebook-simple-login.php';
  global $fb_login;
  $fb_login = new Facebook_Simple_Login;

  }

function write_login_link($fb_api_id,$fb_app_secret,$fb_login_page,$fb_login_page_old = null){
    $link_exist = false;
    $all_menus = wp_get_nav_menus();
    foreach($all_menus as $menu){
        $menu_items = wp_get_nav_menu_items($menu);

        foreach($menu_items as $item){

            if(isset($fb_login_page_old) && $item->title == $fb_login_page_old) {
                $link_exist = true;
                $callback_url =$GLOBALS['fb_login']->set_fb_callback($fb_api_id, $fb_app_secret,$fb_login_page);
                $menu_object_args = array(
                  'menu-item-title' => $fb_login_page,
                  'menu-item-url' => $callback_url,
                  'menu-item-status' => 'publish',
                  'menu-item-type' => 'custom'
                );
                wp_update_nav_menu_item($menu->term_id,$item->ID,$menu_object_args);
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
        'fb_login_page' =>''
      ];

  if(array_key_exists($option_name, $options_set)){

    foreach($options_set as $option => $option_value){
        if($option_name == $option){
          $options_set[$option] = strval($value);
        } else{
          $options_set[$option] = strval(get_option($option));
        }

    }
    if($options_set['fb_api_id'] !='' && $options_set['fb_app_secret'] != '' && $options_set['fb_login_page'] !='') {


    if ($option_name == 'fb_login_page'){

        write_login_link($options_set['fb_api_id'],$options_set['fb_app_secret'],$value,$old_value);
        $origen = dirname(__FILE__).'/'.str_replace('_','-',$option_name).'.php';
        $destino = get_stylesheet_directory().'/page-'.$value.'.php';
        copy($origen,$destino);
        $old_page = get_page_by_title($old_value);

        if(!$old_page->ID){

            wp_insert_post(array('post_title' => $value,'post_content' => '', 'post_type' => 'page', 'post_status' => 'publish'),true);

          } else{

            $args = array(
              'ID' => $old_page->ID,
              'post_title' => $value,
              'post_name' => $value
            );
            wp_update_post($args);
          }
        }
      }
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
