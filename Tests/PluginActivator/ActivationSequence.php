<?php

namespace IamProgrammerLK\TestPlugin\PluginActivator;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class ActivationSequence
{

    protected $pluginOptions;

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function init()
    {
    }

}