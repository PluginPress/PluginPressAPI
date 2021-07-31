<?php

namespace IamProgrammerLK\TestPlugin;

use IamProgrammerLK\PluginPressAPI\WordPress\PluginsPageCustomizer;

// If this file is called directly, abort. for the security purpose.
if ( ! defined( 'WPINC' ) )
{
    die;
}

class DoWordPress
{

    protected $pluginOptions;

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function init()
    {
        ( new PluginsPageCustomizer( $this->pluginOptions ) )->init();
    }

}