<?php

if( !class_exists("Opt_In_ConstantContact") ):

class Opt_In_ConstantContact extends Opt_In_Provider_Abstract  implements  Opt_In_Provider_Interface {

    const ID = "constantcontact";
    const NAME = "ConstantContact";

    const APIKEY = "hhs93vg678hgmkcewc7hmm2s";
    const CONSUMER_SECRET = "SBNfUWY8cB9PMmhXwcUQEREg";
    const REDIRECT_URI = "http://laborin.com.mx/constantcontact.php"; //<-- this must be changed to a WPMU DEV address

    const ACCESS_CODE = "access_code";
    const ACCESS_TOKEN = "access_token";

    protected static $errors;


    static function instance()
    {
        return new self;
    }

    /**
     * Updates api option
     *
     * @param $option_key
     * @param $option_value
     * @return bool
     */
    function update_option($option_key, $option_value)
    {
        return update_site_option(self::ID . "_" . $option_key, $option_value);
    }

    /**
     * Retrieves api option from db
     *
     * @param $option_key
     * @param $default
     * @return mixed
     */
    function get_option($option_key, $default)
    {
        return get_site_option(self::ID . "_" . $option_key, $default);
    }


    function subscribe(Opt_In_Model $optin, array $data)
    {

        $accessToken = $this->get_option(self::ACCESS_TOKEN, false);

        if (!$accessToken)
            return false;



        try {
            $cc_api = new Ctct\ConstantContact(self::APIKEY);
            $contact = new Ctct\Components\Contacts\Contact();
            $contact->addEmail($data['email']);
            $contact->addList($optin->optin_mail_list);
            $contact->first_name = $data['f_name'];
            $contact->last_name = $data['l_name'];
            $returnContact = $cc_api->contactService->addContact($accessToken, $contact);

            self::$errors['success'] = 'success';

        } catch (Ctct\Exceptions\CtctException $e) {
            self::$errors['error'] = $e;
            return false;
        }

        return self::$errors;

    }

    function get_options( $optin_id )
    {

        if( $this->get_option( self::ACCESS_CODE ) !== $this->api_key ){

            $oauth = new Ctct\Auth\CtctOAuth2( self::APIKEY, self::CONSUMER_SECRET, $this->get_redirect_uri( $optin_id ) );
            $accessToken = $oauth->getAccessToken( $this->api_key );
            $accessToken = $accessToken['access_token'];
            $this->update_option( self::ACCESS_CODE, $this->api_key );
            $this->update_option( self::ACCESS_TOKEN, $accessToken );

        }else{

            $accessToken = $this->get_option( self::ACCESS_TOKEN );

        }

        $cc_api = new Ctct\ConstantContact(self::APIKEY);

        $lists_data = $cc_api->listService->getLists( $accessToken );

        $lists = array();
        foreach( $lists_data as $list ){
            $list = (array) $list;
            $lists[ $list['id'] ]['value'] = $list['id'];
            $lists[ $list['id'] ]['label'] = $list['name'];
        }

        $first = count( $lists ) > 0 ? reset( $lists ) : "";
        if( !empty( $first ) )
            $first = $first['value'];

        return array(
            "label" => array(
                "id" => "optin_email_list_label",
                "for" => "optin_email_list",
                "value" => __("Choose Email List:", Opt_In::TEXT_DOMAIN),
                "type" => "label",
            ),
            "choose_email_list" => array(
                "label" => __("Choose Email List:", Opt_In::TEXT_DOMAIN),
                "type" => 'select',
                'name' => "optin_email_list",
                'id' => "optin_email_list",
                "default" => "",
                'options' => $lists,
                'value' => $first,
                'selected' => $first,
            )
        );

    }


    function get_account_options( $optin_id )
    {
        if (version_compare(PHP_VERSION, '5.3', '<')) {
            return array(
                'auth_code_label' => array(
                    "id" => "auth_code_label",
                    "for" => "constant_contact_authorization_url",
                    "value" => sprintf(
                        __('Constant Contact integration requires PHP version 5.3 or higher installed.', Opt_In::TEXT_DOMAIN)
                    ),
                    "type" => "label",
                )
            );
        }

        $oauth = new Ctct\Auth\CtctOAuth2( self::APIKEY, self::CONSUMER_SECRET, $this->get_redirect_uri( $optin_id ) );
        return array(
            'auth_code_label' => array(
                "id" => "auth_code_label",
                "for" => "constant_contact_authorization_url",
                "value" => sprintf(
                    __('Please <a href="%s" onclick="window.onbeforeunload=null;">click here</a> to connect to ConstantContact service. You will be asked to give us access to your ConstantContact account and then redirected back to this screen.', Opt_In::TEXT_DOMAIN),
                    $oauth->getAuthorizationUrl()
                ),
                "type" => "label",
            ),
            "wrapper" => array(
                "id" => "wpoi-get-lists",
                "class" => "block-notification",
                "type" => "wrapper",
                "elements" => array(
                    "consumer_key" => array(
                        "id" => "optin_api_key",
                        "name" => "optin_api_key",
                        "label" => __("Access Code", Opt_In::TEXT_DOMAIN),
                        "type" => "text",
                        "default" => "",
                        "value" => "",
                        "placeholder" => __("Please enter the access code", Opt_In::TEXT_DOMAIN)
                    ),
                    'refresh' => array(
                        "id" => "refresh_constantcontact_lists",
                        "name" => "refresh_constantcontact_lists",
                        "type" => "button",
                        "value" => __("Get Lists", Opt_In::TEXT_DOMAIN),
                        'class' => "wph-button wph-button--filled wph-button--gray optin_refresh_provider_details"
                    ),
                )
            )
        );
    }

    function get_redirect_uri( $optin ){
        $site_url = trailingslashit( get_bloginfo('url') );
        $site_domain = str_replace(array('http://', 'https://'), '', $site_url );

        $redirect_uri_base = '' . self::REDIRECT_URI . '';
        $url = add_query_arg(array(
            'prefix'    => is_ssl() ? 'https' : 'http',
            'domain'    => $site_domain,
            'page'  => 'inc_optin',
            'optin' => $optin
        ), $redirect_uri_base );
        return $url;
    }


    function is_authorized()
    {
        return true;
    }

}
endif;