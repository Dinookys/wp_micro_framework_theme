<?php
/**
*@package theme
*/

namespace lib\helpers;

class themeHelper
{
   /**
    * getImageBox - Cria 2 botões com ações de adicionar 
    * ou remover uma imagem.
    *
    * @param string $field_img_name - campo no qual será armazenado o id da imagem
    * @param string $container_id_preview - id/class do container que ira mostrar a imagem, 
    * se nenhum nome for indicado usa o padrão '.container_preview'
    * @return string
    */
   public static function getImageBox( $field_img_name, $container_id_preview = '.container_preview' ){

        $out = '<button title="'. __( 'Add' ) .'" data-field="'. $field_img_name .'" data-container-preview="'. $container_id_preview .'" data-add="image" class="button button-primary"><i style="margin-top: 3px;" class="dashicons dashicons-format-image" ></i> '. __( 'Add' ) .'</button>' . "\n";
        $out .= '&nbsp;';
        $out .= '<button title="'. __( 'Remove' ) .'" data-field="'. $field_img_name .'" data-remove="image" data-container-preview="'. $container_id_preview .'" class="button button-secondary"><i style="margin-top: 3px;" class="dashicons dashicons-trash" ></i> '. __( 'Remove' ) .'</button>' . "\n";
        return $out;
   }

   /**
    * btnAddNewItem - Retorna um botão de 
    * ação para incluir um item ao container alvo, usando uma chamada ajax
    * 
    * @param string $ajax_action - Nome da chamada em ajax
    * @param string $container_target - id/class do item que receberá o response do ajax    
    * @param string $button_label - Nome do botão
    * @return string
    */
   public static function btnAddNewItem( $ajax_action, $container_target, $button_label = 'Novo item' ){

     return '<button class="button button-primary" data-ajax="'. $ajax_action .'" data-container-target="'. $container_target .'">'. $button_label .'</button>' . "\n";

   }

   public static function addMediaSingleImageUpload(){
        wp_enqueue_media();
        wp_enqueue_script( 'upload-image', THEME_DIR_URI . '/js/media-single-upload.js' );
   }

   public static function _breadcrumbs($home_name= 'Inicio'){
    
        $query_obj = get_queried_object();	

        $output = '<div class="wrap-breadcrumb">';

        $output .= '<div class="container">';
            $output .='<ul class="breadcrumb">';
            $output .= '<li><a href="'. home_url() . '">'. $home_name .'</a></li>';
        
                if( ( is_single( ) || is_page( ) ) && !is_post_type_hierarchical( get_post_type( get_the_ID() ) )){
                    $output .= '<li>'. $query_obj->post_title .'</li>';
                }

                if(( is_single( ) || is_page( ) ) && is_post_type_hierarchical( get_post_type( get_the_ID() ) )){
                    $ancestors = get_post_ancestors( get_the_ID() );
                                
                    if( $ancestors ){
                        foreach( $ancestors as $ancestor ){
                            $output .= '<li><a href="'. get_permalink( $ancestor ) .'" >'. ucfirst( get_the_title( $ancestor ) ) .'</a></li>';
                        }
                    }

                    $output .= '<li>'. $query_obj->post_title .'</li>';
                }
            
                if(is_tax()){			
                    
                    $taxs = self::_taxonomy_recursive($query_obj->term_id, $query_obj->taxonomy);
            
                    $total_tax = count($taxs)-1;		
            
                    foreach($taxs as $key => $tax){
                        if($total_tax == $key){
                            $output .= '<li >'. $tax['name'] .'</li>';
                        }else{
                            $output .= '<li ><a href="'. $tax['link'] .'">'. $tax['name'] .'</a></li>';
                        }
                    }
            
                }

                if( is_archive() ){
                    $output .= '<li>'. $query_obj->label .'</li>';
                }
    
                $output .= '</ul>';
            $output .= '</div>';
        $output .= '</div>';
    
        echo $output;    
    }

    public static function _taxonomy_recursive($id, $taxonomy, $array_return = array()){
        if(is_null($array_return)){
            $array_return = [];
        }
    
        $term = get_term_by( 'id', $id, $taxonomy);	
        $term_link = get_term_link( $term, $term->taxonomy );
    
        if(!is_null($term)){
            $array_return[] = ['id' => $term->term_id, 'name' => $term->name, 'slug' => $term->slug, 'link' => $term_link];
    
            if($term->parent != 0){
                $return = _taxonomy_recursive($term->parent, $taxonomy, $array_return);
            }else{
                $return = array_reverse($array_return); //Reordenando os itens
            }
        }
    
        return $return;
    }

    public static function pagination()
    {
        $links = paginate_links( array(            
            'type' => 'array',
            'mid_size' => 5
        ) );

        if( empty( $links ) ){
            return false;
        }

        $out = '<ul class="pagination pagination-sm" style="display: table; margin: 15px auto 15px 0;" >';
        
            foreach( $links as $link ){
                if( strpos($link, 'current') ){
                    $out .= '<li class="active" >'. $link .'</li>';
                }else{
                    $out .= '<li>'. $link .'</li>';
                }

            }

        $out .='</ul>';

        return $out;
    }
}