<?php

namespace IamProgrammerLK\TestPlugin;

use IamProgrammerLK\PluginPressAPI\PluginPressAPI;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class TestPlugin extends PluginPressAPI
{

    public function __construct( string $plugin_file_path, string $config_file_path )
    {
        parent::__construct( $plugin_file_path, $config_file_path );
    }

    public function init()
    {
        ( new CreateAdminPages( $this->plugin_options ) )->init();

    }

}
