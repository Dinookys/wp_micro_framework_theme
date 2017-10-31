<?php

namespace lib\setup;

use lib\core\page;

use lib\core\inputs;

use lib\helpers\themeHelper as helper;

use lib\callbacks\adminPage as callbacks;

/**
* Classe responsavel pela inicialização 
* dos scripts do tema
*/

class adminPage extends page{

    public function __construct()
    {   
        $this->build();

        $this->helper = new helper;

        $this->callbacks = new callbacks;

        add_action( 'admin_enqueue_scripts', array( &$this->helper, 'addMediaSingleImageUpload' ) );

        parent::__construct();
    }

    public function build(){                    

        $this->setPage(array(
            'page_title' => 'Theme Config.' ,
            'menu_title' => 'Config. Options',
            'capability' => 'manage_options',
            'menu_slug'  => 'tema-config' ,
            'callback' => null,
            'icon' => 'dashicons-admin-generic',
            'sub_page_from' => 'themes.php',
            'position' => 75
        ));

        $this->setSection( array(                        
            'id' => 'theme-options',
            'title' => '<span class="dashicons dashicons-admin-home" ></span> Section Example ( Home Page )',
            'page' => $this->getPage()->menu_slug            
        ));        

        $this->setSetting( array(
            'title' => 'Example image upload',
            'option_name' => 'theme_example_image_upload',
            'args' => array( 'type' => 'hidden' ),
            'callback' => array( &$this->callbacks, 'get_container_image' )
        ) );     
        
        $this->setSetting( array(
            'title' => 'Example text input',
            'option_name' => 'theme_example_text',
            'args' => array( 
                'class' => 'regular-text',
                'placeholder' => 'Example text input'
             ),
            'callback' => array( &$this, 'input_text' )
        ) ); 

        $this->setSetting( array(
            'title' => 'Example textarea',
            'option_name' => 'theme_example_text',
            'args' => array( 
                'class' => 'regular-text',
                'placeholder' => 'Example textarea'
             ),
            'callback' => array( &$this, 'input_textarea' )
        ) ); 
        

        $this->setSetting( array(
            'title' => 'Example select',
            'option_name' => 'theme_example_select',
            'args' => array( 
                'class' => 'regular-text',
                'placeholder' => 'Example select'
             ),
            'callback' => array( &$this, 'input_select' )
        ) ); 

        $this->setSection( array(                        
            'id' => 'theme-options-empty',
            'title' => '<span class="dashicons dashicons-file" ></span> Section Example ( Empty )',
            'page' => $this->getPage()->menu_slug            
        )); 

    }

    public function input_text( $args )
    {   
        if( get_option( $args['name'], null ) == false ){
            add_option( $args['name'], ( isset( $args['value_default'] ) ? $args['value_default'] : '' ) );
        }

        if( !isset( $args['placeholder'] ) && isset( $args['value_default'] ) ){
            $args['placeholder'] = $args['value_default'];
        }

        if( isset( $args['value_default'] ) ) {
            unset( $args['value_default'] );
        }

        $args['value'] = htmlentities( get_option( $args['name'], null ) );

        echo inputs::input($args);
        echo '<hr>';                
    }

    public function input_select( $args )
    {   
        if( get_option( $args['name'], null ) == false ){
            add_option( $args['name'], $args['value_default'] );
        }

        if( isset( $args['value_default'] ) ) {
            unset( $args['value_default'] );
        }

        $args['value'] = get_option( $args['name'], null );
        $args['options'] = $args['options'] ? $args['options'] : array(      
            array( 'value' => '1', 'text' => 'Yes' ),
            array( 'value' => '0', 'text' => 'No')             
        );
        
        echo inputs::select($args);                
    }

    public function input_textarea( $args ){

        if( get_option( $args['name'], null ) == false && isset( $args['value_default'] ) ){
            add_option( $args['name'], $args['value_default'] );
        }

        if( !isset( $args['placeholder'] ) && isset( $args['value_default'] ) ){
            $args['placeholder'] = $args['value_default'];
        }

        if( isset( $args['value_default'] ) ) {
            unset( $args['value_default'] );
        }

        $args['value'] = htmlentities( get_option( $args['name'], null ) );

        echo inputs::textarea($args);
        echo '<hr>';   
    }    

    public function sanitize_number( $data ){        
        
        if( is_numeric( $data ) ){
            return $data;
        }       

        return 0;
    }
    
}