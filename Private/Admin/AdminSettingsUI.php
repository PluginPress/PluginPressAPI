<?php

namespace IamProgrammerLK\PluginPressAPI\Admin;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

trait AdminSettingsUI
{

    public function render_tabs( $page_tabs ) : void
    {
        $active_tab = $this->get_active_tab( $page_tabs );
        $html = '<h2 class="nav-tab-wrapper">';
        foreach( $page_tabs as $tab )
        {
            // TODO: implement the before and after tab icons
            $html .= '<a href="?page=' . $tab[ 'tab_parent_page_slug' ] .
                '&tab=' . $tab[ 'tab_slug' ] .
                '" class="nav-tab ' . ( ( $tab[ 'tab_slug' ] == $active_tab[ 'tab_slug' ] ) ? 'nav-tab-active' : '' ) .
                '"><span><i class="' . $tab[ 'tab_before_icon' ] .
                '"></i></span>' . $tab[ 'tab_title' ] .
                '<span><i class="' . $tab[ 'tab_after_icon' ] .
                '"></i></span></a>';
        }
        $html .= '</h2>';
        echo $html;
        foreach( $page_tabs as $tab )
        {
            if( $tab[ 'tab_slug' ] == $active_tab[ 'tab_slug' ] && isset( $tab[ 'tab_description' ] ) )
            {
                echo '<p>' . $tab[ 'tab_description' ] . '</p>';
            }
        }
    }

    public function render_sections_and_fields( array $current_page, array $active_tab = [] )
    {
        global $wp_settings_sections, $wp_settings_fields;
        if( ! isset( $wp_settings_sections[ $current_page[ 'page_slug' ] ] ) )
        {
            return;
        }
        foreach( $this->sections as $available_section )
        {
            if( $active_tab[ 'tab_slug' ] == $available_section[ 'section_parent_tab_slug' ] )
            {
                foreach( ( array ) $wp_settings_sections[ $current_page[ 'page_slug' ] ] as $registered_section )
                {
                    if( $available_section[ 'section_slug' ] == $registered_section[ 'id' ] )
                    {
                        if( $registered_section[ 'title' ] )
                        {
                            echo "<h2>" . $registered_section[ 'title' ] . "</h2>\n";
                        }
                        if( $registered_section[ 'callback' ] )
                        {
                            call_user_func( $registered_section[ 'callback' ], $registered_section );
                        }
                        if(
                            ! isset( $wp_settings_fields ) ||
                            ! isset( $wp_settings_fields[ $current_page[ 'page_slug' ] ] ) || 
                            ! isset( $wp_settings_fields[ $current_page[ 'page_slug' ] ][ $registered_section[ 'id' ] ] )
                        )
                        {
                            continue;
                        }
                        echo '<table class="form-table" role="presentation">';
                        do_settings_fields( $current_page[ 'page_slug' ], $registered_section[ 'id' ] );
                        echo '</table>';
                    }
                }
            }
        }
    }

    public function render_sections( $args ) : void
    {
        if( isset( $this->sections ) && ! empty( $this->sections ) )
        {
            foreach( $this->sections as $section )
            {
                if( isset( $section[ 'section_description' ] ) && ( $args[ 'id' ] == $section[ 'section_slug' ] ) )
                {
                    echo '<p>' . $section[ 'section_description' ] . '</p>';
                }
            }
        }
    }

    public function render_fields( $args ) : void
    {
        // TODO: echo deferent elements baced on the callback setting (text input/text area/checkbox/radio button/ext)

        echo '<input type="text" name="' . $args[ 'option_slug' ] .
            '" id="' . $args[ 'option_slug' ] .
            '" value="' . get_option( $args[ 'option_slug' ] ) .
            '" placeholder="' . $args[ 'option_placeholder' ] .
            '" class="' . $args[ 'option_class' ] .
            // '" style="' . $args[ 'css_style' ] .
            '"/><p>' . $args[ 'option_description' ] . '</p>';
    }

}