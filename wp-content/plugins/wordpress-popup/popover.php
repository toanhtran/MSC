<?php
/*
Plugin Name: Hustle
Plugin URI: https://premium.wpmudev.org/project/hustle/
Description: Start collecting email addresses and quickly grow your mailing list with big bold pop-ups, slide-ins, widgets, or in post opt-in forms.
Version: 5.0.1.1
Author: WPMU DEV
Author URI: https://premium.wpmudev.org
WDP ID: 1107020
*/

// +----------------------------------------------------------------------+
// | Copyright Incsub (http://incsub.com/)                                |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

if( version_compare(PHP_VERSION, '5.3.2', ">=") )
    require 'vendor/autoload.php';
else
    require 'vendor/autoload_52.php';

require_once 'lib/wpmu-lib/core.php';
require_once 'opt-in-static.php';
require_once 'assets/shared-ui/plugin-ui.php';
require_once 'inc/providers/opt-in-e-newsletter.php';

if( !class_exists( "Opt_In" ) ):

class Opt_In extends Opt_In_Static{

    const VERSION = "2.0.2";

    const TEXT_DOMAIN = "hustle";

    const VIEWS_FOLDER = "views";

    public static $plugin_base_file;
    public static $plugin_url;
    public static $plugin_path;
    public static $vendor_path;
    public static $template_path;

    protected static $_registered_providers = array();

    protected $_providers = array(
        array(
            "id" => "aweber",
            "name" => "AWeber",
            "file_name" => "opt-in-aweber.php",
            "class_name" => "Opt_In_Aweber"
        ),
       array(
            "id" => "activecampaign",
            "name" => "ActiveCampaign",
            "file_name" => "opt-in-activecampaign.php",
            "class_name" => "Opt_In_Activecampaign"
        ),
        array(
            "id" => "campaignmonitor",
            "name" => "Campaignmonitor" ,
            "file_name" => "opt-in-campaignmonitor.php",
            "class_name" => "Opt_In_Campaignmonitor"
        ),
        array(
            "id" => "mailchimp",
            "name" => "MailChimp",
            "file_name" => "opt-in-mailchimp.php",
            "class_name" => "Opt_In_Mailchimp"
        ),
        array(
            "id" => "constantcontact",
            "name" => "ConstantContact",
            "file_name" => "opt-in-constantcontact-loader.php",
            "class_name" => "Opt_In_ConstantContact"
        ),
		array(
			'id' => 'convertkit',
			'name' => 'ConvertKit',
			'file_name' => 'opt-in-convertkit.php',
			'class_name' => 'Opt_In_ConvertKit',
		),
        array(
            "id" => "getresponse",
            "name" => "GetResponse",
            "file_name" => "opt-in-get-response.php",
            "class_name" => "Opt_In_Get_Response"
        ),
        array(
            "id" => "sendy",
            "name" => "Sendy",
            "file_name" => "opt-in-sendy.php",
            "class_name" => "Opt_In_Sendy"
        ),
        array(
            "id" => "mad_mimi",
            "name" => "Mad Mimi",
            "file_name" => "opt-in-mad-mimi.php",
            "class_name" => "Opt_In_Mad_Mimi"
        ),
        array(
            "id" => "infusionsoft",
            "name" => "Infusionsoft",
            "file_name" => "opt-in-infusion-soft.php",
            "class_name" => "Opt_In_Infusion_Soft"
        ),
    );

    /**
     * @var Opt_In_E_Newsletter $_e_newsletter
     */
    protected static $_e_newsletter;

    /**
     * @var $_email_services Hustle_Email_Services
     */
    private static $_email_services;

    /**
     * Opt_In constructor.
     *
     *
     * @since 1.0.0
     */
    function __construct(){

        self::$plugin_base_file = plugin_basename( __FILE__ );
        self::$plugin_url = plugin_dir_url( self::$plugin_base_file );
        self::$plugin_path = trailingslashit( dirname( __FILE__ ) );
        self::$vendor_path = self::$plugin_path . "vendor/";
        self::$template_path = trailingslashit( dirname( __FILE__ ) ) . 'views/';

        // Register autoloader
        spl_autoload_register( array( $this, 'autoload' ) );

        // Register text domain
        add_action( 'plugins_loaded', array($this, 'load_text_domain')   );

        /**
         * Boot up and instantiate core classes
         */
        $this->_boot();
    }

    /**
     * Sets Hustle_Email_Services instance
     *
     * @param Hustle_Email_Services $email_services
     */
    function set_email_services( Hustle_Email_Services $email_services){
        self::$_email_services = $email_services;
    }

    /**
     * Sets Opt_In_E_Newsletter instance
     *
     * @since 2.0
     * @param $e_newsletter Opt_In_E_Newsletter
     */
    function set_e_newsletter( Opt_In_E_Newsletter $e_newsletter ){
        self::$_e_newsletter = $e_newsletter;
    }

    /**
     * Returns static variable from class instance
     *
     * @since 2.0
     *
     * @param $var_name
     * @return mixed
     */
    public function get_static_var( $var_name ){
        static $static = array();
        if( !isset( $static[  $var_name ] ) ){
            $class = new ReflectionClass( $this );
            $static[  $var_name ]  = $class->getStaticPropertyValue( $var_name );
        }
        return $static[  $var_name ];
    }

    /**
     * Returns constant variable from class instance
     *
     * @since 2.0
     * @param $var_name
     * @param $class_instance
     * @return mixed
     */
    public function get_const_var( $var_name, $class_instance = null ){
        static $const = array();

        if( !isset( $const[ $var_name ] ) ){
            $r = new ReflectionObject( is_null( $class_instance ) ? $this : $class_instance );
            $const[ $var_name  ] = $r->getConstant( $var_name );
        }

        return $const[ $var_name ];
    }

    /**
     * Returns list of optin providers based on their declared classes that implement Opt_In_Provider_Interface
     *
     * @return array
     */
    function get_providers(){
        return self::$_registered_providers;
    }

    /**
     * Returns provider class by name
     *
     * @param $id string provider ID
     * @return bool|Opt_In_Provider_Interface|Opt_In_Provider_Abstract provider class
     *
     * @since 1.0.0
     */
    public static function get_provider_by_id( $id ){
        if('test' == $id ) return false;

        return  self::$_registered_providers !== array() && isset( self::$_registered_providers[$id],  self::$_registered_providers[$id]['class'])  ?  self::$_registered_providers[$id]['class']  : false;
    }
    /**
     * Loads text domain
     *
     * @since 1.0.0
     */
    public function load_text_domain() {
        load_plugin_textdomain( self::TEXT_DOMAIN, false, dirname( plugin_basename( self::$plugin_base_file ) ) . '/languages/' );
    }

    /**
     * Autoloads undefined classes
     *
     * @since 1.0.0
     *
     * @param $class
     * @return bool
     */
    function autoload( $class ) {

        $dirs = array("inc", "inc/meta", "inc/providers", "inc/display-conditions", "inc/custom-content");

        foreach( $dirs as $dir ){
            $filename = self::$plugin_path  . $dir . DIRECTORY_SEPARATOR . str_replace( "_", "-", strtolower( $class ) ) . ".php";
            if ( is_readable( $filename ) ) {
                require_once $filename;
                return true;
            }
        }


        return false;
    }

    /**
     * Boots up the plugin and instantiates core classes
     *
     * @since 1.0.0
     */
    private function _boot(){
        $this->_register_providers();
    }


    /**
     * Scans the providers folders and includes provider classes
     *
     * @since 1.0.0
     */
    private function _register_providers(){

        foreach ( $this->_providers as $provider) {
            $path = dirname(__FILE__) . "/inc/providers/" . $provider['file_name'];
            if ( is_file($path) && is_readable( $path ) ) {
                require_once $path;

                $id = $provider['id'];
                self::$_registered_providers[ $id ]['class'] = $provider['class_name'];
                self::$_registered_providers[ $id ]["name"] = $provider['name'];
                self::$_registered_providers[ $id ]['id'] = $id;
                unset($id);
            }
        }

    }


    /**
     * Renders a view file
     *
     * @param $file
     * @param array $params
     * @param bool|false $return
     * @return string
     */
    public function render( $file, $params = array(), $return = false )
    {
        $params = array_merge( array('self' => $this), $params );
        /**
         * assign $file to a variable which is unlikely to be used by users of the method
         */
        $Opt_In_To_Be_File_Name = $file;
        extract( $params, EXTR_OVERWRITE );

        if($return){
            ob_start();
        }


        $template_file = trailingslashit( self::$plugin_path ) . self::VIEWS_FOLDER . "/" . $Opt_In_To_Be_File_Name . '.php';
        if( file_exists( $template_file ) ){
            include $template_file;
        }else{
			$template_path = self::$template_path . $Opt_In_To_Be_File_Name . '.php';

			if ( file_exists( $template_path ) ) include( $template_path );
        }

        if($return){
            return ob_get_clean();
        }

        if( !empty( $params ) )
        {
            foreach( $params as $param )
            {
                unset( $param );
            }
        }
    }

    /**
     * Renders a view file with static call
     *
     * @param $file
     * @param array $params
     * @param bool|false $return
     * @return string
     */
    public static function static_render( $file, $params = array(), $return = false )
    {
        $params = array_merge( $params );
        /**
         * assign $file to a variable which is unlikely to be used by users of the method
         */
        $Opt_In_To_Be_File_Name = $file;
        extract( $params, EXTR_OVERWRITE );

        if($return){
            ob_start();
        }


        $template_file = trailingslashit( Opt_In::$plugin_path ) . Opt_In::VIEWS_FOLDER . "/" . $Opt_In_To_Be_File_Name . '.php';
        if( file_exists( $template_file ) ){
            include $template_file;
        }else{
            include( Opt_In::$template_path . $Opt_In_To_Be_File_Name . '.php' );
        }

        if($return){
            return ob_get_clean();
        }

        if( !empty( $params ) )
        {
            foreach( $params as $param )
            {
                unset( $param );
            }
        }
    }


    protected function get_palette( $palette_name ){
        $palette_name = ucwords( str_replace("_", " ", $palette_name) );

        $palettes = $this->get_palettes();
        return $palettes[ $palette_name ];
    }


    function current_page_type() {
        /**
         * @var $wp_query WP_Query
         */
        global $wp_query, $post;
        $type = 'notfound';

        if ( $wp_query->is_page ) {
            $type = is_front_page() ? 'front' : 'page';
        } elseif ( $wp_query->is_home ) {
            $type = 'home';
        } elseif ( $wp_query->is_single  ) {
            $type = ( $wp_query->is_attachment ) ? 'attachment' : get_post_type();
        } elseif ( $wp_query->is_category ) {
            $type = 'category';
        } elseif ( $wp_query->is_tag ) {
            $type = 'tag';
        } elseif ( $wp_query->is_tax ) {
            $type = 'tax';
        } elseif ( $wp_query->is_archive ) {
            if ( $wp_query->is_day ) {
                $type = 'day';
            } elseif ( $wp_query->is_month ) {
                $type = 'month';
            } elseif ( $wp_query->is_year ) {
                $type = 'year';
            } elseif ( $wp_query->is_author ) {
                $type = 'author';
            } else {
                $type = 'archive';
            }
        } elseif ( $wp_query->is_search ) {
            $type = 'search';
        } elseif ( $wp_query->is_404 ) {
            $type = 'notfound';
        }

        return $type;
    }

    /**
     * Prepares the custom css string
     *
     * @since 1.0
     * @param $cssString
     * @param $prefix
     * @param bool|false $as_array
     * @param bool|true $separate_prefix
     * @return array|string
     */
    public static function prepare_css( $cssString, $prefix, $as_array = false, $separate_prefix = true ) {
        $css_array = array(); // master array to hold all values
        $elements = explode('}', $cssString);
        $prepared = "";
        foreach ($elements as $element) {

            // get the name of the CSS element
            $a_name = explode('{', $element);
            $name = $a_name[0];
            // get all the key:value pair styles
            $a_styles = explode(';', $element);
            // remove element name from first property element
            $a_styles[0] = str_replace($name . '{', '', $a_styles[0]);
            $names = explode(',', $name);
            foreach ($names as $name){
                $prepared .= ( $prefix . ( $separate_prefix ? " " : "" ) . trim($name).',' );
            }
            $prepared = trim($prepared, ",");
            $prepared .= "{";
            // loop through each style and split apart the key from the value
            $count = count($a_styles);
            for ($a=0;$a<$count;$a++) {
                if (trim($a_styles[$a]) != '') {
                    $a_key_value = explode(':', $a_styles[$a]);
                    // build the master css array
                    $css_array[$name][$a_key_value[0]] = $a_key_value[1];
                    $prepared .= ($a_key_value[0] . ": " . $a_key_value[1]);// . strpos($a_key_value[1], "!important") === false ? " !important;": ";";
                    if( strpos($a_key_value[1], "!important") === false ) $prepared .= " !important";
                    $prepared .= ";";
                }
            }
            $prepared .= "}";
        }

        return $as_array ? $css_array : $prepared;
    }

    /**
     * Returns constant value from the provided $class_name
     * this method is to provide compatibility to php versions less than 5.3
     *
     * @param $class_name
     * @param $const_name
     * @return mixed
     */
    public static function get_const($class_name, $const_name ){
        $reflection = new ReflectionClass($class_name);
        return $reflection->getConstant($const_name);
    }

    /**
     *
     *
     * @param $provider_obj
     * @return Opt_In_Provider_Abstract
     */
    public static function provider_instance( $provider_obj ){
        return call_user_func( array( $provider_obj, "instance" ) );
    }


    public static function render_attributes( $htmlOptions, $echo = true ){

        $specialAttributes = array(
            'async' => 1,
            'autofocus' => 1,
            'autoplay' => 1,
            'checked' => 1,
            'controls' => 1,
            'declare' => 1,
            'default' => 1,
            'defer' => 1,
            'disabled' => 1,
            'formnovalidate' => 1,
            'hidden' => 1,
            'ismap' => 1,
            'loop'=> 1,
            'multiple' => 1,
            'muted' => 1,
            'nohref' => 1,
            'noresize' => 1,
            'novalidate' => 1,
            'open' => 1,
            'readonly' => 1,
            'required' => 1,
            'reversed' => 1,
            'scoped' => 1,
            'seamless' => 1,
            'selected' => 1,
            'typemustmatch' => 1,
        );
        if( $htmlOptions === array() )
            return '';

        $html='';
        if( isset($htmlOptions['encode']))
        {
            $raw = !$htmlOptions['encode'];
            unset( $htmlOptions['encode'] );
        }
        else
            $raw=false;
        foreach( $htmlOptions as $name => $value )
        {
            if(isset($specialAttributes[$name]))
            {
                if( $value )
                {
                    $html .= ' ' . $name;
                    $html .= '="' . $name . '"';
                }
            }
            elseif( $value!==null )
                $html .= ' ' . $name . '="' . ($raw ? $value : esc_attr($value) ) . '"';
        }

        if( $echo )
            echo $html;
        else
            return $html;
    }

    /**
     * Returns instance of Opt_In_E_Newsletter
     *
     * @since 1.1.1
     * @return Opt_In_E_Newsletter
     */
    public function get_e_newsletter(){
        return self::$_e_newsletter;
    }

    /**
     *
     * since 2.0
     * @return Hustle_Email_Services
     */
    public static function get_email_services(){
        return self::$_email_services;
    }

    public static function is_free(){
        return false;
    }
}
endif;

$hustle = new Opt_In();
$optin_db = new Opt_In_Db();

$email_services = new Hustle_Email_Services();
$enews_letter = new Opt_In_E_Newsletter();
$hustle->set_e_newsletter( $enews_letter );
$hustle->set_email_services( $email_services );

$optin_front = new Opt_In_Front( $hustle );

// Legacy Popups
$legacy_popups = new Hustle_Legacy_Popups();

if( is_admin() ){
    $optin_admin = new Opt_In_Admin( $hustle, $email_services  );
    new Opt_In_Admin_Ajax( $hustle, $optin_admin );

	$popup_update = new Hustle_Legacy_Popups_Admin( $hustle );

    $hustle_dashboard_admin = new Hustle_Dashboard_Admin( $email_services );

    $hustle_settings_admin = new Hustle_Settings_Admin( $hustle, $email_services );
    new Hustle_Settings_Admin_Ajax($hustle, $hustle_settings_admin );

    $cc_admin = new Hustle_Custom_Content_Admin( $legacy_popups );
    new Hustle_Custom_Content_Admin_Ajax( $hustle, $cc_admin );
}else{
    $cc_front = new Hustle_Custom_Content_Front( $hustle );
}
new Opt_In_Front_Ajax( $hustle );
$cc_front_ajax = new Hustle_Custom_Content_Front_Ajax();

//Load dashboard notice
if ( file_exists( Opt_In::$plugin_path . 'lib/wpmudev-dashboard/wpmudev-dash-notification.php' ) ) {
    global $wpmudev_notices;
    $wpmudev_notices[] = array(
        'id' => 1107020,
        'name' => 'Hustle',
        'screens' => array(
            'toplevel_page_inc_optins',
            'optin-pro_page_inc_optin'
        ),
    );
    require_once Opt_In::$plugin_path . 'lib/wpmudev-dashboard/wpmudev-dash-notification.php';
}

if( is_admin() && Opt_In_Utils::_is_free() ) {
    require_once Opt_In::$plugin_path . 'lib/free-dashboard/module.php';
}
