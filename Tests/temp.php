
<?php

class Settings
{

	public $script_path;
	public $enqueues = array();
	public $enqueue_on_pages = array();


	public function register()
	{
		if ( !empty( $this->enqueues ) )
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Dinamically enqueue styles and scripts in admin area
	 *
	 * @param  array  $scripts file paths or wp related keywords of embedded files
	 * @param  array  $pages    pages id where to load scripts
	 */
	public function admin_enqueue( $scripts = array(), $pages = array() )
	{
		if ( empty( $scripts ) )
			return;

		$i = 0;
		foreach ( $scripts as $key => $value ) :
			foreach ( $value as $val ):
				$this->enqueues[ $i ] = $this->enqueue_script( $val, $key );
				$i++;
			endforeach;
		endforeach;

		if ( !empty( $pages ) ) :
			$this->enqueue_on_pages = $pages;
		endif;

		return $this;
	}

	/**
	 * Call the right WP functions based on the file or string passed
	 *
	 * @param  array $script  file path or wp related keyword of embedded file
	 * @param  var $type      style | script
	 * @return variable functions
	 */
	private function enqueue_script( $script, $type ) {
		if ( $script === 'media_uploader' )
			return 'wp_enqueue_media';

		return ( $type === 'style' ) ? array( 'wp_enqueue_style' => $script ) : array( 'wp_enqueue_script' => $script );
	}

	/**
	 * Print the methods to be called by the admin_enqueue_scripts hook
	 *
	 * @param  var $hook      page id or filename passed by admin_enqueue_scripts
	 */
	public function admin_scripts( $hook )
	{
		// dd( $hook );
		$this->enqueue_on_pages = ( !empty( $this->enqueue_on_pages ) ) ? $this->enqueue_on_pages : array( $hook );

		if ( in_array( $hook, $this->enqueue_on_pages ) ) :
			foreach ( $this->enqueues as $enqueue ) :
				if ( $enqueue === 'wp_enqueue_media' ) :
					$enqueue();
				else :
					foreach( $enqueue as $key => $val ) {
						$key($val, $val);
					}
				endif;
			endforeach;
		endif;
	}




}