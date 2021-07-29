<?php

namespace IamProgrammerLK\Tests;

// If this file is called directly, abort. for the security purpose.
if ( ! defined( 'WPINC' ) )
{
    die;
}

class Tests
{

    protected $pluginOptions;

    public function __construct( object $pluginOptions )
    {
        $this->pluginOptions = $pluginOptions;
    }

    public function init()
    {
        echo '<pre> '; var_dump( $this->pluginOptions->get( 'namespace' ) ); echo ' </pre>';
        $this->pluginOptions->set( 'namespace', 'updated' );
    }

}