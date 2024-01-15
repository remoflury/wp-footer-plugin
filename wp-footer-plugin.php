<?php
/*
Plugin Name: Footer Plugin
Description: Custom plugin for managing footer details like opening hours.
Version: 1.0
Author: Remo Flury
*/

function my_footer_plugin_menu() {
  add_menu_page(
      'Footer Settings',
      'Footer Settings',
      'manage_options',
      'my-footer-plugin-settings',
      'my_footer_plugin_settings_page',
      null,
      99
  );
}
add_action('admin_menu', 'my_footer_plugin_menu');

function my_footer_plugin_settings_page() {
  ?>
  <div class="wrap">
  <h2>Öffnungszeiten</h2>
  <form method="post" action="options.php">
      <?php
      settings_fields("section");
      do_settings_sections("my-footer-plugin-options");
      submit_button();
      ?>          
  </form>
  </div>
  <?php
}

// Registering Settings and Fields
function my_footer_plugin_settings() {
  add_settings_section("section", "All Settings", null, "my-footer-plugin-options");
  
  add_settings_field("my-footer-uberschrift", "Überschrift", "display_uberschrift_element", "my-footer-plugin-options", "section");
  add_settings_field("my-footer-montag-freitag", "Montag bis Freitag", "display_montag_freitag_element", "my-footer-plugin-options", "section");
  add_settings_field("my-footer-samstag", "Samstag", "display_samstag_element", "my-footer-plugin-options", "section");
  add_settings_field("my-footer-sonntag", "Sonntag", "display_sonntag_element", "my-footer-plugin-options", "section");
  register_setting("section", "my-footer-uberschrift");
  register_setting("section", "my-footer-montag-freitag");
  register_setting("section", "my-footer-samstag");
  register_setting("section", "my-footer-sonntag");
}

function display_uberschrift_element() {
  ?>
  <input type="text" name="my-footer-uberschrift" id="my-footer-uberschrift" value="<?php echo get_option('my-footer-uberschrift'); ?>" />
  <?php
}
function display_montag_freitag_element() {
  ?>
  <input type="text" name="my-footer-montag-freitag" id="my-footer-montag-freitag" value="<?php echo get_option('my-footer-montag-freitag'); ?>" />
  <?php
}
function display_samstag_element() {
  ?>
  <input type="text" name="my-footer-samstag" id="my-footer-samstag" value="<?php echo get_option('my-footer-samstag'); ?>" />
  <?php
}
function display_sonntag_element() {
  ?>
  <input type="text" name="my-footer-sonntag" id="my-footer-sonntag" value="<?php echo get_option('my-footer-sonntag'); ?>" />
  <?php
}

add_action("admin_init", "my_footer_plugin_settings");

// exposing data through API
add_action('rest_api_init', function () {
  register_rest_route('/wp/v2/footer-details/v1', '/data/', array(
      'methods' => 'GET',
      'callback' => 'my_footer_plugin_api_data'
  ));
});

function my_footer_plugin_api_data() {
  $data = [
      'uberschrift' => get_option('my-footer-uberschrift'),
      'montag_bis_freitag' => get_option('my-footer-montag-freitag'),
      'samstag' => get_option('my-footer-samstag'),
      'sonntag' => get_option('my-footer-sonntag')
  ];
  return new WP_REST_Response($data, 200);
}

