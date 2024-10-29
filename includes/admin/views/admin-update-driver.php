<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<style>
    .post-type-addressya_driver #postbox-container-1{
        display: none;
    }
    .addressya_text{
        width: 111px !important;
        padding: 5px;
        box-shadow: 0 0 0 transparent;
        border-radius: 4px;
        border: 1px solid #8c8f94;
        background-color: #fff;
        color: #2c3338;
    }
    .addressya_driver_start_time{
    }
    .addressya_driver_end_time{
    }
    .addressya_driver_timezone{
        width:138px
    }
    .err{
        color:#f00;
        display:none;
    }
</style>
<div class="wrap">
    <h1 class="wp-heading-inline">Update Driver</h1>
<table class="form-table">
    <tbody>
    <tr>
            <th scope="row">
                <label for="">Addressya Username</label>
            </th>
            <td>
                <input readonly type="text" name="addressya_driver_username" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">First Name</label>
            </th>
            <td>
                <input readonly type="text" name="addressya_driver_firstname" class="regular-text" value="">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="">Last Name</label>
            </th>
            <td>
                <input readonly type="text" name="addressya_driver_lastname" class="regular-text" value="">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="">Email</label>
            </th>
            <td>
                <input readonly type="email" name="addressya_driver_email" class="regular-text" value="">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Number</label>
            </th>
            <td>
                <input readonly type="text" name="addressya_driver_number" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_number', true ) ); ?>">
            </td>
        </tr>
     
        <tr>
            <th scope="row">
                <label for="">Vehicle</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_vehicle" class="regular-text" value="">
                <label class="err addressya_driver_vehicle_err">Please enter vehicle.</label>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="">Vehicle Number</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_vehicle_number" class="regular-text" value="">
                <label class="err addressya_driver_vehicle_number_err">Please enter vehicle number.</label>
            </td>
        </tr>
            
        <tr>
            <th scope="row">
                <label for="">Working Hours</label>
            </th>
            <td>
                <!-- <input type="time" id="start_time" min="20:00" max="22:00" name="start_time" value=""> to 
                <input type="time" name="end_time"> -->
                <!-- <input autocomplete="off" type="input" placeholder="Start Time" name="addressya_driver_start_time" class="addressya_driver_start_time addressya_text" id="addressya_driver_start_time" value=""> To 
                <input autocomplete="off" type="input" placeholder="End Time" name="addressya_driver_end_time" class="addressya_driver_end_time addressya_text" id="addressya_driver_end_time" value="">
                 --><input autocomplete="off" type="time" placeholder="Start Time" name="addressya_driver_start_time" class="addressya_driver_start_time addressya_text" id="addressya_driver_start_time" value=""> To 
                <input autocomplete="off" type="time" placeholder="End Time" name="addressya_driver_end_time" class="addressya_driver_end_time addressya_text" id="addressya_driver_end_time" value="">
               
                <select name="addressya_driver_timezone" id="addressya_driver_timezone" class="addressya_driver_timezone">
                    
                </select>
                <label class="err addressya_driver_timezone_err">Please enter working hours.</label>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Status</label>
            </th>
            <td>
                <select name="addressya_driver_status" id="driver_status">
                    <option value="inactive" >Inactive</option>
                    <option value="active" >Active</option>
                </select>
                <label class="err addressya_driver_status_err">Please select status.</label>
            </td>
        </tr>
    </tbody>
</table>
</div>
<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Update">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type = "text/JavaScript" src = "https://MomentJS.com/downloads/moment.js"></script>
<script type = "text/JavaScript" src = "https://momentjs.com/downloads/moment-timezone-with-data-10-year-range.js"></script>
<script>

    // Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

})()
jQuery(document).ready(function($) {

    var wh_start_time = wh_end_time = '';
    function cal_time(inputEle){
        var timeSplit = inputEle.split(':'),
            hours,
            minutes,
            meridian;
        hours = timeSplit[0];
        minutes = timeSplit[1];
        if (hours > 12) {
            meridian = 'PM';
            hours -= 12;
            (hours < 10) ? hours = "0"+hours : hours;
        } else if (hours < 12) {
            meridian = 'AM';
            if (hours == 0) {
            hours = 12;
            }
        } else {
            meridian = 'PM';
        }
        
        return hours + ':' + minutes + ' ' + meridian;
    }

    const convertTime12to24 = (time12h) => {
        const [time, modifier] = time12h.split(' ');
        let [hours, minutes] = time.split(':');
        if (hours === '12') {
            hours = '00';
        }
        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }
        return `${hours}:${minutes}`;
    }
    $("#addressya_driver_start_time").change(function(){
        var inputEle = $("#addressya_driver_start_time").val();
        wh_start_time = cal_time(inputEle);
    })

    $("#addressya_driver_end_time").change(function(){
        var inputEle = $("#addressya_driver_end_time").val();
        wh_end_time = cal_time(inputEle);
    })
    var member_id = "<?php echo $_GET['member_id']?>";
    $.ajax( {
        url: addressya.ajax_url,
        type: 'GET',
        data: {
            'action':'addressya_get_driver',
            'member_id': member_id,
        },
        success: function (response) {                  
            var member = response.data.data.member;      
            $("input[name=addressya_driver_start_time").val(convertTime12to24(member.startTime));
            $("input[name=addressya_driver_end_time").val(convertTime12to24(member.endTime));
            $("input[name=addressya_driver_username").val(member.userName);
            $("input[name=addressya_driver_firstname").val(member.firstName);
            $("input[name=addressya_driver_lastname").val(member.lastName);
            $("input[name=addressya_driver_vehicle").val(member.vehicleName );
            $("input[name=addressya_driver_vehicle_number").val(member.vehicleNumber);
            $("select[name=addressya_driver_timezone").val(member.timezone);
            $("select[name=addressya_driver_status").val(member.status);
            $("select[name=addressya_driver_status").val(member.status);
            $("input[name=addressya_driver_email").val(member.displayEmail);
            $("input[name=addressya_driver_number").val(member.displayPhone);
            
            
        }
    } );

    $('#publish').click(function(e) {
            $(".addressya_driver_vehicle_err").hide();
            $(".addressya_driver_vehicle_number_err").hide();
            $(".addressya_driver_timezone_err").hide();
            $(".addressya_driver_status_err").hide();
            var username = $("input[name=addressya_driver_username").val();
            var first_name = $("input[name=addressya_driver_firstname").val();
            var last_name = $("input[name=addressya_driver_lastname").val();
            var vehicle_name = $("input[name=addressya_driver_vehicle").val();
            var vehicle_number = $("input[name=addressya_driver_vehicle_number").val();
            var start_time = cal_time($("input[name=addressya_driver_start_time").val());
            var end_time = cal_time($("input[name=addressya_driver_end_time").val());

            var wh_timezone = $("select[name=addressya_driver_timezone").val();
            var status = $("select[name=addressya_driver_status").val();
            
            if(vehicle_name == ''){
                $(".addressya_driver_vehicle_err").show();
            }
            if(vehicle_number == ''){
                $(".addressya_driver_vehicle_number_err").show();
            }
            if(end_time == ''){
                $(".addressya_driver_timezone_err").show();
            }
            if(start_time == ''){
                $(".addressya_driver_timezone_err").show();
            }            
            if(status == ''){
                $(".addressya_driver_status_err").show();
            }
            if(vehicle_name != '' && vehicle_number != '' && end_time != '' && start_time != '' && status != ''){

                $.ajax( {
                    url: addressya.ajax_url,
                    type: 'POST',
                    data: {
                        'action':'addressya_update_driver',
                        'memberId': member_id,
                        'username': username,
                        'vehicleName': vehicle_name,
                        'vehicleNumber': vehicle_number,
                        'startTime': start_time,
                        'endTime': end_time,
                        'timezone': wh_timezone,
                        'status': status,
                    },
                    success: function (response) {
                        console.log('response');
                        console.log(response);
                        window.location.href = `admin.php?page=driver`;
                    }
                } );
            }
        

    });
    
    var timezones = moment.tz.names();
    //var tz = moment().tz(String);
    

    console.log('timezones');
    //console.log(timezones);
    var selectedTimezone = "<?php echo get_post_meta( $post->ID, 'addressya_driver_timezone', true ); ?>";
    console.log(selectedTimezone);
    select = document.getElementById( "addressya_driver_timezone" );
    for ( i = 0; i < timezones.length; i += 1 ) {
        option = document.createElement( 'option' );
        option.value = option.text = timezones[i];
        if(timezones[i] == selectedTimezone){
            option.selected = selectedTimezone;
        }        
        select.add( option );
    }
    console.log('select');
    console.log(select);
    /* var a = moment().toString();
    document.getElementById("todaysdate").innerHTML = a; */


});
</script>