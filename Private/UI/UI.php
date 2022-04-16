<?php


namespace IamprogrammerLK\PluginPressAPI\UI;

use IamprogrammerLK\PluginPressAPI\PluginOptions\PluginOptions;

// If this file is called directly, abort. for the security purpose.
if( ! defined( 'WPINC' ) )
{
    die;
}

class UI
{

    private $plugin_options;

    public function __construct( PluginOptions $plugin_options )
    {
        $this->plugin_options = $plugin_options;
    }
    public static function get_html( $args )
    {
        $html = [];

        // HTML input tag attributes - https://www.w3schools.com/tags/tag_input.asp
        // $html[ 'accept' ] = 'accept="' . $args[ 'option_accept' ] . '"';
        // $html[ 'alt' ] = 'alt="' . $args[ 'option_alt' ] . '"';
        // $html[ 'autocomplete' ] = 'autocomplete="' . $args[ 'option_autocomplete' ] . '"';
        // $html[ 'autofocus' ] = ( true == $args[ 'option_autofocus' ] ) ? 'autofocus' : '';
        // $html[ 'dir_name' ] = 'dirname="' . $args[ 'option_dir_name' ] . '"';
        $html[ 'default' ] = $args[ 'option_default_value' ];
        if( $args[ 'option_disabled' ] )
        {
            $html[ 'disabled' ] = 'disabled="true"';
            $html[ 'value' ] = $args[ 'option_default_value' ];
            $html[ 'checked' ] = ( true === $args[ 'option_default_value' ]  ) ?  'checked' : '';
        }
        else
        {
            $html[ 'disabled' ] = '';
            $html[ 'value' ] = get_option( $args[ 'option_slug' ] );
            $html[ 'checked' ] = ( get_option( $args[ 'option_slug' ] ) ) ? 'checked' : '';
        }
        // $html[ 'form' ] = 'form="' . $args[ 'option_form_id' ] . '"';
        // $html[ 'form_action' ] = 'formaction="' . $args[ 'option_form_action' ] . '"';
        // $html[ 'form_enc_type' ] = 'formenctype="' . $args[ 'option_form_enc_type' ] . '"';
        // $html[ 'form_method' ] = 'formmethod="' . $args[ 'option_form_method' ] . '"';
        // $html[ 'form_no_validate' ] = 'formnovalidate="' . $args[ 'option_form_no_validate' ] . '"';
        // $html[ 'form_target' ] = 'formtarget="' . $args[ 'option_form_target' ] . '"';
        // $html[ 'height' ] = 'height="' . $args[ 'option_height' ] . '"';
        $html[ 'list' ] = ( isset( $args[ 'option_list' ] ) ) ? $args[ 'option_list' ] : [];
        $html[ 'max' ] = 'max="' . $args[ 'option_max' ] . '"';
        $html[ 'max_length' ] = 'maxlength="' . $args[ 'option_max_length' ] . '"';
        $html[ 'min' ] = 'min="' . $args[ 'option_min' ] . '"';
        $html[ 'min_length' ] = 'minlength="' . $args[ 'option_min_length' ] . '"';
        // $html[ 'multiple' ] = ( true == $args[ 'option_multiple' ] ) ? 'multiple' : '';
        $html[ 'name' ] = 'name="' . $args[ 'option_slug' ] . '"';
        // $html[ 'pattern' ] = 'pattern="' . $args[ 'option_pattern' ] . '"';
        $html[ 'placeholder' ] = 'placeholder="' . $args[ 'option_placeholder' ] . '"';
        $html[ 'readonly' ] = ( true == $args[ 'option_readonly' ] ) ? 'readonly' : '';
        $html[ 'required' ] = ( true == $args[ 'option_required' ] ) ? 'required' : '';
        // $html[ 'size' ] = 'size="' . $args[ 'option_size' ] . '"';
        // $html[ 'src' ] = 'src="' . $args[ 'option_src' ] . '"';
        // $html[ 'step' ] = 'step="' . $args[ 'option_step' ] . '"';
        $html[ 'type' ] = 'type="' . $args[ 'option_type' ] . '"';
        // $html[ 'width' ] = 'width="' . $args[ 'option_width' ] . '"';

        // HTML Global Attributes - https://www.w3schools.com/tags/ref_standardattributes.asp
        // $html[ 'access_key' ] = 'accesskey="' . $args[ 'option_access_key' ] . '"';
        $html[ 'class' ] = 'class="' . $args[ 'option_class' ] . '"';
        $html[ 'content_editable' ] = 'contenteditable="' . $args[ 'option_content_editable' ] . '"';
        // $html[ 'data' ] = $args[ 'option_data' ];
        // $html[ 'text_direction' ] = 'dir="' . $args[ 'option_text_direction' ] . '"';
        // $html[ 'draggable' ] = 'draggable="' . $args[ 'option_draggable' ] . '"';
        $html[ 'hidden' ] = ( true == $args[ 'option_hidden' ] ) ? 'hidden' : '';
        $html[ 'id' ] = 'id="' . $args[ 'option_slug' ] . '"';
        // $html[ 'lang' ] = 'lang="' . $args[ 'option_lang' ] . '"';
        // $html[ 'spell_check' ] = 'spellcheck="' . $args[ 'option_spell_check' ] . '"';
        $html[ 'style' ] = 'style="' . $args[ 'option_style' ] . '"';
        // $html[ 'tab_index' ] = 'tabindex="' . $args[ 'option_tab_index' ] . '"';
        // $html[ 'title' ] = 'title="' . $args[ 'option_title' ] . '"';
        // $html[ 'translate' ] = 'translate="' . $args[ 'option_translate' ] . '"';
        return $html; 
    }

    public static function get( $args )
    {
        // TODO: echo deferent elements baced on the callback setting (text input/text area/checkbox/radio button/ext)
        if( ! isset( $args[ 'option_type' ] ) )
        {
            return;
        }
        switch( $args[ 'option_type' ] )
        {
            case 'text':
                return self::text( $args );
                break;
            case 'number':
                return self::number( $args );
                break;
            case 'checkbox':
                return self::checkbox( $args );
                break;
            case 'textarea':
                return self::textarea( $args );
                break;
            case 'select':
                return self::select( $args );
                break;
        }
    }

    public static function text( $args )
    {
        $html = self::get_html( $args );
        $html = '<input ' .
            $html[ 'disabled' ] . ' ' .
            'value="' . esc_html( $html[ 'value' ] ) . '" ' .
            $html[ 'max' ] . ' ' .
            $html[ 'max_length' ] . ' ' .
            $html[ 'min' ] . ' ' .
            $html[ 'min_length' ] . ' ' .
            $html[ 'name' ] . ' ' .
            $html[ 'placeholder' ] . ' ' .
            $html[ 'readonly' ] . ' ' .
            $html[ 'required' ] . ' ' .
            $html[ 'type' ] . ' ' .
            $html[ 'class' ] . ' ' .
            $html[ 'content_editable' ] . ' ' .
            $html[ 'hidden' ] . ' ' .
            $html[ 'id' ] . ' ' .
            $html[ 'style' ] . ' ' .
            '/>';
        return $html;
    }

    public static function number( $args )
    {
        $html = self::get_html( $args );
        $html = '<input ' .
            $html[ 'disabled' ] . ' ' .
            'value="' . esc_html( $html[ 'value' ] ) . '" ' .
            $html[ 'max' ] . ' ' .
            $html[ 'min' ] . ' ' .
            $html[ 'name' ] . ' ' .
            $html[ 'readonly' ] . ' ' .
            $html[ 'required' ] . ' ' .
            $html[ 'type' ] . ' ' .
            $html[ 'class' ] . ' ' .
            $html[ 'content_editable' ] . ' ' .
            $html[ 'hidden' ] . ' ' .
            $html[ 'id' ] . ' ' .
            $html[ 'style' ] . ' ' .
            '/>';
        return $html;
    }

    public static function checkbox( $args )
    {
        $html = self::get_html( $args );
        $html = '<input ' .
            $html[ 'checked' ] . ' ' .
            $html[ 'disabled' ] . ' ' .
            $html[ 'name' ] . ' ' .
            $html[ 'required' ] . ' ' .
            $html[ 'type' ] . ' ' .
            $html[ 'class' ] . ' ' .
            $html[ 'hidden' ] . ' ' .
            $html[ 'id' ] . ' ' .
            $html[ 'style' ] . ' ' .
            '/>';
        return $html;
    }

    public static function textarea( $args )
    {
        $html = self::get_html( $args );
        $html = '<textarea ' .
            // $html[ 'autofocus' ] . ' ' .
            // $html[ 'cols' ] . ' ' .
            // $html[ 'dir_name' ] . ' ' .
            $html[ 'disabled' ] . ' ' .
            // $html[ 'form' ] . ' ' .
            $html[ 'max_length' ] . ' ' .
            $html[ 'name' ] . ' ' .
            $html[ 'placeholder' ] . ' ' .
            $html[ 'readonly' ] . ' ' .
            $html[ 'required' ] . ' ' .
            // $html[ 'rows' ] . ' ' .
            // $html[ 'wrap' ] . ' ' .
            $html[ 'class' ] . ' ' .
            $html[ 'hidden' ] . ' ' .
            $html[ 'id' ] . ' ' .
            $html[ 'style' ] . ' ' .
            '>' . 
            esc_html( $html[ 'value' ] ) .
            '</textarea>';
        return $html;
    }

    public static function select( $args )
    {
        $html = self::get_html( $args );
        $options = '';
        foreach( $html[ 'list' ] as $value => $label )
        {
            $selected = ( $html[ 'value' ] == $value ) ? 'selected': '';
            $options .= '<option value="' . $value . '" ' . $selected . ' >' . $label . '</option>';
        }
        $html = '<select ' .
            // $html[ 'autofocus' ] . ' ' .
            // $html[ 'cols' ] . ' ' .
            // $html[ 'dir_name' ] . ' ' .
            $html[ 'disabled' ] . ' ' .
            // $html[ 'form' ] . ' ' .
            $html[ 'max_length' ] . ' ' .
            $html[ 'name' ] . ' ' .
            $html[ 'placeholder' ] . ' ' .
            $html[ 'readonly' ] . ' ' .
            $html[ 'required' ] . ' ' .
            // $html[ 'rows' ] . ' ' .
            // $html[ 'wrap' ] . ' ' .
            $html[ 'class' ] . ' ' .
            $html[ 'hidden' ] . ' ' .
            $html[ 'id' ] . ' ' .
            $html[ 'style' ] . ' ' .
            '>' . 
            $options .
            '</select >';
        return $html;
    }
}
