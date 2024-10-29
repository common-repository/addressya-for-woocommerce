<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>



</style>
<?php
global $wpdb;

//echo $_GET['status'];
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  'pending'" ) );
/* echo "<pre>";
print_r($results);
die(); */

if(isset($_GET['status']) && $_GET['status'] !== ''){
    //$results = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' ORDER BY post_date desc" );     
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  '".$_GET['status']."'" ) );
} else {
    $results = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' ORDER BY post_date desc" ); 
}
//$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' " ) );
//$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  '$status'" ) );
//echo "<pre>";
//print_r($results);

/* foreach($results as $result){
    //echo $result->ID;
    //echo get_post_meta($result->ID, '_billing_first_name') . "<br>";
    
    echo $order_id = wp_slash(  get_post_meta($result->ID, 'order_id', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_first_name', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_last_name', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_addressya_credit_status', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_addressya_uid', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_addressya_location_city', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_addressya_location_country', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_addressya_location_region', true ) ) . "<br>";
    echo wp_slash(  get_post_meta($result->ID, '_billing_addressya_location_lat', true ) ) . "<br>";
    echo get_post_meta(wp_slash(  get_post_meta($result->ID, 'order_id', true ) ), '_billing_addressya_locationId', true ) . "<br>";
    echo wp_slash(  $result->post_date ) . "<br>";
   // $location_data = ADDRESSYA_Admin_API::getLocationForOrder( get_post_meta($result->ID, '_billing_addressya_uid', true), get_post_meta($order_id, '_billing_addressya_locationId', true ) );
    //print_r($location_data['data']);die();
} */
?>
<div class="wrap">
    <h1>Credit Application</h1>
    <?php //print_r($results); ?>
    <div class="container-fluid">
        <div class="row ">            
            <div class="col-3 p-2 ">
            <select name="addressya_credit_mp_status" id="addressya_credit_mp_status">
                    <option value="">Application Status</option>    
                    <option value="">All</option><option value="approved">Approved</option><option value="pending">Pending</option><option value="declined">Declined</option>
            </select>
            </div>
            <div class="col-6 p-0 ">
            </div>
            <div class="col-3 p-2 ">
                <p class="text-end">
                    <a href="edit.php?post_type=addressya_creditapp" class="map-view-button">List View</a>
                </div>
            </div>
        </div>
        <div class="row credit-mv-row">
            <div class="col-3 p-0 credit-mv-col-left">
                <ul class="credit-mv-ul">
                    <?php $locatioData = array(); ?>
                <?php foreach($results as $key=>$result){ ?>
                <li class="credit-mv-cont" data-value="<?php echo $key ?>">
                    <?php 
                        $order_id = wp_slash(  get_post_meta($result->ID, 'order_id', true ) );
                        array_push(
                            $locatioData,
                            [
                                ['lat'=> (float)get_post_meta($result->ID, '_billing_addressya_location_lat', true  ),'lng'=>(float)get_post_meta($result->ID, '_billing_addressya_location_lon', true  )],
                                wp_slash(  get_post_meta($result->ID, '_order_total', true ) ),
                                $result->ID,  
                                wp_slash(  get_post_meta($order_id, '_billing_phone', true ) ),
                            ]
                        );
                        //$locatioData = ['lat'=>'adasd','lon'=>'asdasdads'];
                        $appliedDate = date("d M Y", strtotime(wp_slash(  $result->post_date ))); 
                        $startDate = date("d M Y", strtotime(wp_slash(  get_post_meta($result->ID, '_billing_addressya_credit_contractStartDate', true ) ))); 
                        $endDate = date("d M Y", strtotime(wp_slash(  get_post_meta($result->ID, '_billing_addressya_credit_contractEndDate', true ) ))); 

                        $appStatus = wp_slash(  get_post_meta($result->ID, '_billing_addressya_credit_status', true ) );                         
                        $country = wp_slash(  get_post_meta($result->ID, '_billing_addressya_location_country', true ) ); 
                        $region = wp_slash(  get_post_meta($result->ID, '_billing_addressya_location_region', true ) ); 
                    ?>
                        <div class="credit-mv-name col-12"><?php echo wp_slash(  get_post_meta($result->ID, '_billing_first_name', true ) ) . " " . wp_slash(  get_post_meta($result->ID, '_billing_last_name', true ) );?></div>
                        <?php if($country != ''){ ?>
                            <div class="credit-mv-address col-12"><img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_city.svg'; ?> width="18px" /><?php echo $country; ?></div>
                        <?php } ?>
                        <?php if($region != ''){ ?>
                            <div class="credit-mv-address col-12"><img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_region.svg'; ?> width="18px" /><?php echo $region; ?></div>
                        <?php } ?>
                        <div class="credit-mv-date col-12">
                        <?php 
                        if($appStatus == 'pending'){ 
                            echo "Applied on: " . $appliedDate;
                        } if($appStatus == 'approved'){
                            echo "Contract: " . $startDate . " - " . $endDate;
                        }
                        ?>  
                        </div>  
                        <div class="credit-mv-status col-12">
                        <?php if($appStatus == 'pending'){ ?>    
                            <span class="pending"><?php echo $appStatus; ?></span>
                        <?php } if($appStatus == 'approved'){ ?>
                            <span class="approved"><?php echo $appStatus; ?></span>
                        <?php } 
                        
                        /* echo "<pre>";
             print_r($locatioData);
                echo "</pre>"; */?>
                        </div>                    
                </li>
                <?php }   ?>
                </ul>
            </div>
            <div class="col-9 p-0">
            <div id="map"></div>
            </div>
        </div>
    </div>

</div>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVF-NDUdl0nK6898LtgxQWX4_GlmtkOl8&callback=initMap&v=weekly"
    defer
></script>
<script>
      
    jQuery(document).ready(function($) {
        console.log("all is well");
        $('#addressya_credit_mp_status').change(function(){
            window.location.href = `admin.php?page=credit-app-map-view&status=${$(this).val()}`;
        })

        $('.credit-mv-cont').click(function(){
            var lid = $(this).attr('data-value');
            console.log('Location Id: ' + lid);
        })
        $.ajax( {
            url: addressya.ajax_url,
            type: 'POST',
            data: {
                'action':'addressya_save_credentials',
                'addressya_email': $("#addressya_email").val(),
                'addressya_password': $("#addressya_password").val(),
                'addressya_client_id': $("#addressya_client_id").val(),
                'addressya_user_uid': $("#addressya_user_uid").val(),
                'addressya_business_name': orgnisation_name,
                'addressya_business_id': orgnisation_id,
            },
            success: function (response) {  
                console.log(response);
            }
        } );
    });
    // Initialize and add the map
    function initMap() {
  
    
    var locatioArr = <?php echo json_encode($locatioData); ?>;
    //console.log(locatioArr[0][0]);
    const uluru = { lat: 30.6942091, lng: 76.860565 };
    // The map, centered at Uluru
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: locatioArr[0][0],
    });
    // Create an info window to share between markers.
    //const infoWindow = new google.maps.InfoWindow();

    // Create the markers.
    locatioArr.forEach(([position, price, applicationId, phoneNo], i) => {
        const contentString =
        `<div id="content">
            <div id="locDetail">
                <div id="locImage"></div>
                <div id="locTitle">Home <img src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/addressya_flower.svg'; ?> width="18px" /></div>
                <div id="locContTitle">
                    <div id="locPhone"> 
                    <img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/iconPhone.svg'; ?> />
                    <span>+${phoneNo}</span>
                    </div>
                    <div id="locPhone">More details</div>
                </div>
                <div id="locContPor">
                    <div id="locPor">Proof of residency: 70%</div>
                    <div id="locPor">Address Score: 7/10</div>  
                </div>
                <div id="locPricecont"> 
                    <div id="locPrice">$US: ${price}</div>   
                    <div id="locOpenApp"><a href="post.php?post=${applicationId}&action=edit" >Open application</a></div>                  
                </div>
            </div>
        </div>`;
        const infowindow = new google.maps.InfoWindow({
            content: contentString,
            ariaLabel: "Addressya",
        });
        const marker = new google.maps.Marker({
        position,
        map,
        icon:`<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/mapPinAddressya.svg'; ?>`,
        title: `${i}`,
        optimized: false,
        });

        // Add a click listener for each marker, and set up the info window.
        marker.addListener("click", () => {
        infowindow.open({
            anchor: marker,
            map,
        });

        /* infoWindow.close();
        infoWindow.setContent(marker.getTitle());
        infoWindow.open(marker.getMap(), marker); */
        });
    });

    }

    window.initMap = initMap;

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
