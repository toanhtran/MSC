<?php

class Opt_In_Front_Ajax {

    private $_hustle;

    function __construct( Opt_In $hustle ){
        $this->_hustle = $hustle;
        // When optin is viewed
        add_action("wp_ajax_inc_opt_optin_viewed", array( $this, "optin_viewed" ));
        add_action("wp_ajax_nopriv_inc_opt_optin_viewed", array( $this, "optin_viewed" ));

        // When optin form is submitted
        add_action("wp_ajax_inc_opt_submit_opt_in", array( $this, "submit_optin" ));
        add_action("wp_ajax_nopriv_inc_opt_submit_opt_in", array( $this, "submit_optin" ));
    }


    function submit_optin(){
        $data = $_POST['data'];
        parse_str( $data['form'], $form_data );

        if( !is_email( $form_data['inc_optin_email'] ) )
            wp_send_json_error( __("Invalid email address", Opt_In::TEXT_DOMAIN) );


        $subscribe_data = array_merge( $form_data, array(
                "email" => $form_data['inc_optin_email']
            )
        );

        $e_newsletter_data = array();
        $e_newsletter_data['member_email'] = $subscribe_data['email'];

        if( isset( $form_data['inc_optin_first_name'] ) )
            $e_newsletter_data['member_fname'] = $subscribe_data['f_name'] = $form_data['inc_optin_first_name'];

        if( isset( $form_data['inc_optin_last_name'] ) )
            $e_newsletter_data['member_lname'] = $subscribe_data['l_name'] = $form_data['inc_optin_last_name'];



        $optin = Opt_In_Model::instance()->get( $data['optin_id'] );

        $optin_type = $data["type"];
        $api_result = false;
        $local_save = false;

        if( $this->_hustle->get_e_newsletter()->is_plugin_active() && $optin->sync_with_e_newsletter ){
            $this->_hustle->get_e_newsletter()->subscribe( $e_newsletter_data, $optin->get_e_newsletter_groups() );
        }


        if( $optin->save_to_collection && !$optin->test_mode ){ // Save to local collection
            $local_subscription_data = array(
                "email" =>  $subscribe_data['email'] ,
                "f_name" => isset( $subscribe_data['f_name'] ) ? $subscribe_data['f_name'] : "",
                "l_name" => isset( $subscribe_data['l_name'] ) ? $subscribe_data['l_name'] : "",
                'optin_type' => $optin_type,
                "time" => current_time("timestamp")
            );

            $local_save = $optin->add_local_subscription( $local_subscription_data );
        }

        $provider = false;
        if( $optin->optin_provider ){

            $provider = Opt_In::get_provider_by_id( $optin->optin_provider );
            $provider = Opt_In::provider_instance( $provider );

            if( !is_subclass_of( $provider, "Opt_In_Provider_Abstract") && !$optin->test_mode )
               wp_send_json_error( __("Invalid provider", Opt_In::TEXT_DOMAIN) );
        }


        if( $provider )
            $api_result = $provider->subscribe( $optin, $subscribe_data );

        if( ( $api_result && !is_wp_error( $api_result ) ) || ( $local_save && !is_wp_error( $local_save ) )  ){

            $optin->log_conversion( array(
                'page_type' => $data['page_type'],
                'page_id'   => $data['page_id'],
                'optin_id' => $optin->id
            ), $optin_type );

            $message = $api_result ? $api_result : $local_save;
            wp_send_json_success( $message );
        }

        $collected_errs_messages = array();
        if( is_wp_error( $api_result )  )
            $collected_errs_messages = $api_result->get_error_messages();

        if( is_wp_error( $local_save )  ) {
            $collected_errs_messages = array_merge( $collected_errs_messages, $local_save->get_error_messages() );
		}

        if( $collected_errs_messages !== array()  ){
            wp_send_json_error( $collected_errs_messages);
        }

        wp_send_json_error( $api_result );
    }

    function optin_viewed(){
        $data = $_REQUEST['data'];

        $optin_id = is_array( $data ) ?  $data['optin_id'] : null;
        $optin_type = is_array( $data ) ?  $data['type'] : null;

        if( empty( $optin_id ) )
            wp_send_json_error( __("Invalid Request: Opt-In id invalid") );

        $optin = Opt_In_Model::instance()->get( $optin_id );

         $res = $optin->log_view( array(
            'page_type' => $data['page_type'],
            'page_id'   => $data['page_id'],
            'optin_id' => $optin_id,
             'uri' => $data['uri']
        ), $optin_type );

        if( is_wp_error( $res ) || empty( $data ) )
            wp_send_json_error( __("Error saving stats") );
        else
            wp_send_json_success( __("Stats Successfully saved") );

    }


} 