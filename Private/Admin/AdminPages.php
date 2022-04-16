<?php

namespace PluginPress\PluginPressAPI\Admin;

use PluginPress\PluginPressAPI\PluginOptions\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class AdminPages extends AdminSettings
{

    use AdminPagesUI;

    private $plugin_options;
    private $admin_enqueue_scripts = [];
    private $admin_option_pages = [];
    private $admin_pages = [];
    private $admin_sub_pages = [];
    private $registered_pages = [];

    public function __construct( PluginOptions $plugin_options )
    {
        $this->plugin_options = $plugin_options;
        parent::__construct( $plugin_options );
    }

    public function init()
    {

        if ( empty( $this->admin_enqueue_scripts ) )
        {
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_enqueue_scripts' ) );
        }
        if( ! empty( $this->admin_option_pages) )
        {
            add_action( 'admin_menu', array( $this, 'register_option_pages' ), 20 );
        }
        if( ! empty( $this->admin_pages ) )
        {
            add_action( 'admin_menu', array( $this, 'register_menu_pages' ), 30 );
        }
        if( ! empty( $this->admin_sub_pages ) )
        {
            add_action( 'admin_menu', array( $this, 'register_submenu_pages' ), 40 );
        }
        parent::init();
    }

    // TODO: Implements the function to add page/tabs/sections/fields at once.


    // NOTE: @array $args -- single array for the single page and multi-array for the multiple pages
    public function add_option_pages( array $args ) : void
    {
        foreach( $args as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                array_push( $this->admin_option_pages, $args );
                return;
            }
            else
            {
                array_push( $this->admin_option_pages, $value );
            }
        }
    }

    // NOTE: @array $args -- single array for the single page and multi-array for the multiple pages
    public function add_menu_pages( array $args ) : void
    {
        foreach( $args as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                array_push( $this->admin_pages, $args );
                return;
            }
            else
            {
                array_push( $this->admin_pages, $value );
            }
        }
    }

    // NOTE: @array $args -- single array for the single page and multi-array for the multiple pages
    public function add_submenu_pages( array $args ) : void
    {
        foreach( $args as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                array_push( $this->admin_sub_pages, $args );
                return;
            }
            else
            {
                array_push( $this->admin_sub_pages, $value );
            }
        }
    }

    // user will redirected to the welcome page when plugin is activated.
    // If a plugin is silently activated (such as during an update, multisite, or multiple plugin activation), this does not redirect to the welcome page.
    public function add_admin_welcome_page( array $args )
    {
        $this->add_submenu_pages(
            [
                'page_parent_slug'      => 'index.php',
                'page_title'            => $args[ 'page_title' ],
                'page_menu_title'       => $args[ 'page_title' ],
                'page_capabilities'     => 'manage_options',
                'page_slug'             => 'welcome_page',
                'page_ui'               => $args[ 'page_ui' ],
            ]
        );
        if( ! isset( $args[ 'page_show_always' ] ) || $args[ 'page_show_always' ] == false )
        {
            add_action(
                'admin_head',
                function()
                {
                    remove_submenu_page( 'index.php', $this->plugin_options->get( 'plugin_slug' ) . '_welcome_page' );
                }
            );
        }
        add_action(
            'admin_init',
            function()
            {
                if( ! get_transient( $this->plugin_options->get( 'plugin_slug' ) . '_welcome_page_redirect' ) )
                {
                    return;
                }

                delete_transient( $this->plugin_options->get( 'plugin_slug' ) . '_welcome_page_redirect' );
                if( isset( $_GET[ 'activate-multi' ] ) )
                {
                    return;
                }
                wp_safe_redirect( admin_url( 'index.php?page=' . $this->plugin_options->get( 'plugin_slug' ) . '_welcome_page' ) );
                die();
            }
        );
    }

    public function register_admin_enqueue_scripts( $hooks )
    {
        wp_enqueue_style(
            'pluginpressapi_admin_main',
            $this->plugin_options->get( 'plugin_dir_url' ) . 'vendor/pluginpress/pluginpressapi/Public/AdminAssets/StyleSheets/AdminMain.css'
        );
        wp_enqueue_script(
            'pluginpressapi_admin_main',
            $this->plugin_options->get( 'plugin_dir_url' ) . 'vendor/pluginpress/pluginpressapi/Public/AdminAssets/JavaScripts/AdminMain.js'
        );
    }

    public function register_option_pages() : void
    {
        foreach( $this->admin_option_pages as $page )
        {
            $page[ 'page_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $page[ 'page_slug' ];
            if( isset( $page[ 'page_ui' ] ) )
            {
                if( ! is_array( $page[ 'page_ui' ] ) )
                {
                    if( is_file( $page[ 'page_ui' ] ) )
                    {
                        $page[ 'page_ui_template' ] = $page[ 'page_ui' ];
                    }
                    $page[ 'page_ui' ] = [ $this, 'render_admin_page_ui' ];
                }
            }
            else
            {
                $page[ 'page_ui' ] = [ $this, 'render_admin_page_ui' ];
            }
            if( ! isset( $page[ 'page_position' ] ) )
            {
                $page[ 'page_position' ] = 10;
            }
            $page[ 'page_hook_suffix' ] = add_options_page(
                $page[ 'page_title' ],
                $page[ 'page_menu_title' ],
                $page[ 'page_capabilities' ],
                $page[ 'page_slug' ],
                $page[ 'page_ui' ],
                $page[ 'page_position' ]
            );
            $page[ 'page_type' ] = 'options_page';
            $this->update_registered_pages( $page );
        }
    }

    public function register_menu_pages() : void
    {
        foreach( $this->admin_pages as $page )
        {
            $page[ 'page_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $page[ 'page_slug' ];
            if( isset( $page[ 'page_ui' ] ) )
            {
                if( ! is_array( $page[ 'page_ui' ] ) )
                {
                    if( is_file( $page[ 'page_ui' ] ) )
                    {
                        $page[ 'page_ui_template' ] = $page[ 'page_ui' ];
                    }
                    $page[ 'page_ui' ] = [ $this, 'render_admin_page_ui' ];
                }
            }
            else
            {
                $page[ 'page_ui' ] = [ $this, 'render_admin_page_ui' ];
            }
            if( ! isset( $page[ 'page_icon_url' ] ) )
            {
                $page[ 'page_icon_url' ] = 'dashicons-admin-generic';
            }
            if( ! isset( $page[ 'page_position' ] ) )
            {
                $page[ 'page_position' ] = 10;
            }
            $page[ 'page_hook_suffix' ] = add_menu_page(
                $page[ 'page_title' ],
                $page[ 'page_menu_title' ],
                $page[ 'page_capabilities' ],
                $page[ 'page_slug' ],
                $page[ 'page_ui' ],
                $page[ 'page_icon_url' ],
                $page[ 'page_position' ]
            );
            $page[ 'page_type' ] = 'menu_page';
            $this->update_registered_pages( $page );
        }
    }

    public function register_submenu_pages() : void
    {
        foreach( $this->admin_sub_pages as $page )
        {
            if( ! str_ends_with( $page[ 'page_parent_slug' ], '.php' ) )
            {
                $page[ 'page_parent_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $page[ 'page_parent_slug' ];
            }
            $page[ 'page_slug' ] = $this->plugin_options->get( 'plugin_slug' ) . '_' . $page[ 'page_slug' ];
            if( isset( $page[ 'page_ui' ] ) )
            {
                if( ! is_array( $page[ 'page_ui' ] ) )
                {
                    if( is_file( $page[ 'page_ui' ] ) )
                    {
                        $page[ 'page_ui_template' ] = $page[ 'page_ui' ];
                    }
                    $page[ 'page_ui' ] = [ $this, 'render_admin_page_ui' ];
                }
            }
            else
            {
                $page[ 'page_ui' ] = [ $this, 'render_admin_page_ui' ];
            }
            if( ! isset( $page[ 'page_position' ] ) )
            {
                $page[ 'page_position' ] = 10;
            }
            $page[ 'page_hook_suffix' ] = add_submenu_page(
                $page[ 'page_parent_slug' ],
                $page[ 'page_title' ],
                $page[ 'page_menu_title' ],
                $page[ 'page_capabilities' ],
                $page[ 'page_slug' ],
                $page[ 'page_ui' ],
                $page[ 'page_position' ]
            );
            $page[ 'page_type' ] = 'submenu_page';
            $this->update_registered_pages( $page );
        }
    }

    protected function get_registered_pages() : array
    {
        return $this->registered_pages;
    }

    protected function get_current_page() : array
    {
        $current_screen = get_current_screen();
        foreach( $this->get_registered_pages() as $page )
        {
            if( isset( $page[ 'page_hook_suffix' ] ) && $page[ 'page_hook_suffix' ] != false && $page[ 'page_hook_suffix' ] == $current_screen->id )
            {
                return $page;
            }
        }
    }

    private function update_registered_pages( $page ) : void
    {
        if( $page[ 'page_hook_suffix' ] == false )
        {
            // The user does not have the capability required to create a page.
            return;
        }
        array_push( $this->registered_pages, $page );
        $this->enqueue_on_page( $page );
    }

    private function enqueue_on_page( $page ) : void
    {
        // Prints in head section for a specific admin page.
        if( isset( $page[ 'enqueue_on_page_head' ] ) && ! empty( $page[ 'enqueue_on_page_head' ] ) && is_array( $page[ 'enqueue_on_page_head' ] ) )
        {
            add_action(
                'admin_head-' . $page[ 'page_hook_suffix' ],
                function() use( $page )
                {
                    foreach( $page[ 'enqueue_on_page_head' ] as $script )
                    {
                        echo $script . '<br/>';
                    }
                } 
            );
        }
        // Prints scripts or data after the default footer scripts.
        if( isset( $page[ 'enqueue_on_page_footer' ] ) && ! empty( $page[ 'enqueue_on_page_footer' ] ) && is_array( $page[ 'enqueue_on_page_footer' ] ) )
        {
            add_action(
                'admin_footer-' . $page[ 'page_hook_suffix' ],
                function() use( $page )
                {
                    foreach( $page[ 'enqueue_on_page_footer' ] as $script )
                    {
                        echo $script . '<br/>';
                    }
                } 
            );
        }
    }

}