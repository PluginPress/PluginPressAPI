<?php

namespace PluginPress\PluginPressAPI\Admin;

use PluginPress\PluginPressAPI\PluginOptions\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class AdminSettings
{

    use AdminSettingsUI;

    private $plugin_options;
    protected $tabs = [];
    protected $sections = [];
    protected $fields = [];
    
    public function __construct( PluginOptions $plugin_options )
    {
        $this->plugin_options = $plugin_options;
    }
    
    public function init()
    {
        if( ! empty( $this->tabs ) )
        {
            add_action( 'admin_init', array( $this, 'register_tabs' ), 11 );
        }
        if( ! empty( $this->sections ) )
        {
            add_action( 'admin_init', array( $this, 'register_sections' ), 12 );
        }
        if( ! empty( $this->fields ) )
        {
            add_action( 'admin_init', array( $this, 'register_fields' ), 13 );
        }
    }
    
    // TODO: Implements the function to add tabs/sections/fields at once.
    // TODO: implement the user input data sanitization function for input fields
    
    // NOTE: @array $tabs - Adds only tabs to register, this will not register fields or sections
    public function add_tabs( array $tabs ) : void
    {
        foreach( $tabs as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                $this->add_tab( $tabs );
                return;
            }
            else
            {
                $this->add_tab( $tabs[ $key ] );
            }
        }
    }

    // NOTE: @array $sections - Adds only sections to register, this will not register fields
    public function add_sections( array $sections ) : void
    {
        foreach( $sections as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                $this->add_section( $sections );
                return;
            }
            else
            {
                $this->add_section( $sections[ $key ] );
            }
        }
    }

    // NOTE: @array $fields - Adds only fields to register, this will not register sections
    public function add_fields( array $fields ) : void
    {
        foreach( $fields as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                $this->add_field( $fields );
                return;
            }
            else
            {
                $this->add_field( $fields[ $key ] );
            }
        }
    }

    public function register_tabs() : void
    {
        // HOOK: Filter before_register_tabs_{PLUGIN_SLUG}
        $this->tabs = apply_filters( 'before_register_tabs_' . $this->plugin_options->get( 'plugin_slug' ), $this->tabs );
    }

    public function register_sections() : void
    {
        foreach( $this->sections as $section )
        {
            add_settings_section(
                $section[ 'section_slug' ],
                $section[ 'section_title' ],
                $section[ 'section_ui' ],
                $section[ 'section_parent_page_slug' ]
            );
        }
    }

    public function register_fields() : void
    {
        foreach( $this->fields as $field )
        {
            register_setting(
                $field[ 'option_parent_tab_slug' ],
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
                $field[ 'option_parent_page_slug' ],
                $field[ 'option_parent_section_slug' ],
                $field
            );
        }
    }

    protected function get_current_page_tabs( array $current_page ) : array
    {
        $page_tabs = [];
        foreach( $this->tabs as $tab )
        {
            if( $tab[ 'tab_parent_page_slug' ] == $current_page[ 'page_slug' ] )
            {
                array_push( $page_tabs, $tab );
            }
        }
        return $page_tabs;
    }

    protected function get_active_tab( array $page_tabs ) : array | bool
    {
        $active_tab_slug = ( isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : null );
        $active_tab = [];
        if( empty( $page_tabs ) )
        {
            return false;
        }
        if( $active_tab_slug == null )
        {
            foreach( $page_tabs as $tab )
            {
                if( isset( $tab[ 'tab_default' ] ) && $tab[ 'tab_default' ] == true )
                {
                    $active_tab = $tab;
                }
            }
            if( empty( $active_tab ) )
            {
                // If no default tab is defined, first tab will set as a default tab
                $active_tab = $page_tabs [ 0 ];
            }
        }
        else
        {
            foreach( $page_tabs as $tab )
            {
                if( $tab[ 'tab_slug' ] == $active_tab_slug )
                {
                    $active_tab = $tab;
                }
            }
        }
        return $active_tab;
    }

    private function add_tab( $tab ) : void
    {
        $tab[ 'tab_parent_page_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $tab[ 'tab_parent_page_slug' ];
        $tab[ 'tab_slug' ] = $tab[ 'tab_parent_page_slug' ] . '_' . $tab[ 'tab_slug' ];
        $tab[ 'tab_title' ] = ( isset( $tab[ 'tab_title' ] ) ) ? $tab[ 'tab_title' ] : '';
        $tab[ 'tab_description' ] = ( isset( $tab[ 'tab_description' ] ) ) ? $tab[ 'tab_description' ] : '';
        $tab[ 'tab_before_icon_style' ] = ( isset( $tab[ 'tab_before_icon_style' ] ) ) ? $tab[ 'tab_before_icon_style' ] : '';
        $tab[ 'tab_before_icon' ] = ( isset( $tab[ 'tab_before_icon' ] ) ) ? $tab[ 'tab_before_icon' ] : 'dashicons dashicons-screenoptions';
        $tab[ 'tab_after_icon_style' ] = ( isset( $tab[ 'tab_after_icon_style' ] ) ) ? $tab[ 'tab_after_icon_style' ] : '';
        $tab[ 'tab_after_icon' ] = ( isset( $tab[ 'tab_after_icon' ] ) ) ? $tab[ 'tab_after_icon' ] : '';
        $tab[ 'tab_default' ] = ( isset( $tab[ 'tab_default' ] ) ) ? $tab[ 'tab_default' ] : false;
        array_push( $this->tabs, $tab );
    }

    private function add_section( $section ) : void
    {
        $section[ 'section_parent_page_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $section[ 'section_parent_page_slug' ];
        $section[ 'section_parent_tab_slug' ] = $section[ 'section_parent_page_slug' ] . '_' . $section[ 'section_parent_tab_slug' ];
        $section[ 'section_slug' ] = $section[ 'section_parent_page_slug' ] . '_' . $section[ 'section_slug' ];
        $section[ 'section_title' ] = ( isset( $section[ 'section_title' ] ) ) ? $section[ 'section_title' ] : '';
        $section[ 'section_description' ] = ( isset( $section[ 'section_description' ] ) ) ? $section[ 'section_description' ] : '';
        $section[ 'section_ui' ] = ( isset( $section[ 'section_ui' ] ) ) ? $section[ 'section_ui' ] : null;
        array_push( $this->sections, $section );
    }

    private function add_field( $field ) : void
    {
        $field[ 'option_parent_page_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $field[ 'option_parent_page_slug' ];
        $field[ 'option_slug' ] = $field[ 'option_parent_page_slug' ] . '_' . $field[ 'option_slug' ];
        if( ! isset( $field[ 'option_parent_tab_slug' ] ) || empty( $field[ 'option_parent_tab_slug' ] ) )
        {
            $field[ 'option_parent_tab_slug' ] = 'default';
        }
        else
        {
            $field[ 'option_parent_tab_slug' ] = $field[ 'option_parent_page_slug' ] . '_' . $field[ 'option_parent_tab_slug' ];
        }
        if( ! isset( $field[ 'option_parent_section_slug' ] ) || empty( $field[ 'option_parent_section_slug' ] ) )
        {
            $field[ 'option_parent_section_slug' ] = 'default';
        }
        else
        {
            $field[ 'option_parent_section_slug' ] = $field[ 'option_parent_page_slug' ] . '_' . $field[ 'option_parent_section_slug' ];
        }
        if( ! isset( $field[ 'option_class' ] ) || empty( $field[ 'option_class' ] ) )
        {
            $field[ 'option_class' ] = '';
        }
        else
        {
            $field[ 'option_class' ] = $field[ 'option_class' ];
        }
        if( ! isset( $field[ 'option_data_type' ] ) || empty( $field[ 'option_data_type' ] ) )
        {
            $field[ 'option_data_type' ] = 'string';
        }
        if( ! isset( $field[ 'option_description' ] ) || empty( $field[ 'option_description' ] ) )
        {
            $field[ 'option_description' ] = '';
        }
        if( ! isset( $field[ 'option_show_in_rest' ] ) || empty( $field[ 'option_show_in_rest' ] ) )
        {
            $field[ 'option_show_in_rest' ] = false;
        }
        if( ! isset( $field[ 'option_default_value' ] ) || empty( $field[ 'option_default_value' ] ) )
        {
            $field[ 'option_default_value' ] = false;
        }
        if( ! isset( $field[ 'option_style' ] ) || empty( $field[ 'option_style' ] ) )
        {
            $field[ 'option_style' ] = '';
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
        if( ! isset( $field[ 'option_disabled' ] ) || empty( $field[ 'option_disabled' ] ) )
        {
            $field[ 'option_disabled' ] = false;
        }
        if( ! isset( $field[ 'option_checked' ] ) || empty( $field[ 'option_checked' ] ) )
        {
            $field[ 'option_checked' ] = false;
        }
        if( ! isset( $field[ 'option_list' ] ) || empty( $field[ 'option_list' ] ) )
        {
            $field[ 'option_list' ] = '';
        }
        if( ! isset( $field[ 'option_hidden' ] ) || empty( $field[ 'option_hidden' ] ) )
        {
            $field[ 'option_hidden' ] = false;
        }
        if( ! isset( $field[ 'option_max' ] ) || empty( $field[ 'option_max' ] ) )
        {
            $field[ 'option_max' ] = '';
        }
        if( ! isset( $field[ 'option_max_length' ] ) || empty( $field[ 'option_max_length' ] ) )
        {
            $field[ 'option_max_length' ] = '';
        }
        if( ! isset( $field[ 'option_min' ] ) || empty( $field[ 'option_min' ] ) )
        {
            $field[ 'option_min' ] = '';
        }
        if( ! isset( $field[ 'option_min_length' ] ) || empty( $field[ 'option_min_length' ] ) )
        {
            $field[ 'option_min_length' ] = false;
        }
        if( ! isset( $field[ 'option_content_editable' ] ) || empty( $field[ 'option_content_editable' ] ) )
        {
            $field[ 'option_content_editable' ] = false;
        }
        if( ! isset( $field[ 'option_required' ] ) || empty( $field[ 'option_required' ] ) )
        {
            $field[ 'option_required' ] = false;
        }
        if( ! isset( $field[ 'option_readonly' ] ) || empty( $field[ 'option_readonly' ] ) )
        {
            $field[ 'option_readonly' ] = false;
        }
        if( ! isset( $field[ 'option_ui' ] ) || empty( $field[ 'option_ui' ] ) )
        {
            $field[ 'option_ui' ] = [ $this, 'render_fields' ];
        }
        if( ! isset( $field[ 'option_sanitize_callback' ] ) || empty( $field[ 'option_sanitize_callback' ] ) )
        {
            // TODO: implement the sanitized data function
            $field[ 'option_sanitize_callback' ] = NULL;
        }
        array_push( $this->fields, $field );
    }

}