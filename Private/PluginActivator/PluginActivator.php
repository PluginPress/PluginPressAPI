<?php

namespace IamProgrammerLK\PluginPressAPI\PluginActivator;

use IamProgrammerLK\PluginPressAPI\PluginOptions\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginActivator
{

    protected $plugin_options;

    public function __construct( PluginOptions $plugin_options )
    {
        $this->plugin_options = $plugin_options;
    }

    public function init()
    {
        // If a plugin is silently activated (such as during an update), this hook does not fire.
        register_activation_hook( $this->plugin_options->get( 'plugin_base_name' ), [ $this, 'do_activation_hook'] );
        // If a plugin is silently deactivated (such as during an update), this hook does not fire.
        register_deactivation_hook( $this->plugin_options->get( 'plugin_base_name' ), [ $this, 'do_deactivation_hook'] );
    }
    
    public function do_activation_hook()
    {
        set_transient( $this->plugin_options->get( 'plugin_slug' ) . '_welcome_page_redirect', true, 30 );
        do_action( 'activation_hook_' . $this->plugin_options->get( 'plugin_slug' ) );
    }

    public function do_deactivation_hook()
    {
        do_action( 'deactivation_hook_' . $this->plugin_options->get( 'plugin_slug' ) );
    }

}