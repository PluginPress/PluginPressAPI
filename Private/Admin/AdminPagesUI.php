<?php

namespace IamProgrammerLK\PluginPressAPI\Admin;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

trait AdminPagesUI
{

    // protected $currentPage;

    public function renderAdminPageUI()
    {
        $currentPage = $this->getCurrentPage();
        $this->renderPageHeaderSection( $currentPage );
        if( ! empty( $this->tabs ) )
        {
            $pageTabs = $this->getTabs( $currentPage );
            $activeTab = $this->getActiveTab( $pageTabs );
            if( $activeTab )
            {
                $this->renderTabs( $pageTabs );
                echo '<form method="post" action="options.php">';
                settings_fields( $activeTab[ 'tab_slug' ] );
                $this->renderSectionsAndFields( $currentPage, $activeTab );
                submit_button();
                echo '</form>';
            }
        }
        else
        {
            //TODO render default setting section and fields
            // $this->renderSections();
        }
        $this->renderPageFooterSection( $currentPage );
    }

    public function renderPageHeaderSection( $currentPage ) : void
    {
        //$currentPage[ 'icon_url' ] = ( isset( $currentPage[ 'icon_url' ] ) ) ? $currentPage[ 'icon_url' ] : '';
        // TODO:: Implement the icon section before the title
        echo '<div class="wrap">';
        echo '<h1>' . $this->pluginOptions->get( 'name' ) . ' <small>(v' . $this->pluginOptions->get( 'version' ) . ')</small> | ' . $currentPage[ 'page_title' ] . '</h1>';
        if( isset( $currentPage[ 'page_description' ] ) && ( $currentPage[ 'page_description' ] != null || $currentPage[ 'page_description' ] != '' ) )
        {
            echo '<p>' . $currentPage[ 'page_description' ] . '</p>';
        }
    }

    public function renderPageFooterSection( $currentPage ) : void
    {
        if( isset( $currentPage[ 'page_footer' ] ) && ( $currentPage[ 'page_footer' ] != null || $currentPage[ 'page_footer' ] != '' ) )
        {
            echo '<div class="page-footer">' . $currentPage[ 'page_footer' ] . '</div>';
        }
        else
        {
            // TODO:: Create default footer area for admin options page
            // echo '<div class="page-footer"><a href="' . $this->pluginOptions->get( 'author_url' ) . '" > ' . $this->pluginOptions->get( 'author_name' ) . '</a></div>';
        }
        echo '</div>';
    }

}

