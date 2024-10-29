<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
    <?php settings_errors(); ?>
        <?php settings_fields('addressya_credentials'); ?>
        <div class="addressya-credentials-container">
            <div id="wpbody-content">
                <div id="screen-meta" class="metabox-prefs">
                    <div id="contextual-help-wrap" class="hidden no-sidebar" tabindex="-1" aria-label="Contextual Help Tab">
                        <div id="contextual-help-back"></div>
                        <div id="contextual-help-columns">
                            <div class="contextual-help-tabs">
                                <ul>
                                </ul>
                            </div>
                            <div class="contextual-help-tabs-wrap">
                            </div>
                        </div>
                    </div>
                </div>
                <h1>Login to Addressya </h1>
                <div class="afw_panel_style">
                    <div style="font-size:30px;display: inline-block;" class="dashicons dashicons-edit"></div>
                    <div class="afw_login_head">Login </div>
                </div>
                <div class="afw_form_style" id="addressya-login-form-id">
                    <div class="afw_main_block">
                        <div class="afw_main_head">E-Mail: </div>
                        <div class="afw_main_input" id="e_mail_title">
                            <input style="width: 100%" type="email" name="addressya_email" value="<?php echo get_option( 'addressya_login_email' ); ?>" id="addressya_email" required />
                        </div>
                    </div>
                    <div class="afw_main_block">
                        <div class="afw_main_head">Client Id: </div>
                        <div class="afw_main_input" id="client_id_title">
                            <input style="width: 100%" type="text" name="addressya_client_id" value="<?php echo get_option( 'addressya_client_id' ); ?>" id="addressya_client_id" required />
                        </div>
                    </div>
                    <div class="afw_main_block">
                        <div class="afw_main_head">Password: </div>
                        <div class="afw_main_input" id="password_title">
                            <input style="width: 100%" type="password" name="addressya_password" value="<?php echo get_option( 'addressya_password' ); ?>" id="addressya_password" required />
                        </div>
                    </div>  
                    
                    <div class="afw_main_block orgnisation_cont">
                        <div class="afw_main_head">Organisation: </div>
                        <div class="afw_main_input" id="password_title">
                            <input style="width: 100%" disabled type="text" name="addressya_business_name" value="<?php echo get_option( 'addressya_business_name' ); ?>" id="addressya_business_name" required />
                        </div>
                    </div>  
                    <div id="org-cont" style="display:none"></div>

                    <!-- <div id="addressya_company_came">
                        <p> Hide/show this div </p>
                    </div> -->

                    <div class="afw_main_block" style="padding-left:20px">
                        <input type="button" value="Verify" id="verify_credentials" class="button button-primary" />
                        <input type="button" style="padding-left:20px; display:none" value="Save Credentials" id="save_credentials" class="button button-primary" />
                        <?php //submit_button(); ?> 
                    </div>
                </div>
                <!-- <p class="form-row form-row-first">
                    <input type="button" value="Save Credentialsh" id="save_credentials" />
                </p> -->
            </div>
    <div>
        
<script>
      
      jQuery(document).ready(function($) {
        
        $("#verify_credentials").on('click', function() {
            //alert('verify_credentials');

            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action':'addressya_verify_credentials',
                    'email': $("#addressya_email").val(),
                    'password': $("#addressya_password").val(),
                    'client_id': $("#addressya_client_id").val(),
                },
                success: function (response) {  

                    var data_option = '';
                    var organization_list = '';    
                    for(i in response['data']['organizationData']){
                        data_option += '<option value="' + response['data']['organizationData'][i]['id'] + '">' + response['data']['organizationData'][i]['businessName'] + '</option>';                    
                    }
                    //data_option += '<option value="M8TCCEVwS8MOdzHo01Dp">Travel</option>';                    
                    organization_list = '<div class="afw_main_block"><div class="afw_main_head">Organisation: </div><div class="afw_main_input" id="password_title"><div style="width:100%" ><input type="hidden" value="' + response['data']['user_uid'] + '" id="addressya_user_uid" /><select style="width: 100%" name="organization_id" id="input-organization-id" class="form-control"><option value="">Select</option>' + data_option + '</select></div><div style="width:100%" >'+ response['data']['message'] +'</div></div></div>';
                    console.log('organization_list');
                    console.log(organization_list);
                    $("#org-cont").show();
                    $(".orgnisation_cont").hide();
                    $("#org-cont").html(organization_list);
                    $("#save_credentials").show();
                } 
            });
        })
        $("#save_credentials").on('click', function() {
           // preventDefault(e);
            var orgnisation_name = $( "#input-organization-id option:selected" ).text();
            var orgnisation_id = $( "#input-organization-id option:selected" ).val();
            if(orgnisation_name ){
                
            }

            console.log( $("#addressya_email").val() );

            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action':'addressya_save_credentials',
                    'addressya_email': $("#addressya_email").val(),
                    'addressya_password': $("#addressya_password").val(),
                    'addressya_client_id': $("#addressya_client_id").val(),
                    'addressya_user_uid': $("#addressya_user_uid").val(),
                    'addressya_business_name': orgnisation_name,
                    'addressya_business_id': orgnisation_id,
                },
                success: function (response) {  
                    console.log(response);
                }
            } );
            
        });
    });
</script>