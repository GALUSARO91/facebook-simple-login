
<?php
  function register_fb_settings (){
    register_setting('fblogin', 'facebook_login_options');

    add_settings_section(
      'facebook_login_section', // identificador de la seccion a la que pertenece
      'Facebook App Login info', //informacion que aparecera en el website
      'facebook_login_sections_cb', // funcion para los encabezados
      'fblogin'); //nombre de la pagina que se mostrara en el menu (slug)

    add_settings_field(
      'app_id', //identificador del campo agregardo
      'Identificador de la API', //nombre del campo que aparecera en el website
      'get_input_field', //funcion que se ejecutara
      'fblogin', //identificador de ajustes registrados en el plugin
      'facebook_login_section', // identificador de la seccion a la que pertenece
      [
        'label_for' => 'app_id',
        'input_type' => 'text'
      ]);
      add_settings_field(
        'app_secret', //identificador del campo agregardo
        'Token de acceso', //nombre del campo que aparecera en el website
        'get_input_field', //funcion que se ejecutara
        'fblogin', //identificador de ajustes registrados en el plugin
        'facebook_login_section', // identificador de la seccion a la que pertenece
        [
          'label_for' => 'app_secret',
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
          'login_page', //identificador del campo agregardo
          'Pagina de inicio de sesion', //nombre del campo que aparecera en el website
          'get_input_field', //funcion que se ejecutara
          'fblogin', //identificador de ajustes registrados en el plugin
          'facebook_login_section', // identificador de la seccion a la que pertenece
          [
            'label_for' => 'login_page',
            'input_type' => 'text'
          ]);

        add_settings_field(
            'signup_page', //identificador del campo agregardo
            'Pagina de Registro', //nombre del campo que aparecera en el website
            'get_input_field', //funcion que se ejecutara
            'fblogin', //identificador de ajustes registrados en el plugin
            'facebook_login_section', // identificador de la seccion a la que pertenece
            [
              'label_for' => 'signup_page',
              'input_type' => 'text'
            ]);


        add_settings_section(
          'facebook_login_dependences', // identificador de la seccion a la que pertenece
          'Install dependences', //informacion que aparecera en el website
          'facebook_login_dependences_cb', // funcion para los encabezados
          'fblogin'); //nombre de la pagina que se mostrara en el menu (slug)

        add_settings_field(
          'install_composer', //identificador del campo agregardo
          'Instalar Composer', //nombre del campo que aparecera en el website
          'get_input_field', //funcion que se ejecutara
          'fblogin', //identificador de ajustes registrados en el plugin
          'facebook_login_dependences', // identificador de la seccion a la que pertenece
          [
            'label_for' => 'install_composer',
            'input_type' => 'checkbox'
          ]);

          add_settings_field(
            'install_fb_graph', //identificador del campo agregardo
            'Instalar FB api graph', //nombre del campo que aparecera en el website
            'get_input_field', //funcion que se ejecutara
            'fblogin', //identificador de ajustes registrados en el plugin
            'facebook_login_dependences', // identificador de la seccion a la que pertenece
            [
              'label_for' => 'install_fb_graph',
              'input_type' => 'checkbox'
            ]);

  }

  add_action('admin_init', 'register_fb_settings');


  function get_input_field ($args){
      ?>
        <input type=<?php echo esc_attr($args['input_type']) ?> name=<?php echo esc_attr($args['label_for'])?> value="">
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

  function fb_sanitize_options($args){
    //// TODO: 
  }
?>
