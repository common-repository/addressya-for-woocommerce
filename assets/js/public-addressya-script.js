( function( $ ) {
    "use strict";
    $( document ).ready( function() {
        $(".woocommerce-billing-fields__field-wrapper").hide();
        $( ".addressya_check" ).on( 'click', function() {
            //alert($('input[type=radio][name="addressya_check"]:checked').val());
            if($('input[type=radio][name="addressya_checkout_option"]:checked').val() == 'addressya_address'){
                $(".woocommerce-billing-fields__field-wrapper").hide();
                $(".addressy_username_form").show();
                $(".addressya_account").hide();
            } else {
                $(".woocommerce-billing-fields__field-wrapper").show();
                $(".addressy_username_form").hide();
                $(".addressya_account").show();
                if($("#billing_email").val() != ""){
                    $( '#create_addressya_email' ).val($("#billing_email").val());
                }
                if($("#billing_phone").val() != ""){
                    $( '#create_addressya_telephone' ).val($("#billing_phone").val());
                }
            }            
        });
 

        $( '#send_otp' ).on( 'click', function() {
            $('#m-spinner').addClass('is-active');
            var username = $( '#addressya_username' ).val();
            $(".addressya_error").remove();
            if (!username) {
                $('#addressya_username').after('<div style="color: red;" class="addressya_error">Please enter your Addressya username</div>');
                return;
            }
            if (!$("#choose_addressya").prop('checked')) {
                $('#choose_addressya').parent().after('<div style="color: red;" class="addressya_error">Please check this box to proceed</div>');
                return;
            }
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action':'addressya_send_otp',
                    'username': username,
                },
                success: function (response) {               
                    console.log(response);                 
                    if(response.data.status == 404){
                        $('#addressya_username').after('<div style="color: red;" class="addressya_error">'+response.data.message+'</div>');
                        return;
                    } else {
                        $(".verify_otp_cont").show();
                        $("#request_id").val(response.data.requestId);
                        $('#send_otp').attr('disabled','disabled');
                        //$('.addressya_locations').html(`<input type="hidden" id="request_id" name="request_id" value="${response.data.requestId}">`);
                        /* let companyId = '';
                        $("#existing_company_detail").hide();
                        $("#company_detail_form").show();
                        if(response.data.companyLocations.length > 0){
                            companyId = response.data.companyLocations[0].lid;
                            $("#company_detail_form").hide();
                            $("#existing_company_detail").show();
                        }

                        let j= 0;
                        let location_html = '';
                        for (let i = 0; i < response.data.locations.length; i++) {
                            if (j == 2)
                                j = 0;
                            if (j == 0) {
                                location_html += `<div style="display: flex;">`;
                            }
                            j++;
                            location_html += `<div style="width: 50%;">
                                                <input type="radio" id="${response.data.locations[i].id}" name="customer_location" class="select_location" value="${response.data.locations[i].id}">
                                                <span style="width: 70%;" for="${response.data.locations[i].id}">${response.data.locations[i].locationName}</span>
                                            </div>`;
                            if (j == 2 || i == response.data.locations.length - 1) {
                                location_html += `</div>`;
                            }
                        }
                        $(".send_code_success").show();
                        setTimeout(() => {
                            $('.addressya_locations').html(`<label><b>Select location:</b></label><span>${location_html}</span><input type="hidden" id="request_id" name="request_id" value="${response.data.requestId}"><input type="hidden" id="company_id" name="company_id" value="${companyId}">`);
                            $(".verify_otp_cont").show();
                            $('.select_location' ).on( 'click', function() {
                                $("#otp_with_new_location").hide();
                            } );
                        }, 1000); */
                        
                        
                    }
                }
            } );
        } );

        $( '.addNewLocationBtn' ).on( 'click', function() {
            $("#otp_with_new_location").show();     
            $(".add_location_button").hide();            
            $("#selected_location_map").hide(); 
            $(".confirm_location_button").hide();
            $(".addressya_locations_score").hide();
            $("input[type=radio][name=customer_location]").prop('checked', false);
            $("input[type=radio][name=addLocation]").prop('checked', true);
            
        } );

        $(".updateEmployer").on( 'click', function() {
            $("#company_detail_form").show();     
            $("#existing_company_detail").hide();  
            $(".confirm_location_button").hide();
        });
        /* $('.select_location' ).on( 'click', function() {
            $("#otp_with_new_location").hide();
        } ); */

        $( '#verify_otp' ).on( 'click', function() {
            $(".addressya_error").remove();
            
            var addressya_username = $('input[type=text][name="addressya_username"]').val();
            var addressya_otp = $('input[type=text][name="addressya_otp"]').val();
            var request_id = $('input[type=hidden][name="request_id"]').val();
            if(addressya_otp != '')
                $('#addressya_otp').attr('disabled','disabled');

            if (!addressya_otp) {
                $('#addressya_otp').after('<div style="color: red;" class="addressya_error">Please enter code.</div>');
                return;
            }
            
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action':'addressya_verify_otp',
                    'addressya_username': addressya_username,
                    'addressya_otp': addressya_otp,
                    'request_id': request_id,
                },
                success: function (response) {
                    console.log(response);
                    if (response.data.status == 200) {
                        //$('.verify_code_success').show();
                        $('#verify_otp').attr('disabled','disabled');
                        let companyId = '';
                        $("#existing_company_detail").hide();
                        $("#company_detail_form").show();
                        $("#addressya_username_verified").val("true");
                        if(response.data.companyLocations.length > 0){
                            companyId = response.data.companyLocations[0].lid;
                            $("#company_detail_form").hide();
                            $("#existing_company_detail").show();

                            $("#addressya_company_name").val(response.data.companyLocations[0].details.locationName);
                            $("#addressya_company_country").val(response.data.companyLocations[0].details.country);
                            $("#addressya_company_city").val(response.data.companyLocations[0].details.city);
                            $("#addressya_company_state").val(response.data.companyLocations[0].details.region);
                            $("#addressya_company_address1").val(response.data.companyLocations[0].details.houseNumber);
                            $("#addressya_company_address2").val(response.data.companyLocations[0].details.streetName);

                            $("#empCompanyName").html(response.data.companyLocations[0].details.locationName);
                            $("#empCountry").html(response.data.companyLocations[0].details.country);
                            $("#empCity").html(response.data.companyLocations[0].details.city);
                            $("#empRegion").html(response.data.companyLocations[0].details.region);
                            $("#empAddress").html(response.data.companyLocations[0].details.houseNumber + ", " + response.data.companyLocations[0].details.streetName);
                        }

                        let j= 0;
                        let location_html = '';
                        let lat = "";
                        let lon = "";
                        let src = "";
                        let initialMapImg = "";
                        let mapAPIKey = "AIzaSyB3Bk9l7T7wFqI7Yie-upi2e12RP1rn_wg";
                        
                        if(response.data.locations.length > 0){
                            for (let i = 0; i < response.data.locations.length; i++) {
                                lat = response.data.locations[i].position.latitude;
                                lon = response.data.locations[i].position.longitude;
                                src = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lon}&zoom=15&size=600x160&scale=2&sensor=false&markers=color:red%7Clabel:S%7C${lat},${lon}&key=${mapAPIKey}`;
                                if (j == 2)
                                    j = 0;
                                if (j == 0) {
                                    //location_html += `<div style="display: flex;">`;
                                }
                                j++;
                                if (i == 0){
                                    initialMapImg = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lon}&zoom=15&size=600x160&scale=2&sensor=false&markers=color:red%7Clabel:S%7C${lat},${lon}&key=${mapAPIKey}`;

                                    location_html += `<div style="width: 50%; float:left;"><input type="hidden" id="key${i}" name="key${i}" value="${i}"><input type="radio" checked id="${response.data.locations[i].lid}" name="customer_location" class="select_location" value="${response.data.locations[i].lid}"><input type="hidden" id="map_image_src_${i}" name="map_image_src_${i}" value="${src}"> <label style="width: 70%; display: unset;" for="${response.data.locations[i].lid}">${response.data.locations[i].details.locationName}</label><input type="hidden" id="location_score_${i}" name="location_score_${i}" value="${response.data.locations[i].config.score}"><input type="hidden" id="proof_of_res_${i}" name="proof_of_res_${i}" value="${response.data.locations[i].config.proofOfResScore}"><input type="hidden" id="type_${i}" name="type_${i}" value="${response.data.locations[i].config.type}"> </div>`;
                                } else {
                                     
                                    location_html += `<div style="width: 50%; float:left;"><input type="hidden" id="key${i}" name="key${i}" value="${i}"><input type="radio" id="${response.data.locations[i].lid}" name="customer_location" class="select_location" value="${response.data.locations[i].lid}"><input type="hidden" id="map_image_src_${i}" name="map_image_src_${i}" value="${src}"> <label style="width: 70%; display: unset;" for="${response.data.locations[i].lid}">${response.data.locations[i].details.locationName}</label><input type="hidden" id="location_score_${i}" name="location_score_${i}" value="${response.data.locations[i].config.score}"><input type="hidden" id="proof_of_res_${i}" name="proof_of_res_${i}" value="${response.data.locations[i].config.proofOfResScore}"><input type="hidden" id="type_${i}" name="type_${i}" value="${response.data.locations[i].config.type}"></div>`;
                                }
                                if (j == 2 || i == response.data.locations.length - 1) {
                                    //location_html += `</div>`;
                                }
                            }
                            $(".send_code_success").show();
                            setTimeout(() => {
                                $('.addressya_locations').html(`<label><b>Select location:</b></label><span>${location_html}</span><input type="hidden" id="addressya_token" name="addressya_token" value="${response.data.token}"><input type="hidden" id="company_id" name="company_id" value="${companyId}">`);
                                $('.addressya_locations_img').html(`<img id="selected_location_map" src="${initialMapImg}" />`);
                                if(typeof response.data.locations[0].config.score != "undefined" && response.data.locations[0].config.score != ""){
                                    $('.addressya_locations_score').html(`<span style="width: 50%;float: left;"><label id="locationScore" >Address score: ${response.data.locations[0].config.score}/10</label></span><span style="width: 50%;text-align: right;float: left;"><label id="proofOfResidency" style="display: unset;" ></label></span>`);
                                }       
                                if(typeof response.data.locations[0].config.proofOfResScore != "undefined" && response.data.locations[0].config.proofOfResScore >= 0 && response.data.locations[0].config.type == "home"){
                                    var proof_of_residency = response.data.locations[0].config.proofOfResScore;
                                    $('#proofOfResidency').html('Proof of residency: '+ proof_of_residency +'%');
                                    $("#proofOfResidency").show();
                                }
                                $(".after_verify_otp_cont").show();

                                $('.select_location' ).on( 'click', function() {
                                    
                                    
                                    var index = $(`#${this.id}`).prev().val();                                
                                    $(".confirm_location_button").show();  
                                    var updateImageSrc = $(`#${this.id}`).next().val();
                                    var locationLabel = $(`#${this.id}`).next().next().html();
                                    var location_score = $(`#${this.id}`).next().next().next().val();
                                    var proof_of_residency = $(`#${this.id}`).next().next().next().next().val();
                                    var addressya_type = $(`#${this.id}`).next().next().next().next().next().val();
                                    
                                    if(location_score == "undefined"){
                                        $('.addressya_locations_score').html('');
                                    } else {
                                        $('.addressya_locations_score').html(`<span style="width: 50%;float: left;"><label id="locationScore" >Address score: ${location_score}/10</label></span><span style="width: 50%;text-align: right;float: left;"><label id="proofOfResidency" style="display: unset;" ></label></span>`)
                                        $(".addressya_locations_score").show();

                                    }
                                    //if(typeof response.data.locations[index].config.proofOfResScore != "undefined" && response.data.locations[index].config.proofOfResScore >= 0 && response.data.locations[index].config.type == "home"){
                                    if(proof_of_residency != "undefined" && proof_of_residency >= 0 && addressya_type == "home"){
                                        //var proof_of_residency = response.data.locations[index].config.proofOfResScore;
                                        $('#proofOfResidency').html('Proof of residency: '+ proof_of_residency +'%');
                                        $("#proofOfResidency").show();
                                    } else {                                        
                                        $('#proofOfResidency').html('');
                                        $("#proofOfResidency").hide();
                                    }
                                    $("#otp_with_new_location").hide();
                                    if(updateImageSrc != ''){
                                        $("#selected_location_map").show();
                                        $("#selected_location_map").attr('src', updateImageSrc);
                                    } else {
                                        $("#selected_location_map").hide();
                                        $("#selected_location_map").attr('src', '');
                                    }
                                    $(".add_location_button").show();
                                    $("input[type=radio][name=addLocation]").prop('checked', false);
                                } );
                                if(response.data.locations[0].details.locationName == 'home' && response.data.locations[0].config.proofOfResScore != ''){
                                    $('#proofOfResidency').html('Proof of residency: ' + response.data.locations[0].config.proofOfResScore);
                                    $("#proofOfResidency").show();
                                }
                            }, 1000);
                        } else {
                            $(".after_verify_otp_cont").show();
                            $("#otp_with_new_location").show();    
                            $(".add_location_button").hide();  
                        }
                    } else if(response.data.status == 422) {
                        
                        $('#addressya_otp').removeAttr('disabled');
                        $('#addressya_otp').after('<div style="color: red;" class="addressya_error">' + response.data.message + '</div>');
                    }
                }
            } );
        } );

        $( '#add_user_address' ).on( 'click', function() {
            $(".addressya_error").remove();
            
            var addressya_username = $('input[type=text][name="addressya_username"]').val();
            var request_id = $('input[type=hidden][name="request_id"]').val();
            var addressya_token = $('input[type=hidden][name="addressya_token"]').val();
            var selected_location = $('input[type=radio][name="customer_location"]:checked').val();    
            var adding_new_location = $('input[type=radio][name="addLocation"]:checked').val();        

            var location_name = $('input[type=text][name="location_name"]').val();
            var addressya_address1 = $('input[type=text][name="addressya_address1"]').val();
            var addressya_address2 = $('input[type=text][name="addressya_address2"]').val();
            var addressya_area = $('input[type=text][name="addressya_area"]').val();
            var addressya_zipcode = $('input[type=text][name="addressya_zipcode"]').val();
            var addressya_country = $('input[type=text][name="addressya_country"]').val();
            var addressya_city = $('input[type=text][name="addressya_city"]').val();
            var addressya_state = $('input[type=text][name="addressya_state"]').val();
            
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action'                        : 'addressya_add_user_address',
                    'addressya_username'            : addressya_username,
                    'request_id'                    : request_id,
                    'addressya_token'               : addressya_token,
                    'location_name'                 : location_name,
                    'addressya_address1'            : addressya_address1,
                    'addressya_address2'            : addressya_address2,
                    'addressya_area'                : addressya_area,
                    'addressya_zipcode'             : addressya_zipcode, 
                    'addressya_city'                : addressya_city,
                    'addressya_state'               : addressya_state,
                    'addressya_country'             : addressya_country
                },
                success: function (result) {
                    console.log(result);
                   if (result.data.status == 200) {
                        if(!selected_location){
                            $('.addressya_locations_score').html('');
                            $(".add_location_button").hide(); 
                            $("#otp_with_new_location").hide();
                            $("#after_location_added").show();
                            $(".confirm_location_button").show();    
                            $("#resLocationName").html(result.data.userLocation.details.locationName);
                            $("#resLocationCountry").html(result.data.userLocation.details.country);
                            $("#resLocationCity").html(result.data.userLocation.details.city);
                            $("#resLocationRegion").html(result.data.userLocation.details.region);
                            $("#resLocationArea").html(result.data.userLocation.details.area);
                            $("#resLocationZipcode").html(result.data.userLocation.details.zipcode);
                            $("#resLocationStreetName").html(result.data.userLocation.details.streetName);
                            $("#resLocationAddressScore").html(result.data.userLocation.config.score + "/10");

                            var mapAPIKey = "AIzaSyB3Bk9l7T7wFqI7Yie-upi2e12RP1rn_wg";
                            var lat = result.data.userLocation.position.latitude;
                            var lon = result.data.userLocation.position.longitude;
                            var src = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lon}&zoom=15&size=600x160&scale=2&sensor=false&markers=color:red%7Clabel:S%7C${lat},${lon}&key=${mapAPIKey}`;
                            
                            $('.addressya_locations_img').html(`<img id="selected_location_map" src="${src}" />`);
                            var resLocScore;
                            if(result.data.userLocation.config.score == "undefined"  || typeof result.data.userLocation.config.score == "undefined"){
                                resLocScore = 0;
                            } else {
                                resLocScore = result.data.userLocation.config.score;                               
                            }
                            var resLocProofOfResScore;
                            if(result.data.userLocation.config.proofOfResScore == "undefined"  || typeof result.data.userLocation.config.proofOfResScore == "undefined"){
                                resLocProofOfResScore = 0;
                            } else {
                                resLocProofOfResScore = result.data.userLocation.config.proofOfResScore;                               
                            }
                            
                            //$("#selected_location_map").hide();         // hidding map here
                            var index = 786;
                            var location_html = `<div style="width: 50%; float:left;"><input type="hidden" id="key_${index}" name="key_${index}" value="786"><input type="radio" id="${result.data.userLocation.lid}" name="customer_location" class="select_location" value="${result.data.userLocation.lid}" checked><input type="hidden" id="map_image_src_${index}" name="map_image_src_${index}" value="${src}"> <label style="width: 70%; display: unset;" for="${result.data.userLocation.lid}">${result.data.userLocation.details.locationName}</label><input type="hidden" id="location_score_${index}" name="location_score_${index}" value="${resLocScore}"><input type="hidden" id="proof_of_res_${index}" name="proof_of_res_${index}" value="${resLocProofOfResScore}"><input type="hidden" id="type_${index}" name="type_${index}" value="${result.data.userLocation.config.type}"></div>`;
                            //$(".addressya_locations").append(`<div style="width: 50%; float:left;"><input type="radio" id="${result.data.userLocation.lid}" name="customer_location" class="select_location" value="${result.data.userLocation.lid}" checked><input type="hidden" id="map_image_src" name="map_image_src" value=""> <label style="width: 70%; display: unset;" for="${result.data.userLocation.lid}">${result.data.userLocation.details.locationName}</label></div>`);
                            $(".addressya_locations").append(location_html);
                            console.log('result.data.userLocation.config.score');
                            console.log(result.data.userLocation.config.score);
                            if(typeof result.data.userLocation.config.score != "undefined" && result.data.userLocation.config.score != ""){
                                $('.addressya_locations_score').html(`<span style="width: 50%;float: left;"><label id="locationScore" >Address score: ${result.data.userLocation.config.score}/10</label></span><span style="width: 50%;text-align: right;float: left;"><label id="proofOfResidency" style="display: unset;" ></label></span>`);
                                $(".addressya_locations_score").show();
                            }   
                               
                            if(typeof result.data.userLocation.config.proofOfResScore != "undefined" && result.data.userLocation.config.proofOfResScore >= 0 && result.data.userLocation.config.type == "home"){
                                var proof_of_residency = result.data.userLocation.config.proofOfResScore;
                                $('#proofOfResidency').html('Proof of residency: '+ proof_of_residency +'%');
                                $("#proofOfResidency").show();
                            }
                                
                        }
                        $('.select_location' ).on( 'click', function() {
                                 
                            //alert(this.id);
                            $(".confirm_location_button").show();  
                            var updateImageSrc = $(`#${this.id}`).next().val();
                            var locationLabel = $(`#${this.id}`).next().next().html();
                            var location_score = $(`#${this.id}`).next().next().next().val();                            
                            var proof_of_residency = $(`#${this.id}`).next().next().next().next().val();
                            var address_type = $(`#${this.id}`).next().next().next().next().next().val();
                            console.log('proof_of_residency');
                            console.log(proof_of_residency);
                            if(location_score == "undefined"  || typeof location_score == "undefined"){
                                $('.addressya_locations_score').html('');
                            } else {
                                $('.addressya_locations_score').html(`<span style="width: 50%;float: left;"><label id="locationScore" >Address score: ${location_score}/10</label></span><span style="width: 50%;text-align: right;float: left;"><label id="proofOfResidency" style="display: unset;" ></label></span>`)

                            }

                            if(proof_of_residency != "undefined" && proof_of_residency >= 0 && address_type == "home"){
                                console.log('here');
                                //var proof_of_residency = result.data.userLocation.config.proofOfResScore;
                                $('#proofOfResidency').html('Proof of residency: '+ proof_of_residency +'%');
                                $("#proofOfResidency").show();
                            } else {                                        
                                $('#proofOfResidency').html('');
                                $("#proofOfResidency").hide();
                            }
                            /* if(locationLabel == 'home'){
                                $('#proofOfResidency').html('Proof of residency: 70%');
                                $("#proofOfResidency").show();
                            } else {                                        
                                $('#proofOfResidency').html('');
                                $("#proofOfResidency").hide();
                            } */
                            $("#otp_with_new_location").hide();
                            if(updateImageSrc != ''){
                                $("#selected_location_map").show();
                                $("#selected_location_map").attr('src', updateImageSrc);
                            } else {
                                $("#selected_location_map").hide();
                                $("#selected_location_map").attr('src', '');
                            }
                            //$(".add_location_button").show();
                            $("input[type=radio][name=addLocation]").prop('checked', false);
                        } );
                    }
                }
            } );
        } );

        $( '#update_emp_detail' ).on( 'click', function() {
            $(".addressya_error").remove();            
            
            var addressya_username = $('input[type=text][name="addressya_username"]').val();
            var request_id = $('input[type=hidden][name="request_id"]').val();
            var addressya_token = $('input[type=hidden][name="addressya_token"]').val();
            
            var company_id = $('input[type=hidden][name="company_id"]').val();
            var addressya_company_name = $('input[type=text][name="addressya_company_name"]').val();
            var addressya_company_address1 = $('input[type=text][name="addressya_company_address1"]').val();
            var addressya_company_address2 = $('input[type=text][name="addressya_company_address2"]').val();
            var addressya_company_country = $('input[type=text][name="addressya_company_country"]').val();
            var addressya_company_city = $('input[type=text][name="addressya_company_city"]').val();
            var addressya_company_state = $('input[type=text][name="addressya_company_state"]').val();

            if(company_id == ''){
                if(addressya_company_name == '' || addressya_company_address1 == '' || addressya_company_country == '' || addressya_company_city == '' || addressya_company_state == ''){
                    $('#company_detail_form').after('<div style="color: red;" class="addressya_error">Please enter employer details.</div>');
                    return;
                }
            }
            
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action'                        : 'addressya_update_emp_detail',
                    'addressya_username'            : addressya_username,
                    'request_id'                    : request_id,
                    'addressya_token'               : addressya_token,
                    'company_id'                    : company_id,
                    'addressya_company_name'        : addressya_company_name,
                    'addressya_company_address1'    : addressya_company_address1,
                    'addressya_company_address2'    : addressya_company_address2,
                    'addressya_company_country'     : addressya_company_country,
                    'addressya_company_city'        : addressya_company_city,
                    'addressya_company_state'       : addressya_company_state,
                },
                success: function (result) {
                   console.log(result);
                   if (result.data.status == 200) {

                        $("#company_id").val(result.data.companyLocation.lid);
                        $("#addressya_company_name").val(result.data.companyLocation.details.locationName);
                        $("#addressya_company_country").val(result.data.companyLocation.details.country);
                        $("#addressya_company_city").val(result.data.companyLocation.details.city);
                        $("#addressya_company_state").val(result.data.companyLocation.details.region);
                        $("#addressya_company_address1").val(result.data.companyLocation.details.houseNumber);
                        $("#addressya_company_address2").val(result.data.companyLocation.details.streetName);

                        $("#empCompanyName").html(result.data.companyLocation.details.locationName);
                        $("#empCountry").html(result.data.companyLocation.details.country);
                        $("#empCity").html(result.data.companyLocation.details.city);
                        $("#empRegion").html(result.data.companyLocation.details.region);
                        $("#empAddress").html(result.data.companyLocation.details.houseNumber + ", " + result.data.companyLocation.details.streetName); 

                        $("#company_detail_form").hide();
                        $("#existing_company_detail").show();
                        $(".confirm_location_button").show();               
                    }
                }
            } )
        } );


        $( '#confirm_location' ).on( 'click', function() {
            $(".addressya_error").remove();
            var addressya_username = $('input[type=text][name="addressya_username"]').val();            
            var request_id = $('input[type=hidden][name="request_id"]').val();
            var addressya_token = $('input[type=hidden][name="addressya_token"]').val();
            var selected_location = $('input[type=radio][name="customer_location"]:checked').val();
            var company_id = $('input[type=hidden][name="company_id"]').val();
            
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action'                        : 'addressya_confirm_location',
                    'addressya_username'            : addressya_username,
                    'request_id'                    : request_id,
                    'addressya_token'               : addressya_token,
                    'selected_location'             : selected_location,
                    'company_id'                    : company_id,
                },
                success: function (result) {
                   if (result.data.status == 200) {
                        $('#confirm_location').attr('disabled','disabled');
                        $("#addressya_location_confirmed").val("true");
                        $('.verify_code_success').show();
                        var scrollDiv = document.getElementById("place_order").offsetTop;
                        window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
                    }
                }
            } );
        } );
        
        $( '#create_account' ).on( 'click', function() {
            $('#create_addressya_username_err').hide();
            $('.addressya_error').hide();
            $('.account_create_failure').hide();
            
            var error = false;
            var company_name = '';
            var company_country = '';
            var company_city = '';
            var company_state = '';
            var company_address1 = '';
            var company_address2 = '';
            
           // $('#create_account').attr('disabled','disabled');
            var addressya_email = $( '#create_addressya_email' ).val();
            var addressya_password = $( '#create_addressya_password' ).val();
            var addressya_telephone = $( '#create_addressya_telephone' ).val();
            var addressya_username = $( '#create_addressya_username' ).val();
            
            var firstname = $( '#billing_first_name' ).val();
            var lastname = $( '#billing_last_name' ).val();

            var city = $( '#billing_city' ).val();
            var address_2 = $( '#billing_address_2' ).val();
            var address_1 = $( '#billing_address_1' ).val();
            
            var country_element = document.getElementById("billing_country");
            var country_name = country_element.options[country_element.selectedIndex].text;
            var state_element = document.getElementById("billing_state");
            if(state_element.selectedIndex){
                var state_name = state_element.options[state_element.selectedIndex].text;
            } else {
                console.log("exit");
            }

            if($("#addressya_credit_enable").val() == 'on'){
                company_name        = $( '#create_addressya_company_name' ).val();
                company_country     = $( '#create_addressya_company_country' ).val();
                company_city        = $( '#create_addressya_company_city' ).val();
                company_state       = $( '#create_addressya_company_state' ).val();
                company_address1    = $( '#create_addressya_company_address1' ).val();
                company_address2    = $( '#create_addressya_company_address2' ).val();

                if($( '#create_addressya_company_name' ).val() == ''){ 
                    checkAndShowErr('create_addressya_company_name');
                    error = true;
                }
                if($( '#create_addressya_company_country' ).val() == ''){ 
                    checkAndShowErr('create_addressya_company_country');
                    error = true;
                }
                if($( '#create_addressya_company_city' ).val() == ''){ 
                    checkAndShowErr('create_addressya_company_city');
                    error = true;
                }
                if($( '#create_addressya_company_state' ).val() == ''){ 
                    checkAndShowErr('create_addressya_company_state');
                    error = true;
                }
                if($( '#create_addressya_company_address1' ).val() == ''){ 
                    checkAndShowErr('create_addressya_company_address1');
                    error = true;
                }
            }

            /* if(company_id == ''){
                if(firstname == '' || lastname == '' || address_1 == '' || address_2 == '' || city == '' || state_name == '' || country_name == ''){
                    $('#company_detail_form').after('<div style="color: red;" class="addressya_error">Please enter address details.</div>');
                    return;
                }
            }
            if (!addressya_otp) {
                $('#addressya_otp').after('<div style="color: red;" class="addressya_error">Please enter code.</div>');
                return;
            } */
            if(!error){
                $.ajax( {
                    url: addressya.ajax_url,
                    type: 'POST',
                    data: {
                        'action':'addressya_create_account',
                        'firstname': firstname,
                        'lastname': lastname,
                        'addressya_email': addressya_email,
                        'addressya_password': addressya_password,
                        'addressya_username': addressya_username,
                        'addressya_telephone': addressya_telephone,
                        'addressya_city': city,
                        'addressya_country': country_name,
                        'addressya_state': state_name ? state_name : '',
                        'addressya_address_2': address_2,
                        'addressya_address_1': address_1,
                        'company_name': company_name,
                        'company_country': company_country,
                        'company_city': company_city,
                        'company_state': company_state,
                        'company_address1': company_address1,
                        'company_address2': company_address2,
                    },
                    success: function (result) {
                    console.log(result);
                    if (result.data.status == 422 && result.data.message == 'Username already exists') {
                        $('#create_account').prop('disabled', false);
                        $('#create_addressya_username_err').show();
                        var scrollDiv = document.getElementById("create_addressya_username").offsetTop;
                        window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
                    }
                    if (result.data.status == 422 && result.data.message == 'Email Already Exists') {
                        $('#create_account').prop('disabled', false);
                        $('.account_create_failure').show();
                    }
                    if (result.data.status == 200) {
                        $(".account_create_success").show();
                        var scrollDiv = document.getElementById("place_order").offsetTop;
                        window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
                    }

                    }
                } );
            }
        } );

        function checkAndShowErr(elementId){
            $('#' + elementId + '_err').show();
            var scrollDiv = document.getElementById(elementId).offsetTop;
            window.scrollTo({ top: scrollDiv, behavior: 'smooth'});
        }
        
        $( '#onboard_addressya' ).on( 'click', function() {
            if (!$("#onboard_addressya").prop('checked')) {
                $("#addressya-account-form").hide();
            } else {
                $("#addressya-account-form").show();
            }
        } );
    
    } ); // Document ready end.

} )( jQuery );

 
