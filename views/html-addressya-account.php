<?php defined( 'ABSPATH' ) || exit; ?>
<style>
.cbx-lft-mgn{
    margin-right:5px;
}
    </style>
    
<div class="addressya-checkout-container addressya_account addressy_username_form" style="display:none">
    <p class="form-row form-row-wide"> 
        <img src="<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/addressya_logo.svg'; ?>" width="120" alt="//">
    </p> 
    
    
    <p class="form-row form-row-wide">
        <input type="checkbox" checked="checked" name="onboard_addressya" id="onboard_addressya" class="cbx-lft-mgn" />Save this address on Addressya for easier checkout next time! Create your account below. <a href="https://addressya.com/app/" target="_blank">Learn more</a>
    </p>  
    <div id="addressya-account-form">
    <p class="form-row form-row-wide">
        <label>Email<abbr class="required" title="required">*</abbr></label>
        <span><input type="text" name="create_addressya_email" id="create_addressya_email"/></span>
    </p>
    <p class="form-row form-row-wide">
        <label>Password<abbr class="required" title="required">*</abbr></label>
        <span><input type="password" name="create_addressya_password" id="create_addressya_password"/></span>
    </p>
    <p class="form-row form-row-wide">
        <label>Username<abbr class="required" title="required">*</abbr></label>
        <span><input type="text" name="create_addressya_username" id="create_addressya_username"/></span>
        <span style="color: red; display:none;" id="create_addressya_username_err" class="addressya_error">Username already exists</span>
    </p>
    <p class="form-row form-row-wide">
        <label>Telephone<abbr class="required" title="required">*</abbr></label>
        <span><input type="text" name="create_addressya_telephone" id="create_addressya_telephone"/></span>
    </p>    
    <p class="form-row form-row-wide">        
        <span><input class="addressya_check addressya_accept_tc_check" type="checkbox" checked="checked" />I accept Addressya's <a href="https://addressya.com/terms-and-conditions/" target="_blank"> terms and conditions</a></span>
    </p>    
    <p class="form-row form-row-wide">        
        <span><input class="addressya_check addressya_share_address_check" type="checkbox" checked="checked" />Yes, please share my address details for this service delivery. I understand that I can revoke the permission at any time.</span>
        <span><input id="addressya_credit_enable" type="hidden" value="<?php echo get_option('addressya_credit_enable') ?>" /></span>
    </p>
    <?php if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') { ?>
    <div class="addressya_user_employer_detail">
        <h2 class="emp_detail_h1">Employer details</h2>      
        <div id="company_detail_form">
            <p class="form-row form-row-wide">
                <label>Company Name</label>
                <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="create_addressya_company_name" id="create_addressya_company_name" value="" /></span>
                <span style="color: red; display:none;" id="create_addressya_company_name_err" class="addressya_error">Employer name is required</span>
            </p> 
            <p class="form-row form-row-wide">
                <label>Employer Country</label>
                <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="create_addressya_company_country" id="create_addressya_company_country"/></span>
                <span style="color: red; display:none;" id="create_addressya_company_country_err" class="addressya_error">Employer country is required</span>
            </p>
            <p class="form-row form-row-first">
                <label>Employer City</label>
                <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="create_addressya_company_city" id="create_addressya_company_city"/></span>
                <span style="color: red; display:none;" id="create_addressya_company_city_err" class="addressya_error">Employer city is required</span>
            </p>
            <p class="form-row form-row-last">
                <label>Employer Region</label>
                <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="create_addressya_company_state" id="create_addressya_company_state"/></span>
                <span style="color: red; display:none;" id="create_addressya_company_state_err" class="addressya_error">Employer region is required</span>
            </p>
            <p class="form-row form-row-wide">
                <label>Employer Address Line 1</label>
                <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="create_addressya_company_address1" id="create_addressya_company_address1"/></span>
                <span style="color: red; display:none;" id="create_addressya_company_address1_err" class="addressya_error">Employer address line 1 is required</span>
            </p>
            <p class="form-row form-row-wide">
                <label>Employer Address Line 2</label>
                <span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="create_addressya_company_address2" id="create_addressya_company_address2"/></span>
            </p>   
        </div>
    </div>
    <?php } ?>
    <p class="form-row form-row-wide">        
        <span>Don't have Addressya yet?</span>
        Get it on <a href="https://play.google.com/store/apps/details?id=com.addressya.app&amp;pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1" target="_blank">Google Play</a> or use <a href="https://business.addressya.com/" target="_blank">Addressya for web</a>
    </p>


    <p class="form-row form-row-first">
        <input type="button" class="button alt" value="Create Account" id="create_account" />
    </p>  
    <div class="alert account_create_success col-sm-12" style="float:left; margin-bottom: 10px;color: #000000;background-color: #f3e26b;border-color: #f3e26b;padding:10px; display:none">
        <img style="float:left" width="20px" src="https://firebasestorage.googleapis.com/v0/b/map-project-refactor.appspot.com/o/opencart%2Flogo_no_text_small.png?alt=media&amp;token=05131c2b-10cd-4d39-86fa-ac0a5865f2dc">
        <span style="padding-left: 10px;">Your acount is created on Addressya. Thank you for using Addressya.</span>        
    </div> 
    
    <div class="alert account_create_failure col-sm-12" style="float:left; margin-bottom: 10px;color: #ffffff;background-color: #e2401c;border-color: #e2401c;padding:10px; display:none">        
        <span style="padding-left: 10px;">Email is already exist or email is wrong.</span>        
    </div>  
    </div>
</div>
