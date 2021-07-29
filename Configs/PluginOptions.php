<?php

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

return [
    'required_plugins' => [
        [
            'plugin_required' => false,
            'plugin_id' => 'WooCommerce',
            'plugin_name' => 'WooCommerce',
            'plugin_class_name' => 'WooCommerce',
            'plugin_url' => 'https://wordpress.org/plugins/woocommerce/',
            'plugin_min_version' => '3.9.9',
            'plugin_max_version' => '',
            'plugin_get_version' => '',
        ],
        [
            'plugin_required' => false,
            'plugin_id' => 'LearnPress',
            'plugin_name' => 'LearnPress',
            'plugin_class_name' => 'LearnPress',
            'plugin_url' => 'https://wordpress.org/plugins/learnpress/',
            'plugin_min_version' => '3.9.9',
            'plugin_max_version' => '',
            'plugin_get_version' => '',
        ],

    ],

    // Displays at the end of the update message container in row of the plugins list table.
    'update_notice_url' => 'http://plugins.svn.wordpress.org/testplugin/trunk/updatenotice.txt',
    // Plugin settings page url
    'settings_url' => 'admin.php?page=testplugin_default_option_page',
    // Plugin help and support url
    'support_url' => 'https://wordpress.org/support/plugin/testplugin/',
    // Plugin feedback url
    'feedback_url' => 'https://wordpress.org/plugins/testplugin/#reviews',
    // Social profile links
    'social_urls' => [
        [
            'name' => 'donations',
            'title' => 'Donate',
            'profile_link' => 'https://sponsors.iamprogrammer.lk',
            'color' => '#A66D97',
            'icon' => 'dashicons-before dashicons-heart',
        ],
        [
            'name' => 'upgrade',
            'title' => 'Upgrade',
            'profile_link' => 'https://www.buymeacoffee.com/IamProgrammerLK/e/7620',
            'color' => '',
            'icon' => 'dashicons-before dashicons-awards',
        ],
        [
            'name' => 'github_profile',
            'title' => 'Github',
            'profile_link' => 'https://github.com/IamProgrammerLK',
            'color' => '#2B8C69',
            'icon' => 'dashicons-before dashicons-editor-code',
        ],
        [
            'name' => 'twitter_profile',
            'title' => 'Twitter',
            'profile_link' => 'https://twitter.com/IamProgrammerLK',
            'color' => '',
            'icon' => 'dashicons-before dashicons-twitter',
        ],
        [
            'name' => 'youtube_profile',
            'title' => 'Youtube',
            'profile_link' => 'https://www.youtube.com/user/IamProgrammerLK',
            'color' => '',
            'icon' => 'dashicons-before dashicons-youtube',
        ],
        [
            'name' => 'linkedin_profile',
            'title' => 'Linkedin',
            'profile_link' => 'https://www.linkedin.com/company/iamprogrammerlk',
            'color' => '',
            'icon' => 'dashicons-before dashicons-linkedin',
        ],
    ],
    
];