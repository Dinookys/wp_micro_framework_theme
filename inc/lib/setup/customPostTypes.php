<?php

namespace lib\setup;

use lib\core\postType;

use lib\callbacks\postTypesCallbacks as callbacks;

class customPostTypes extends postType
{
    public $callback;    

    public function __construct()
    {
        // Instanciando callbacks para ser usado dentro dos metaboxes
        $this->callback = new callbacks();

        $this->setMetaBoxes();       

        // Iniciando o registro e update do metaboxes
        parent::__construct();
    }   

    protected function setMetaBoxes()
    {
        // Criando Metabox
        $this->setMetabox( array(
            'id' => 'wpmf_example',
            'title' => 'Example MetaBox',
            'screen' => array( 'post' ),
            'context' => 'normal',
            'callback' => array( $this->callback, 'metabox_wpmf_example' )
        ) );

        // Criando update do metabox
        $this->setMetaUpdateRef( array(
            'field_name' => 'wpmf_example_field',
            'meta_key' => 'wpmf_example'
        ) );
        
    }

}