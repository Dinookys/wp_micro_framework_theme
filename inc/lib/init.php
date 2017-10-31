<?php
/**
*@package theme
*/
namespace lib;

use lib\setup\enqueue;
use lib\setup\adminPage;
use lib\setup\themeSupport;
use lib\setup\customPostTypes;
use lib\ajax\adminPage as AjaxAdmin;

/**
* Classe responsavel pela inicialização 
* dos recursos do tema
*/
class init
{
    protected static $loaded = false;

    function __construct()
    {
        if( self::$loaded ) : 
            return;
        endif;

        self::$loaded = true;

        $this->instance();
    }

    private function instance()
    {        

        // inicializando o ajax para admin page
        new AjaxAdmin();        

        new enqueue();
        new adminPage();
        new themeSupport();     
        new customPostTypes();        

    }
}