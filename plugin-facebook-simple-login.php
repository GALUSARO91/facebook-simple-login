<?php
/**
 * Plugin Name: Facebook simple login
 */


 function plugin_init(){
   require_once dirname( __FILE__ ) . '/class-facebook-simple-login.php';
   $fb_login = new Facebook_Simple_Login;
   $fb_login->write_ref_log('test');
   register_activation_hook( __FILE__, array( $fb_login,$fb_login->__construct ) );
}

/*function test_calback(){
  $content = 'callback works';
  $fb_login->write_ref_log($content);

}*/

try{

  add_action( 'plugins_loaded', 'plugin_init' );
  //add_action( 'plugins_loaded', 'test-callback');
  /*function test_callback(){
    $content = 'callback works';
    $fb_login->write_ref_log($content);

  }
  test_callback();*/
}

catch (Exception $e) {
  $fb_login->write_error_log($e->getMessage());
}
