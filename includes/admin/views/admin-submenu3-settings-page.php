<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
    <?php settings_errors(); ?>
    <form id="addressya-features-form" action="options.php" method="post">
        <?php settings_fields('adressya_features'); ?>

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
                <h1>Enable/Disable features in Addressya </h1>
                <!-- <div class="afw_panel_style">
                    <div style="font-size:30px;display: inline-block;" class="dashicons dashicons-edit"></div>
                    <div class="afw_login_head">Driver </div>
                </div> -->
                <div class="afw_form_style" id="addressya-login-form-id">
                    <div class="afw_main_block">
                        <div class="afw_main_head">Driver  </div> 
                        <div class="afw_main_input" >
                            <input type="checkbox" name="addressya_driver_enable" <?php checked('on', get_option('addressya_driver_enable'), true); ?>> Enable/ Disable
                            <p class="description">You can enable and disable Driver Module.</p>
                        </div>
                    </div>

                    <div class="afw_main_block">
                        <div class="afw_main_head">Vehicle </div>
                        <div class="afw_main_input" >
                            <input type="checkbox" name="addressya_vehicle_enable" <?php checked('on', get_option('addressya_vehicle_enable'), true); ?>> Enable/ Disable
                            <p class="description">You can enable and disable Vehicle Module.</p>
                        </div>
                    </div>

                    <!-- <div class="afw_main_block">
                        <div class="afw_main_head">E-commerce </div>
                        <div class="afw_main_input" >
                            <input type="checkbox" name="addressya_ecom_enable" <?php checked('on', get_option('addressya_ecom_enable'),  true); ?>> Enable/ Disable
                            <p class="description">You can enable and disable E-commerce Module.</p>
                        </div>
                    </div> -->

                    <div class="afw_main_block">
                        <div class="afw_main_head">Credit Application </div>
                        <div class="afw_main_input" >
                            <input type="checkbox" name="addressya_credit_enable" <?php checked('on', get_option('addressya_credit_enable', true)); ?>> Enable/ Disable
                            <p class="description">You can enable and disable Credit Module.</p>
                        </div>
                    </div>


                    <!-- <div id="addressya_company_came">
                        <p> Hide/show this div </p>
                    </div> -->

                    <div style="padding-bottom: 5px;
    padding-top: 5px;
    padding-left: 15px;">
                        <div class=""> <?php submit_button(); ?> </div>
                    </div>
                </div>
                <!-- <p class="form-row form-row-first">
                    <input type="button" value="Save Credentialsh" id="save_credentials" />
                </p> -->
            </div>
    </form>
    <div>