<?php defined( 'ABSPATH' ) || exit; ?>

<div class="addressya-checkout-container">
    
<p class="form-row form-row-wide"> 
        <span><input class="addressya_check" name="addressya_checkout_option" value="addressya_address" type="radio" checked/><b>I want to use my Addressya address</b></span>
    </p> 
    
    <div class="addressy_username_form">
    <p class="form-row form-row-wide"> 
        <img style="padding-bottom:10px" src="<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/addressya_logo.svg'; ?>" width="120" alt="//">
    </p> 
        <p class="form-row form-row-first">
            <label>First Name <abbr class="required" title="required">*</abbr></label>
            <span><input type="text" name="addressya_billing_first_name" id="addressya_billing_first_name"/></span>
        </p>
        <p class="form-row form-row-last">
            <label>Last Name <abbr class="required" title="required">*</abbr></label>
            <span><input type="text" name="addressya_billing_last_name" id="addressya_billing_last_name"/></span>
        </p>
        
        <p class="form-row form-row-wide">
            <label>Email <abbr class="required" title="required">*</abbr></label>
            <span><input type="text" name="addressya_billing_email" id="addressya_billing_email"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Phone <abbr class="required" title="required">*</abbr></label>
            <span><input type="text" name="addressya_billing_phone" id="addressya_billing_phone"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Addressya Username <abbr class="required" title="required">*</abbr></label>            
            <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_username" id="addressya_username"/></span>    
            <span style="font-size:12px"><input class="addressya_check" id="choose_addressya" type="checkbox" checked/><b>Yes, please share my address details for this service delivery. I understand that I can revoke the permission at any time</b></span>
        </p>    
        <p class="form-row form-row-first">
            <input type="button" class="button alt" value="Confirm" id="send_otp" />
        </p>
        
        <p class="form-row form-row-wide">
        <span><b>Get Addressya on
                        <a href="https://play.google.com/store/apps/details?id=com.addressya.app&amp;pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1" target="_blank"> Google Play</a> or use 
                        <a href="https://app.addressya.com/" target="_blank">Addressya Web App</a></b>
                        </span>
        </p>
        <div class="verify_otp_cont" style="display:none;">
            <input type="hidden" id="request_id" name="request_id" value="">
            <input type="hidden" id="addressya_username_verified" name="addressya_username_verified" value="false">
            <input type="hidden" id="addressya_location_confirmed" name="addressya_location_confirmed" value="false">
            <p class="form-row form-row-wide">
                <label>Input Code</label>
                <span class="woocommerce-input-wrapper"><input type="text"  class="input-text" name="addressya_otp" id="addressya_otp"/></span>
                <span style="font-size:12px">Retrieve code sent to your Addressya app for sharing of your address</span>
            </p>    
            <p class="form-row form-row-first" style="float:none;" >
                <input type="button" class="button alt" value="Verify code" id="verify_otp" />
            </p>
        </div>

    <div class=" after_verify_otp_cont" style="display:none">
        <div class="addressya_user_locations">
            <p class="form-row form-row-wide addressya_locations"></p>
            <p class="form-row form-row-wide addressya_locations_img"></p>
            <p class="form-row form-row-wide addressya_locations_score">
                <!-- <span style="width: 50%;float: left;">Address score: 7/10</span><span style="width: 50%;text-align: right;float: left;">Proof of residency: 70%</span> -->
            </p>
            <p class="form-row form-row-wide add_location_button">
                <span><a href="javascript:void(0)" class="addNewLocationBtn">+ Add new location</a></span>
            </p>
            <div id="otp_with_new_location" style="display:none">
                <p class="form-row form-row-wide addLocationSection">
                    <input type="radio" id="addLocation" name="addLocation" class="addLocation" value="Add new location">
                    <span style="width: 70%;" for="addLocation">Add new location</span>
                </p>
                <p class="form-row form-row-wide">
                    <label>Address Name</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="location_name" id="location_name"/></span>
                </p>
                <p class="form-row form-row-wide">
                    <label>Country</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_country" id="addressya_country"/></span>
                </p>
                <p class="form-row form-row-first">
                    <label>Town/City</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_city" id="addressya_city"/></span>
                </p>    
                <p class="form-row form-row-last">
                    <label>Region</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_state" id="addressya_state"/></span>
                </p>
                <p class="form-row form-row-first">
                    <label>Area</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_area" id="addressya_area"/></span>
                </p>
                <p class="form-row form-row-last">
                    <label>Postal/Zip code</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_zipcode" id="addressya_zipcode"/></span>
                </p>
                <p class="form-row form-row-wide">
                    <label>Street detail</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" placeholder="House number and Street name" name="addressya_address1" id="addressya_address1"/></span>
                </p>       
                <p class="form-row form-row-wide">             
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" placeholder="Building name/Apartment etc." name="addressya_address2" id="addressya_address2"/></span>
                </p>  
                <p class="form-row form-row-wide">
                    <input type="button" class="button alt" value="Add Address" id="add_user_address" />
                </p> 
            </div>

            <!-- Only after adding new location -->
            <div id="after_location_added" style="display:none">
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left"><b>Address details</b></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Address name</label>
                    <label class="label_right" id="resLocationName"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Country</label>
                    <label class="label_right" id="resLocationCountry"></label>
                </p>    
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Town/City</label>
                    <label class="label_right" id="resLocationCity"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Region</label>
                    <label class="label_right" id="resLocationRegion"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Area</label>
                    <label class="label_right" id="resLocationArea"></label>
                </p>                   
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Postal/Zip code</label>
                    <label class="label_right" id="resLocationZipcode"></label>
                </p>                    
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Street details</label>
                    <label class="label_right" id="resLocationStreetName"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Address score</label>
                    <label class="label_right" id="resLocationAddressScore">4/10</label>
                </p>    
                    
                <p class="form-row form-row-wide address_success_msg">
                    <span style="color:#0170B9;font-size:18px"><i class="fa fa-check" aria-hidden="true"></i> Address has been added</span>
                </p>  
                <p class="form-row form-row-wide ">
                    <span style="font-size:14px">Please update the GPS coordinates of this address when you're at the location by signing in to <a href="https://app.addressya.com/" target="_blank">Addressya App</a>. Update more address details to achieve a good score.</span>
                </p>  
            </div> 
        </div>
        <?php if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') { ?>
        <div class="addressya_user_employer_detail">
            <h2 class="emp_detail_h1">Employer details</h2>
            <!-- If the company detail is already there -->
            <div id="existing_company_detail">
                <p class="form-row form-row-wide location_detail_sect">
                    <span>Please confirm your employer details and proceed. You may edit to update the details.</span>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Company Name</label>
                    <label class="label_right" id="empCompanyName"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Employer Country</label>
                    <label class="label_right" id="empCountry"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Employer City</label>
                    <label class="label_right" id="empCity"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Employer Region</label>
                    <label class="label_right" id="empRegion"></label>
                </p>
                <p class="form-row form-row-wide location_detail_sect">
                    <label class="label_left">Employer Address</label>
                    <label class="label_right" id="empAddress"></label>
                </p>
                <p class="form-row form-row-wide update_employer_button">
                    <span><a href="javascript:void(0)" class="updateEmployer">Edit employer details</a></span>
                </p>
            </div>
            <!-- If the company detail is not exist -->
            <div id="company_detail_form">
                <p class="form-row form-row-wide">
                    <label>Company Name</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_company_name" id="addressya_company_name" value="" /></span>
                </p> 
                <p class="form-row form-row-wide">
                    <label>Employer Country</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_company_country" id="addressya_company_country"/></span>
                </p>
                <p class="form-row form-row-first">
                    <label>Employer City</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_company_city" id="addressya_company_city"/></span>
                </p>
                <p class="form-row form-row-last">
                    <label>Employer Region</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_company_state" id="addressya_company_state"/></span>
                </p>
                <p class="form-row form-row-wide">
                    <label>Employer Address Line 1</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_company_address1" id="addressya_company_address1"/></span>
                </p>
                <p class="form-row form-row-wide">
                    <label>Employer Address Line 2</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="addressya_company_address2" id="addressya_company_address2"/></span>
                </p>   
                
                <p class="form-row form-row-wide">
                    <input type="button" class="button alt" value="Update details" id="update_emp_detail" />
                </p>   
            </div>
        </div>
        <?php } ?>

        <p class="form-row form-row-first confirm_location_button" style="float:none;" >
            <input type="button" class="button alt" value="Confirm" id="confirm_location" />
        </p> 

        <div class="alert verify_code_success col-sm-12" style="margin-bottom: 10px;color: #000000;background-color: #f3e26b;border-color: #f3e26b;padding:10px; display:none">
            <img style="float:left" width="20px" src="https://firebasestorage.googleapis.com/v0/b/map-project-refactor.appspot.com/o/opencart%2Flogo_no_text_small.png?alt=media&amp;token=05131c2b-10cd-4d39-86fa-ac0a5865f2dc">
            <span style="padding-left: 10px;">Your address was shared. Thank you for using Addressya.</span>        
        </div>
    </div>
    
    </div>
</div>

<div class="default-checkout-container">
    <p class="form-row form-row-wide"> 
        <span><input class="addressya_check" name="addressya_checkout_option" value="default_address" type="radio"/><b>I want to use a new address</b></span>
    </p>     
</div>
