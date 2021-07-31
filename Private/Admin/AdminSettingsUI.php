<?php

namespace IamProgrammerLK\PluginPressAPI\Admin;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

trait AdminSettingsUI
{

    public function renderTabs( $pageTabs ) : void
    {
        $activeTab = $this->getActiveTab( $pageTabs );
        $html = '<h2 class="nav-tab-wrapper">';
        foreach( $pageTabs as $tab )
        {
            // TODO:: implement the before and after tab icon sections
            $html .= '<a href="?page=' . $tab[ 'parent_page_slug' ] .
                '&tab=' . $tab[ 'tab_slug' ] .
                '" class="nav-tab ' . ( ( $tab[ 'tab_slug' ] == $activeTab[ 'tab_slug' ] ) ? 'nav-tab-active' : '' ) .
                '"><span><i class="' . $tab[ 'tab_before_icon' ] .
                '"></i></span>' . $tab[ 'tab_title' ] .
                '<span><i class="' . $tab[ 'tab_after_icon' ] .
                '"></i></span></a>';
        }
        $html .= '</h2>';
        echo $html;
        foreach( $pageTabs as $tab )
        {
            if( $tab[ 'tab_slug' ] == $activeTab[ 'tab_slug' ] && isset( $tab[ 'tab_description' ] ) )
            {
                echo '<p>' . $tab[ 'tab_description' ] . '</p>';
            }
        }
    }

    public function renderSectionsAndFields( array $currentPage, array $activeTab = [] )
    {
        global $wp_settings_sections, $wp_settings_fields;
        if( ! isset( $wp_settings_sections[ $currentPage[ 'menu_slug' ] ] ) )
        {
            return;
        }
        foreach( $this->sections as $availableSection )
        {
            if( $activeTab[ 'tab_slug' ] == $availableSection[ 'parent_tab_slug' ] )
            {
                foreach( ( array ) $wp_settings_sections[ $currentPage[ 'menu_slug' ] ] as $registeredSection )
                {
                    if( $availableSection[ 'section_slug' ] == $registeredSection[ 'id' ] )
                    {
                        if( $registeredSection[ 'title' ] )
                        {
                            echo "<h2>" . $registeredSection[ 'title' ] . "</h2>\n";
                        }
                        if( $registeredSection[ 'callback' ] )
                        {
                            call_user_func( $registeredSection[ 'callback' ], $registeredSection );
                        }
                        if(
                            ! isset( $wp_settings_fields ) ||
                            ! isset( $wp_settings_fields[ $currentPage[ 'menu_slug' ] ] ) || 
                            ! isset( $wp_settings_fields[ $currentPage[ 'menu_slug' ] ][ $registeredSection[ 'id' ] ] )
                        )
                        {
                            continue;
                        }
                        echo '<table class="form-table" role="presentation">';
                        do_settings_fields( $currentPage[ 'menu_slug' ], $registeredSection[ 'id' ] );
                        echo '</table>';
                    }
                }
            }
        }
    }

    public function renderSections( $args ) : void
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

    public function renderFields( $args ) : void
    {
        // TODO:: echo deferent elements baced on the callback setting (text input/text area/checkbox/radio button/ext)

        echo '<input type="text" name="' . $args[ 'option_slug' ] .
            '" id="' . $args[ 'option_slug' ] .
            '" value="' . get_option( $args[ 'option_slug' ] ) .
            '" placeholder="' . $args[ 'option_placeholder' ] .
            '" class="' . $args[ 'option_class' ] .
            // '" style="' . $args[ 'css_style' ] .
            '"/><p>' . $args[ 'option_description' ] . '</p>';
    }

}