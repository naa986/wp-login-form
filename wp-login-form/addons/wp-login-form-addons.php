<?php

function wp_login_form_display_addons()
{
    /*
    echo '<div class="wrap">';
    echo '<h2>' .__('WP Login Form Add-ons', 'wp-login-form') . '</h2>';
    */
    $addons_data = array();

    $addon_1 = array(
        'name' => 'Force Redirect',
        'thumbnail' => WPLF_LOGIN_FORM_URL.'/addons/images/wp-login-form-force-redirect.png',
        'description' => 'Redirect all successful WordPress logins to a specific URL',
        'page_url' => 'https://noorsplugin.com/wordpress-login-form-plugin/',
    );
    array_push($addons_data, $addon_1);
    
    //Display the list
    foreach ($addons_data as $addon) {
        ?>
        <div class="wp_login_form_addons_item_canvas">
        <div class="wp_login_form_addons_item_thumb">
            <img src="<?php echo esc_url($addon['thumbnail']);?>" alt="<?php echo esc_attr($addon['name']);?>">
        </div>
        <div class="wp_login_form_addons_item_body">
        <div class="wp_login_form_addons_item_name">
            <a href="<?php echo esc_url($addon['page_url']);?>" target="_blank"><?php echo esc_html($addon['name']);?></a>
        </div>
        <div class="wp_login_form_addons_item_description">
        <?php echo esc_html($addon['description']);?>
        </div>
        <div class="wp_login_form_addons_item_details_link">
        <a href="<?php echo esc_url($addon['page_url']);?>" class="wp_login_form_addons_view_details" target="_blank">View Details</a>
        </div>    
        </div>
        </div>
        <?php
    }
    echo '</div>';//end of wrap
}
