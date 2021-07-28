<?php

namespace IamProgrammerLK\Tests\PluginActivator;

use IamProgrammerLK\PluginPressAPI\PluginOptions\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginActivator
{

    private $pluginOptions;
    private $activationSequence;
    private $deactivationSequence;

    public function __construct()
    {
        $this->pluginOptions = PluginOptions::getInstance();
    }

    public function activate()
    {
        $this->activationSequence = new ActivationSequence();
        $this->activationSequence->init();
    }

    public function deactivate()
    {
        $this->deactivationSequence = new DeactivationSequence();
        $this->deactivationSequence->init();
    }

}