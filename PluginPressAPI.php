<?php

/*
** @project
** Project Name:        PluginPress API
** Project Description: API wrapper classes for building object-oriented WordPress plugins.
** Project Version:     1.0.0
** File Name:           PluginPressAPI.php
** File Description:    This file is read by WordPress to generate the plugin information in the plugin admin area.
**                      This file also includes all of the dependencies used by the plugin, registers the activation
**                      and deactivation functions, and defines a function that starts the plugin.
** File Version:        1.0.0
** Last Change:         2021-07-27
**
** @wordpress-plugin
** Plugin Name:         PluginPress API
** Short Name:          PluginPress API
** Plugin Slug:         pluginpressapi
** Plugin Namespace:    IamProgrammerLK\PluginPressAPI
** Title:               PluginPress API
** Plugin URI:          https://iamprogrammerlk.github.io/PluginPressAPI/
** Version:             1.0.0
** Description:         API wrapper classes for building object-oriented WordPress plugins.
** Text Domain:         pluginpressapi
** Domain Path:         /Common/Languages
** Network:             
** Author:              I am Programmer
** Author URI:          https://iamprogrammer.lk
** Requires PHP:        7.0.0
** Requires at least:   5.6.0
** WP tested up to:     5.8.0
** License:             MIT
** License URI:         https://github.com/IamProgrammerLK/PluginPressAPI/blob/main/LICENSE
**
** @copyrights
** Copyright:           Copyright (C) IamProgrammerLK - All Rights Reserved
** Copyright Note:      |
**
** @authors
** Author:              I am Programmer
** Author URL:          https://iamprogrammer.lk
** Since                1.0.0 (2021-07-26)
*/

namespace IamProgrammerLK\Tests;

use IamProgrammerLK\PluginPressAPI\PluginOptions\PluginOptions;

use IamProgrammerLK\Tests\PluginActivator\PluginActivator;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

// Dynamically include all the classes.
require_once trailingslashit( dirname( __FILE__ ) ) . 'vendor/autoload.php';

// triggers when the plugin is activated
function pluginActivationHook()
{
    $pluginOptions = new PluginOptions( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $pluginActivator = new PluginActivator( $pluginOptions );
    $pluginActivator->activate();
}
register_activation_hook( __file__, 'IamProgrammerLK\Tests\pluginActivationHook' );

// triggers when the plugin is deactivated
function pluginDeactivationHook()
{
    $pluginOptions = new PluginOptions( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $pluginActivator = new PluginActivator( $pluginOptions );
    $pluginActivator->deactivate();
}
register_deactivation_hook( __FILE__, 'IamProgrammerLK\Tests\pluginDeactivationHook' );

// initiate the plugin
if( ! class_exists( 'Tests' ) )
{
    $pluginOptions = new PluginOptions( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $tests = new Tests( $pluginOptions );
    $tests->init();
    echo '<pre> '; var_dump( $pluginOptions->get( 'namespace' ) ); echo ' </pre>';
}
