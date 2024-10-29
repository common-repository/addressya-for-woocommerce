<style>
    img{
        height: auto;
        max-width: 100%;
        display: block;
        border-radius: 3px;
        float: left;
    }
</style>
<?php defined( 'ABSPATH' ) || exit; ?>
<div class="addressya-checkout-container verify_otp_cont" style="display:none">
    <p class="form-row form-row-wide addressya_locations">
    </p>
    <p class="form-row form-row-wide">
        <span><a href="javascript:void(0)" class="addLocation">Add new location +</a></span>
    </p>

    <div class="alert send_code_success col-sm-12" style="margin-bottom: 10px; color: #000000;background-color: #f3e26b;border-color: #f3e26b;padding:10px;display:none">
        <img width="20px" src="https://firebasestorage.googleapis.com/v0/b/map-project-refactor.appspot.com/o/opencart%2Flogo_no_text_small.png?alt=media&amp;token=05131c2b-10cd-4d39-86fa-ac0a5865f2dc">
        <span  style="padding-left: 10px;">Please retrieve code sent to your Addressya app for sharing of your address!</span>        
    </div>
    
    <div id="otp_with_new_location" style="display:none">
        <p class="form-row form-row-wide">
            <label>Location Name</label>
            <span><input type="text" name="location_name" id="location_name"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Address Line 1</label>
            <span><input type="text" name="addressya_address1" id="addressya_address1"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Address Line 2</label>
            <span><input type="text" name="addressya_address2" id="addressya_address2"/></span>
        </p>    
        <p class="form-row form-row-wide">
            <label>Country</label>
            <span><input type="text" name="addressya_country" id="addressya_country"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>City</label>
            <span><input type="text" name="addressya_city" id="addressya_city"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>State/Region</label>
            <span><input type="text" name="addressya_state" id="addressya_state"/></span>
        </p>       
    </div>
    <?php if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') { ?>
    <div id="existing_company_detail">
        <p class="form-row form-row-wide">
            <span><b>Employer detail: </b>We already have your company detail with us.</span>
        </p>
    </div>
    <div id="company_detail_form">
        <p class="form-row form-row-wide">
            <span><b>Please provide your employer detail for credit application</b></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Company Name</label>
            <span><input type="text" name="addressya_company_name" id="addressya_company_name"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Employer Address Line 1</label>
            <span><input type="text" name="addressya_company_address1" id="addressya_company_address1"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Employer Address Line 2</label>
            <span><input type="text" name="addressya_company_address2" id="addressya_company_address2"/></span>
        </p>    
        <p class="form-row form-row-wide">
            <label>Employer Country</label>
            <span><input type="text" name="addressya_company_country" id="addressya_company_country"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Employer City</label>
            <span><input type="text" name="addressya_company_city" id="addressya_company_city"/></span>
        </p>
        <p class="form-row form-row-wide">
            <label>Employer State/Region</label>
            <span><input type="text" name="addressya_company_state" id="addressya_company_state"/></span>
        </p>
    </div>
    <?php } ?>
    <div>
        <p class="form-row form-row-wide">
            <label>Input Code</label>
            <span><input type="text" name="addressya_otp" id="addressya_otp"/></span>    
            Retrieve code sent to your Addressya app for sharing of your address
        </p>    
        <p class="form-row form-row-first">
            <input type="button" value="Verify code" id="verify_otp" />
        </p>        
    </div>
    <div class="alert verify_code_success col-sm-12" style="margin-bottom: 10px;color: #000000;background-color: #f3e26b;border-color: #f3e26b;padding:10px; float:left; display:none">
        <img width="20px" src="https://firebasestorage.googleapis.com/v0/b/map-project-refactor.appspot.com/o/opencart%2Flogo_no_text_small.png?alt=media&amp;token=05131c2b-10cd-4d39-86fa-ac0a5865f2dc">
       <span  style="padding-left: 10px;">Your address was shared. Thank you for using Addressya.</span>        
    </div>
</div>
<div class="addressya-checkout-container verify_otp_cont">
    <p class="form-row form-row-wide"> 
        <span><input class="addressya_check" name="addressya_checkout_option" value="default_address" type="radio"/><b>I want to use a new address</b></span>
    </p> 
    
</div>
