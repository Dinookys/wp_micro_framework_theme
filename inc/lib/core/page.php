<?php
/**
*@package theme
*/

namespace lib\core;

/**
 * Class responsavel por criar páginas dentro do painel
 */
class page
{
    protected $settings = array();
    protected $fields = array();    
    protected $page = array();
    protected $sections = array();    
    protected $totalOptions = 0;
    protected $totalSections = 0;

    
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'addMenuPage' ));        
    }

    /**
     * Method para criar a pagina.
     *
     * @return void
     */
    public function addMenuPage( )
    {
        // Verifica se o callback é uma função e não for aplica o estrutura padrão da class
        if( ! is_callable( $this->page['callback'] ) ){
            $this->page['callback'] = array( &$this, 'default_template' );
        }

        if( $this->getPage()->sub_page_from ){

            add_submenu_page( 
                $this->getPage()->sub_page_from, 
                $this->getPage()->page_title, 
                $this->getPage()->menu_title, 
                $this->getPage()->capability, 
                $this->getPage()->menu_slug, 
                $this->getPage()->callback 
            );
        }else{  
  
            add_menu_page( 
                $this->getPage()->menu_title, 
                $this->getPage()->page_title, 
                $this->getPage()->capability, 
                $this->getPage()->menu_slug, 
                $this->getPage()->callback, 
                $this->getPage()->icon, 
                $this->getPage()->position
            );
            
        }

        $this->registerSections();

        $this->registerSettings();       
        
    }

    /**
     * Method para adicionar as configurações da pagina.
     *
     * @param array $page
     * @return $this
     */
    public function setPage( $page = array() )
    {
        $default = array(
            'page_title' => 'Theme Admin Config' ,
            'menu_title' => 'Theme Config.',
            'capability' => 'manage_options',
            'menu_slug'  => 'theme-admin-page' ,
            'callback' => null,
            'icon' => 'dashicons-admin-settings',
            'sub_page_from' => false,
            'position' => 50
        );        

        $this->page = is_array( $page ) ? array_merge( $default, $page ) : $default;        

        return $this;
    }

    /**
     * Método para separar sessões
     *
     * @param array $section
     * @return void
     */
    public function setSection( $section = array() )
    {
        $default = array(                        
            'id' => 'field_section_' . $this->getTotalSections(),
            'title' => 'Section #' . $this->getTotalSections(),
            'page' => !empty( $this->getPage() ) ? $this->getPage()->menu_slug : '',
            'callback' => null,
        );

        $section = is_array( $section ) ? array_merge( $default, $section ) : $default;
        $this->sections[] = $section;

        $this->incrementTotalSections();

        return $this;        
    }

    /**
     * Método que registra uma opção no banco de dados e 
     * cria campo no formulario da página
     *
     * @param array $settings
     * @return $this
     */
    public function setSetting( $settings = array() )
    {    

        $default = array(
            //Data Base Options
            'option_group' => 'theme-settings-group',
            'option_name' => 'theme-option-' . $this->getTotalOptions(),
            'sanitize_callback' => null,
            //Field Options
            'id' => 'field_option_' . $this->getTotalOptions(),
            'title' => 'Option #' . $this->getTotalOptions(),
            'page' => !empty( $this->getPage() ) ? $this->getPage()->menu_slug : '',
            'section' => $this->getTotalSections() ? $this->getSection(0)->id : '',
            'callback' => null
        );

        $settings = is_array( $settings ) ? array_merge( $default, $settings ) : $default;
        $this->settings[] = $settings;

        $this->incrementTotalOptions();

        return $this;
    }    

    private function incrementTotalSections()
    {
        $this->totalSections += 1;
    }

    public function getTotalSections()
    {
        return $this->totalSections;
    }

    private function incrementTotalOptions()
    {
        $this->totalOptions += 1;
    }

    /**
     * Retonar o total de campos registrados
     *
     * @return void
     */
    public function getTotalOptions()
    {
        return $this->totalOptions;
    }
    
    /**
     * Retorna um objeto com os dados da página
     *
     * @return object
     */
    public function getPage()
    {
        $page = $this->page;
        return is_array( $page ) ? ( object ) $page : array();
        
    }

    /**
     * Retorna um objeto com informações da sessão pedida
     * através de seu index dentro do array de $this->sections
     * se nenhum index for informado retorna com o primeiro
     *
     * @param int $index
     * @return object
     */
    public function getSection( $index )
    {
        $section = ( object ) $this->sections[ is_numeric( $index ) ? $index : 0 ];
        return  $section;
    }

    /**
     * Retorna um objeto com informações da opções pedida
     * através de seu index dentro do array de $this->settings
     * se nenhum index for informado retorna com o primeiro
     *
     * @param int $index
     * @return object
     */
    public function getSetting( $index )
    {
        $setting = ( object ) $this->settings[ is_numeric( $index ) ? $index : 0 ];
        return  $setting;
    }

    /**
     * Retorna todos os itens dentro de $this->sections
     *     
     * @return object
     */
    public function getSections( )
    {       
        $sections = ( object ) $this->sections;
        return $sections;
    }

    /**
     * Retorna todos os itens dentro de $this->settings
     *     
     * @return object
     */
    public function getSettings( )
    {       
        $settings = ( object ) $this->settings;
        return $settings;
    }

    /**
     * Registra todas as sessões criadas
     *
     * @return void
     */
    private function registerSections()
    {        

        if( empty( $this->getSections() ) ){
            return;
            
        }

        foreach ( $this->getSections() as $key => $section ){

            $section = ( object ) $section;

            add_settings_section( 
                $section->id, 
                $section->title, 
                $section->callback, 
                $section->page 
            );
        }
    }

    /**
     * Registra todas a opções criadas
     *
     * @return void
     */
    private function registerSettings()
    {
        if( empty( $this->getSettings() ) ){
            return;
        }

        foreach ( $this->getSettings() as $key => $settings ){
            $settings = ( object ) $settings;

            $args = array(
                'name' => $settings->option_name                
            );

            if( isset( $settings->args ) ){
                $args = array_merge( $args, $settings->args );
            }

            register_setting( $settings->option_group, $settings->option_name, $settings->sanitize_callback );
            add_settings_field( $settings->id, $settings->title, $settings->callback, $settings->page, $settings->section, $args );
        }
    }

    /**
     * Cria um layout basico para página
     *
     * @return void
     */
    public function default_template()
    {
        global $wp_settings_sections, $wp_settings_fields;

        $sections = $wp_settings_sections[ $this->getPage()->menu_slug ];
        $sections_keys = array_keys( $sections );
        $current_item = array_shift( $sections_keys );        

    ?>
        <h1 class="aligncenter" ><?php echo $this->getPage()->page_title; ?></h1>
        <hr>

        <?php settings_errors() ?>
        <form action="options.php" method="post" >   
            <?php settings_fields( $this->getSetting(0)->option_group ); ?>            
            
            <ul class="tab-link-list" >                
                <?php foreach( $sections as $key => $section ){
                    
                    $this->create_section_link( $section, $current_item );
                } ?>
            </ul>

            <div class="tab-list" >
                <?php foreach( $sections as $key => $section ){
                    $this->create_section( $section, $current_item );
                } ?>
            </div>

            <?php submit_button(  ) ?>
        </form>

    <?php
    }

    protected function create_section_link( $section = array(), $current_item = null )
    {
        echo "\t<li><a data-tab=\"#{$section['id']}\" href=\"#!{$section['id']}\" ". ( $current_item == $section['id'] ? ' class="active" ' : '' ) ." >{$section['title']}</a></li>";
    }

    protected function create_section( $section = array(), $current_item = null )
    {
        echo "\t<div id=\"{$section['id']}\" ". ( $current_item == $section['id'] ? ' class="tab-item active" ' : ' class="tab-item"' ) ." >";

        if( is_callable( $section['callback'] ) ){
            echo call_user_func( $section['callback'], $section );
        }

        $this->create_fields( $section['id'] );

        echo "\t</div>";        
    }

    protected function create_fields( $section = null ){

        global $wp_settings_fields;

        if( is_null( $section ) || empty( $section )){
            return;
        }

        $fields = ( array ) $wp_settings_fields[ $this->getPage()->menu_slug ][ $section ];

        foreach( $fields as $field ){
            echo "\t\t<div>";
                if( !empty( $field['title'] ) ){
                    echo "\t\t\t<h3>{$field['title']}</h3>";
                }
                call_user_func( $field['callback'], $field['args'] );

            echo "\t\t</div>";
        }        
    }

}