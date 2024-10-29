(function ($) {
    "use strict";
    $(document).ready(function () {

        /* $('#addffgghh').on('submit', function(e){
            e.preventDefault();

            console.log("I am hrere sdsds")
            var len = $('#username').val().length;
            if (len < 6 && len > 1) {
                this.submit();
            }
        }); */
       // $link .= '<a class="map-view-button" href="'. admin_url( 'post.php?post=' . absint( $meta_post_id ) . '&action=edit' ) .'" >';
        $(".post-type-addressya_creditapp .tablenav-pages").after('<div class="alignleft actions" style="float:right"><p style="margin:5px"><a href="admin.php?page=credit-app-map-view" class="map-view-button">Map View</a></p></div>');

        $( '#assign-driver-btn' ).on( 'click', function() {
            let order_id = $("#hidden-order-id").val();
            let driver_list_element = document.getElementById("assign_driver");
            let driver_member_id = driver_list_element.options[driver_list_element.selectedIndex].value;
            //alert("driver_member_id" + driver_member_id);
            if (driver_member_id) {
                document.getElementById("assign-driver-btn").style.cursor = 'not-allowed';
                document.getElementById("assign-driver-btn").value = 'Assigning';
                let value = 'billing';
               // alert(driver_member_id);
                $.ajax( {
                    url: addressya.ajax_url,
                    type: 'POST',
                    data: {
                        'action':'addressya_assign_driver',
                        'order_id': order_id,
                        'driver_member_id': driver_member_id,
                        'value': value,
                    },
                    success: function (result) {          
                        if (result) {
                            if (result && result.data.message == 'Connection Copied'){
                                location.reload();
                            }
                        }
                    }
                } );

            } else {
                document.getElementById('driver-bill-error').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('driver-bill-error').style.display = 'none';
                }, 2000);
            }
        });
        
        $( '#unassign-driver-btn' ).on( 'click', function() {
            let order_id = $("#hidden-order-id").val();
            document.getElementById("unassign-driver-btn").style.cursor = 'not-allowed';
            document.getElementById("unassign-driver-btn").value = 'Unassigning';
                let value = 'billing';
            // alert(driver_member_id);
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action':'addressya_unassign_driver',
                    'order_id': order_id,
                    'value': value,
                },
                success: function (result) {         
                    if (result) {
                        if (result && result.data.message == 'Connection Deleted'){
                            location.reload();
                        }
                    }
                }
            } );
        });

        $( '#request_address_score' ).on( 'click', function() {
            let loc_id = $("#hidden-loc-id").val();
            let cus_id = $("#hidden-cus-id").val();

            let value = 'billing';
            $.ajax( {
                url: addressya.ajax_url,
                type: 'POST',
                data: {
                    'action':'addressya_request_address_score',
                    'cus_id': cus_id,
                    'loc_id': loc_id,
                    'value': value,
                },
                success: function (result) {         
                    console.log('result: Score requested');
                    console.log(result.data);
                    console.log(result.data.status);
                    console.log(result.data.data);
                    console.log('result: Score requested');
                    if (result) {
                        if (result.data && result.data.status == 200){
                            location.reload();
                        }
                    }
                }
            } );
        });

    }); // Document ready end.

})(jQuery);


