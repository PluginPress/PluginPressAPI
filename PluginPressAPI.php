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

namespace IamProgrammerLK\PluginPressAPI;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

// Dynamically include the classes.
require_once trailingslashit( dirname( __FILE__ ) ) . 'vendor/autoload.php';

// // triggers when the plugin is activated
// function pluginActivationHook()
// {
//     $pluginOptions = require( 'Private/PluginOptions.php' );
//     $pluginActivator = new PluginActivator( $pluginOptions );
//     $pluginActivator->activate();
// }
// register_activation_hook( __file__, 'IamProgrammerLK\PluginPress\pluginActivationHook' );

// // triggers when the plugin is deactivated
// function pluginDeactivationHook()
// {
//     $pluginOptions = require( 'Private/PluginOptions.php' );
//     $pluginActivator = new PluginActivator( $pluginOptions );
//     $pluginActivator->deactivate();
// }
// register_deactivation_hook( __FILE__, 'IamProgrammerLK\PluginPress\pluginDeactivationHook' );

// initiate the plugin
if( ! class_exists( 'PluginPressAPI' ) )
{
    // $pluginOptions = require( 'Private/PluginOptions.php' );
    $pluginpressAPI = new PluginPressAPI();
    $pluginpressAPI->init();
}