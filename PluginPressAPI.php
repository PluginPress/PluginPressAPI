<?php

/*
** @project
** Project Name:        PluginPressAPI - TestPlugin
** Project Description: Wrapper classes for building object-oriented WordPress plugins.
** Project Version:     1.0.0
** File Name:           PluginPressAPI.php
** File Description:    This file is read by WordPress to generate the plugin information in the plugin admin area.
**                      This file also includes all of the dependencies used by the plugin, registers the activation
**                      and deactivation functions, and defines a function that starts the plugin.
** File Version:        1.0.0
** Last Change:         2021-08-02
**
** @wordpress-plugin
** Plugin Name:         PluginPressAPI - TestPlugin
** Short Name:          PluginPressAPI - TestPlugin
** Plugin Slug:         pluginpressapi
** Plugin Namespace:    IamProgrammerLK\TestPlugin
** Title:               PluginPressAPI - TestPlugin
** Plugin URI:          https://iamprogrammerlk.github.io/PluginPressAPI/
** Version:             1.0.0
** Description:         Wrapper classes for building object-oriented WordPress plugins.
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

namespace IamProgrammerLK\TestPlugin;

use IamProgrammerLK\PluginPressAPI\PluginOptions\PluginOptions;

use IamProgrammerLK\TestPlugin\PluginActivator\PluginActivator;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

// Dynamically include all the classes.
require_once trailingslashit( dirname( __FILE__ ) ) . 'vendor/autoload.php';

// triggers when the plugin is activated
function plugin_activation_hook()
{
    $plugin_options     = new PluginOptions( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $plugin_activator   = new PluginActivator( $plugin_options );
    $plugin_activator->activate();
}
register_activation_hook( __file__, 'IamProgrammerLK\Tests\plugin_activation_hook' );

// triggers when the plugin is deactivated
function plugin_deactivation_hook()
{
    $plugin_options     = new PluginOptions( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $plugin_activator   = new PluginActivator( $plugin_options );
    $plugin_activator->deactivate();
}
register_deactivation_hook( __FILE__, 'IamProgrammerLK\Tests\plugin_deactivation_hook' );

// initiate the plugin
if( ! class_exists( 'TestPlugin' ) )
{
    // $plugin_options     = new PluginOptions( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $test_plugin = new TestPlugin( __FILE__, plugin_dir_path( __FILE__ ) . 'Configs/PluginOptions.php' );
    $test_plugin->init();
}