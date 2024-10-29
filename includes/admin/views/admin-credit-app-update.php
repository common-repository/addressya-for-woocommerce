<?php echo $_GET['app']; ?>
<style>
    .post-type-addressya_creditapp #postbox-container-1{
        display: none;
    }
    
    .company_score_td{
        display: none;
    }
     .company_req_sent_td{
        display: none;
    }
     .company_req_btn_td{
        display: none;
    }
    .contract_dates{
        display:none;
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<div class="wrap">
    <h1 class="wp-heading-inline">Update Application</h1>
<table class="form-table">
    
    <tbody>
    <tr>
            <th scope="row">
                <label for="">Addressya Username</label>
            </th>
            <td>
                <input type="text" name="_billing_addressya_username" id="_billing_addressya_username" class="regular-text" value="">
                <input type="hidden" name="_billing_addressya_lid" class="regular-text" value="">
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="">Customer Name</label>
            </th>
            <td>
                <input type="text" name="_billing_addressya_name" id="_billing_addressya_name" class="regular-text" value="">
            </td>
        </tr>

        <tr class="score_data">
            <th scope="row">
                <label for="">Address Score</label>
            </th>
            <td>
                <span id="_billing_addressya_address_score"></span>
            </td>
        </tr>
        <tr class="score_data">
            <th scope="row">
                <label for="">Proof of Residency</label>
            </th>
            <td>
                <span id="_billing_addressya_por"></span>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="">Amount</label>
            </th>
            <td>
                <input type="text" name="_billing_addressya_credit_amount" id="_billing_addressya_credit_amount" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Status</label>
            </th>
            <td>
                <select name="_billing_addressya_credit_status" id="application_status">
                    <option value="pending" >Pending</option>
                    <option value="approved" >Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </td>
        </tr>

        <tr class="contract_dates">
            <th scope="row">
                <label for="">Contract Start Date</label>
            </th>
            <td>
                <input type="text" name="_billing_addressya_credit_contractStartDate" id="_billing_addressya_credit_contractStartDate" class="regular-text datepicker" value="">
            </td>
        </tr>

        <tr class="contract_dates">
            <th scope="row">
                <label for="">Contract End Date</label>
            </th>
            <td>
                <input type="text" name="_billing_addressya_credit_contractEndDate" id="_billing_addressya_credit_contractEndDate" class="regular-text datepicker" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">                
                <label for=""><b>Employer Detail</b></label>
            </th>
            <td>
                <span id="loading_emp_detail">Loading...</span>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Company Name</label>
            </th>
            <td>
                <input type="text" readonly name="company_name" id="company_name" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Employer Address Line 1</label>
            </th>
            <td>
                <input type="text" readonly name="company_address1" id="company_address1" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Employer Address Line 2</label>
            </th>
            <td>
                <input type="text" readonly name="company_address2" id="company_address2" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Employer Country</label>
            </th>
            <td>
                <input type="text" readonly name="company_country" id="company_country" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Employer State</label>
            </th>
            <td>
                <input type="text" readonly name="company_state" id="company_state" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Employer City</label>
            </th>
            <td>
                <input type="text" readonly name="company_city" id="company_city" class="regular-text" value="">
            </td>
        </tr>
        <tr class="employer_score_data">
            <th scope="row">
                <label for="">Address Score</label>
            </th>
            <td class="company_score_td">
                <input type="text" readonly name="company_score" id="company_score" class="regular-text" value="">
            </td>            
            <td class="company_req_sent_td">
                Address Score Requested
            </td>            
            <td class="company_req_btn_td">
                <input type="hidden" value="" id="hidden-loc-id">
                <input type="hidden" value="" id="hidden-cus-id">                        
                <input type="button" value="Request Address Score" id="request_address_score" class="afw_resend_btn afw_unassign_btn" >   
            </td>
            <!-- <td>
                <span id="_billing_addressya_address_score"></span>
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
            </td> -->
        </tr>

    </tbody>
</table>
                    </div>

<input type="submit" name="publish" id="updateApp" class="button button-primary button-large" value="Save">

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>
    jQuery(document).ready(function($) {
        
        $(".company_score_td").hide();
        $(".company_req_btn_td").hide();             
        $(".company_req_sent_td").hide();
        $(".score_data").hide();
        $(".datepicker").datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: 0, 
        });

        $('#application_status').on('change', function() {
            //alert( this.value );
            $(".contract_dates").show();
            if(this.value == "rejected" || this.value == "pending"){
                $(".datepicker").val('');
                $(".contract_dates").hide();
            }
        });

        

        $.ajax( {
            url: addressya.ajax_url,
            type: 'POST',
            data: {
                'action':'addressya_get_credits',
                'order_id': "<?php echo $_GET['order_id']; ?>",
            },
            success: function (response) {  
                $.ajax( {
                    url: addressya.ajax_url,
                    type: 'POST',
                    data: {
                        'action':'addressya_get_location',
                        'uid': response.data.data.uid,
                        'companyLid': response.data.data.companyLocationId,
                    },
                    success: function (comLocRes) {  
                        if(comLocRes.data.data[0].requestedScore == true && comLocRes.data.data[0].scoreShared == false){                    
                            $(".company_req_sent_td").show();
                        } 
                        if(comLocRes.data.data[0].requestedScore == true && comLocRes.data.data[0].scoreShared == true){
                            $(".company_score_td").show();
                            $("#company_score").val(comLocRes.data.data[0].config.score + "/10");
                        } 
                        if(comLocRes.data.data[0].requestedScore == false && comLocRes.data.data[0].scoreShared == false){
                            $("#hidden-loc-id").val(comLocRes.data.data[0].lid);
                            $("#hidden-cus-id").val(comLocRes.data.data[0].owner.uid);
                            $(".company_req_btn_td").show();
                        }
                    }
                } );

                $.ajax( {
                    url: addressya.ajax_url,
                    type: 'POST',
                    data: {
                        'action':'addressya_get_location_score',
                        'uid': response.data.data.uid,
                        'locationLid': response.data.data.locationId,
                    },
                    success: function (userLocRes) {                
                        if(userLocRes.data.data[0].config.score != '' && userLocRes.data.data[0].scoreShared){
                            $(".score_data").show();
                            $("#_billing_addressya_address_score").html(userLocRes.data.data[0].config.score + "/10");
                            $("#_billing_addressya_por").html(userLocRes.data.data[0].config.proofOfResScore);
                        }
                    }
                } );
                $("#loading_emp_detail").hide();                  
                console.log(response.data.data.companyLocationId);
                //console.log();

                let startDate = endDate = '';
                if(response.data.data.contractStartDate != ''){
                    let startDateObj = new Date(response.data.data.contractStartDate);
                    startDate = `${startDateObj.getDate()}-${startDateObj.getMonth()+1}-${startDateObj.getFullYear()}`;
                }
                if(response.data.data.contractEndDate != ''){
                    let endDateObj = new Date(response.data.data.contractEndDate);
                    endDate = `${endDateObj.getDate()}-${endDateObj.getMonth()+1}-${endDateObj.getFullYear()}`;
                }
                $("#_billing_addressya_username").val(response.data.data.userName);
                $("#_billing_addressya_name").val(response.data.data.userData.name);
                $("#_billing_addressya_credit_amount").val(response.data.data.amount);
                $("#company_name").val(response.data.data.companyLocationData.details.locationName);
                $("#company_city").val(response.data.data.companyLocationData.details.city);
                $("#company_state").val(response.data.data.companyLocationData.details.region);
                $("#company_country").val(response.data.data.companyLocationData.details.country);
                $("#company_address2").val(response.data.data.companyLocationData.details.streetName);
                $("#company_address1").val(response.data.data.companyLocationData.details.houseNumber);

                if(response.data.data.status == "rejected" ){      
                    $(".contract_dates").hide();
                    document.getElementById("application_status").value = "rejected";
                } else if(response.data.data.status == "pending" ){      
                    $(".contract_dates").hide();
                    document.getElementById("application_status").value = "pending";
                } else if(response.data.data.status == "approved" ){      
                    document.getElementById("application_status").value = "approved";
                    $(".contract_dates").show();
                    $("#_billing_addressya_credit_contractStartDate").val(startDate);
                    $("#_billing_addressya_credit_contractEndDate").val(endDate);
                } 

                /* if(response.data.data[0].requestedScore == true && response.data.data[0].scoreShared == false){                    
                    $(".company_req_sent_td").show();
                } 
                if(response.data.data[0].requestedScore == true && response.data.data[0].scoreShared == true){
                    $(".company_score_td").show();
                    $("#company_score").val(response.data.data[0].config.score + "/10");
                } 
                if(response.data.data[0].requestedScore == false && response.data.data[0].scoreShared == false){
                    $("#hidden-loc-id").val(response.data.data[0].lid);
                    $("#hidden-cus-id").val(response.data.data[0].owner.uid);
                    $(".company_req_btn_td").show();
                } */

                $('#updateApp').on('click', function() {
                    //alert( this.value );
                    $.ajax( {
                    url: addressya.ajax_url,
                    type: 'POST',
                    data: {
                        'action':'addressya_update_credit',
                        'order_id': "<?php echo $_GET['order_id']; ?>",
                        'status': $("#application_status").val(),
                        'credit_contractStartDate': $("#_billing_addressya_credit_contractStartDate").val(),
                        'credit_contractEndDate': $("#_billing_addressya_credit_contractEndDate").val(),
                    },
                    success: function (response) {  


                        }
                    } );
                });
            }
        } );


});
</script>