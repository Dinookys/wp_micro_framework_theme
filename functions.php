<?php
// Defines
define('THEME_DIR_URI', get_template_directory_uri() );
define('THEME_DIR_PATH', get_template_directory() );

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) :
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
endif;

if( class_exists( 'lib\\init' ) ):    
    new \lib\init();
endif;