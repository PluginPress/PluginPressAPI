<?php

namespace IamProgrammerLK\PluginPressAPI\Admin;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

trait AdminPagesUI
{

    public function render_admin_page_ui()
    {
        $current_page = $this->get_current_page();
        if( isset( $current_page[ 'page_ui_template' ] ) )
        {
            include_once $current_page[ 'page_ui_template' ];
            return;
        }
        $this->render_page_header_section( $current_page );
        if( ! empty( $this->tabs ) )
        {
            $page_tabs = $this->get_current_page_tabs( $current_page );
            $active_tab = $this->get_active_tab( $page_tabs );
            if( $active_tab )
            {
                $this->render_tabs( $page_tabs );
                echo '<form method="post" action="options.php">';
                settings_fields( $active_tab[ 'tab_slug' ] );
                $this->render_sections_and_fields( $current_page, $active_tab );
                submit_button();
                echo '</form>';
            }
        }
        else
        {
            //TODO: render default setting section and fields
            // $this->render_sections();
        }
        $this->render_page_footer_section( $current_page );
    }

    public function render_page_header_section( $current_page ) : void
    {
        echo '<div class="wrap">';
        echo '<p>';
        // HOOK: Action - before_admin_page_header_section_{PLUGIN_SLUG} - All Admin page
        do_action( 'before_admin_page_header_section_' . $this->plugin_options->get( 'plugin_slug' ) );
        echo '</p>';
        echo '<p>';
        // HOOK: Action - before_admin_page_header_section_{PAGE_SLUG} -  Admin page
        do_action( 'before_admin_page_header_section_' . $current_page[ 'page_slug' ] );
        echo '</p>';

        // TODO: Implement the icon section

        echo '<h1>' . $this->plugin_options->get( 'plugin_name' ) . ' | ' . $current_page[ 'page_title' ] . '</h1>';
        if( isset( $current_page[ 'page_description' ] ) && ( $current_page[ 'page_description' ] != null || $current_page[ 'page_description' ] != '' ) )
        {
            echo '<p>' . $current_page[ 'page_description' ] . '</p>';
        }
        echo '<p>';
        // HOOK: Action - after_admin_page_header_section_{PLUGIN_SLUG} - All Admin page
        do_action( 'after_admin_page_header_section_' . $this->plugin_options->get( 'plugin_slug' ) );
        echo '</p>';
        echo '<p>';
        // HOOK: Action - after_admin_page_header_section_{PAGE_SLUG} - Admin page
        do_action( 'after_admin_page_header_section_' . $current_page[ 'page_slug' ] );
        echo '</p>';
    }

    public function render_page_footer_section( $current_page ) : void
    {
        echo '<p>';
        // HOOK: Action - before_admin_page_footer_section_{PLUGIN_SLUG} - All Admin page 
        do_action( 'before_admin_page_footer_section_' . $this->plugin_options->get( 'plugin_slug' ) );
        echo '</p>';
        echo '<p>';
        // HOOK: Action - before_admin_page_footer_section_{PAGE_SLUG} - Admin page 
        do_action( 'before_admin_page_footer_section_' . $current_page[ 'page_slug' ] );
        echo '</p>';
        if( isset( $current_page[ 'page_footer' ] ) && ( $current_page[ 'page_footer' ] != null || $current_page[ 'page_footer' ] != '' ) )
        {
            echo '<div class="page-footer">' . $current_page[ 'page_footer' ] . '</div>';
        }
        else
        {
            echo '<div class="page-footer"><p><i>Thank you for using <a href="' . $this->plugin_options->get( 'plugin_url' ) .
                '" title="' . $this->plugin_options->get( 'plugin_name' ) . '"> ' .
                $this->plugin_options->get( 'plugin_name' ) . '</a> - Please consider leaving your valued <a href="' . $this->plugin_options->get( 'plugin_feedback_url' ) .
                '" title="' . $this->plugin_options->get( 'plugin_name' ) . '">feedback <a href="' . $this->plugin_options->get( 'plugin_feedback_url' ) .
                '" title="' . $this->plugin_options->get( 'plugin_name' ) . '" style="color:#D97D0D;"><span class="dashicons-before dashicons-star-half">' .
                '<span class="dashicons-before dashicons-star-half"><span class="dashicons-before dashicons-star-half"><span class="dashicons-before dashicons-star-half">' .
                '<span class="dashicons-before dashicons-star-half"></a></i></p>';
        }
        echo '<p>';
        // HOOK: Action - after_admin_page_footer_section_{PLUGIN_SLUG} - All Admin page 
        do_action( 'after_admin_page_footer_section_' . $this->plugin_options->get( 'plugin_slug' ) );
        echo '</p>';
        echo '<p>';
        // HOOK: Action - after_admin_page_footer_section_{PAGE_SLUG} - Admin page 
        do_action( 'after_admin_page_footer_section_' . $current_page[ 'page_slug' ] );
        echo '</p>';
        echo '</div>';
    }

}

