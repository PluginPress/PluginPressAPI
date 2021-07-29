<?php

namespace IamProgrammerLK\PluginPressAPI\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginOptions
{

    private $pluginOptions = [];

    public function __construct( string $pluginFilePath, string $configFilePath )
    {
        if( empty( $pluginFilePath ) || empty( $configFilePath ) )
        {
            return false;
        }
        $this->pluginOptions = $this->getPluginData( $pluginFilePath, $configFilePath );
        return $this;
    }

    public function set( $optionName, $optionValue = null )
    {
        $this->pluginOptions[ $optionName ] = $optionValue ;
        return $this->get( $optionName );
    }

    public function get( $optionName = null )
    {
        $this->pluginOptions = apply_filters( $this->pluginOptions[ 'slug' ] . '_plugin_options', $this->pluginOptions );
        if( $optionName == null )
        {
            return $this->pluginOptions;
        }
        if( array_key_exists( $optionName, $this->pluginOptions ) && ( $this->pluginOptions[ $optionName ] != null || $this->pluginOptions[ $optionName ] != '' ) )
        {
            return $this->pluginOptions[ $optionName ];
        }
        return false;
    }

    private function getPluginData( $pluginFilePath, $configFilePath ) : array
    {
        $pluginFilePath = str_replace( '\\', '/', $pluginFilePath );
        $configFilePath = str_replace( '\\', '/', $configFilePath );
        $dirTree = explode( "/", $pluginFilePath );
        $pluginFileName = end( $dirTree );
        array_pop( $dirTree );
        $pluginDirName = end( $dirTree );
        $requiredPluginData = [
            // Plugin basename. sanitize_key( 'basename' )
            'plugin_base_name' => $pluginDirName . '/' . $pluginFileName,
            // Plugin directory name
            'plugin_dir_name' => $pluginDirName,  
            // Plugin file name
            'plugin_file_name' => str_replace( '.php', '', $pluginFileName ),
            // Plugin directory url
            'plugin_dir_url' => plugin_dir_url( $pluginFilePath ),
            // Plugin directory path
            'plugin_dir_path' => plugin_dir_path( $pluginFilePath ),
            // Plugin file path
            'plugin_file_path' => $pluginFilePath,
            // For plugin version compatibility check 
            'plugin_disabled' => false,
        ];
        $headerKeys = [
            // Plugin name.
            'name' => 'Plugin Name',
            // Plugin Short name. Max 20 Char
            'shortname' => 'Short Name',
            // Title of the plugin and link to the plugin's site (if set).
            'title' => 'Title',
            // Plugin prefix/slug name. case sensitive and no spaces. Max 20 char
            'slug' => 'Plugin Slug',
            // Plugin namespace. sanitize_key( 'namespace' )
            'namespace' => 'Plugin Namespace',
            // Plugin URL
            'plugin_url' => 'Plugin URI',
            // Current plugin version. update it as you release new versions.
            'version' => 'Version',
            // Plugin description
            'description' => 'Description',
            // Plugin text domain. Max 20 Char
            'text_domain' => 'Text Domain',
            // Plugins relative directory path to .mo files.
            'text_domain_path' => 'Domain Path',
            // Whether the plugin can only be activated network-wide.
            'network' => 'Network',
            // Plugin author name
            'author_name' => 'Author',
            // Plugin author URL
            'author_url' => 'Author URI',
            // Minimum required version of PHP.
            'requires_php' => 'Requires PHP',
            // Minimum required version of WordPress.
            'requires_wp' => 'Requires at least',
            'wp_tested_up_to' => 'WP tested up to',
            // Minimum required version of WooCommerce.
            'requires_wc' => 'WC requires at least',
            'wc_tested_up_to' => 'WC tested up to',
        ];
        $pluginMetaData = get_file_data( $pluginFilePath, $headerKeys, 'plugin' );
        $customPluginOptions = require_once( $configFilePath );
        return array_merge( $requiredPluginData, $pluginMetaData, $customPluginOptions );
    }

}