<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<style>
    .post-type-addressya_driver #postbox-container-1{
        display: none;
    }
    .addressya_text{
        width: 100px !important;
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
</style>
<table class="form-table">
    <tbody>
    <tr>
            <th scope="row">
                <label for="">Addressya Username</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_username" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_username', true ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">First Name</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_firstname" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_firstname', true ) ); ?>">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="">Last Name</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_lastname" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_lastname', true ) ); ?>">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="">Email</label>
            </th>
            <td>
                <input type="email" name="addressya_driver_email" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_email', true ) ); ?>">
            </td>
        </tr>
     
        <tr>
            <th scope="row">
                <label for="">Vehicle</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_vehicle" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_vehicle', true ) ); ?>">
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="">Vehicle Number</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_vehicle_number" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_vehicle_number', true ) ); ?>">
            </td>
        </tr>
            
        <tr>
            <th scope="row">
                <label for="">Working Hours</label>
            </th>
            <td>
                <!-- <input type="time" id="start_time" min="20:00" max="22:00" name="start_time" value=""> to 
                <input type="time" name="end_time"> -->
                <input autocomplete="off" type="input" placeholder="Start Time" name="addressya_driver_start_time" class="addressya_driver_start_time addressya_text" id="addressya_driver_start_time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_start_time', true ) ); ?>"> To 
                <input autocomplete="off" type="input" placeholder="End Time" name="addressya_driver_end_time" class="addressya_driver_end_time addressya_text" id="addressya_driver_end_time" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_end_time', true ) ); ?>">
                <select name="addressya_driver_timezone" id="addressya_driver_timezone" class="addressya_driver_timezone">
                    
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Status</label>
            </th>
            <td>
                <select name="addressya_driver_status" id="driver_status">
                    <option value="disable" <?php if(get_post_meta( $post->ID, 'addressya_driver_status', true ) == 'disable'){echo("selected");}?>>Disable</option>
                    <option value="enable" <?php if(get_post_meta( $post->ID, 'addressya_driver_status', true ) == 'enable'){echo("selected");}?>>Enable</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Number</label>
            </th>
            <td>
                <input type="text" name="addressya_driver_number" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'addressya_driver_number', true ) ); ?>">
            </td>
        </tr>
    </tbody>
</table>
<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Update">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type = "text/JavaScript" src = "https://MomentJS.com/downloads/moment.js"></script>
<script type = "text/JavaScript" src = "https://momentjs.com/downloads/moment-timezone-with-data-10-year-range.js"></script>
<script>

    // Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
jQuery(document).ready(function($) {
    //$('input[addressya_driver_start_time]').timepicker({});
    $('form').submit(function(e) {

            //alert($("input[name=addressya_driver_username").val());
            $("input[name=addressya_driver_username").val()
            
            e.preventDefault();
       
       

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

    $('#addressya_driver_start_time').timepicker  
    (  
        {  
        timeFormat: 'hh:mm p',  
        interval: 60,  
        minTime: '00',  
        maxTime: '23',    
        dynamic: false,  
        dropdown: true,  
        scrollbar: true  
        }  
    ); 
    $('#addressya_driver_end_time').timepicker  
    (  
        {  
        timeFormat: 'hh:mm p',  
        interval: 60,  
        minTime: '00',  
        maxTime: '23',   
        dynamic: false,  
        dropdown: true,  
        scrollbar: true  
        }  
    );  


});
</script>