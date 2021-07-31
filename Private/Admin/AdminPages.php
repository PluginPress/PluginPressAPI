<?php

namespace IamProgrammerLK\PluginPressAPI\Admin;


// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class AdminPages extends AdminSettings 
{

    use AdminPagesUI;

    private $pluginOptions;
    private $optionPages = [];
    private $adminPages = [];
    private $adminSubPages = [];
    private $registeredPages = [];

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
        parent::__construct( $pluginOptions );
    }

    public function init()
    {

        // if ( !empty( $this->enqueues ) )
        // 	add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

        if( ! empty( $this->optionPages) )
        {
            add_action( 'admin_menu', array( $this, 'registerOptionPages' ), 20 );
        }
        if( ! empty( $this->adminPages ) )
        {
            add_action( 'admin_menu', array( $this, 'registerMenuPages' ), 30 );
        }
        if( ! empty( $this->adminSubPages ) )
        {
            add_action( 'admin_menu', array( $this, 'registerSubMenuPages' ), 40 );
        }
        parent::init();
    }

    // public function addPages( $pageType, $args ) : void
    // {
    //     switch( $pageType )
    //     {
    //         case 'options_page':
    //             $this->addOptionPages( $args );
    //             break;
    //         case 'menu_page':
    //             $this->addMenuPages( $args );
    //             break;
    //         case 'submenu_page':
    //             $this->addSubMenuPages( $args );
    //             break;
    //         default:
    //             $this->addOptionPages( $args );
    //     }
    // }

    // @array $args -- single array for the single page and multi-array for the multiple pages
    public function addOptionPages( array $args ) : void
    {
        foreach( $args as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                array_push( $this->optionPages, $args );
                return;
            }
            else
            {
                array_push( $this->optionPages, $value );
            }
        }
    }

    // @array $args -- single array for the single page and multi-array for the multiple pages
    public function addMenuPages( array $args ) : void
    {
        foreach( $args as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                array_push( $this->adminPages, $args );
                return;
            }
            else
            {
                array_push( $this->adminPages, $value );
            }
        }
    }

    // @array $args -- single array for the single page and multi-array for the multiple pages
    public function addSubMenuPages( array $args ) : void
    {
        foreach( $args as $key => $value )
        {
            if( ! is_array( $value ) )
            {
                array_push( $this->adminSubPages, $args );
                return;
            }
            else
            {
                array_push( $this->adminSubPages, $value );
            }
        }
    }

    public function registerOptionPages() : void
    {
        foreach( $this->optionPages as $page )
        {
            $page[ 'menu_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $page[ 'menu_slug' ];
            if( ! isset( $page[ 'render_page_ui' ] ) )
            {
                $page[ 'render_page_ui' ] = [ $this, 'renderAdminPageUI' ];
            }
            if( ! isset( $page[ 'position' ] ) )
            {
                $page[ 'position' ] = 10;
            }
            $page[ 'page_hook_suffix' ] = add_options_page(
                $page[ 'page_title' ],
                $page[ 'menu_title' ],
                $page[ 'capabilities' ],
                $page[ 'menu_slug' ],
                $page[ 'render_page_ui' ],
                $page[ 'position' ]
            );
            $page[ 'page_type' ] = 'options_page';
            $this->updateRegisteredPages( $page );
        }
    }

    public function registerMenuPages() : void
    {
        foreach( $this->adminPages as $page )
        {
            $page[ 'menu_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $page[ 'menu_slug' ];
            if( ! isset( $page[ 'render_page_ui' ] ) )
            {
                $page[ 'render_page_ui' ] = [ $this, 'renderAdminPageUI' ];
            }
            if( ! isset( $page[ 'icon_url' ] ) )
            {
                $page[ 'icon_url' ] = 'dashicons-admin-generic';
            }
            if( ! isset( $page[ 'position' ] ) )
            {
                $page[ 'position' ] = 10;
            }
            $page[ 'page_hook_suffix' ] = add_menu_page(
                $page[ 'page_title' ],
                $page[ 'menu_title' ],
                $page[ 'capabilities' ],
                $page[ 'menu_slug' ],
                $page[ 'render_page_ui' ],
                $page[ 'icon_url' ],
                $page[ 'position' ]
            );
            $page[ 'page_type' ] = 'menu_page';
            $this->updateRegisteredPages( $page );
        }
    }

    public function registerSubMenuPages() : void
    {
        foreach( $this->adminSubPages as $page )
        {
            if( ! str_ends_with( $page[ 'parent_page' ], '.php' ) )
            {
                $page[ 'parent_page' ] = $this->pluginOptions->get( 'slug' ) . '_' . $page[ 'parent_page' ];
            }
            $page[ 'menu_slug' ] = $this->pluginOptions->get( 'slug' ) . '_' . $page[ 'menu_slug' ];
            if( ! isset( $page[ 'render_page_ui' ] ) )
            {
                $page[ 'render_page_ui' ] = [ $this, 'renderAdminPageUI' ];
            }
            if( ! isset( $page[ 'position' ] ) )
            {
                $page[ 'position' ] = 10;
            }
            $page[ 'page_hook_suffix' ] = add_submenu_page(
                $page[ 'parent_page' ],
                $page[ 'page_title' ],
                $page[ 'menu_title' ],
                $page[ 'capabilities' ],
                $page[ 'menu_slug' ],
                $page[ 'render_page_ui' ],
                $page[ 'position' ]
            );
            $page[ 'page_type' ] = 'submenu_page';
            $this->updateRegisteredPages( $page );
        }
    }

    protected function getRegisteredPages() : array
    {
        return $this->registeredPages;
    }

    protected function getCurrentPage() : array
    {
        $currentScreen = get_current_screen();
        foreach( $this->getRegisteredPages() as $page )
        {
            if( isset( $page[ 'page_hook_suffix' ] ) && $page[ 'page_hook_suffix' ] != false && $page[ 'page_hook_suffix' ] == $currentScreen->id )
            {
                return $page;
            }
        }
    }

    private function updateRegisteredPages( $page ) : void
    {
        if( $page[ 'page_hook_suffix' ] == false )
        {
            // NOTE:The user does not have the capability required to create a page.
            return;
        }
        if( isset( $page[ 'render_page_ui' ] ) )
        {
            unset( $page[ 'render_page_ui' ] );
        }
        array_push( $this->registeredPages, $page );
        $this->enqueueOnPage( $page );
    }

    private function enqueueOnPage( $page ) : void
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