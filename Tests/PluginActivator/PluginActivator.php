<?php

namespace IamProgrammerLK\Tests\PluginActivator;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginActivator
{

    protected $pluginOptions;

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function activate()
    {
        ( new ActivationSequence( $this->pluginOptions ) )->init();
    }

    public function deactivate()
    {
        ( new DeactivationSequence( $this->pluginOptions ) )->init();
    }

}