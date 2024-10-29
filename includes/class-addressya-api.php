<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_API {

    /* const API_CLIENT_ID         = 'kJyaClkM91SbOaWn5A6PTU2j6zrFVhNq';
    const API_CLIENT_PASSWORD   = '123456';
    const API_CLIENT_EMAIL      = 'internal-stage-business@yopmail.com';
    const API_BUSINESS_ID       = 'M8TCCEVwS8MOdzHo01Dp';
    const API_BUSINESS_NAME     = 'Travel'; */

    public static function get_token() {

        $apiEndPoint = self::api_base() . "oauth/token";
        $remote_body = wp_remote_post($apiEndPoint, array(
            'timeout' => 15,
            'sslverify' => false, 
            'body'        => array(
                'grant_type'           => 'password',
                'email'                =>  get_option( 'addressya_login_email' ) ,
                'password'             => get_option( 'addressya_password' ),
                'return_refresh_token' => true,
                'client_id'            => get_option( 'addressya_client_id' ),
            )
        ));
        $remote_body = json_decode( wp_remote_retrieve_body( $remote_body ), true );
        
        return $remote_body['access_token'];
    }

    public static function get_token_dev() {

        $apiEndPoint = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=AIzaSyBpMpZogKxwMm0-HtOR4FkY-k-bhSmoFEs";
        $remote_body = wp_remote_post($apiEndPoint, array(
            'timeout' => 15,
            'sslverify' => false, 
            'body'        => array(
                'email'                =>  "addressya01@yopmail.com" ,
                'password'             => "123456",
                'returnSecureToken' => true
            )
        ));
        $remote_body = json_decode( wp_remote_retrieve_body( $remote_body ), true );
        
        return $remote_body['idToken'];
    }

    /* public static function getLocation($uid, $lid) {
        $body = '';
        $response = self::response( 
            "connections/getLocationForConnection/" . $uid . "?locationId=" . $lid, 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        wp_send_json_success($response);
    } */

    public static function update_credit_application( $username, $firstName, $lastName, $status, $addressyaUid, $order_id, $amount, $locationId, $companyLocationId, $startDate = '', $endDate = '' ){

        $body = json_encode(array(
            'userName' => $username,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'businessUid' => get_option( 'addressya_business_id' ),
            'orderId' => (string)$order_id,
            'amount' => (string)$amount,
            'status' => $status,
            'contractStartDate' => $startDate,
            'contractEndDate' => $endDate,
            'locationId' => $locationId,
            'companyLocationId' => $companyLocationId
        ));
       // echo "<pre>";print_r($body);wp_die();
        $remote_body = self::response( 
            'credits/create', 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        );
        //wp_send_json_success($remote_body);
    }

    public static function update_status_credit_application( $order_id, $status, $startDate, $endDate){

        /* $body = json_encode(array(
            'userName'     => $username,
            'businessUid'   => get_option( 'addressya_business_id' ),
            'orderId' => (string)$order_id,
            'status' => $status,
            'contractStartDate' => $startDate,
            'contractEndDate' => $endDate,
            'locationId' => $locationId,
            'companyLocationId' => $companyLocationId
        ));
        $remote_body = self::response( 
            'credits/create', 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        ); */

        $end_point = "credits/updateStatus/" . $order_id;
        $body = array(
            "status" => $status,
            "businessUid" => get_option( 'addressya_business_id' ),
            'contractStartDate' => $startDate,
            'contractEndDate' => $endDate,
        );
        $args = array(
            'headers' => array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ),
            'body'      => json_encode($body),
            'method'    => 'PUT'
        );

        $result =  wp_remote_request( self::api_base() . $end_point, $args );

        //wp_send_json_success($remote_body);
    }

    public static function send_otp_v2( $user_name ){
        
        if($user_name == ''){
            wc_add_notice('enter username.', 'error');
        }
        $body = json_encode(array(
            'userName'     => $user_name,
            'businessId'   => get_option( 'addressya_business_id' ),
            'businessName' => get_option( 'addressya_business_name' ),
        ));
        $remote_body = self::response( 
            'generateOtpV2', 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        );     
        WC()->session->set( 'username_verified', false );
        if(isset( $remote_body['status'] ) && '200' == $remote_body['status']){
            WC()->session->set('addressya_user_uid', $remote_body['uid']);            
            WC()->session->set( 'username_verified', true );
        }
        wp_send_json_success($remote_body);
    }

    public static function send_otp( $user_name ){
        /* WC()->session->set('addressya_sess_username','');
        WC()->session->set('locationId','');
        WC()->session->set('companyLocationId','');
        WC()->session->set('addressya_user_uid',''); */
        if($user_name == ''){
            wc_add_notice('enter username.', 'error');
        }
        $body = json_encode(array(
            'userName'     => $user_name,
            'businessId'   => get_option( 'addressya_business_id' ),
            'businessName' => get_option( 'addressya_business_name' ),
        ));
        $remote_body = self::response( 
            'generateOtp', 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        );     
        WC()->session->set( 'username_verified', false );
        if(isset( $remote_body['status'] ) && '200' == $remote_body['status']){
            WC()->session->set('addressya_user_uid', $remote_body['uid']);            
            WC()->session->set( 'username_verified', true );
        }
        wp_send_json_success($remote_body);
    }

    public static function verify_otp_v2($data){
        
        $bodyArray['businessId'] = get_option( 'addressya_business_id' );
        $bodyArray['businessName'] = get_option( 'addressya_business_name' );
        $json = array();
        
        $bodyArray['userName'] = $data['addressya_username'];

        $bodyArray['requestId'] = $data['request_id'];
        $bodyArray['otp'] = $data['addressya_otp'];      
        
        if(!isset($json['error'])){

            $bodyArray = json_encode($bodyArray);
            $remote_body = self::response( 'verifyOtpV2', 'post', $bodyArray,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ));
            if($remote_body['status'] == '200'){
                WC()->session->set('addressya_sess_username', $data['addressya_username']);
                //WC()->session->set('companyLocationId', $remote_body['location']['companyLocationId']);
                //WC()->session->set('locationId', $remote_body['location']['locationId']);
            }
            wp_send_json_success($remote_body);            
        }
        wp_send_json_success($json);
    }

    public static function verify_otp($data){
        
        $bodyArray['businessId'] = get_option( 'addressya_business_id' );
        $bodyArray['businessName'] = get_option( 'addressya_business_name' );
        $json = array();
        $locationId = '';
        if(isset($data['selected_location']) && $data['selected_location'] != ''){
            $locationId = $data['selected_location'];
            WC()->session->set('locationId', $data['selected_location']);
        }
        $company_id = '';
        if(isset($data['company_id']) && $data['company_id'] != ''){
            $company_id = $data['company_id'];
            WC()->session->set('companyLocationId', $data['company_id']);
        }
        $bodyArray['userName'] = $data['addressya_username'];
        WC()->session->set('addressya_sess_username', $data['addressya_username']);

        $bodyArray['requestId'] = $data['request_id'];
        $bodyArray['otp'] = $data['addressya_otp'];
        $bodyArray['requestId'] = $data['request_id'];        
        
        $credit_application_active = "active";
        if($credit_application_active == "active"){
            if(!$company_id){
                if( $data['addressya_company_address1'] == ''){
                    $json['error']['addressya_company_address1'] = "Please enter address line 1";
                }
                if( $data['addressya_company_address2'] == ''){
                    $json['error']['addressya_company_address2'] = "Please enter address line 2";
                }
                if( $data['addressya_company_city'] == ''){
                    $json['error']['addressya_company_city'] = "Please enter city";
                }
                if( $data['addressya_company_state'] == ''){
                    $json['error']['addressya_company_state'] = "Please enter state";
                }
                if( $data['addressya_company_country'] == ''){
                    $json['error']['addressya_company_country'] = "Please enter country";
                }
                if(!isset($json['error'])){
                    $bodyArray['companyLocation']['companyName'] = $data['addressya_company_name'];
                    $bodyArray['companyLocation']['details']['locationName'] = $data['addressya_company_address1'];
                    $bodyArray['companyLocation']['details']['houseNumber'] = $data['addressya_company_address1'];
                    $bodyArray['companyLocation']['details']['streetName'] = isset($data['addressya_company_address2']) ? $data['addressya_company_address2'] : "";
                    $bodyArray['companyLocation']['details']['city'] = $data['addressya_company_city'];
                    $bodyArray['companyLocation']['details']['region'] = $data['addressya_company_state'];
                    $bodyArray['companyLocation']['details']['country'] = $data['addressya_company_country'];

                    $companyAddress = $data['addressya_company_address1'] . "+" .  $data['addressya_company_address2'] . "+" .  $data['addressya_company_city'] . "+" .  $data['addressya_company_state'] . "+" .  $data['addressya_company_country'];		
                    $body = array('address' => $companyAddress);	
                    $position = self::response( 'getLatLng', 'get', $body );
                    $compPositionRes = json_decode($position);
                    $bodyArray['companyLocation']['position']['latitude'] = $position['lat'];
                    $bodyArray['companyLocation']['position']['longitude'] = $position['lng'];
                    $bodyArray['companyLocation']['position']['accuracy'] = 99;
                    $bodyArray['companyLocation']['position']['positioningTech'] = "gps";
                }
            } else {
                $bodyArray['companyLocationId'] = $data['company_id'];
            }
        }

        if(!isset($json['error'])){
            if($locationId){
                $bodyArray['locationId'] = $locationId;
            } else {
                $address = $data['addressya_address1'] . "+" .  $data['addressya_address2'] . "+" .  $data['addressya_city'] . "+" .  $data['addressya_state'] . "+" .  $data['addressya_country'];
                $body = array('address' => $address);
                $position = self::response( 'getLatLng', 'get', $body );
                $positionRes = json_decode($position);
                $bodyArray['location']['details']['locationName'] = $data['location_name'];
                $bodyArray['location']['details']['houseNumber'] = $data['addressya_address1'];
                $bodyArray['location']['details']['streetName'] = isset($data['addressya_address2']) ? $data['addressya_address2'] : "";
                $bodyArray['location']['details']['city'] = $data['addressya_city'];
                $bodyArray['location']['details']['region'] = $data['addressya_state'];
                $bodyArray['location']['details']['country'] = $data['addressya_country'];

                $bodyArray['location']['position']['latitude'] = $position['lat'];
                $bodyArray['location']['position']['longitude'] = $position['lng'];
                $bodyArray['location']['position']['accuracy'] = 99;
                $bodyArray['location']['position']['positioningTech'] = "gps";

                $bodyArray['location']['config']['visible'] = "true";
            }            

            $bodyArray = json_encode($bodyArray);
            $remote_body = self::response( 'verifyOtp', 'post', $bodyArray,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ));
            if($remote_body['status'] == '200'){
                WC()->session->set('companyLocationId', $remote_body['location']['companyLocationId']);
                WC()->session->set('locationId', $remote_body['location']['locationId']);
            }
            wp_send_json_success($remote_body);            
        }
        wp_send_json_success($json);
        
    }

    public static function getLocationForOrder($uid, $lid) {
        $body = '';
        $response = self::response( 
            "connections/getLocationForConnection/" . $uid . "?locationId=" . $lid, 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        return $response;
    }

    public static function add_user_address_v2($data){
        
        $bodyArray['businessId'] = get_option( 'addressya_business_id' );
        $bodyArray['businessName'] = get_option( 'addressya_business_name' );
        
        $/* bodyArray['businessId'] = "CfBVo1kTc0R9JtyIoOPh";
        $bodyArray['businessName'] = "Adil Business"; */
        $json = array();
        $locationId = '';
        if(isset($data['selected_location']) && $data['selected_location'] != ''){
            $locationId = $data['selected_location'];
            WC()->session->set('locationId', $data['selected_location']);
        }
        
        $bodyArray['userName'] = $data['addressya_username'];
        WC()->session->set('addressya_sess_username', $data['addressya_username']);

        $bodyArray['requestId'] = $data['request_id'];
        $bodyArray['token'] = $data['addressya_token'];        
       
        if(!isset($json['error'])){
            if($locationId){
                $bodyArray['locationId'] = $locationId;
            } else {
                $bodyArray['location']['details']['locationName'] = $data['location_name'];
                $bodyArray['location']['details']['houseNumber'] = $data['addressya_address1'];
                $bodyArray['location']['details']['streetName'] = isset($data['addressya_address2']) ? $data['addressya_address2'] : "";
                $bodyArray['location']['details']['streetName'] = isset($data['addressya_address2']) ? $data['addressya_address2'] : "";
                $bodyArray['location']['details']['city'] = $data['addressya_city'];
                $bodyArray['location']['details']['region'] = $data['addressya_state'];
                $bodyArray['location']['details']['country'] = $data['addressya_country'];
                $bodyArray['location']['details']['zipcode'] = $data['addressya_zipcode'];
                $bodyArray['location']['details']['area'] = $data['addressya_area'];

                $address = $data['addressya_address1'] . "+" .  $data['addressya_address2'] . "+" .  $data['addressya_city'] . "+" .  $data['addressya_state'] . "+" .  $data['addressya_country'];
                $body = array('address' => $address);
                $position = self::response( 'getLatLng', 'get', $body );
                $bodyArray['location']['position']['latitude'] = $position['lat'];
                $bodyArray['location']['position']['longitude'] = $position['lng'];
                $bodyArray['location']['position']['accuracy'] = 99;
                $bodyArray['location']['position']['positioningTech'] = "gps";
                $bodyArray['location']['config']['visible'] = "true";
                $bodyArray['location']['config']['type'] = "home";
            }
            

            $bodyArray = json_encode($bodyArray);
            //print_r($bodyArray);wp_die();
            $remote_body = self::response( 'checkoutUpdateLocation', 'post', $bodyArray,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ));
            if($remote_body['status'] == '200'){
                /* WC()->session->set('companyLocationId', $remote_body['location']['companyLocationId']);
                WC()->session->set('locationId', $remote_body['location']['locationId']); */
                
            }
            wp_send_json_success($remote_body);            
        }
        wp_send_json_success($json);
        
    }

    public static function update_emp_detail_v2($data){
        
        $bodyArray['businessId'] = get_option( 'addressya_business_id' );
        $bodyArray['businessName'] = get_option( 'addressya_business_name' );
        
        $json = array();
        
        $bodyArray['userName'] = $data['addressya_username'];
        WC()->session->set('addressya_sess_username', $data['addressya_username']);

        $bodyArray['requestId'] = isset($data['request_id']) ? $data['request_id'] : "";
        $bodyArray['token'] = isset($data['addressya_token']) ? $data['addressya_token'] : "";
        $userBearerToken = isset($data['addressya_user_bearer_token']) ? $data['addressya_user_bearer_token'] : "";        
       
        if(!isset($json['error'])){
            
            $company_id = '';
            if(isset($data['company_id']) && $data['company_id'] != ''){
                $company_id = $data['company_id'];
                $bodyArray['companyLocationId'] = $data['company_id'];
                WC()->session->set('companyLocationId', $data['company_id']);
            }
            $credit_application_active = "active";
            if($credit_application_active == "active"){
                
                if( $data['addressya_company_address1'] == ''){
                    $json['error']['addressya_company_address1'] = "Please enter address line 1";
                }
                if( $data['addressya_company_name'] == ''){
                    $json['error']['addressya_company_name'] = "Please enter employer name";
                }
                if( $data['addressya_company_city'] == ''){
                    $json['error']['addressya_company_city'] = "Please enter city";
                }
                if( $data['addressya_company_state'] == ''){
                    $json['error']['addressya_company_state'] = "Please enter state";
                }
                if( $data['addressya_company_country'] == ''){
                    $json['error']['addressya_company_country'] = "Please enter country";
                }
                if(!isset($json['error'])){
                    $bodyArray['companyLocation']['companyName'] = $data['addressya_company_name'];
                    $bodyArray['companyLocation']['details']['locationName'] = $data['addressya_company_name'];
                    $bodyArray['companyLocation']['details']['houseNumber'] = $data['addressya_company_address1'];
                    $bodyArray['companyLocation']['details']['streetName'] = isset($data['addressya_company_address2']) ? $data['addressya_company_address2'] : "";
                    $bodyArray['companyLocation']['details']['city'] = $data['addressya_company_city'];
                    $bodyArray['companyLocation']['details']['region'] = $data['addressya_company_state'];
                    $bodyArray['companyLocation']['details']['country'] = $data['addressya_company_country'];

                    $companyAddress = $data['addressya_company_address1'] . "+" .  $data['addressya_company_address2'] . "+" .  $data['addressya_company_city'] . "+" .  $data['addressya_company_state'] . "+" .  $data['addressya_company_country'];		
                    $body = array('address' => $companyAddress);	
                    $position = self::response( 'getLatLng', 'get', $body );
                    //$compPositionRes = json_decode($position);
                    $bodyArray['companyLocation']['position']['latitude'] = $position['lat'];
                    $bodyArray['companyLocation']['position']['longitude'] = $position['lng'];
                    $bodyArray['companyLocation']['position']['accuracy'] = 99;
                    $bodyArray['companyLocation']['position']['positioningTech'] = "gps";
                }
                
            }

            $bodyArray = json_encode($bodyArray);
            //print_r($bodyArray);wp_die();
            $remote_body = self::response( 'checkoutUpdateEmployer', 'post', $bodyArray,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'userBearerToken' => $userBearerToken,
            ));
            if($remote_body['status'] == '200'){
                /* WC()->session->set('companyLocationId', $remote_body['location']['companyLocationId']);
                WC()->session->set('locationId', $remote_body['location']['locationId']); */
            }
            wp_send_json_success($remote_body);            
        }
        wp_send_json_success($json);
        
    }
    public static function confirm_location_v2($data){
        
        $bodyArray['businessId'] = get_option( 'addressya_business_id' );
        $bodyArray['businessName'] = get_option( 'addressya_business_name' );
        
        /* $bodyArray['businessId'] = "CfBVo1kTc0R9JtyIoOPh";
        $bodyArray['businessName'] = "Adil Business"; */
        $json = array();
        $locationId = '';
        if(isset($data['selected_location']) && $data['selected_location'] != ''){
            $locationId = $data['selected_location'];
            WC()->session->set('locationId', $data['selected_location']);
        }
        
        $bodyArray['userName'] = $data['addressya_username'];
        WC()->session->set('addressya_sess_username', $data['addressya_username']);

        $bodyArray['requestId'] = $data['request_id'];
        $bodyArray['token'] = $data['addressya_token'];        
       
        if(!isset($json['error'])){
            if($locationId){
                $bodyArray['locationId'] = $locationId;
            } 
            
            $company_id = '';
            if(isset($data['company_id']) && $data['company_id'] != ''){
                $company_id = $data['company_id'];
                $bodyArray['companyLocationId'] = $data['company_id'];
                WC()->session->set('companyLocationId', $data['company_id']);
            }

            $bodyArray = json_encode($bodyArray);
            //print_r($bodyArray);wp_die();
            $remote_body = self::response( 'checkoutSubmission', 'post', $bodyArray,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ));
            if($remote_body['status'] == '200'){
                /* WC()->session->set('companyLocationId', $remote_body['location']['companyLocationId']);
                WC()->session->set('locationId', $remote_body['location']['locationId']); */
            }
            wp_send_json_success($remote_body);            
        }
        wp_send_json_success($json);
        
    }

    public static function create_account($data){

        $address = $data['streetName'] . "+" .  $data['apartment'] . "+" .  $data['city'] . "+" .  $data['region'] . "+" .  $data['country'];			
        $position = self::response( 'getLatLng', 'get', array(
            'address' => $address,
        ) );
        
        $data['latitude'] = $position['lat'];
        $data['longitude'] = $position['lng'];
        
        WC()->session->set('addressya_sess_username', $data['userName']);
        /* echo "<pre>";
        print_r($data);
        wp_die(); */
        $bodyArray = array(
            'business'  => array(
                'id' => get_option( 'addressya_business_id' )
            ),
            'userData'  => array(
                'email'                => $data['email'],
                'password'             => $data['password'],
                'return_refresh_token' => 'true',
                'displayEmail'         => $data['email'],
                'firstName'            => $data['firstName'],
                'lastName'             => $data['lastName'],
                'phoneNumber'          => $data['phoneNumber'],
                'userName'             => $data['userName'],
                'client_id'            => get_option( 'addressya_client_id' ),
                'grant_type'           => 'password',
                'isd'                  => $data['isd'],
                'from'                 => 'apigee'
            ),
            'location'  => array(
                'details'   => array(
                    'locationName'  => 'Home',
                    'city'          => $data['city'],
                    'country'       => $data['country'],
                    'region'        => $data['region'],
                    'streetName'    => $data['streetName'],
                    'apartment'     => $data['apartment']
                ),
                'position'  => array(
                    'latitude'        => $data['latitude'],
                    'longitude'       => $data['longitude'],
                    'accuracy'        => 99,
                    'positioningTech' => 'gps'
                ),
                'config'    => array(
                    'visible' => true
                )
            )
        );

        $bodyArray = json_encode($bodyArray);
        /* echo "<pre>";
        print_r($bodyArray);
        wp_die(); */
        $remote_body = self::response( 'oauth2/createUser', 'post', $bodyArray,
        array(
            'Accept' => '*/*',
            'Content-Type' => 'application/json'
        ));
        return $remote_body;
        //wp_send_json_success($remote_body);   

    }
    
    public static function is_user_exists( $user_name ) {
        $remote_body = self::response( 'searchByUserName?userName=' . $user_name, 'get' );
        if(isset( $remote_body['status'] ) && '200' == $remote_body['status']){
            //WC()->session->set('addressya_user_uid', $remote_body['id']);            
        }
        return $remote_body;
    }

    public static function is_user_available( $user_name ) {
        $remote_body = self::response( 'isUserNameAvailable?userName=' . $user_name, 'get' );
        if(isset( $remote_body['status'] ) && '200' == $remote_body['status']){
            //WC()->session->set('addressya_user_uid', $remote_body['id']);            
        }
        return $remote_body;
    }

    protected static function response( $end_point, $method, $body = array(), $headers = array() ) {
        
        $remote_args = array(
            'timeout' => 15,
            'sslverify' => false, 
            'body' => $body,
            'headers' => $headers
        );
        
        if ( 'post' === $method ) {
            $response = wp_remote_post( 
                self::api_base() . $end_point, 
                $remote_args
            );
        } else {
            $response = wp_remote_get( 
                self::api_base() . $end_point, 
                $remote_args
            );
        }

        $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $remote_body ? $remote_body : false;
    }

    protected static function response_v2( $end_point, $method, $body = array(), $headers = array() ) {
        
        $remote_args = array(
            'timeout' => 15,
            'sslverify' => false, 
            'body' => $body,
            'headers' => $headers
        );
        
        if ( 'post' === $method ) {
            $response = wp_remote_post( 
                "https://us-central1-map-project-test-addressya.cloudfunctions.net/api/" . $end_point, 
                $remote_args
            );
        } else {
            $response = wp_remote_get( 
                "https://us-central1-map-project-test-addressya.cloudfunctions.net/api/" . $end_point, 
                $remote_args
            );
        }

        $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $remote_body ? $remote_body : false;
    }


    protected static function api_base() {
        if ( 'dev' === ADDRESSYA_ENV ) {
            return "https://stage-api.addressya.com/v1/";
        }

        return "https://api.addressya.com/v1/";
    }

    function check_update() {
        //update_option( 'addressya_login_email', 'yatul@addressya.com' );
    }


    function getCountryCallingCode() {
        $country_calling_code = array(
            ""       => "Select Country Code",
            "+7 840" => "Abkhazia (+7 840)",
            "+93"    => "Afghanistan (+93)",
            "+355"   => "Albania (+355)",    
            "+213"   => "Algeria (+213)",
            "+1 684" => "American Samoa (+1 684)",
            "+376"   => "Andorra (+376)",
            "+244"   => "Angola (+244)",
            "+1 264" => "Anguilla (+1 264)",
            "+1 268" => "Antigua and Barbuda (+1 268)",
            "+54"    => "Argentina (+54)",
            "+374"   => "Armenia (+374)",
            "+297"   => "Aruba (+297)",
            "+247"   => "Ascension (+247)",
            "+61"    => "Australia (+61)",
            "+672"   => "Australian External Territories (+672)",
            "+43"    => "Austria (+43)",
            "+994"   => "Azerbaijan (+994)",
            "+1 242" => "Bahamas (+1 242)",
            "+973"   => "Bahrain (+973)",
            "+880"   => "Bangladesh (+880)",
            "+1 246" => "Barbados (+1 246)",
            "+1 268" => "Barbuda (+1 268)", 
            "+375"   => "Belarus (+375)",
            "+32"    => "Belgium (+32)",
            "+501"   => "Belize (+501)",
            "+229"   => "Benin (+229)",
            "+1 441" => "Bermuda (+1 441)",
            "+975"   => "Bhutan (+975)",
            "+591"   => "Bolivia (+591)",
            "+387"   => "Bosnia and Herzegovina (+387)",
            "+267"   => "Botswana (+267)",
            "+55"    => "Brazil (+55)",
            "+246"   => "British Indian Ocean Territory (+246)",
            "+1 284" => "British Virgin Islands (+1 284)",
            "+673"   => "Brunei (+673)",
            "+359"   => "Bulgaria (+359)",
            "+226"   => "Burkina Faso (+226)",
            "+257"   => "Burundi (+257)",
            "+855"   => "Cambodia (+855)",
            "+237"   => "Cameroon (+237)",
            "+1"     => "Canada (+1)",
            "+238"   => "Cape Verde (+238)",
            "+345"   => "Cayman Islands (+345)",
            "+236"   => "Central African Republic (+236)",
            "+235"   => "Chad (+235)",
            "+56"    => "Chile (+56)",
            "+86"    => "China (+86)",
            "+57"    => "Colombia (+57)",
            "+269"   => "Comoros (+269)",
            "+242"   => "Congo (+242)",
            "+243"   => "Congo, Dem. Rep. of (Zaire) (+243)",
            "+682"   => "Cook Islands (+682)",
            "+506"   => "Costa Rica (+506)",
            "+385"   => "Croatia (+385)",
            "+53"    => "Cuba (+53)",
            "+599"   => "Curacao (+599)",
            "+537"   => "Cyprus (+537)",
            "+420"   => "Czech Republic (+420)",
            "+45"    => "Denmark (+45)",
            "+246"   => "Diego Garcia (+246)",
            "+253"   => "Djibouti (+253)",
            "+1 767" => "Dominica (+1 767)",
            "+1 809" => "Dominican Republic (+1 809)",
            "+670"   => "East Timor (+670)",
            "+56"    => "Easter Island (+56)",
            "+593"   => "Ecuador (+593)",
            "+20"    => "Egypt (+20)",
            "+503"   => "El Salvador(+503)",
            "+240"   => "Equatorial Guinea (+240)",
            "+291"   => "Eritrea (+291)",
            "+372"   => "Estonia (+372)",
            "+251"   => "Ethiopia (+251)",
            "+500"   => "Falkland Islands (+500)",
            "+298"   => "Faroe Islands (+298)",
            "+679"   => "Fiji (+679)",
            "+358"   => "Finland (+358)",
            "+33"    => "France (+33)",
            "+596"   => "French Antilles (+596)",
            "+594"   => "French Guiana (+594)",
            "+689"   => "French Polynesia (+689)",
            "+241"   => "Gabon (+241)",
            "+220"   => "Gambia (+220)",
            "+995"   => "Georgia (+995)",
            "+49"    => "Germany (+49)",
            "+233"   => "Ghana (+233)",
            "+350"   => "Gibraltar (+350)",
            "+30"    => "Greece (+30)",
            "+299"   => "Greenland (+299)",
            "+1 473" => "Grenada (+1 473)",
            "+590"   => "Guadeloupe (+590)",
            "+1 671" => "Guam (+1 671)",
            "+502"   => "Guatemala (+502)",
            "+224"   => "Guinea (+224)",
            "+245"   => "Guinea-Bissau (+245)",
            "+595"   => "Guyana (+595)",
            "+509"   => "Haiti (+509)",
            "+504"   => "Honduras (+504)",
            "+852"   => "Hong Kong SAR China (+852)",
            "+36"    => "Hungary (+36)",
            "+354"   => "Iceland (+354)",
            "+91"    => "India (+91)",
            "+62"    => "Indonesia (+62)",
            "+98"    => "Iran (+98)",
            "+964"   => "Iraq (+964)",
            "+353"   => "Ireland (+353)",
            "+972"   => "Israel (+972)",
            "+39"    => "Italy (+39)",
            "+225"   => "Ivory Coast (+225)",
            "+1 876" => "Jamaica (+1 876)",
            "+81"    => "Japan (+81)",
            "+962"   => "Jordan (+962)",
            "+7 7"   => "Kazakhstan (+7 7)",
            "+254"   => "Kenya (+254)",
            "+686"   => "Kiribati (+686)",
            "+965"   => "Kuwait (+965)",
            "+996"   => "Kyrgyzstan (+996)",
            "+856"   => "Laos (+856)",
            "+371"   => "Latvia (+371)",
            "+961"   => "Lebanon (+961)",
            "+266"   => "Lesotho (+266)",
            "+231"   => "Liberia (+231)",
            "+218"   => "Libya (+218)",
            "+423"   => "Liechtenstein (+423)",
            "+370"   => "Lithuania (+370)",
            "+352"   => "Luxembourg (+352)",
            "+853"   => "Macau SAR China (+853)",
            "+389"   => "Macedonia (+389)",
            "+261"   => "Madagascar (+261)",
            "+265"   => "Malawi (+265)",
            "+60"    => "Malaysia (+60)",
            "+960"   => "Maldives (+960)",
            "+223"   => "Mali (+223)",
            "+356"   => "Malta (+356)",
            "+692"   => "Marshall Islands (+692)",
            "+596"   => "Martinique (+596)",
            "+222"   => "Mauritania (+222)",
            "+230"   => "Mauritius (+230)",
            "+262"   => "Mayotte (+262)",
            "+52"    => "Mexico (+52)",
            "+691"   => "Micronesia (+691)",
            "+1 808" => "Midway Island (+1 808)",
            "+373"   => "Moldova (+373)",
            "+377"   => "Monaco (+377)",
            "+976"   => "Mongolia (+976)",
            "+382"   => "Montenegro (+382)",
            "+1664"  => "Montserrat (+1664)",
            "+212"   => "Morocco (+212)",
            "+95"    => "Myanmar (+95)",
            "+264"   => "Namibia (+264)",
            "+674"   => "Nauru (+674)",
            "+977"   => "Nepal (+977)",
            "+31"    => "Netherlands (+31)",
            "+599"   => "Netherlands Antilles (+599)",
            "+1 869" => "Nevis (+1 869)",
            "+687"   => "New Caledonia (+687)",
            "+64"    => "New Zealand (+64)",
            "+505"   => "Nicaragua (+505)",
            "+227"   => "Niger (+227)",
            "+234"   => "Nigeria (+234)",
            "+683"   => "Niue (+683)",
            "+672"   => "Norfolk Island (+672)",
            "+850"   => "North Korea (+850)",
            "+1 670" => "Northern Mariana Islands (+1 670)",
            "+47"    => "Norway (+47)",
            "+968"   => "Oman (+968)",
            "+92"    => "Pakistan (+92)",
            "+680"   => "Palau (+680)",
            "+970"   => "Palestinian Territory (+970)",
            "+507"   => "Panama (+507)",
            "+675"   => "Papua New Guinea (+675)",
            "+595"   => "Paraguay (+595)",
            "+51"    => "Peru (+51)",
            "+63"    => "Philippines (+63)",
            "+48"    => "Poland (+48)",
            "+351"   => "Portugal (+351)",
            "+1 787" => "Puerto Rico (+1 787)",
            "+974"   => "Qatar (+974)",
            "+262"   => "Reunion (+262)",
            "+40"    => "Romania (+40)",
            "+7"     => "Russia (+7)",
            "+250"   => "Rwanda (+250)",
            "+685"   => "Samoa (+685)",
            "+378"   => "San Marino (+378)",
            "+966"   => "Saudi Arabia (+966)",
            "+221"   => "Senegal (+221)",
            "+381"   => "Serbia (+381)",
            "+248"   => "Seychelles (+248)",
            "+232"   => "Sierra Leone (+232)",
            "+65"    => "Singapore (+65)",
            "+421"   => "Slovakia (+421)",
            "+386"   => "Slovenia (+386)",
            "+677"   => "Solomon Islands (+677)",
            "+27"    => "South Africa (+27)",
            "+500"   => "South Georgia and the South Sandwich Islands (+500)",
            "+82"    => "South Korea (+82)",
            "+34"    => "Spain (+34)",
            "+94"    => "Sri Lanka (+94)",
            "+249"   => "Sudan (+249)",
            "+597"   => "Suriname (+597)",
            "+268"   => "Swaziland (+268)",
            "+46"    => "Sweden (+46)",
            "+41"    => "Switzerland (+41)",
            "+963"   => "Syria (+963)",
            "+886"   => "Taiwan (+886)",
            "+992"   => "Tajikistan (+992)",
            "+255"   => "Tanzania (+255)",
            "+66"    => "Thailand (+66)",
            "+670"   => "Timor Leste (+670)",
            "+228"   => "Togo (+228)",
            "+690"   => "Tokelau (+690)",
            "+676"   => "Tonga (+676)",
            "+1 868" => "Trinidad and Tobago (+1 868)",
            "+216"   => "Tunisia (+216)",
            "+90"    => "Turkey (+90)",
            "+993"   => "Turkmenistan (+993)",
            "+1 649" => "Turks and Caicos Islands (+1 649)",
            "+688"   => "Tuvalu (+688)",
            "+1 340" => "U.S. Virgin Islands (+1 340)",
            "+256"   => "Uganda (+256)",
            "+380"   => "Ukraine (+380)",
            "+971"   => "United Arab Emirates (+971)",
            "+44"    => "United Kingdom (+44)",
            "+1"     => "United States (+1)",
            "+598"   => "Uruguay (+598)",
            "+998"   => "Uzbekistan (+998)",
            "+678"   => "Vanuatu (+678)",
            "+58"    => "Venezuela (+58)",
            "+84"    => "Vietnam (+84)",
            "+1 808" => "Wake Island (+1 808)",
            "+681"   => "Wallis and Futuna (+681)",
            "+967"   => "Yemen (+967)",
            "+260"   => "Zambia (+260)",
            "+255"   => "Zanzibar (+255)",
            "+263"   => "Zimbabwe (+263)"
        );
        return $country_calling_code;
    }

    public static function sendNotification($locationId, $recipientId, $driverUsername, $customerUsername, $type, $message) {
       
        $apiEndPoint = self::api_base() . "send-notification";       
        $response = wp_remote_request($apiEndPoint, array(
            'method'      => 'POST',
            'redirection' => 10,
            'headers'     => array(
                "Authorization"  => "Bearer " . self::get_token(),
                'Accept'         => 'application/json',
                'Content-Type'   => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' )
            ),
            'httpversion' => '1.1',
            'body'        => json_encode(array(
                'locationId'       => $locationId,
                'recipient'        => $recipientId,
                'driverUsername'   => $driverUsername,
                'message'          => $message,
                'type'             => $type,
                'customerUsername' => $customerUsername
            ))
        ));
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != '200') {
            return 'Error';
        } else {
            $body = wp_remote_retrieve_body($response);
            return json_decode($body, true);
        }
    }

    public static function testCreditData($post_id) {
       
        $location_data = ADDRESSYA_API::getLocationForOrder( '7wH8xvx5vyPQZpEZf2p9J4oJD7j1', 'DqzWHriACBo0VwJ4wlor' );
        //$location_data = ADDRESSYA_API::getLocationForOrder( $addressyaUid, $locationId );
        
        update_post_meta( $post_id, '_billing_addressya_location_lat', $location_data['data'][0]['position']['latitude'] ); 
        update_post_meta( $post_id, '_billing_addressya_location_lon', $location_data['data'][0]['position']['longitude'] ); 
        update_post_meta( $post_id, '_billing_addressya_location_city', $location_data['data'][0]['details']['city'] ); 
        update_post_meta( $post_id, '_billing_addressya_location_region', $location_data['data'][0]['details']['region'] ); 
        update_post_meta( $post_id, '_billing_addressya_location_country', $location_data['data'][0]['details']['country'] ); 
   
    }

}
//var_dump(ADDRESSYA_API::testCreditData('227'));
//var_dump(ADDRESSYA_API::getLocationForOrder( '7wH8xvx5vyPQZpEZf2p9J4oJD7j1', 'DqzWHriACBo0VwJ4wlor' ));
//var_dump(ADDRESSYA_API::get_token_dev());
//var_dump("here");
//var_dump( ADDRESSYA_API::update_credit_application($username="atulgupta", "pending", $addressyaUid = "cJFjWmHBa4blDBeZ21IqcEBLSvJ2", $order_id = "49", $locationId = "B5me7AvYTnfG8W1xD22w", $companyLocationId = "B5me7AvYTnfG8W1xD22w") );