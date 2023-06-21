<?php
/*
Plugin Name: WP Login Form
Version: 1.0.11
Plugin URI: https://noorsplugin.com/wordpress-login-form-plugin/
Author: naa986
Author URI: https://noorsplugin.com/
Description: Create a simple login form for use anywhere within WordPress. 
Text Domain: wp-login-form
Domain Path: /languages
*/

if(!defined('ABSPATH')) exit;

class WPLF_LOGIN_FORM
{
    var $plugin_version = '1.0.11';
    var $plugin_url;
    var $plugin_path;
    function __construct()
    {
        define('WPLF_LOGIN_FORM_VERSION', $this->plugin_version);
        define('WPLF_LOGIN_FORM_SITE_URL',site_url());
        define('WPLF_LOGIN_FORM_URL', $this->plugin_url());
        define('WPLF_LOGIN_FORM_PATH', $this->plugin_path());
        $this->plugin_includes();
    }
    function plugin_includes()
    {
        if(is_admin())
        {
            add_filter('plugin_action_links', array($this,'add_plugin_action_links'), 10, 2 );
            include_once('addons/wp-login-form-addons.php');
        }
        add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
        add_action('admin_menu', array($this, 'add_options_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));
        add_filter('do_shortcode_tag', array($this, 'enqueue_plugin_scripts'), 10, 3);
        add_shortcode('wp_login_form', 'wplf_login_form_handler');          
    }
    function enqueue_admin_scripts($hook) {
        if('settings_page_wp-login-form-settings' != $hook) {
            return;
        }
        wp_register_style('wp-login-form-addons-menu', WPLF_LOGIN_FORM_URL.'/addons/wp-login-form-addons.css');
        wp_enqueue_style('wp-login-form-addons-menu');
    }
    function register_plugin_scripts() {
        $options = wp_login_form_get_option();
        if(!isset($options['enable_google_recaptcha_v3']) || empty($options['enable_google_recaptcha_v3'])){
            return;
        }
        if (!is_admin()) {
            wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null);       
        }
    }
    function enqueue_plugin_scripts($output, $tag, $attr) {
        $options = wp_login_form_get_option();
        if(!isset($options['enable_google_recaptcha_v3']) || empty($options['enable_google_recaptcha_v3'])){
            return $output;
        }
        if (!is_admin()) {
            if('wp_login_form' != $tag){ //make sure it is the right shortcode
                return $output;
            }
            wp_enqueue_script('google-recaptcha'); //enqueue the script for printing          
        }
        return $output;
    }
    function plugin_url()
    {
        if($this->plugin_url) return $this->plugin_url;
        return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
    }
    function plugin_path(){ 	
        if ( $this->plugin_path ) return $this->plugin_path;		
        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
    function add_plugin_action_links($links, $file)
    {
        if ( $file == plugin_basename( dirname( __FILE__ ) . '/main.php' ) )
        {
            $links[] = '<a href="options-general.php?page=wp-login-form-settings">'.__('Settings', 'wp-login-form').'</a>';
        }
        return $links;
    }

    function plugins_loaded_handler()
    {
        load_plugin_textdomain('wp-login-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
    }

    function add_options_menu()
    {
        if(is_admin())
        {
            add_options_page(__('WP Login Form', 'wp-login-form'), __('WP Login Form', 'wp-login-form'), 'manage_options', 'wp-login-form-settings', array($this, 'display_options_page'));
        }
    }

    function display_options_page()
    {    
        $plugin_tabs = array(
            'wp-login-form-settings' => __('General', 'wp-login-form'),
            'wp-login-form-settings&action=addons' => __('Add-ons', 'wp-login-form')
        );
        $url = "https://noorsplugin.com/wordpress-login-form-plugin/";
        $link_text = sprintf(__('Please visit the <a target="_blank" href="%s">WP Login Form</a> documentation page for setup instructions.', 'wp-login-form'), esc_url($url));          
        $allowed_html_tags = array(
            'a' => array(
                'href' => array(),
                'target' => array()
            )
        );
        echo '<div class="wrap"><h2>WP Login Form - v'.WPLF_LOGIN_FORM_VERSION.'</h2>';               
        echo '<div class="update-nag">'.wp_kses($link_text, $allowed_html_tags).'</div>';
        $current = '';
        $action = '';
        if (isset($_GET['page'])) {
            $current = sanitize_text_field($_GET['page']);
            if (isset($_GET['action'])) {
                $action = sanitize_text_field($_GET['action']);
                $current .= "&action=" . $action;
            }
        }
        $content = '';
        $content .= '<h2 class="nav-tab-wrapper">';
        foreach ($plugin_tabs as $location => $tabname) {
            if ($current == $location) {
                $class = ' nav-tab-active';
            } else {
                $class = '';
            }
            $content .= '<a class="nav-tab' . $class . '" href="?page=' . $location . '">' . $tabname . '</a>';
        }
        $content .= '</h2>';
        $allowed_html_tags = array(
            'a' => array(
                'href' => array(),
                'class' => array()
            ),
            'h2' => array(
                'href' => array(),
                'class' => array()
            )
        );
        echo wp_kses($content, $allowed_html_tags);

        if(!empty($action))
        { 
            switch($action)
            {
                case 'addons':
                    wp_login_form_display_addons();
                    break;
            }
        }
        else
        {
            $this->general_settings();
        }

        echo '</div>'; 
    }

    function general_settings() {
        if (isset($_POST['wp_login_form_update_settings'])) {
            $nonce = sanitize_text_field($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'wp_login_form_general_settings')) {
                wp_die(__('Error! Nonce Security Check Failed! please save the general settings again.', 'wp-login-form'));
            }
            $enable_google_recaptcha_v3 = '';
            if(isset($_POST['enable_google_recaptcha_v3']) && !empty($_POST['enable_google_recaptcha_v3'])){
                $enable_google_recaptcha_v3 = sanitize_text_field($_POST['enable_google_recaptcha_v3']);
            }
            $google_recaptcha_v3_site_key = '';
            if(isset($_POST['google_recaptcha_v3_site_key']) && !empty($_POST['google_recaptcha_v3_site_key'])){
                $google_recaptcha_v3_site_key = sanitize_text_field($_POST['google_recaptcha_v3_site_key']);
            }
            $post = $_POST;
            do_action('wp_login_form_general_settings_submitted', $post);
            $options = array();
            $options['enable_google_recaptcha_v3'] = $enable_google_recaptcha_v3;
            $options['google_recaptcha_v3_site_key'] = $google_recaptcha_v3_site_key;
            wp_login_form_update_option($options);
            echo '<div id="message" class="updated fade"><p><strong>';
            echo __('Settings Saved', 'wp-login-form').'!';
            echo '</strong></p></div>';
        }
        $options = wp_login_form_get_option();

        ?>

        <form method="post" action="">
            <?php wp_nonce_field('wp_login_form_general_settings'); ?>

            <table class="form-table">

                <tbody>

                    <tr valign="top">
                        <th scope="row"><label for="enable_google_recaptcha_v3"><?php _e('Enable Google reCAPTCHA v3', 'wp-login-form');?></label></th>
                        <td><input name="enable_google_recaptcha_v3" type="checkbox" id="enable_google_recaptcha_v3" <?php checked($options['enable_google_recaptcha_v3'], 1); ?> value="1">
                            <p class="description"><?php _e('Check this option to enable Google reCAPTCHA v3', 'wp-login-form');?></p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="google_recaptcha_v3_site_key"><?php _e('Site Key', 'wp-login-form');?></label></th>
                        <td><input name="google_recaptcha_v3_site_key" type="text" id="google_recaptcha_v3_site_key" value="<?php echo esc_attr($options['google_recaptcha_v3_site_key']); ?>" class="regular-text">
                            <p class="description"><?php _e('Your Google reCAPTCHA v3 site key', 'wp-login-form');?></p></td>
                    </tr>
                    <?php
                    $settings_fields = '';
                    $settings_fields = apply_filters('wp_login_form_general_settings_fields', $settings_fields);
                    if(!empty($settings_fields)){
                        echo $settings_fields;
                    }
                    ?>
                </tbody>

            </table>

            <p class="submit"><input type="submit" name="wp_login_form_update_settings" id="wp_login_form_update_settings" class="button button-primary" value="<?php _e('Save Changes', 'wp-login-form');?>"></p></form>

        <?php
    }
}

$GLOBALS['wplf_login_form'] = new WPLF_LOGIN_FORM();

function wplf_login_form_handler($atts)
{
    $atts = shortcode_atts(array(
        'redirect' => '',
        'form_id' => '',
        'label_username' => '',
        'label_password' => '',
        'label_remember' => '',
        'label_log_in' => '',
        'id_username' => '',
        'id_password' => '',
        'id_remember' => '',
        'id_submit' => '',
        'remember' => '',
        'value_username' => '',
        'value_remember' => '',
        'lost_password' => '',
        'label_lost_password' => '',
    ), $atts);
    $atts = map_deep($atts, 'sanitize_text_field');
    extract($atts);
    $args = array();
    $args['echo'] = "0";
    if(isset($redirect) && $redirect != ""){
        $args['redirect'] = esc_url_raw($redirect);
    }
    if(isset($form_id) && $form_id != ""){
        $args['form_id'] = $form_id; //changing the default id breaks the login form functionality
    }
    if(isset($label_username) && $label_username != ""){
        $args['label_username'] = $label_username;
    }
    if(isset($label_password) && $label_password != ""){
        $args['label_password'] = $label_password;
    }
    if(isset($label_remember) && $label_remember != ""){
        $args['label_remember'] = $label_remember;
    }
    if(isset($label_log_in) && $label_log_in != ""){
        $args['label_log_in'] = $label_log_in;
    }
    if(isset($id_username) && $id_username != ""){
        $args['id_username'] = $id_username;
    }
    if(isset($id_password) && $id_password != ""){
        $args['id_password'] = $id_password;
    }
    if(isset($id_remember) && $id_remember != ""){
        $args['id_remember'] = $id_remember;
    }
    if(isset($id_submit) && $id_submit != ""){
        $args['id_submit'] = $id_submit;
    }
    if(isset($remember) && $remember != ""){
        $args['remember'] = $remember;
    }
    if(isset($value_username) && $value_username != ""){
        $args['value_username'] = $value_username;
    }
    if(isset($value_remember) && $value_remember != ""){
        $args['value_remember'] = $value_remember;
    }
    $login_form = "";
    //$login_form = print_r($args, true);
    if(is_user_logged_in()){
        $login_form .= wp_loginout(esc_url($_SERVER['REQUEST_URI']), false);
    }
    else{
        $login_form .= wp_login_form($args);
        if(isset($lost_password) && $lost_password != "0"){
            $lost_password_label = !empty($label_lost_password) ? $label_lost_password : 'Lost your password?';
            $lost_password_link = '<a class="wplf-lostpassword" href="'.esc_url(wp_lostpassword_url()).'">'.$lost_password_label.'</a>';
            $login_form .= $lost_password_link;
        }
        $options = wp_login_form_get_option();
        if(!isset($options['enable_google_recaptcha_v3']) || empty($options['enable_google_recaptcha_v3'])){  //Google reCAPTCHA v3 is not enabled
            return $login_form;
        }
        if(!isset($options['google_recaptcha_v3_site_key']) || empty($options['google_recaptcha_v3_site_key'])){
            return $login_form;
        }
        $form_class = 'wplf'.uniqid(); //trying a unique class since login form doesn't seem to work if the default form id loginform is changed
        $login_form = str_replace('<form', '<form class="'.$form_class.'" ', $login_form);
        $recaptcha_attr = 'g-recaptcha" data-sitekey="'.$options['google_recaptcha_v3_site_key'].'" data-callback="'.$form_class.'onSubmit"';
        $login_form = str_replace('button-primary"', 'button-primary '.$recaptcha_attr, $login_form);
        //a long unique token string means it's working
        $login_form .= <<<EOT
        <script>
            function {$form_class}onSubmit(token) {
                //console.log(token);
                document.getElementsByClassName('$form_class')[0].submit();
            }
        </script>       
EOT;
        
    }
    return $login_form;
}

function wp_login_form_get_option(){
    $options = get_option('wp_login_form_options');
    if(!is_array($options)){
        $options = wp_login_form_get_empty_options_array();
    }
    return $options;
}

function wp_login_form_update_option($new_options){
    $empty_options = wp_login_form_get_empty_options_array();
    $options = wp_login_form_get_option();
    if(is_array($options)){
        $current_options = array_merge($empty_options, $options);
        $updated_options = array_merge($current_options, $new_options);
        update_option('wp_login_form_options', $updated_options);
    }
    else{
        $updated_options = array_merge($empty_options, $new_options);
        update_option('wp_login_form_options', $updated_options);
    }
}

function wp_login_form_get_empty_options_array(){
    $options = array();
    $options['enable_google_recaptcha_v3'] = '';
    $options['google_recaptcha_v3_site_key'] = '';
    return $options;
}
