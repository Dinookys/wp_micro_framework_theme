<?php

namespace lib\core;

/**
 * Class responsavel por adicionar custom post types e metaboxes
 */
class postType
{

    protected $meta_boxes = array();
    protected $meta_boxes_update_ref = array();

    function __construct(){

        // Adicionando Meta boxes
        add_action( 'add_meta_boxes', array( &$this, 'addMetaBoxes' ) );

        // Adicionando update Meta boxes
        add_action( 'save_post', array( &$this, 'updateMetaBoxes' ) );
    }   
    
    /**
    *@todo Implementar
    */ 
    public function unregisterPostType(){}

    /**
     * Metodo para criar os metaboxes
     *
     * @return void
     */
    public function addMetaBoxes()
    {
        foreach ($this->getMetaBoxes() as $key => $metabox) :
            
            $default = array(
                'id' => sprintf('metabox-%s', $key),
                'title' => null, 
                'callback' => null, 
                'screen' => null, 
                'context' => 'normal', 
                'priority' => 'low', 
                'callback_args' => array()
            );

            $arg = array_merge($default, $metabox);
            add_meta_box( $arg['id'], $arg['title'], $arg['callback'], $arg['screen'], $arg['context'], $arg['priority'], $arg['callback_args'] );            

        endforeach;
    }

    /**
     * Metodo responsavel por salvar/atualizar os dados dos metaboxes
     * parametros esperados dentro do "objeto" field_name ( nome do campo que contem os dados ), 
     * is_input_array ( se true o metodo filter_input tratara o campo como um array ),
     * callback ( função de callback, para de tratamento dos dados antes de ser inserido no DB  )
     *
     * @param int $post_id
     * @return void
     */
    public function updateMetaBoxes( $post_id )
    {   
        
        foreach ($this->meta_boxes_update_ref as $key => $ref) :  
                        
            $ref['is_input_array'] = $ref['is_input_array'] ? $ref['is_input_array'] : false;

            $data = filter_input( INPUT_POST, $ref['field_name'], FILTER_DEFAULT,
                $ref['is_input_array'] ? FILTER_REQUIRE_ARRAY : '' );            

            if( isset( $ref['callback'] ) ){
                // Verifica se o callback passado é um metodo de uma objeto
                if( is_array( $ref['callback'] )                 
                    && method_exists( $ref['callback'][0], $ref['callback'][1] ) ){

                    // tenta invocar o metodo do objeto e passa o data como parametro
                    $data = call_user_func( $ref['callback'], $data );
                }else{                    
                    // tenta invocar a função e passa o data como parametro                    
                    $data = call_user_func( $ref['callback'], $data );
                }
            }

            update_post_meta( 
                $post_id, 
                $ref['meta_key'], 
                $data
            );

        endforeach;
    }

    protected function setMetabox( array $args )
    {
        $this->meta_boxes[] = $args;
    }

    protected function getMetaboxes()
    {
        return $this->meta_boxes;
    }

    protected function setMetaUpdateRef( $args ){
        $this->meta_boxes_update_ref[] = $args;
    }

    protected function register($names = 'Custom Post', $args = array()){

        $labels = array(
            'name'              => '%s',
            'singular_name'     => '%s',
            'menu_name'         => '%s',
            'all_items'         => 'Lista de %s',
            'name_admin_bar'    => '%s',
            'add_new_item'      => 'Adicionar novo %s',
            'edit_item'         => 'Editando %s',
            'add_new'           => 'Novo',
            'archives'          => '%s'
        );

        array_walk($labels, function(&$item, $key, $names){

            $uppercase = array('name', 'singular_name', 'menu_name', 'name_admin_bar');
            $singular = array('singular_name', 'add_new_item', 'edit_item' );

            if( is_array( $names ) && isset( $names['title'] ) ){

                $title = strtolower( $names['title'] );

                if( in_array( $key, $singular ) && isset( $names['singular'] ) ){
                    $title = strtolower( $names['singular'] );
                }

                if( in_array( $key, $uppercase )){
                    $title = ucfirst( $title );
                }             

                $item = sprintf($item, $title);
            }

        }, $names);

//        wp_die( var_dump( $labels ) );
        
        $default = array(
            'labels'            => $labels,
            'has_archive'       => false,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'capability_type'   => 'post', 
            'supports'          => array('title','editor','thumbnail'),
            'hierarchical'      => false,
            'menu_position'     => 15,
            'menu_icon'         => 'dashicons-wordpress-alt',            
            'rewrite'           => true,            
            'public' => true            
        );

        $args = array_merge($default, $args);

        register_post_type( is_array( $names ) 
            && isset( $names['post_type'] ) 
            ? $names['post_type'] 
            : $names,
            $args
        );
    }
}