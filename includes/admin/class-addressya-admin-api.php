<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_API {

    /* const API_CLIENT_ID       = 'kJyaClkM91SbOaWn5A6PTU2j6zrFVhNq';
    const API_CLIENT_PASSWORD = '123456';
    const API_CLIENT_EMAIL    = 'internal-stage-business@yopmail.com';
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

    public static function get_business_detail($data) {
        //echo "<pre>";print_r($data);wp_die();
        $apiEndPoint = self::api_base() . "oauth/token";
        $remote_body = wp_remote_post($apiEndPoint, array(
            'timeout' => 15,
            'sslverify' => false, 
            'body'        => array(
                'grant_type'           => 'password',
                'email'                =>  $data['email'] ,
                'password'             => $data['password'],
                'return_refresh_token' => true,
                'client_id'            => $data['client_id'],
            )
        ));
        //echo print_r($remote_body['body']);wp_die();
        $remote_body = json_decode( wp_remote_retrieve_body( $remote_body ), true );
       // $remote_body =  wp_remote_retrieve_body( $remote_body );
        
        
        return $remote_body;

    }

    public static function get_token_bk() {

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

    public static function getBusinessCreditApplications($status) {
        $body = '';
        $queryParam = "?limitNum=100";
        if(isset($status) && $status != ''){
            $queryParam = $queryParam . '&status=' . $status;
        }
        $response = self::response( 
            "credits/" . get_option( 'addressya_business_id' ) .  $queryParam, 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        );
        return $response;
    }

    public static function getDriver() {
        $body = '';
        $queryParam = "?role=driver";
        
        $response = self::response( 
            "organizations/" . get_option( 'addressya_business_id' ) . "/members" . $queryParam, 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        );
        return $response;
    }

    public static function getDriverById($memberId) {
        $body = '';
        
        $response = self::response( 
            "organizations/" . get_option( 'addressya_business_id' ) . "/members/" . $memberId, 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            )
        );
        return $response;
    }

    

    public static function getLocation($uid, $lid) {
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
    }

    public static function getCredit($oder_id) {
        
        $body = '';
        $response = self::response( 
            "credits/details/" . $oder_id, 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'businessuid' => get_option( 'addressya_business_id' ),
            )
        );
        wp_send_json_success($response);
    }

    function addDriverUnderOrganization($memberId, $startTime, $endTime, $timezone) {
        
        $body = json_encode(array(
            'memberId'      => $memberId,
            'userRoleId'    => 'driver',
            'startTime'    => $startTime,
            'endTime'    => $endTime,
            'timezone'    => $timezone
        ));
        $response = self::response(             
            "organizations/" . get_option( 'addressya_business_id' ) . "/members", 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        return $response;
        //wp_send_json_success($response);
    }

    public static function addDriver($memberId, $vehicle_name, $vehicle_number, $wh_start_time, $wh_end_time, $wh_timezone) {
        
        $body = json_encode(array(
            'memberId'      => $memberId,
            'userRoleId'    => 'driver',
            'startTime'    => $wh_start_time,
            'endTime'    => $wh_end_time,
            'timezone'    => $wh_timezone,
            'vehicleName'    => $vehicle_name,
            'vehicleNumber'    => $vehicle_number,
        ));
        $response = self::response(             
            "organizations/" . get_option( 'addressya_business_id' ) . "/members", 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        //return $response;
        wp_send_json_success($response);
    }

    public static function updateDriver__($memberId, $vehicleName, $vehicleNumber, $startTime, $endTime, $timezone, $status) {
        
        $body = json_encode(array(
            'memberId'      => $memberId,
            'startTime'    => $startTime,
            'endTime'    => $endTime,
            'timezone'    => $timezone,
            'vehicleName'    => $vehicleName,
            'vehicleNumber'    => $vehicleNumber,
            'status'    => $status,
        ));
        $response = self::response(             
            "organizations/" . get_option( 'addressya_business_id' ) . "/members", 
            'patch', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        //return $response;
        wp_send_json_success($response);
    }

     public static function updateDriver( $memberId, $vehicleName, $vehicleNumber, $startTime, $endTime, $timezone, $status){

        $end_point = "organizations/" . get_option( 'addressya_business_id' ) . "/members";
        $body = array(
            'memberId'      => $memberId,
            'userRoleId'    => 'driver',
            'startTime'    => $startTime,
            'endTime'    => $endTime,
            'timezone'    => $timezone,
            'vehicleName'    => $vehicleName,
            'vehicleNumber'    => $vehicleNumber,
            'status'    => $status,
        );
        $args = array(
            'headers' => array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ),
            'body'      => json_encode($body),
            'method'    => 'PATCH'
        );

        $response =  wp_remote_request( self::api_base() . $end_point, $args );
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != '200') {
            return 'Error';
        } else {            
            $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
            //return $remote_body ? $remote_body : false;
            wp_send_json_success($remote_body);
        }

    }

    public static function deleteDriver($memberId) {
        
        $apiEndPoint = self::api_base() . "organizations/" . get_option( 'addressya_business_id' ) . "/members/" .$memberId;
        
        $response = wp_remote_request($apiEndPoint, array(
            'method'      => 'DELETE',
            'redirection' => 10,
            'headers'     => array(
                "Authorization" => "Bearer " . self::get_token(),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ),
            'httpversion' => '1.1'
        ));
        
        $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $remote_body ? $remote_body : false;
    }

      
    public static function copyConnectionToDriver($memberId, $connectionId, $locationId) {
    
        $body = json_encode(array(
            'connectionId' => $connectionId,
            'memberId'     => $memberId,        //need to update the UID of driver
            'locationId'   => $locationId
        ));
        $response = self::response( 
            "organizations/" . get_option( 'addressya_business_id' ) . "/copyConnection", 
            'post', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        return $response;
        //wp_send_json_success($response);
    }

    public static function deleteInheritedConnectionFromDriver($memberId, $connectionId, $locationId) {
        
        $apiEndPoint = self::api_base() . "organizations/" . get_option( 'addressya_business_id' ) . "/deleteInheritedConnection";
        
        $response = wp_remote_request($apiEndPoint, array(
            'method'      => 'DELETE',
            'redirection' => 10,
            'headers'     => array(
                "Authorization" => "Bearer " . self::get_token(),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'connectionId'  => $connectionId,
                'memberId'      => $memberId,
                'locationId'    => $locationId
            ),
            'httpversion' => '1.1'
        ));
        
        $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $remote_body ? $remote_body : false;
    }

    public static function deleteCreditApplication($orderId) {
        
        $apiEndPoint = self::api_base() . "credits/" . $orderId;
        $response = wp_remote_request($apiEndPoint, array(
            'method'      => 'DELETE',
            'redirection' => 10,
            'headers'     => array(
                "Authorization" => "Bearer " . self::get_token(),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'businessUid'   => get_option( 'addressya_business_id' )
            ),
            'httpversion' => '1.1'
        ));
        
        $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $remote_body ? $remote_body : false;
    }
    public static function update_status_credit_application( $order_id, $status, $startDate, $endDate){

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

        $response =  wp_remote_request( self::api_base() . $end_point, $args );
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != '200') {
            return 'Error';
        } else {            
            $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
            return $remote_body ? $remote_body : false;
        }

        //wp_send_json_success($remote_body);
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

    public static function requestScore($cid, $lid) {
        /* $body = '';
        $response = self::response( 
            "connections/" . $cid . "/location/" . $lid . "/requestScore", 
            'PUT', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            )
        );
        //return $response;
        wp_send_json_success($response); */

        $body = array(
            'lic' => $lid,
        );
        $apiEndPoint = self::api_base() . "connections/" . $cid . "/location/" . $lid . "/requestScore";
        $response = wp_remote_request($apiEndPoint, array(
            'method'      => 'PUT',
            'redirection' => 10,
            'body'      => json_encode($body),
            'headers'     => array(
                "Authorization" => "Bearer " . self::get_token(),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'organizationid' => get_option( 'addressya_business_id' ),
            ),
            'httpversion' => '1.1'
        ));
        
        $remote_body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $remote_body ? $remote_body : false;
    }

    public static function show_credit_info($order) {
        global $wpdb;
        $link = "";
        $results = $wpdb->get_results( "select post_id from $wpdb->postmeta where meta_value = $order->id", ARRAY_A );
        $meta_post_id = $results[0]['post_id'];
        $link .= '<a target="_blank" href="'. admin_url( 'post.php?post=' . absint( $meta_post_id ) . '&action=edit' ) .'" >';
        $link .= __( 'View Application', 'your_domain' );
        $link .= '</a>';
       ?>
        <div style="margin-top: 10px !important;width: 100%;float: left;">
            <h3><?php echo __('Credit Application ') ?></h3>
            <span style="margin-top: 10px !important;width: 50%;float: left;">
                Status: <?php echo ucfirst(get_post_meta($meta_post_id, '_billing_addressya_credit_status', true)) ?>
           </span>                
            <span style="margin-top: 10px !important;width: 50%;float: right;text-align: right;">
                <?php echo $link ?>
           </span>
        </div>
       <?php
    }

    function getDriversOnOrder() {
        /* global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_driver' AND post_status = 'publish' AND meta_key = 'addressya_driver_status' AND meta_value = 'enable'" ) );
        return $results; */

        $body = '';
        $response = self::response( 
            "organizations/" . get_option( 'addressya_business_id' ) . "/membersDriverList", 
            'get', 
            $body,
            array(
                'Authorization' => "Bearer " . self::get_token(),	
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'businessuid' => get_option( 'addressya_business_id' ),
            )
        );
        return $response['data']['members'];

     }

    public static function assign_driver_section($order) {
       ?>
        <div style="float:left; width:96%; background-color: #F7F7F7;padding: 8px; border-top: solid 1px #c7c7c7;" >
            <input type="hidden" value="<?php echo esc_html($order->get_id()) ?>" id="hidden-order-id">
            <?php if (get_option('addressya_driver_enable') != null &&  get_option('addressya_driver_enable') == 'on') { ?>
            <div style="float:left; width:50%;">
                <strong><?php echo __('Assign Driver: ') ?></strong><br />
            </div>
            <div style="float:left; width:50%; text-align: right;">
            <?php
            if (!empty(get_post_meta($order->get_id(), 'driver_member_id_billing', true))) { ?>
                <label class="afw_pending_label afw_assigned_driver_label" id="assigned_driver"><?php echo __('Assigned to ') ?><?php echo esc_html(get_post_meta($order->get_id(), 'driver_firstname_billing', true)) ?></label>
                <input type="button" value="Un-Assign" id="unassign-driver-btn" class="afw_resend_btn afw_unassign_btn" >
            <?php } else { ?>
                <select name="assign_driver" id="assign_driver" style="min-height: 10px;line-height: 22px;max-width: 35%;">
                <?php
                //$driver_list = self::getDriversOnOrder();
                
                echo htmlspecialchars_decode('<option value="">Select</option>');
                foreach ($driver_list as $key) {
                    //$driver_vehicle_name = $this->getVehicleNameOnDriverAssignment($key->vehicle_id);
                    echo htmlspecialchars_decode('<option value="' . $key->ID . '">' . $key->post_title . '</option>');
                }
                ?>
                </select>
                
                <input type="button" value="Assign" id="assign-driver-btn" class="afw_resend_btn" style="padding: 3px 14px;"> <br />
                <label id="driver-bill-error" style="color: red;display: none;"><?php echo __('Please select driver. ') ?></label>
                
            <?php } ?>
            </div>
            <?php } ?>

            </div>
       <?php
    }

    
    
    function add_addressya_username_admin_bill($order) {
        
        if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'edit') {
           ?>
              <style>
                 .address,
                 .edit_address {
                    display: none
                 }
              </style>
           <?php
           $order_data = $order->get_data();
           $username = get_post_meta($order->get_id(), '_billing_addressya_username', true);
           $location_id = get_post_meta($order->get_id(), '_billing_addressya_locationId', true);//wp_die($location_id);
           $customer_id = get_post_meta($order->get_id(), '_billing_addressya_uid', true);//wp_die($location_id);
           if (!empty($location_id)) {
              //$client = new AFW_ClientModule();
              $location_data = self::getLocationForOrder( get_post_meta($order->get_id(), '_billing_addressya_uid', true), $location_id );
              //$location_data = ADDRESSYA_API::getLocation(get_post_meta($order->get_id(), '_billing_addressya_uid', true), $location_id);
            //echo "<pre>";print_r($location_data);die();
              if (isset($location_data['data'])) {
                $driver_list = $this->getDriversOnOrder();
                //echo "<pre>"; print_r($location_data['data'][0]['config']);echo "</pre>";
                 ?>
                    <div style="background-color: #F7F7F7;padding: 10px;">
                       <p style="overflow-wrap: break-word;">
                          <?php echo esc_html($order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name']) ?> <br />
                          <strong><?php echo __('Phone: ') ?></strong><?php echo esc_html($order_data['billing']['phone']) ?><br />
                          <strong><?php echo __('E-mail: ') ?></strong><?php echo esc_html($order_data['billing']['email']) ?><br />
                       </p>
                       <p>
                          <strong><?php echo __('Addressya Username: ') ?></strong><br />
                          <?php echo esc_html($username) ?>
                       </p>
                       <p>
                          <strong><?php echo __('Address Score: ') ?></strong><br />
                          <?php if($location_data['data'][0]['scoreShared'] == true ){ ?>
                            <?php echo !empty($location_data['data'][0]['config']['score']) ? esc_html($location_data['data'][0]['config']['score']) . "/10": esc_html('') ?>                            
                          <?php } else { ?>
                            <?php if($location_data['data'][0]['requestedScore'] == true ){ ?>
                                Address Score Requested
                            <?php } else if($location_data['data'][0]['scoreShared'] == false ){ ?>
                                <input type="hidden" value="<?php echo $location_id ?>" id="hidden-loc-id">
                                <input type="hidden" value="<?php echo $customer_id ?>" id="hidden-cus-id">
                                <input type="button" value="Request Address Score" id="request_address_score" class="afw_resend_btn afw_unassign_btn" >                              
                            <?php }  ?>
                          <?php }  ?>
                       </p>
                       <?php if($location_data['data'][0]['scoreShared'] == true && $location_data['data'][0]['config']['type'] == 'home' ){ ?>
                       <p>
                          <strong><?php echo __('Proof of residency: ') ?></strong>
                          <?php echo isset($location_data['data'][0]['config']['proofOfResScore']) ? esc_html($location_data['data'][0]['config']['proofOfResScore']) ."%" : esc_html('') ?>
                       </p>
                       <?php } ?>
                       <p>
                          <label><strong><?php echo __('Area: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['area']) ? esc_html($location_data['data'][0]['details']['area']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Location Name: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['locationName']) ? esc_html($location_data['data'][0]['details']['locationName']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Street/Road: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['streetName']) ? esc_html($location_data['data'][0]['details']['streetName']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('House No: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['houseNumber']) ? esc_html($location_data['data'][0]['details']['houseNumber']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Building: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['buildingName']) ? esc_html($location_data['data'][0]['details']['buildingName']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Floor: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['floor']) ? esc_html($location_data['data'][0]['details']['floor']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Apartment: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['apartment']) ? esc_html($location_data['data'][0]['details']['apartment']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('City: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['city']) ? esc_html($location_data['data'][0]['details']['city']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Region: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['region']) ? esc_html($location_data['data'][0]['details']['region']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Country: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['country']) ? esc_html($location_data['data'][0]['details']['country']) : esc_html('') ?>
                          </label><br />
                          <label><strong><?php echo __('Directions: ') ?></strong>
                          <?php echo !empty($location_data['data'][0]['details']['directions']) ? esc_html($location_data['data'][0]['details']['directions']) : esc_html('') ?>
                          </label><br />
                          <label>
                          <?php echo !empty($location_data['data'][0]['position']['latitude']) && !empty($location_data['data'][0]['position']['longitude']) ? htmlspecialchars_decode('<iframe style="width: -webkit-fill-available; height: 250px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=' . $location_data['data'][0]['position']['latitude'] . ',' . $location_data['data'][0]['position']['longitude'] . '&amp;output=embed"></iframe>') : esc_html('') ?>
                          </label><br />
                          <label>
                          <?php echo !empty($location_data['data'][0]['images']) ? htmlspecialchars_decode('<img src="' . $location_data['data'][0]['images'][0] . '" style="width: -webkit-fill-available; height: 250px;">') : esc_html('') ?>
                          </label><br />
                       </p>
                       <p>
                       <input type="hidden" value="<?php echo esc_html($order->get_id()) ?>" id="hidden-order-id">
                        <?php if (get_option('addressya_driver_enable') != null &&  get_option('addressya_driver_enable') == 'on') { ?>
                          <strong><?php echo __('Assign Driver: ') ?></strong><br />
                          <?php
                          if (!empty(get_post_meta($order->get_id(), 'driver_member_id_billing', true))) { ?>
                             <label class="afw_pending_label afw_assigned_driver_label" id="assigned_driver"><?php echo __('Assigned to ') ?><?php echo esc_html(get_post_meta($order->get_id(), 'driver_firstname_billing', true)) ?></label>
                             <input type="button" value="Un-Assign" id="unassign-driver-btn" class="afw_resend_btn afw_unassign_btn" >
                          <?php } else { ?>
                             <select name="assign_driver" id="assign_driver" style="min-height: 10px;line-height: 22px;max-width: 35%;">
                                <?php
                                echo htmlspecialchars_decode('<option value="">Select</option>');
                                foreach ($driver_list as $driver) {
                                   //$driver_vehicle_name = $this->getVehicleNameOnDriverAssignment($key->vehicle_id);
                                   //echo htmlspecialchars_decode('<option value="' . $key->ID . '">' . $key->post_title . '</option>');
                                   if($driver['status'] == 'active'){
                                       echo htmlspecialchars_decode('<option value="' . $driver['memberId'] . '">' . $driver['firstName'] . " " . $driver['lastName'] . '</option>');
                                   }
                                }
                                ?>
                             </select>
                             
                             <input type="button" value="Assign" id="assign-driver-btn" class="afw_resend_btn"> <br />
                             <label id="driver-bill-error" style="color: red;display: none;"><?php echo __('Please select driver. ') ?></label>
                          <?php }
                           } ?>

                       </p>
                    </div>
                 <?php
              } else {
                 ?>
                    <div>
                       <p style="overflow-wrap: break-word;">
                          <?php echo esc_html($order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name']) ?> <br />
                          <strong><?php echo __('Phone: ') ?></strong><?php echo esc_html($order_data['billing']['phone']) ?><br />
                          <strong><?php echo __('E-mail: ') ?></strong><?php echo esc_html($order_data['billing']['email']) ?><br />
                       </p>
                       <p>
                          <strong><?php echo __('Addressya Username: ') ?></strong><br />
                          <?php echo esc_html($username) ?>
                       </p>
                       <p>
                          <strong><?php echo __('Connection Request Status: ') ?></strong><br />
                          <label class="afw_pending_label"><?php echo __('Permission revoked ') ?></label>
                       </p>
                    </div>
                 <?php
              }
           } else {
              ?>
                 <p style="overflow-wrap: break-word;">
                    <?php echo esc_html($order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name']) ?> <br />
                    <?php echo esc_html($order_data['billing']['address_1']) ?> <br />
                    <?php echo esc_html($order_data['billing']['address_2']) ?> <br />
                    <?php echo esc_html($order_data['billing']['city'] . ' ' . $order_data['billing']['postcode']) ?> <br />
                    <strong><?php echo __('Phone: ') ?></strong><?php echo esc_html($order_data['billing']['phone']) ?><br />
                    <strong><?php echo __('E-mail: ') ?></strong><?php echo esc_html($order_data['billing']['email']) ?><br />
                 </p>
              <?php
           }
        }
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

    protected static function response_bk( $end_point, $method, $body = array(), $headers = array() ) {
        
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

}

//var_dump( ADDRESSYA_API::get_token() );