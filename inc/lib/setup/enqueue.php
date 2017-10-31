<?php
/**
*@package theme
*/

namespace lib\setup;


/**
* Classe responsavel pela inicialização 
* dos scripts do tema
*/
class enqueue
{
    function __construct()
    {
        add_action( 'admin_enqueue_scripts', array( &$this, 'adminEnqueue' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'siteEnqueue' ) );
    }

    public function adminEnqueue( $hook )
    {
        global $post_type;        

        wp_enqueue_style( 'admin-current-theme', THEME_DIR_URI . '/css/admin-current-theme.css' );
        wp_enqueue_script( 'admin-current-theme', THEME_DIR_URI . '/js/admin-current-theme-script.js' );
    }    

    public function siteEnqueue( $hook )
    {        
        global $post_type;        
        
        wp_enqueue_style( 'awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
        // Font Family       
        wp_enqueue_style( 'site-current-theme', THEME_DIR_URI . '/style.css' );

        if( !wp_script_is( 'jquery' ) ){
            wp_enqueue_script( 'jquery' );
        }        
        
        wp_enqueue_script( 'theme', THEME_DIR_URI . '/js/theme.js' );        
    }

}