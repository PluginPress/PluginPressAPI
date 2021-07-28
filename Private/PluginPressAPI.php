<?php

namespace IamProgrammerLK\PluginPressAPI;

// If this file is called directly, abort. for the security purpose.
if ( ! defined( 'WPINC' ) )
{
    die;
}

class PluginPressAPI
{

    // private $pluginOptions;

    public function __construct()
    {

        // $this->pluginOptions = ( PluginOptions::getInstance() )->getPluginOptions();

    }

    public function init()
    {

        var_dump( 'Works' ); echo ' </pre>'; die( '' );

    }

}