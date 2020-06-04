
<?php
  function register_fb_settings (){
    /*$default = json_encode([
      'api_id' =>'',
      'app_secret' => '',
      'login_page' => '',
      'signup_page' => '',
      'install_composer' => false,
      'install_fb_graph' => false,
  ],JSON_FORCE_OBJECT);*/

    register_setting('fblogin', 'fb_api_id',['type' => 'string','default' => '']);
    register_setting('fblogin', 'fb_app_secret', ['type' => 'string','default' => '']);
    register_setting('fblogin', 'fb_login_page', ['type' => 'string','default' => '']);
    register_setting('fblogin', 'fb_signup_page', ['type' => 'string','default' => '']);
    register_setting('fblogin', 'fb_install_composer', ['type' => 'boolean','default' => false ]);
    register_setting('fblogin', 'fb_install_fb_graph',['type' => 'boolean','default' => false]);

    add_settings_section(
      'facebook_login_section', // identificador de la seccion a la que pertenece
      'Facebook App Login info', //informacion que aparecera en el website
      'facebook_login_sections_cb', // funcion para los encabezados
      'fblogin'); //nombre de la pagina que se mostrara en el menu (slug)

    add_settings_field(
      'fb_api_id', //identificador del campo agregardo
      'Identificador de la API', //nombre del campo que aparecera en el website
      'get_input_text_field', //funcion que se ejecutara
      'fblogin', //identificador de ajustes registrados en el plugin
      'facebook_login_section', // identificador de la seccion a la que pertenece
      [
        'label_for' => 'fb_api_id',
        'input_type' => 'text'

      ]);
      add_settings_field(
        'fb_app_secret', //identificador del campo agregardo
        'Token de acceso', //nombre del campo que aparecera en el website
        'get_input_text_field', //funcion que se ejecutara
        'fblogin', //identificador de ajustes registrados en el plugin
        'facebook_login_section', // identificador de la seccion a la que pertenece
        [
          'label_for' => 'fb_app_secret',
          'input_type' => 'password'

        ]);
        /*add_settings_field(
          'callback_page', //identificador del campo agregardo
          'Pagina de retorno', //nombre del campo que aparecera en el website
          'get_input_field', //funcion que se ejecutara
          'fblogin', //identificador de ajustes registrados en el plugin
          'facebook_login_section', // identificador de la seccion a la que pertenece
          [
            'label_for' => 'callback_page',
            'input_type' => 'text'
          ]);*/

        add_settings_field(
          'fb_login_page', //identificador del campo agregardo
          'Pagina de inicio de sesion', //nombre del campo que aparecera en el website
          'get_input_text_field', //funcion que se ejecutara
          'fblogin', //identificador de ajustes registrados en el plugin
          'facebook_login_section', // identificador de la seccion a la que pertenece
          [
            'label_for' => 'fb_login_page',
            'input_type' => 'text'

          ]);

        add_settings_field(
            'fb_signup_page', //identificador del campo agregardo
            'Pagina de Registro', //nombre del campo que aparecera en el website
            'get_input_text_field', //funcion que se ejecutara
            'fblogin', //identificador de ajustes registrados en el plugin
            'facebook_login_section', // identificador de la seccion a la que pertenece
            [
              'label_for' => 'fb_signup_page',
              'input_type' => 'text'

            ]);


        add_settings_section(
          'facebook_login_dependences', // identificador de la seccion a la que pertenece
          'Install dependences', //informacion que aparecera en el website
          'facebook_login_dependences_cb', // funcion para los encabezados
          'fblogin'); //nombre de la pagina que se mostrara en el menu (slug)

        add_settings_field(
          'fb_install_composer', //identificador del campo agregardo
          'Composer', //nombre del campo que aparecera en el website
          'get_input_checkbox_field', //funcion que se ejecutara
          'fblogin', //identificador de ajustes registrados en el plugin
          'facebook_login_dependences', // identificador de la seccion a la que pertenece
          [
            'label_for' => 'fb_install_composer',
            'input_type' => 'checkbox'

          ]);

          add_settings_field(
            'fb_install_fb_graph', //identificador del campo agregardo
            'Facebook api graph', //nombre del campo que aparecera en el website
            'get_input_checkbox_field', //funcion que se ejecutara
            'fblogin', //identificador de ajustes registrados en el plugin
            'facebook_login_dependences', // identificador de la seccion a la que pertenece
            [
              'label_for' => 'fb_install_fb_graph',
              'input_type' => 'checkbox'

            ]);

  }

  add_action('admin_init', 'register_fb_settings');



  function get_input_text_field ($args){
      ?>
        <?php
          $option = get_option($args['label_for']);
         ?>
      <input type= <?php
      echo esc_attr($args['input_type'])
      ?> name= <?php
      echo esc_attr($args['label_for'])
      ?> value=
      <?php echo isset($option)? $option : ''
      ?> >

      <?php

    }

  function get_input_checkbox_field ($args){
      ?>
        <?php
          $option = get_option($args['label_for']);
         ?>
      <input type= <?php
      echo esc_attr($args['input_type']);
      ?> name=<?php
      echo esc_attr($args['label_for']);?>
      value = <?php
      echo isset($option) && $option != '' ? esc_attr(false) : esc_attr(true); ?>
      > <?php
      echo isset($option) && $option != '' ? 'Uninstall' : 'Install';?>
    </input>
    <?php
    }




  function facebook_login_sections_cb (){
    echo '<p><i>
        Please fill up fields below with required info.
     </i></p>';

  }

  function facebook_login_dependences_cb (){
    echo '<p><i>
        If you are able to install the dependences on you own, you can leave this unchecked.
     </i></p>';
  }

?>
<?php
  function facebook_login_options_page_html() {
      ?>
      <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
          <?php
          // output security fields for the registered setting
          settings_fields('fblogin');
          // output setting sections and their fields
          // (sections are registered for "wporg", each field is registered to a specific section)
          do_settings_sections('fblogin');
          // output save settings button
          submit_button('Save Settings');
          ?>
        </form>
      </div>
      <?php
  }

?>
