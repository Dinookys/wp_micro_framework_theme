<?php

namespace lib\core;

use Walker_Nav_Menu;

class Walker_Menu_Bootstrap extends Walker_Nav_menu {

    protected $indent;
    protected $item_class;
    protected $count_item = 0;
    protected $current_parent = 0;


    public function start_lvl(&$output, $depth = 0, $args = array()) { //ul                       
        $this->indent = str_repeat("\t", $depth);
        
        $sub_menu = ($depth > 0) ? 'sub-menu' : '';
        $output .= "\n$this->indent<ul class=\"dropdown-menu depth_$depth $sub_menu \" >";
        
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {        
        $output .= "\n$this->indent</ul>";
    }    

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {  // li a span
        $this->indent = str_repeat("\t", $depth);
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        
        ($args->walker->has_children) ? ( $classes[] = 'dropdown' ) : '';
        ($item->current || $item->current_item_anchestory) ? ( $classes[] = 'active' ) : '';
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args));
        $class_names = ' class="'. esc_attr( $class_names ) .'"';
        
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
        $id = strlen( $id ) ?  ' id="'. esc_attr( $id ) .'"' : ''; 
        
        // if($item->menu_item_parent != 0 && $this->current_parent != $item->menu_item_parent || $this->count_item == 4){
        //     $this->current_parent = $item->menu_item_parent;
        //     $this->count_item = 0;
        //     $output .= "\n<li class=\"col-sm-3\" ><ul class=\"nav\" >";
        // }
        
        $output .= "\n$this->indent<li $id $class_names >";

        //var_dump($item);
        
        //Link
        $link_attr  = ! empty($item->attr_title) ? ' title="'. esc_attr( $item->attr_title ) .'"' : '';
        $link_attr .= ! empty($item->target) ? ' target="'. esc_attr( $item->target ) .'"' : '';
        $link_attr .= ! empty($item->xfn) ? ' rel="'. esc_attr( $item->xfn ) .'"' : '';
        $link_attr .= ! empty($item->url) ? ' href="'. esc_attr( $item->url ) .'"' : '';        
        $link_attr .= ! empty($args->walker->has_children) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a'. $link_attr .'>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title ? $item->title : $item->post_title, $item->ID ) . $args->link_after;
        $item_output .= ( $depth == 0 && $args->walker->has_children ) ? '<span class="caret" ></span></a>' : '</a>';
        $item_output .= $args->after;
        
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        
        $this->count_item++;
        
    }

    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        
        $output .= '</li>';
        
        // if($this->count_item == 4 || $this->current_parent != $item->menu_item_parent){
        //     $this->current_parent = $item->menu_item_parent;
        //     $output .= "\n</ul></li>";
        // }
        
    }

}