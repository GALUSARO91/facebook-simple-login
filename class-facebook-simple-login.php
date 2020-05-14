<?php
//namespace Wordpress\fb_login
require_once 'facebook-menu-layout.php';//dependencias para el menu
//require_once 'test-menu.php';

class Facebook_Simple_login{

  function __construct(){
      //$this->load_dependences();
      $this->create_menu(); //crear menu

    }

  function find_composer(){
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
      $facebook_graph_path = $this->find_root_directory().'/vendor/facebook/';
      if(!file_exists($facebook_graph_path)){
        $this->install_facebook_graph();
      }
    }

  private function  install_composer(){
      if(!file_exists($this->find_root_directory().'/composer.json')){
        $composer_json_file = fopen($this->find_root_directory().'/composer.json','w+');
        $content = json_encode(array("autoload" => array("psr-4"=> array("Worpress\\" => $this->find_root_directory())),"require" => array()),JSON_FORCE_OBJECT);
        fwrite($composer_json_file, $content);
        fclose($composer_json_file);

      }
      //copy('https://getcomposer.org/composer-stable.phar', $this->find_root_directory().'/composer.phar');
      copy('https://getcomposer.org/installer', $this->find_root_directory().'/composer-setup.php');

      if (hash_file('sha384', $this->find_root_directory().'/composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a')
      {
        $this->write_ref_log('Installer verified');
      } else {
        $this->write_error_log('Installer corrupt');

        unlink($this->find_root_directory().'/composer-setup.php');
      }
      try{
        //system('php '.$this->find_root_directory().'/composer.phar --install',$value);
        system('sudo php '.$this->find_root_directory().'/composer-setup.php',$test1);
        $this->write_ref_log($test1);
        system('php '.$this->find_root_directory().'/composer.phar install',$test2);
        $this->write_ref_log($test2);
      }
      catch(Exception $e){
        $error_message = $e->getMessage();
        $this->write_error_log($error_message);
      }

    }

    private function  install_facebook_graph(){
      //TODO
    }

  function load_dependences(){
      $this->find_composer();
      $this->find_facebook_graph();
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

  private function write_ref_log($message){
    $reflog =  fopen(dirname( __FILE__ )."/reflog.text",'w+');
    fwrite($reflog,'[ '.date(DATE_RFC1123).'] '.$message);
    fclose($reflog);
  }

  private function write_error_log($error_message){
    $errorlog =  fopen(dirname( __FILE__ )."/errorlog.text",'w+');
    fwrite($errorlog,'[ '.date(DATE_RFC1123).'] '.$error_message);
    fclose($errorlog);
  }
}
