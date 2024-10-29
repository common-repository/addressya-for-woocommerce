<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
<style>
a{color:#2271b1}
.no-record-found{
    display:none;
}
.widefat td, .widefat th {
    padding: 8px 10px;
}


</style>
<?php
global $wpdb;

$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  'pending'" ) );


if(isset($_GET['status']) && $_GET['status'] !== ''){
    //$results = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' ORDER BY post_date desc" );     
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  '".$_GET['status']."'" ) );
} else {
    $results = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' ORDER BY post_date desc" ); 
}

?>
<div class="wrap">
    <h1>Credit Application</h1>
    <?php //print_r($results); ?>
    <div class="container-fluid" >
        <div class="row ">            
            <div class="col-3 p-2 ">
            <select name="addressya_credit_mp_status" id="addressya_credit_mp_status">
                    <option value="">All Applications</option><option value="approved">Approved</option><option value="pending">Pending</option><option value="rejected">Rejected</option>
            </select>
            </div>
            <div class="col-6 p-0 ">
            </div>
            <div class="col-3 p-2 ">
                <p class="text-end">
                    <!-- <a href="edit.php?post_type=addressya_creditapp" class="map-view-button">List View</a> -->
                    <input type="hidden" id="what_is_view" value="list" />
                    <a href="javascript:void(0)" class="map-view-button" id="show_list"><img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_list.svg'; ?> width="18px" /> List View</a>
                    <a href="javascript:void(0)" class="map-view-button" id="show_map"><img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_map.svg'; ?> width="18px" /> Map View</a>
                </p>
            </div>
        </div>
        <div class="no-record-found">There is no application recorded yet!</div>
        <div class="row credit-mv-row" id="list-view-cont">       
        
        </div>
        <div class="row credit-mv-row" id="map-view-cont"></div>
    </div>
    <div id="modal-html"></div>

</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVF-NDUdl0nK6898LtgxQWX4_GlmtkOl8&callback=initMap&v=weekly"
    defer
></script>
<script>
      
jQuery(document).ready(function($) {
    console.log("all is well");

    
    $('#dtBasicExample').DataTable();
    $("#show_list").hide();
    $('#addressya_credit_mp_status').change(function(){
        //window.location.href = `admin.php?page=credit-app-map-view&status=${$(this).val()}`;
        loadCreditApplication($(this).val());
    })
    $("#map-view-cont").hide();
    $('#show_map').click(function(){
        $("#list-view-cont").hide();
        $("#map-view-cont").show();
        $("#show_map").hide();
        $("#show_list").show();
        $("#what_is_view").val('map');
    })
    $('#show_list').click(function(){
        $("#list-view-cont").show();
        $("#map-view-cont").hide();
        $("#show_map").show();
        $("#show_list").hide();
        $("#what_is_view").val('list');

    })
    
    //$("#map-view-cont").html('Creating Map View for You....');
    loadCreditApplication();

    function loadCreditApplication(queryParam = ""){
        $.ajax( {
            url: addressya.ajax_url,
            type: 'GET',
            data: {
                'action': 'get_business_credit_applications',
                'status': queryParam
            },
            success: function(result) {
                console.log('result');
                console.log(result.data.status);
                if(result.data.status == 200){
                    if($("#what_is_view").val() == 'list'){
                        $("#list-view-cont").show();
                    } else if($("#what_is_view").val() == 'map'){
                        $("#map-view-cont").show();
                    }
                    $(".no-record-found").hide();
                    let mapData = [];
                    let htmlListView = '';
                    let modalData = '';
                    let tableData = '';
                    result.data.data.forEach(function(item, i){
                        let statusHtml = '';
                        let creditDate = '';
                        let startDate = endDate = '';
                        let objectDate = new Date(item.createdAt);                    
                        let appliedOn = `${objectDate.getDate()}-${objectDate.getMonth()+1}-${objectDate.getFullYear()}`;
                        if(item.contractStartDate != ''){
                            let startDateObj = new Date(item.contractStartDate);
                            startDate = `${startDateObj.getDate()}-${startDateObj.getMonth()+1}-${startDateObj.getFullYear()}`;
                        }
                        if(item.contractEndDate != ''){
                            let endDateObj = new Date(item.contractEndDate);
                            endDate = `${endDateObj.getDate()}-${endDateObj.getMonth()+1}-${endDateObj.getFullYear()}`;
                        }
                        if(item.status == 'pending'){
                            statusHtml = `<span class="pending">${item.status}</span>`;                        
                            creditDate = `Applied on: ${appliedOn}`;
                        } else if(item.status == 'approved'){
                            statusHtml = `<span class="approved">${item.status}</span>`;
                            let contractDate = `${startDate} - ${endDate}`;
                            creditDate = `Contract: ${contractDate}`;
                        } else if(item.status == 'rejected'){
                            statusHtml = `<span class="rejected">${item.status}</span>`;
                        }
                        let proImage = '';
                        if(item.userData.profileImage!= undefined && item.userData.profileImage != ''){
                            proImage = `<img src=${item.userData.profileImage} />`;
                        } else if(item.userData.profileImg!= undefined && item.userData.profileImg != ''){
                            proImage = `<img src=${item.userData.profileImg} />`;
                        } else {
                            proImage = `<img src=${item.userData.profileImgMed} />`;
                        }
                        var app_status = '';
                        if(item.status == 'pending'){
                            app_status = `<span style="color:#f79d2f; border:solid 1px #f79d2f;padding:0 4px; border-radius:2px">pending</span>`;
                        } else if(item.status == 'approved'){
                            app_status = `<span style="color:#069c29; border:solid 1px #069c29;padding:0 4px; border-radius:2px"">approved</span>`;
                        } else if(item.status == 'rejected'){
                            app_status = `<span style="color:#cc181b; border:solid 1px #cc181b;padding:0 4px; border-radius:2px"">rejected</span>`;
                        }

                        tableData += `<tr class="iedit author-self level-0 post-247 type-addressya_creditapp status-publish hentry">
                            <td>${item.userData.name}</td>
                            <td>${app_status}</td>
                            <td>${startDate}</td>
                            <td>${endDate}</td>
                            <td>${item.amount}</td>
                            <td><a href="admin.php?page=credit-app-update&order_id=${item.orderId}" >View credit application</a></td>
                            <td><a href="post.php?post=${item.orderId}&action=edit" >View order</a></td>
                            <td>${appliedOn}</td>
                        </tr>`;

                        
                        
                        mapData.push([
                                {'lat':parseFloat(item.locationData.latitude),"lng":parseFloat(item.locationData.longitude)},
                                item.locationData.score ? item.locationData.score : "", 
                                item.locationData.proofOfResScore ? item.locationData.proofOfResScore : "", 
                                item.locationData.details.locationName, 
                                item.amount, 
                                item.userData.displayPhone, 
                                item.orderId,
                                item.locationData.mediumImages,
                                item.locationData.details.type, 
                                item.userData.userName, 
                                item.locationId, 
                            ]);
                        htmlListView += `
                        <li class="credit-mv-cont" data-value="${i}" >                            
                            <div class="credit-mv-name col-12">${proImage} ${item.userData.name}</div>
                            <div class="credit-mv-address col-12"><img class="add-city" height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_city.svg'; ?> width="18px" />${item.locationData.details.country}</div>                    
                            <div class="credit-mv-address col-12"><img class="add-region" height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_region.svg'; ?> width="18px" />${item.locationData.details.region}</div>
                            <div class="credit-mv-date col-12">${creditDate}</div>  
                            <div class="credit-mv-status col-12">${statusHtml}</div>                    
                        </li>
                        `;     
                        
                        modalData += `
                        <div class="modal fade " id="exampleModal-${i}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row "> 
                                            <div class="col-12 md-top">
                                                <div class="col-1 md-top-image">${proImage}</div>                                
                                                <div class="col-11">
                                                    <div class="md-name">${item.userData.name}</div>
                                                    <div class="md-username">Username: ${item.userData.userName}</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="md-phone"><img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/iconPhone.svg'; ?> /> ${item.userData.displayPhone}</div>
                                                <div class="md-email"><img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/iconEmail.svg'; ?> /> ${item.userData.displayEmail}</div>
                                                <div class="md-directions-label">Directions:</div>
                                                <div class="md-directions">${item.locationData.details.directions}</div>
                                            </div>
                                            <div class="col-6">                                    
                                                <div class="md-location-name">${item.locationData.details.locationName}</div>
                                                <div class="md-address">${item.locationData.details.area} ${item.locationData.details.city} ${item.locationData.details.region} ${item.locationData.details.country}</div>
                                                <div class="md-image"><img src=${item.locationData.mediumImages}  /></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;

                    });

                    var tableView =` <table id="dtBasicExample" class="table wp-list-table widefat fixed striped table-view-list posts" width="100%">
                        <thead>
                            <tr>
                            <th scope="col" class=" manage-columnth-sm">Customer Name</th>
                            <th scope="col" class="manage-column th-sm">Status</th>
                            <th scope="col" class="manage-column th-sm">Start Date</th>
                            <th scope="col" class="manage-column th-sm">End Date</th>
                            <th scope="col" class="manage-column th-sm">Amount</th>
                            <th scope="col" class="manage-column th-sm">Application</th>
                            <th scope="col" class="manage-column th-sm">Order</th>
                            <th scope="col" class="manage-column th-sm">Applied On</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableData}
                        </tbody>
                    </table> `;
                    
                    let htmlView = `<div class="col-3 p-0 credit-mv-col-left">
                        <ul class="credit-mv-ul">
                            ${htmlListView}
                        </ul>
                    </div>
                    <div class="col-9 p-0 credit-mv-col-right">
                        <div id="map"></div>
                    </div>`;

                    $('#list-view-cont').html(tableView);
                    $('#map-view-cont').html(htmlView);
                    $('#modal-html').html(modalData);
                    window.initMap = initMap(mapData);

                    $('#dtBasicExample').DataTable({
                        "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 1, 2, 3, 4, 5, 6 ] }, 
                        { "bSearchable": false, "aTargets": [ 1, 2, 3, 4, 5, 6, 7 ] }
                    ]
                    });
                    $('.dataTables_length').addClass('bs-select');

                    $('.credit-mv-cont').click(function (){
                        $('.credit-mv-cont').removeClass('active');
                        $(this).addClass('active');
                        $(".add-city").attr('src',`<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_city.svg'; ?>`);
                        $(".add-city", this).attr('src',`<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_city_white.svg'; ?>`);
                        $(".add-region").attr('src',`<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_region.svg'; ?>`);
                        $(".add-region", this).attr('src',`<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/icon_region_white.svg'; ?>`);
                        $(".credit-mv-status span", this).removeClass('status-active');
                        $(".credit-mv-status span", this).addClass('status-active');
                       
                        var orderID = $(this).attr('data-value');
                        google.maps.event.trigger(markers[orderID], 'click');
                        
                    })
                } else if(result.data.status == 422){
                    $(".no-record-found").show();                    
                    $("#list-view-cont").hide();
                    $("#map-view-cont").hide();
                }

            },error: function(result) {
                console.warn(result);
            }
        } );
    }

    var markers = new Array();
    // Initialize and add the map
    function initMap(mapData) {  
        markers = [];
        const ADDRESSYA_ENV = "<?php echo ADDRESSYA_ENV; ?>";
        let addressya_url = "https://app.addressya.com/address/";
        if ( 'dev' === ADDRESSYA_ENV ) {
            addressya_url = "https://staging.business.addressya.com/address/";
        }
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 8,
            center: mapData[0][0],
        });
        // Create an info window to share between markers.
        //const infoWindow = new google.maps.InfoWindow();

        // Create the markers.
        
        mapData.forEach(([position, score, proofOfResScore, locationName, amount, displayPhone, orderId, mediumImages, type, userName, locationId], i) => {
            let proofOfRes = '';
            if(type = 'home'){
                if(proofOfResScore != ''){
                    proofOfRes = `Proof of residency: ${proofOfResScore}%`;
                }
            }
            let scoreStr = "";
            if(score != ''){
                scoreStr = `Address Score: ${score}/10`;
            }
            const contentString =
            `<div id="content">
                <div id="locDetail">
                    <div id="locImage"><img src=${mediumImages}  /></div>
                    <div id="locTitle"><a target="_blank" href="${addressya_url}${userName}" >${locationName} <img src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/addressya_flower.svg'; ?> width="15px" /></a></div>
                    <div id="locContTitle">
                        <div id="locPhone"> 
                        <img height="15px" width="15px" src=<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/iconPhone.svg'; ?> />
                        <span><a href="tel: ${displayPhone}" >${displayPhone}</a></span>
                        </div>
                        <div id="locPhone" data-bs-toggle="modal" data-bs-target="#exampleModal-${i}">More details</div>
                    </div>
                    <div id="locContPor">
                        <div id="locPor">${proofOfRes}</div>  
                        <div id="locPor">${scoreStr}</div>
                    </div>
                    <div id="locPricecont"> 
                        <div id="locPrice">$US: ${amount}</div>   
                        <div id="locOpenApp"><a target="_blank" href="admin.php?page=credit-app-update&order_id=${orderId}" >Open application</a></div>                  
                    </div>
                </div>
            </div>`;

            const locationInfowindow = new google.maps.InfoWindow({
                content: contentString,
                ariaLabel: "Addressya",
            });
            const marker = new google.maps.Marker({
                position,
                map,
                icon:`<?php echo ADDRESSYA_PLUGIN_URL . 'assets/images/mapPinAddressya.svg'; ?>`,
                title: `${i}`,
                optimized: false,
                infowindow: locationInfowindow
            });

            markers.push(marker);

            // Add a click listener for each marker, and set up the info window.
            google.maps.event.addListener(marker, 'click', function() {
                hideAllInfoWindows(map);
                this.infowindow.open(map, this);
            });            
            
        });


    }

    function hideAllInfoWindows(map) {
        markers.forEach(function(marker) {
            marker.infowindow.close(map, marker);
        }); 
    }
    
});

    //window.initMap = initMap;

</script>
