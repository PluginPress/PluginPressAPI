<?php

namespace IamProgrammerLK\PluginPressAPI\WordPress;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class PluginsPageCustomizer
{

    private $pluginOptions;

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function init() : void
    {
        add_filter( 'plugin_action_links_' . $this->pluginOptions->get( 'plugin_base_name' ), [ $this , 'renderPluginsPageLinks' ] );
        add_filter( 'plugin_row_meta', [ $this , 'renderPluginRowMetaLinks' ], 10, 2 );
        add_action( 'in_plugin_update_message-' . $this->pluginOptions->get(  'plugin_base_name' ), [ $this, 'renderPluginUpdateMessage' ], 10, 2 );
    }

    public function renderPluginsPageLinks( $links ) : array
    {
        if( $this->pluginOptions->get( 'settings_url' ) != false )
        {
            $settingsLink = '<a href="' . $this->pluginOptions->get( 'settings_url' ) . '"><span class="dashicons-before dashicons-admin-generic"></span>Settings</a>';
            array_push( $links, $settingsLink );
        }
        if( $this->pluginOptions->get( 'support_url' ) != false )
        {
            $supportLink = '<a href="' . $this->pluginOptions->get( 'support_url' ) .
                '" target="_blank" style="color:#2B8C69;"><span class="dashicons-before dashicons-sos"></span>Support</a>';
            array_push( $links, $supportLink );
        }
        if( $this->pluginOptions->get( 'feedback_url' ) != false )
        {
            $leaveFeedbackLink = '<a href="' . $this->pluginOptions->get( 'feedback_url' ) .
                '" target="_blank" style="color:#D97D0D;"><span class="dashicons-before dashicons-star-half"></span>Feedback</a>';
            array_push( $links, $leaveFeedbackLink );
        }
        return $links;
    }

    public function renderPluginRowMetaLinks( $metaLinks, $file ) : array
    {
        if( $this->pluginOptions->get( 'plugin_base_name' ) == $file )
        {
            $socialLinks = [];
            if( $this->pluginOptions->get( 'social_urls' ) != false )
            {
                foreach( $this->pluginOptions->get( 'social_urls' ) as $profile )
                {
                    if( ! isset( $profile[ 'profile_link' ] ) || $profile[ 'profile_link' ] == '' || $profile[ 'profile_link' ] == null )
                    {
                        continue;
                    }
                    else
                    {
                        // $profileLink =  $profile[ 'profile_link' ];
                        isset( $profile[ 'name' ] ) ? $profileName =  $profile[ 'name' ] : $profileName = rand( 2, 12 );
                        isset( $profile[ 'title' ] ) ? $profileTitle =  $profile[ 'title' ] : $profileTitle = '';
                        isset( $profile[ 'color' ] ) ? $profileColor =  $profile[ 'color' ] : $profileColor = '#D97D0D';
                        isset( $profile[ 'icon' ] ) ? $profileIcon =  $profile[ 'icon' ] : $profileIcon = 'dashicons-before dashicons-admin-generic';
                        $socialLinks = array_merge(
                            $socialLinks,
                            [
                                $profileName => '<a href="' . $profile[ 'profile_link' ] . '" target="_blank" style="color:' . $profileColor . ';"><span class="'
                                . $profileIcon . '"></span>' . $profileTitle . '</a>',
                            ]
                        );
                    }
                }
            }
            return array_merge( $metaLinks, $socialLinks );
        }
        return $metaLinks;
    }

    function renderPluginUpdateMessage( $pluginData, $response ) : void
    {
        if( $this->pluginOptions->get( 'update_notice_url' ) != false )
        {
            echo '<br/>';
            $curl = curl_init( $this->pluginOptions->get( 'update_notice_url' ) );
            curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7) AppleWebKit/534.48.3 (KHTML, like Gecko) Version/5.1 Safari/534.48.3' );
            curl_setopt( $curl, CURLOPT_FAILONERROR, true);
            $updateNotice = curl_exec( $curl );
            if ( curl_errno( $curl ) )
            {
                $errorMessage = curl_error( $curl );
                // TODO: Handle the curl errors properly (log it)
            }
            else
            {
                ob_start();
                echo $updateNotice;
                // TODO: Customize output to be more visual and user friendly
                ob_clean();
            }
            curl_close( $curl );
        }
    }

}