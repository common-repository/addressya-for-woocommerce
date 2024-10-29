<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
    <?php settings_errors(); ?>
    <form id="addressya-login-form" action="options.php" method="post">
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
                            <input style="width: 100%" type="email" name="addressya_business_name" id="addressya_business_name" required />
                        </div>
                    </div>
                    <div class="afw_main_block">
                        <div class="afw_main_head">Client Id: </div>
                        <div class="afw_main_input" id="client_id_title">
                            <input style="width: 100%" type="text" name="addressya_email" id="addressya_email" required />
                        </div>
                    </div>
                    <div class="afw_main_block">
                        <div class="afw_main_head">Password: </div>
                        <div class="afw_main_input" id="password_title">
                            <input style="width: 100%" type="password" name="addressya_password" id="addressya_password" required />
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