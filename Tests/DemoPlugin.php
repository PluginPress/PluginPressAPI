<?php

namespace IamProgrammerLK\Tests;

use IamProgrammerLK\PluginPressAPI\PluginOptions\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if ( ! defined( 'WPINC' ) )
{
    die;
}

class DemoPlugin
{

    private $pluginOptions;

    public function __construct()
    {

        $this->pluginOptions = PluginOptions::getInstance();

    }

    public function init()
    {

echo $this->pluginOptions->get('namespace');
    }

}