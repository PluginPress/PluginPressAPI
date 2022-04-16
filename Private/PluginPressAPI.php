<?php

namespace PluginPress\PluginPressAPI;

use PluginPress\PluginPressAPI\PluginOptions\PluginOptions;
use PluginPress\PluginPressAPI\PluginActivator\PluginActivator;
use PluginPress\PluginPressAPI\WordPress\PluginsPageCustomizer;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginPressAPI
{

    protected $plugin_options;

    public function __construct( string $plugin_file_path, string $config_file_path )
    {
        $this->plugin_options = new PluginOptions( $plugin_file_path, $config_file_path );
        ( new PluginActivator( $this->plugin_options ) )->init();
        ( new PluginsPageCustomizer( $this->plugin_options ) )->init();
    }

}
