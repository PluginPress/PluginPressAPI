<?php

namespace PluginPress\PluginPressAPI\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginOptions
{
    
    private $plugin_options = [];

    public function __construct( string $plugin_file_path, string $config_file_path )
    {
        if( empty( $plugin_file_path ) || empty( $config_file_path ) )
        {
            return false;
        }
        $this->plugin_options = $this->get_plugin_data( $plugin_file_path, $config_file_path );
        return $this;
    }

    public function set( $option_name, $option_value = null )
    {
        $this->plugin_options[ $option_name ] = $option_value ;
        return $this->get( $option_name );
    }

    public function get( $option_name = null )
    {
         // HOOK: Filter - plugin_options_{PLUGIN_SLUG}
        $this->plugin_options = apply_filters( 'plugin_options_' . $this->plugin_options[ 'plugin_slug' ], $this->plugin_options );
        if( $option_name == null )
        {
            return $this->plugin_options;
        }
        if( array_key_exists( $option_name, $this->plugin_options ) && ( $this->plugin_options[ $option_name ] != null || $this->plugin_options[ $option_name ] != '' ) )
        {
            return $this->plugin_options[ $option_name ];
        }
        return false;
    }

    private function get_plugin_data( $plugin_file_path, $config_file_path ) : array
    {
        $plugin_file_path = str_replace( '\\', "/", $plugin_file_path );
        $config_file_path = str_replace( '\\', "/", $config_file_path );
        $directory_tree = explode( "/", $plugin_file_path );
        $plugin_file_name = end( $directory_tree );
        array_pop( $directory_tree );
        $plugin_dir_name = end( $directory_tree );
        $required_plugin_data = [
            // Plugin basename. sanitize_key( 'basename' )
            'plugin_base_name' => $plugin_dir_name . "/" . $plugin_file_name,
            // Plugin directory name
            'plugin_dir_name' => $plugin_dir_name,  
            // Plugin file name
            'plugin_file_name' => str_replace( '.php', '', $plugin_file_name ),
            // Plugin directory url
            'plugin_dir_url' => plugin_dir_url( $plugin_file_path ),
            // Plugin directory path
            'plugin_dir_path' => plugin_dir_path( $plugin_file_path ),
            // Plugin file path
            'plugin_file_path' => $plugin_file_path,
            // For plugin version compatibility check 
            'plugin_disabled' => false,
        ];
        $header_keys = [
            // Plugin name.
            'plugin_name' => 'Plugin Name',
            // Plugin Short name. Max 20 Char
            'plugin_shortname' => 'Short Name',
            // Title of the plugin and link to the plugin's site (if set).
            'plugin_title' => 'Title',
            // Plugin prefix/slug name. case sensitive and no spaces. Max 20 char
            'plugin_slug' => 'Plugin Slug',
            // Plugin namespace. sanitize_key( 'namespace' )
            'plugin_namespace' => 'Plugin Namespace',
            // Plugin URL
            'plugin_url' => 'Plugin URI',
            // Current plugin version. update it as you release new versions.
            'plugin_version' => 'Version',
            // Plugin description
            'plugin_description' => 'Description',
            // Plugin text domain. Max 20 Char
            'plugin_text_domain' => 'Text Domain',
            // Plugins relative directory path to .mo files.
            'plugin_text_domain_path' => 'Domain Path',
            // Whether the plugin can only be activated network-wide.
            'plugin_network' => 'Network',
            // Plugin author name
            'plugin_author_name' => 'Author',
            // Plugin author URL
            'plugin_author_url' => 'Author URI',
            // Minimum required version of PHP.
            'plugin_requires_php' => 'Requires PHP',
            // Minimum required version of WordPress.
            'plugin_requires_wordpress' => 'Requires at least',
            'plugin_wordpress_tested_up_to' => 'WP tested up to',
            // Minimum required version of WooCommerce.
            'plugin_requires_woocommerce' => 'WC requires at least',
            'plugin_woocommerce_tested_up_to' => 'WC tested up to',
        ];
        $plugin_meta_data = get_file_data( $plugin_file_path, $header_keys, 'plugin' );
        $custom_plugin_options = require_once( $config_file_path );
        return array_merge( $required_plugin_data, $plugin_meta_data, $custom_plugin_options );
    }

}