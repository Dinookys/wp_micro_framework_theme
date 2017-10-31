<?php

namespace lib\callbacks;

use lib\core\inputs;

use lib\helpers\themeHelper as helper;

class postTypesCallbacks
{
    
    public function metabox_wpmf_example( $post )
    {
        $value = get_post_meta( $post->ID, 'wpmf_example', true );
        $image = wp_get_attachment_image_url( $value, 'full' );
        
        echo '<div class="image_container" style="background: #ccc; display: table; margin: 10px 0;" >';        
        if( $image ){
           echo '<img src="'. $image .'" alt="" style="max-width: 100%; display: block;" >';
        }
        echo '</div>';
        
        echo helper::getImageBox( 'wpmf_example_field', '.image_container' );        

        echo inputs::input( array(
            'name' => 'wpmf_example_field',
            'type' => 'hidden',
            'value' => $value
        ) );

    }

}