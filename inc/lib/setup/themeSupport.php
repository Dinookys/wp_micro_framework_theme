<?php
/**
*@package theme
*/

namespace lib\setup;

class themeSupport
{
    public function __construct()
    {
        add_action('after_setup_theme', array( &$this, 'setup_theme' ) );
        add_action('widgets_init', array( &$this, 'widgets_init' ) );
        add_filter('get_search_form', array( &$this, 'custom_search') );        
    }

    public function setup_theme()
    {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'single', '900', '600', array('center','top') );
        add_image_size( 'list', '100', '90', array('left','top') );
        add_image_size( 'news', '366', '300', array('center','center') );
    
        add_theme_support( 'menus' );
        register_nav_menus( array(            
            'primary' => 'Menu Principal',
        ) );
    }

    public function widgets_init()
    {
        //register_sidebar( arra() );        
    }    

    public function custom_search( $form ){
        
            $search  = get_search_query();    
        
            $form = '<form class="navbar-form navbar-left" action="'. get_site_url( ) .'" method="get" id="searchform" >';
            $form .= '<div class="form-group">';
            $form .= '<div class="input-group">';
            $form .= '<input class="form-control" value="'. $search .'" name="s" id="s" placeholder="Pesquisar" type="text">';                            
            $form .= '<div class="input-group-btn">';
            $form .= '<button type="submit" class="btn btn-default" ><i class="fa fa-search"></i></button>';
            $form .= '</div>';
            $form .= '</div>';
            $form .= '</div>';
            $form .= '</form>';
        
            return $form;
        
    }
}