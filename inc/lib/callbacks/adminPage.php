<?php

namespace lib\callbacks;

use lib\core\inputs;

use lib\helpers\themeHelper as helper;

class adminPage
{

    public function get_container_image( $args )
    {
        echo '<hr />';        
        echo '<div class="image-preview">';

        if( isset( $args['name'] ) && get_option( $args['name'] ) ){
            $args['value'] = get_option( $args['name'] );
            $image = wp_get_attachment_image_url( get_option( $args['name'] ), 'full');            
            echo '<img src="'. $image .'" style="max-width: 100%" alt="" />';
        }else{
            $image = wp_get_attachment_image_url( $args['value'], 'full');
            echo '<img src="'. $image .'" style="max-width: 100%" alt="" />';
        }        
        
        echo '</div>';
        echo inputs::input( $args );
        echo helper::getImageBox( $args['name'], '.image-preview' );
    }
    
}