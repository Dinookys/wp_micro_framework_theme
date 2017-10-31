<?php

namespace lib\core;

/**
 * Class para criar inputs de formularios
 */
 class inputs
 {
     public static function input( array $args = array() )
     {
        $input =  "\n\t" . '<input ';
        foreach( $args as $key => $arg ){
            $input .= ' ' . $key . '="'. $arg .'" ';
        }

        if( ! isset( $args['type'] ) ){
            $input .= ' type="text" ';
        }

        $input .= ' />';

        return $input;
     }

     public static function textarea( array $args = array() )
     {

        $value = $args['value'];
        unset( $args['value'] );

        $input = "\n\t" . '<textarea ';
        foreach( $args as $key => $arg ){
            $input .= ' ' . $key . '="'. $arg .'" ';
        }

        $input .= ' >'. $value .'</textarea>';

        return $input;
     }

     public static function select( array $args = array() )
     {

        $options = "\n\t\t" . '<option>Option</option>';
        if( $args['options'] ){
            $options = '';
            foreach( $args['options'] as $key => $option  ){
                if( $args['value'] == $option['value'] ){
                    $options .= "\n\t\t" . '<option value="'. $option['value'] .'" selected="selected" >'. $option['text'] .'</option>';
                }else{
                    $options .= "\n\t\t" .'<option value="'. $option['value'] .'" >'. $option['text'] .'</option>';
                }
            }
        }
        
        unset($args['value']);
        unset($args['options']);

        $select = '<select ';
        foreach( $args as $key => $attr ){
            $select .= $key . '="'  . $attr . '" ';            
        }        
        $select .= '>';
            $select .= $options;
        $select .= '</select>';

        return $select;

     }

     public static function button( array $args )
     {
        $text = isset( $args['text'] ) ? $args['text'] : 'Button Text' ;
        unset( $args['text'] );

        $button =  "\n\t\t" . '<button ';
        foreach( $args as $key => $arg ){
            $button .= ' ' . $key . '="'. $arg .'" ';
        }

        if( ! isset( $args['type'] ) ){
            $button .= ' type="button" ';
        }

        $button .= ' >'. $text .'</button>';

        return $button;
     }
 }
