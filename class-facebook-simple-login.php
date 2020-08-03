<?php
//namespace Wordpress\fb_login
require_once 'facebook-menu-layout.php';//dependencias para el menu
//require_once 'test-menu.php';

class Facebook_Simple_Login{

  function __construct(){
        $this->create_menu(); //crear menu
        //$this->install_dependences();
        register_activation_hook( __FILE__, array('class-facebook-simple-login','create_menu'));
    }

  function install_dependences(){ //instala dependencias
    $this->find_composer();
    //$this->find_facebook_graph();
  }

  function find_composer(){
      //$require_composer = get_option('fb_install_composer');
      $composer_path = $this->find_root_directory().'/vendor/autoload.php';
      if(!file_exists($composer_path)){
        $this->install_composer();
      }
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

  function find_facebook_graph(){
      $require_fb_graph = get_option('fb_intall_fb_graph');
      $facebook_graph_path = $this->find_root_directory().'/vendor/facebook/';
      if($require_fb_graph && !file_exists($facebook_graph_path)){
        $this->install_facebook_graph();
      }
    }

  private function  install_composer(){
      if(!file_exists($this->find_root_directory().'composer.json')){
        $composer_json_file = fopen($this->find_root_directory().'/composer.json','a+');
        $content = json_encode(array("autoload" => array("psr-4"=> array("Worpress\\" => $this->find_root_directory())),"require" => array()),JSON_FORCE_OBJECT);
        fwrite($composer_json_file, $content);
        fclose($composer_json_file);

      }
      copy('https://getcomposer.org/composer.phar', $this->find_root_directory().'composer.phar');



      /*copy('https://getcomposer.org/installer', $this->find_root_directory().'composer-setup.php');

      if (hash_file('sha384', $this->find_root_directory().'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a')
      {
        $this->write_ref_log('Installer verified');
      } else {
        $this->write_error_log('Installer corrupt');

        unlink($this->find_root_directory().'composer-setup.php');
      }*/

      //try{
        //$this->write_ref_log($this->find_root_directory().'composer-setup.php');
        //$path = $this->find_root_directory().'composer-setup.php';
        //system("php $path",$test1);
        //$this->write_ref_log(var_dump($test1));
        //system('php -f '.$this->find_root_directory().'composer.phar install',$test2);
        //shell_exec('php composer.phar install');
        //include_once('./composer.phar');
        //$path= $this->find_root_directory().'composer.phar';
          //exec("php $path install 2>dev/null >&- <&- >/dev/null &");
        //$this->write_ref_log($test1);
      //}
      /*catch(Exception $e){
        $error_message = $e->getMessage();
        $this->write_error_log($error_message);
      }*/


    }

    private function  install_facebook_graph(){
      //TODO
    }



  function  create_menu(){
    function facebook_login_option_page() {
      add_menu_page(
          'Facebook Simple Login',// Page title
          'Facebook Simple Login',// Menu title
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
    //$loginUrl = htmlspecialchars($helper->getLoginUrl(get_home_url().'/'.$fb_login_page.'.php', $permissions));
    $loginUrl = htmlspecialchars($helper->getLoginUrl(get_home_url().'/'.$fb_login_page.'/', $permissions));
    //$this->write_ref_log($loginUrl);
    return $loginUrl;
    }
    catch(Exception $e){
      $this->write_error_log($e->get_message());
    }
  }

  function write_ref_log($message){
      

    if(is_array($message)){
     
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
