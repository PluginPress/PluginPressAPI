<?php

namespace IamProgrammerLK\TestPlugin;

use IamProgrammerLK\PluginPressAPI\Admin\AdminPages;

// If this file is called directly, abort. for the security purpose.
if ( ! defined( 'WPINC' ) )
{
    die;
}

class DoAdminPages
{

    protected $pluginOptions;

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function init()
    {
        $this->createOptionPage01();
        $this->createOptionPage02();
    }

    private function createOptionPage01()
    {
        $optionPage01 = new AdminPages( $this->pluginOptions );
        $optionPage01->addOptionPages(
            [
                'page_title' => 'Test Options Page 01',
                'menu_title' => 'Test Options Page 01',
                'page_description' => 'This is the test option page 01 description',
                'capabilities' => 'manage_options' ,
                'menu_slug' => 'test_options_page_01',
            ]
        );
        $optionPage01->addTabs(
            [
                'parent_page_slug' => 'test_options_page_01',
                'tab_slug' => 'test_tab_01',
                'tab_title' => 'Test Tab 01',
                'tab_description' => 'Test tab 01 description',
                // 'tab_before_icon' => 'dashicons dashicons-admin-generic',
                // 'tab_after_icon' => 'dashicons dashicons-admin-generic',
                // 'tab_default' => true,
            ]
        );
        $optionPage01->addSections(
            [
                'parent_page_slug' => 'test_options_page_01',
                'parent_tab_slug' => 'test_tab_01',
                'section_slug' => 'test_section_01',
                'section_title' => 'Test Section 01 Title',
                'section_description' => 'This is the description for the test section 01',
            ]
        );
        $optionPage01->addFields(
            [
                'parent_page_slug' => 'test_options_page_01',                               // Required
                'parent_tab_slug' => 'test_tab_01',                                         // Optional
                'parent_section_slug' => 'test_section_01',                                 // Optional
                'option_slug' => 'test_option_01',                                          // Required
                'option_title' => 'Test Option 01',                                         // Required
                'option_data_type' => 'string',                                             // Optional
                'option_type' => 'text',                                                    // Optional 
                'option_default_value' => false,                                            // Optional
                'option_description' => 'This is the description for the test option 01',   // Optional
                'option_sanitize_callback' => '',                                           // Optional
                'option_show_in_rest' => false,                                             // Optional
                'option_ui' => '',                                                          // Optional 
                'option_class' => 'test-css-class',                                         // Optional 
                'option_placeholder' => 'Test option placeholder',                          // Optional 
            ]
        );
        $optionPage01->init();
    }

    private function createOptionPage02()
    {
        $optionPage02 = new AdminPages( $this->pluginOptions );
        $optionPage02->addOptionPages(
            [
                [
                    'page_title' => 'Test Options Page 02',
                    'menu_title' => 'Test Options Page 02',
                    'page_description' => 'This is the test option page 02',
                    'capabilities' => 'manage_options' ,
                    'menu_slug' => 'test_options_page_02',
                ],
                [
                    'page_title' => 'Test Options Page 03',
                    'menu_title' => 'Test Options Page 03',
                    'page_description' => 'This is the test option page 03',
                    'capabilities' => 'manage_options' ,
                    'menu_slug' => 'test_options_page_03',
                ],
                // more pages
            ]
        );
        $optionPage02->addTabs(
            [
                [
                    'parent_page_slug' => 'test_options_page_02',
                    'tab_slug' => 'test_tab_01',
                    'tab_title' => 'Page 02 Test Tab 01',
                    'tab_description' => 'Test Tab description 01',
                    // 'tab_before_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_after_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_default' => true,
                ],
                [
                    'parent_page_slug' => 'test_options_page_02',
                    'tab_slug' => 'test_tab_02',
                    'tab_title' => 'Page 02 Test Tab 02',
                    'tab_description' => 'Test Tab description 02',
                    // 'tab_before_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_after_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_default' => true,
                ],
                [
                    'parent_page_slug' => 'test_options_page_03',
                    'tab_slug' => 'test_tab_01',
                    'tab_title' => 'Page 03 Test Tab 01',
                    'tab_description' => 'Test Tab description 01',
                    // 'tab_before_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_after_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_default' => true,
                ],
                [
                    'parent_page_slug' => 'test_options_page_03',
                    'tab_slug' => 'test_tab_02',
                    'tab_title' => 'Page 03 Test Tab 02',
                    'tab_description' => 'Test Tab description 02',
                    // 'tab_before_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_after_icon' => 'dashicons dashicons-admin-generic',
                    // 'tab_default' => true,
                ],
                // more tabs

            ]
        );
        $optionPage02->addSections(
            [
                [
                    'parent_page_slug' => 'test_options_page_02',
                    'parent_tab_slug' => 'test_tab_01',
                    'section_slug' => 'test_section_01',
                    'section_title' => 'Test Section Title 01',
                    'section_description' => 'This is the description for the test section 01',
                ],
                [
                    'parent_page_slug' => 'test_options_page_02',
                    'parent_tab_slug' => 'test_tab_02',
                    'section_slug' => 'test_section_02',
                    'section_title' => 'Test Section Title 02',
                    'section_description' => 'This is the description for the test section 02',
                ],
                [
                    'parent_page_slug' => 'test_options_page_03',
                    'parent_tab_slug' => 'test_tab_02',
                    'section_slug' => 'test_section_03',
                    'section_title' => 'Test Section Title 03',
                    'section_description' => 'This is the description for the test section 03',
                ],
                // more sections

            ]
        );
        $optionPage02->addFields(
            [
                [
                    'parent_page_slug' => 'test_options_page_02',                               // Required
                    'parent_tab_slug' => 'test_tab_01',                                         // Optional
                    'parent_section_slug' => 'test_section_01',                              // Optional
                    'option_slug' => 'test_option_01',                                          // Required
                    'option_title' => 'Test Option 01',                                         // Required
                    'option_data_type' => 'string',                                             // Optional
                    'option_type' => 'text',                                                    // Optional 
                    // 'option_default_value' => false,                                            // Optional
                    'option_description' => 'This is the description for the test option 01',   // Optional
                    // 'option_sanitize_callback' => '',                                           // Optional
                    // 'option_show_in_rest' => false,                                             // Optional
                    // 'option_ui' => '',                                                          // Optional 
                    // 'option_class' => 'test-css-class',                                         // Optional 
                    // 'option_placeholder' => 'Test option placeholder',                          // Optional 
                ],
                [
                    'parent_page_slug' => 'test_options_page_02',                               // Required
                    'parent_tab_slug' => 'test_tab_01',                                         // Optional
                    'parent_section_slug' => 'test_section_02',                              // Optional
                    'option_slug' => 'test_option_02',                                          // Required
                    'option_title' => 'Test Option 02',                                         // Required
                    'option_data_type' => 'string',                                             // Optional
                    'option_type' => 'text',                                                    // Optional 
                    // 'option_default_value' => false,                                            // Optional
                    'option_description' => 'This is the description for the test option 02',   // Optional
                    // 'option_sanitize_callback' => '',                                           // Optional
                    // 'option_show_in_rest' => false,                                             // Optional
                    // 'option_ui' => '',                                                          // Optional 
                    // 'option_class' => 'test-css-class',                                         // Optional 
                    // 'option_placeholder' => 'Test option placeholder',                          // Optional 
                ],
                [
                    'parent_page_slug' => 'test_options_page_03',                               // Required
                    'parent_tab_slug' => 'test_tab_02',                                         // Optional
                    'parent_section_slug' => 'test_section_03',                              // Optional
                    'option_slug' => 'test_option_03',                                          // Required
                    'option_title' => 'Test Option 03',                                         // Required
                    'option_data_type' => 'string',                                             // Optional
                    'option_type' => 'text',                                                    // Optional 
                    // 'option_default_value' => false,                                            // Optional
                    'option_description' => 'This is the description for the test option 03',   // Optional
                    // 'option_sanitize_callback' => '',                                           // Optional
                    // 'option_show_in_rest' => false,                                             // Optional
                    // 'option_ui' => '',                                                          // Optional 
                    // 'option_class' => 'test-css-class',                                         // Optional 
                    // 'option_placeholder' => 'Test option placeholder',                          // Optional 
                ]
            ]
        );
        $optionPage02->init();
    }



}