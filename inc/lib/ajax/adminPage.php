<?php

namespace lib\ajax;

use lib\callbacks\adminPage as callbacks;

class adminPage
{

    public function __construct()
    {
        $this->callback = new callbacks();
        add_action( 'wp_ajax_add_new_haribari', array( &$this, 'add_new_haribari' ) );
    }

    public function add_new_haribari()
    {
        $next_key = filter_input( INPUT_POST, 'key' );        

        if( $next_key ){

            echo $this->callback->haribariItem( array( 'img' => '', 'key' => $next_key, 'link' => null ) );
        }        

        die();

    }

}