<?php

namespace IamProgrammerLK\PluginPressAPI\Admin;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class AdminSettings
{

    // TODO:: implement the user input data sanitization function for input fields

    use AdminSettingsUI;

    private $pluginOptions;
    protected $tabs = [];
    protected $sections = [];
    protected $fields = [];

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function init()
    {
        if( ! empty( $this->tabs ) )
        {
            add_action( 'admin_init', array( $this, 'registerTabs' ), 11 );
        }
        if( ! empty( $this->sections ) )
        {
            add_action( 'admin_init', array( $this, 'registerSections' ), 12 );
        }
        if( ! empty( $this->fields ) )
        {
            add_action( 'admin_init', array( $this, 'registerFields' ), 13 );
        }
    }




    // @array $settings - Adds sections and options to register, this will register section and files at once
    // public function addSettings( array $settings ) : void
    // {
    //     $this->addSections( $settings );
    //     foreach( $settings as $sectionKey => $sectionValue )
    //     {
    //         if( isset( $sectionValue[ 'options' ] ) && ! empty( $sectionValue[ 'options' ] ) )
    //         {
    //             if( ! is_array( $sectionValue[ 'options' ] ) )
    //             {
    //                 return;
    //             }
    //             foreach( $sectionValue[ 'options' ] as $key => $value )
    //             {
    //                 $sectionValue[ 'options' ][ $key ][ 'parent_page_slug' ] = $sectionValue[ 'parent_page_slug' ];
    //                 $sectionValue[ 'options' ][ $key ][ 'parent_tab_slug' ] = $sectionValue[ 'parent_tab_slug' ];
    //                 $sectionValue[ 'options' ][ $key ][ 'parent_section_slug' ] = $sectionValue[ 'section_slug' ] ;
    //             }
    //             $this->addFields( $sectionValue[ 'options' ] );
    //         }
    //     }
    // }


    // @array $tabs - Adds only tabs to register, this will not register fields or sections
    public function addTabs( array $tabs ) : void
    {
        foreach( $tabs as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                $this->addTab( $tabs );
                return;
            }
            else
            {
                $this->addTab( $tabs[ $key ] );
            }
        }
    }

    // @array $sections - Adds only sections to register, this will not register fields
    public function addSections( array $sections ) : void
    {
        foreach( $sections as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                $this->addSection( $sections );
                return;
            }
            else
            {
                $this->addSection( $sections[ $key ] );
            }
        }
    }

    // @array $fields - Adds only fields to register, this will not register sections
    public function addFields( array $fields ) : void
    {
        foreach( $fields as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                $this->addField( $fields );
                return;
            }
            else
            {
                $this->addField( $fields[ $key ] );
            }
        }
    }

    public function registerTabs() : void
    {
        $this->tabs = apply_filters( $this->pluginOptions->get( 'slug' ) . '_add_tabs', $this->tabs );
    }

    public function registerSections() : void
    {
        foreach( $this->sections as $section )
        {
            add_settings_section(
                $section[ 'section_slug' ],
                $section[ 'section_title' ],
                $section[ 'section_ui' ],
                $section[ 'parent_page_slug' ]
            );
        }
    }

    public function registerFields() : void
    {
        foreach( $this->fields as $field )
        {
            register_setting(
                $field[ 'parent_tab_slug' ],
                $field[ 'option_slug' ],
                [
                    'type' => $field[ 'option_data_type' ],
                    'description' => $field[ 'option_description' ],
                    'sanitize_callback' => $field[ 'option_sanitize_callback' ],
                    'show_in_rest' => $field[ 'option_show_in_rest' ],
                    'default' => $field[ 'option_default_value' ],
                ]
            );
            add_settings_field(
                $field[ 'option_slug' ],
                $field[ 'option_title' ],
                $field[ 'option_ui' ],
                $field[ 'parent_page_slug' ],
                $field[ 'parent_section_slug' ],
                $field
            );
        }
    }

    protected function getTabs( array $currentPage ) : array
    {
        $pageTabs = [];
        foreach( $this->tabs as $tab )
        {
            if( $tab[ 'parent_page_slug' ] == $currentPage[ 'menu_slug' ] )
            {
                array_push( $pageTabs, $tab );
            }
        }
        return $pageTabs;
    }

    protected function getActiveTab( array $pageTabs ) : array | bool
    {
        $activeTabSlug = ( isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : null );
        $activeTab;
        if( $activeTabSlug == null )
        {
            foreach( $pageTabs as $tab )
            {
                if( $tab[ 'tab_default' ] == true )
                {
                    $activeTab = $tab;
                }
            }
        }
        else
        {
            foreach( $pageTabs as $tab )
            {
                if( $tab[ 'tab_slug' ] == $activeTabSlug )
                {
                    $activeTab = $tab;
                }
                else
                {
                    $activeTab = [];
                }
            }
        }
        if( empty( $activeTab ) && ! empty( $pageTabs ) )
        {
            // If no default tab is defined, first tab will set as a default tab
            $activeTab = $pageTabs[ 0 ];
        }
        if( empty( $pageTabs ) )
        {
            $activeTab = false;
        }
        return $activeTab;
    }

    private function addTab( $tab ) : void
    {
        $tab[ 'parent_page_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $tab[ 'parent_page_slug' ];
        $tab[ 'tab_slug' ] = $tab[ 'parent_page_slug' ] . '_' . $tab[ 'tab_slug' ];
        $tab[ 'tab_title' ] = ( isset( $tab[ 'tab_title' ] ) ) ? $tab[ 'tab_title' ] : '';
        $tab[ 'tab_description' ] = ( isset( $tab[ 'tab_description' ] ) ) ? $tab[ 'tab_description' ] : '';
        $tab[ 'tab_before_icon' ] = ( isset( $tab[ 'tab_before_icon' ] ) ) ? $tab[ 'tab_before_icon' ] : '';
        $tab[ 'tab_after_icon' ] = ( isset( $tab[ 'tab_after_icon' ] ) ) ? $tab[ 'tab_after_icon' ] : '';
        $tab[ 'tab_default' ] = ( isset( $tab[ 'tab_default' ] ) ) ? $tab[ 'tab_default' ] : false;
        array_push( $this->tabs, $tab );
    }

    private function addSection( $section ) : void
    {
        $section[ 'parent_page_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $section[ 'parent_page_slug' ];
        $section[ 'parent_tab_slug' ] = $section[ 'parent_page_slug' ] . '_' . $section[ 'parent_tab_slug' ];
        $section[ 'section_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $section[ 'section_slug' ];
        $section[ 'section_title' ] = ( isset( $section[ 'section_title' ] ) ) ? $section[ 'section_title' ] : '';
        $section[ 'section_description' ] = ( isset( $section[ 'section_description' ] ) ) ? $section[ 'section_description' ] : '';
        $section[ 'section_ui' ] = ( isset( $section[ 'section_ui' ] ) ) ? $section[ 'section_ui' ] : [ $this, 'renderSections' ];
        array_push( $this->sections, $section );
    }

    private function addField( $field ) : void
    {
        $field[ 'parent_page_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $field[ 'parent_page_slug' ];
        if( ! isset( $field[ 'parent_tab_slug' ] ) || empty( $field[ 'parent_tab_slug' ] ) )
        {
            $field[ 'parent_tab_slug' ] = 'default';
        }
        else
        {
            $field[ 'parent_tab_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $field[ 'parent_tab_slug' ];
        }
        if( ! isset( $field[ 'parent_section_slug' ] ) || empty( $field[ 'parent_section_slug' ] ) )
        {
            $field[ 'parent_section_slug' ] = 'default';
        }
        else
        {
            $field[ 'parent_section_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $field[ 'parent_section_slug' ];
        }
        $field[ 'option_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $field[ 'option_slug' ];
        if( ! isset( $field[ 'option_data_type' ] ) || empty( $field[ 'option_data_type' ] ) )
        {
            $field[ 'option_data_type' ] = 'string';
        }
        if( ! isset( $field[ 'option_description' ] ) || empty( $field[ 'option_description' ] ) )
        {
            $field[ 'option_description' ] = '';
        }
        if( ! isset( $field[ 'option_sanitize_callback' ] ) || empty( $field[ 'option_sanitize_callback' ] ) )
        {
            // TODO:: implement the sanitized data function
            $field[ 'option_sanitize_callback' ] = NULL;
        }
        if( ! isset( $field[ 'option_show_in_rest' ] ) || empty( $field[ 'option_show_in_rest' ] ) )
        {
            $field[ 'option_show_in_rest' ] = false;
        }
        if( ! isset( $field[ 'option_default_value' ] ) || empty( $field[ 'option_default_value' ] ) )
        {
            $field[ 'option_default_value' ] = false;
        }
        if( ! isset( $field[ 'option_ui' ] ) || empty( $field[ 'option_ui' ] ) )
        {
            $field[ 'option_ui' ] = [ $this, 'renderFields' ];
        }
        if( ! isset( $field[ 'option_class' ] ) || empty( $field[ 'option_class' ] ) )
        {
            $field[ 'class' ] = '';
            $field[ 'option_class' ] = '';
        }
        else
        {
            $field[ 'class' ] = $field[ 'option_class' ];
            $field[ 'option_class' ] = $field[ 'option_class' ];
        }
        if( ! isset( $field[ 'option_type' ] ) || empty( $field[ 'option_type' ] ) )
        {
            $field[ 'option_type' ] = 'text';
        }
        if( ! isset( $field[ 'option_placeholder' ] ) || empty( $field[ 'option_placeholder' ] ) )
        {
            $field[ 'option_placeholder' ] = 'Enter option here';
        }
        if( ! isset( $field[ 'label_for' ] ) || empty( $field[ 'label_for' ] ) )
        {
            $field[ 'label_for' ] = $field[ 'option_slug' ];
        }
        array_push( $this->fields, $field );
    }

}