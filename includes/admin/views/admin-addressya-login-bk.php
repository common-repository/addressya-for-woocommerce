<style>
    .post-type-addressya_creditapp #postbox-container-1{
        display: none;
    }
</style>
<table class="form-table">
    
    <tbody>
    <tr>
            <th scope="row">
                <label for="">Email</label>
            </th>
            <td>
                <input type="text" name="addressya_email" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_email', true ) ); ?>">
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="">Client Id</label>
            </th>
            <td>
                <input type="text" name="addressya_client_id" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_client_id', true ) ); ?>">
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="">Password</label>
            </th>
            <td>
                <input type="password" name="addressya_password" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_password', true ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Status</label>
            </th>
            <td>
                <select name="_billing_addressya_credit_status" id="application_status">
                    <option value="pending" <?php if(get_post_meta( $post->ID, '_billing_addressya_credit_status', true ) == 'pending'){echo("selected");}?>>Pending</option>
                    <option value="approved" <?php if(get_post_meta( $post->ID, '_billing_addressya_credit_status', true ) == 'approved'){echo("selected");}?>>Approved</option>
                    <option value="rejected"<?php if(get_post_meta( $post->ID, '_billing_addressya_credit_status', true ) == 'rejected'){echo("selected");}?>>Rejected</option>
                </select>
                <!-- <input type="text" name="_billing_addressya_credit_status" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, '_billing_addressya_credit_status', true ) ); ?>"> -->
            </td>
        </tr>

    </tbody>
</table>

<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Save">

<script>
    jQuery(document).ready(function($) {
        <?php if(get_post_meta( $post->ID, '_billing_addressya_credit_status', true ) == 'approved'){ ?>
            $(".contract_dates").show();
        <?php } else { ?>
            $(".contract_dates").hide();
        <?php }  ?>

        $('#application_status').on('change', function() {
            //alert( this.value );
            $(".contract_dates").show();
            if(this.value == "rejected" || this.value == "pending"){
                $(".datepicker").val('');
                $(".contract_dates").hide();
            }
        });

        $('form').submit(function(e) {
            //alert("dasdsad");
            console.log( $("#application_status").val() );
            
            if($("#application_status").val() == "rejected" || $("#application_status").val() == "pending"){
                $(".datepicker").val('');
           /*  } else if($("#application_status").val() == "approved" && $("input[name = _billing_addressya_credit_contractEndDate]") == "" && $("input[name = _billing_addressya_credit_contractEndDate]") == ""){ */
            } else if($("#application_status").val() == "approved" ){
                if($("input[name = _billing_addressya_credit_contractEndDate]").val() == "" && $("input[name = _billing_addressya_credit_contractEndDate]").val() == ""){
                    alert('Please enter contract start and end dates.');
                    e.preventDefault();

                }
            }

        });

        $.ajax( {
            url: addressyaAdmin.ajax_url,
            type: 'POST',
            data: {
                'action':'addressya_get_location',
                'uid': "<?php echo esc_attr( get_post_meta( $post->ID, '_billing_addressya_uid', true ) ); ?>",
                'companyLid': "<?php echo esc_attr( get_post_meta( $post->ID, '_billing_addressya_credit_companyLocationId', true ) ); ?>",
            },
            success: function (response) {  
                $("#loading_emp_detail").hide();                  
                //console.log(response['data']['data'][0]);
                console.log(response)
                //response.data.data[0].details.city
                $("#company_name").val(response.data.data[0].config.companyName);
                $("#company_city").val(response.data.data[0].details.city);
                $("#company_state").val(response.data.data[0].details.region);
                $("#company_country").val(response.data.data[0].details.country);
                $("#company_address2").val(response.data.data[0].details.streetName);
                $("#company_address1").val(response.data.data[0].details.houseNumber);
            }
        } );
    });
</script>