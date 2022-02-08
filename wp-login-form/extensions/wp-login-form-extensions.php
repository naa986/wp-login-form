<?php

function wp_login_form_display_extensions()
{
    //echo '<div class="wrap">';
    //echo '<h2>' .__('WP Login Form Extensions', 'wp-login-form') . '</h2>';
    echo '<link type="text/css" rel="stylesheet" href="'.WPLF_LOGIN_FORM_URL.'/extensions/wp-login-form-extensions.css" />' . "\n";
    
    $extensions_data = array();

    $extension_1 = array(
        'name' => 'Force Redirect',
        'thumbnail' => WPLF_LOGIN_FORM_URL.'/extensions/images/wp-login-form-force-redirect.png',
        'description' => 'Redirect all successful WordPress logins to a specific URL',
        'page_url' => 'https://noorsplugin.com/wordpress-login-form-plugin/',
    );
    array_push($extensions_data, $extension_1);
    
    //Display the list
    $output = '';
    foreach ($extensions_data as $extension) {
        $output .= '<div class="wp_login_form_extensions_item_canvas">';

        $output .= '<div class="wp_login_form_extensions_item_thumb">';
        $img_src = $extension['thumbnail'];
        $output .= '<img src="' . $img_src . '" alt="' . $extension['name'] . '">';
        $output .= '</div>'; //end thumbnail

        $output .='<div class="wp_login_form_extensions_item_body">';
        $output .='<div class="wp_login_form_extensions_item_name">';
        $output .= '<a href="' . $extension['page_url'] . '" target="_blank">' . $extension['name'] . '</a>';
        $output .='</div>'; //end name

        $output .='<div class="wp_login_form_extensions_item_description">';
        $output .= $extension['description'];
        $output .='</div>'; //end description

        $output .='<div class="wp_login_form_extensions_item_details_link">';
        $output .='<a href="'.$extension['page_url'].'" class="wp_login_form_extensions_view_details" target="_blank">View Details</a>';
        $output .='</div>'; //end detils link      
        $output .='</div>'; //end body

        $output .= '</div>'; //end canvas
    }
    echo $output;
    
    //echo '</div>';//end of wrap
}
