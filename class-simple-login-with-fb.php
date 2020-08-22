<?php
/*


*/
require_once 'menu-layout.php'; //Menu dependences

if(!class_exists( 'Simple_Login_With_Fb' )){

class Simple_Login_With_Fb{

  function __construct(){
        $this->create_menu(); //crear menu
        register_activation_hook( __FILE__, array('class-facebook-simple-login','create_menu'));
    }

  function find_root_directory(){
      if(!ABSPATH){
        $_SERVER = $GLOBALS['_SERVER'];
        $root = $_SERVER['DOCUMENT_ROOT'];
      } else{
        $root = ABSPATH;
      }
      return $root;
    }


  function  create_menu(){
    function facebook_login_option_page() {
      add_menu_page(
          'Simple Login with FB',// Page title
          'Simple Login with FB',// Menu title
          'manage_options',//capabilities
          'facebook-simple-login',//menu slug
          'facebook_login_options_page_html',//function to display info
          '',
          20
        );
      }
    add_action( 'admin_menu', 'facebook_login_option_page' );

    }

  function set_fb_callback($fb_app_id, $fb_app_secret, $fb_login_page){
    try{
    require_once $this->find_root_directory().'vendor/autoload.php';
    if(!session_id()){
        session_start();
    }

    $fb = new \Facebook\Facebook([
      'app_id' => $fb_app_id, // Replace {app-id} with your app id
      'app_secret' => $fb_app_secret,
      'default_graph_version' => 'v3.2',
      'persistent_data_handler' => 'session'
    ]);
    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email']; // Optional permissions
    $loginUrl = htmlspecialchars($helper->getLoginUrl(get_home_url().'/'.$fb_login_page.'/', $permissions));
    return $loginUrl;
    }
    catch(Exception $e){
      $this->write_error_log($e->get_message());
    }
  }

  function write_ref_log($message){

  if (is_object($message)){
    $this->write_ref_log(serialize($message));

  } elseif(is_array($message)){

      foreach($message as $item_name =>$item_value){

        $this->write_ref_log($item_name. ' : '.$item_value);

      }

   } else {

    $reflog =  fopen(dirname( __FILE__ )."/reflog.text",'a+');
    $date = date(DATE_RFC1123);
    $body = "[$date] $message\n";
    fwrite($reflog,$body);
    fclose($reflog);

   }




  }


  function write_error_log($error_message){
    $errorlog =  fopen(dirname( __FILE__ )."/errorlog.text",'a+');
    $date = date(DATE_RFC1123);
    $body = "[$date] $error_message\n";
    fwrite($errorlog,$body);
    fclose($errorlog);
  }


}
}
