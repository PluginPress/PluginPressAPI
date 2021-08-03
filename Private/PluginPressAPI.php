<?php

namespace IamprogrammerLK\PluginPressAPI;

use IamProgrammerLK\PluginPressAPI\PluginOptions\PluginOptions;
use IamProgrammerLK\PluginPressAPI\PluginActivator\PluginActivator;
use IamProgrammerLK\PluginPressAPI\WordPress\PluginsPageCustomizer;

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
