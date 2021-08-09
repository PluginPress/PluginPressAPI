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
        $current_page = $this->get_current_page();
        $active_tab = $this->get_active_tab( $page_tabs );
        $tabs = '';
        $tab_description = '';
        // HOOK: Filter - before_tabs_render_{PAGE_SLUG}
        $page_tabs = apply_filters( 'before_tabs_render_' . $current_page[ 'page_slug' ] , $page_tabs );
        foreach( $page_tabs as $tab )
        {
            // HOOK: Filter - before_tab_render_{TAB_SLUG}
            $tab = apply_filters( 'before_tab_render_' . $tab[ 'tab_slug' ] , $tab );
            $tabs .= '<a href="?page=' . $tab[ 'tab_parent_page_slug' ] . '&tab=' . $tab[ 'tab_slug' ] .
                '" style="display:inline-block;vertical-align:middle;" class="nav-tab ' .
                ( ( $tab[ 'tab_slug' ] == $active_tab[ 'tab_slug' ] ) ? 'nav-tab-active' : '' ) .
                '"><span style="display:inline-block;vertical-align:middle;margin-right:5px;' .
                $tab[ 'tab_before_icon_style' ] . '"><i class="wp-menu-image ' .
                $tab[ 'tab_before_icon' ] . '"></i></span>' .
                $tab[ 'tab_title' ] .
                '<span style="display:inline-block;vertical-align:middle;margin-left:5px;' . 
                $tab[ 'tab_after_icon_style' ] . '"><i class="' .
                $tab[ 'tab_after_icon' ] . '"></i></span></a>';
            if( $tab[ 'tab_slug' ] == $active_tab[ 'tab_slug' ] )
            {
                $tab_description = '<p>' . $tab[ 'tab_description' ] . '</p>';
            }
        }
        echo '<h2 class="nav-tab-wrapper">';
        echo $tabs;
        echo '</h2>';
        echo $tab_description;
    }

    public function render_sections_and_fields( array $current_page, array $active_tab = [] )
    {
        global $wp_settings_sections, $wp_settings_fields;
        if( ! isset( $wp_settings_sections[ $current_page[ 'page_slug' ] ] ) )
        {
            return;
        }
        foreach( $this->sections as $section )
        {
            if( $active_tab[ 'tab_slug' ] == $section[ 'section_parent_tab_slug' ] )
            {
                foreach( ( array ) $wp_settings_sections[ $current_page[ 'page_slug' ] ] as $registered_section )
                {
                    if( $section[ 'section_slug' ] == $registered_section[ 'id' ] )
                    {
                    // HOOK: Filter before_section_render_{SECTION_SLUG}
                        $section = apply_filters( 'before_section_render_' . $section[ 'section_slug' ] , $section );
                        echo '<div style="display:inline-block;vertical-align:middle;"><h2>';
                        if( isset(  $section[ 'section_before_icon' ] ) )
                        {
                            echo '<span style="vertical-align:middle;margin-right:5px;' .
                            ( isset( $section[ 'section_before_icon_style' ] ) ? $section[ 'section_before_icon_style' ] : '' ) .
                            '"><i class="' . $section[ 'section_before_icon' ] . '"></i></span>';
                        }
                        if( isset(  $section[ 'section_title' ] ) )
                        {
                            echo $section[ 'section_title' ];
                        }
                        if( isset(  $section[ 'section_after_icon' ] ) )
                        {
                            echo '<span style="vertical-align:middle;margin-left:5px;' .
                            ( isset( $section[ 'section_after_icon_style' ] ) ? $section[ 'section_after_icon_style' ] : '' ) .
                            '"><i class="' . $section[ 'section_after_icon' ] . '"></i></span>';
                        }
                        echo '</h2>';
                        if( isset( $section[ 'section_ui' ] ) && is_array( $section[ 'section_ui' ] ) )
                        {
                            call_user_func( $section[ 'section_ui' ], $section );
                        }
                        else
                        {
                            if( isset( $section[ 'section_description' ] ) )
                            {
                                echo '<p>' . $section[ 'section_description' ] . '</p>';
                            }
                        }
                        echo '</div>';
                        if(
                            ! isset( $wp_settings_fields ) ||
                            ! isset( $wp_settings_fields[ $current_page[ 'page_slug' ] ] ) || 
                            ! isset( $wp_settings_fields[ $current_page[ 'page_slug' ] ][ $section[ 'section_slug' ] ] )
                        )
                        {
                            continue;
                        }
                        echo '<table class="form-table" role="presentation">';
                        $this->do_settings_fields( $current_page[ 'page_slug' ], $section[ 'section_slug' ] );
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
                if( isset( $section[ 'section_description' ] ) && ( $args[ 'section_slug' ] == $section[ 'section_slug' ] ) )
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

    private function do_settings_fields( $page, $section )
    {
        global $wp_settings_fields;
        if( ! isset( $wp_settings_fields[ $page ][ $section ] ) )
        {
            return;
        }
    
        foreach( (array) $wp_settings_fields[ $page ][ $section ] as $field )
        {
            $class = '';
            if( ! empty( $field[ 'args' ][ 'class' ] ) )
            {
                $class = ' class="' . esc_attr( $field[ 'args' ][ 'class' ] ) . '" valign="top"';
            }
            echo "<tr{$class}>";
            echo '<th scope="row"><div style="display:inline-block;vertical-align:top;"> <div>
            lorem 
            <div class="word">
              ipsum
              <div class="definition">this will be the tooltip text</div>
            </div> 
            blah blah blah
          </div>';
            echo '<label for="' . esc_attr( $field[ 'args' ][ 'label_for' ] ) . '" style="vertical-align:baseline;">' . $field[ 'title' ] . '</label>';


            echo '<div class="tooltip"><span style="vertical-align:baseline;margin-left:5px;' .
                ( isset( $field[ 'args' ][ 'section_before_icon_style' ] ) ? $field[ 'args' ][ 'section_before_icon_style' ] : '' ) .'"><i class="' .
                $field[ 'args' ][ 'option_help_icon' ] . '" aria-hidden="true"></i></span>' .
            '<span class="tooltip_text">Tooltip</span>
            
          </div>';
            
            
            
            // '"><i class="tooltip-text ' . $field[ 'args' ][ 'option_help_icon' ] . '" aria-hidden="true"></i></span>';

            echo '</div></th>';
            echo '<td>';
            call_user_func( $field['callback'], $field['args'] );
            echo '</td>';
            echo '</tr>';
        }
    }
    
    // echo '<span class="woocommerce-help-tip"></span>';
}