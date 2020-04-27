<?php
/**
 * Plugin Name: Facebook simple login
 */
 function plugin_init(){
 include_once dirname( __FILE__ ) . '/class-facebook-simple-login.php';
 register_activation_hook( __FILE__, array( 'Facebook_Simple_login','__construct' ) );
}

try{
  add_action( 'plugins_loaded', 'plugin_init' );
}

catch (Exception $e) {
  echo $e->getMessage();
}
