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


?>
<div class="wrap">
        <div class="row ">            
            <div class="col-2 p-2 ">
            <h1>Driver List</h1>
            </div>
            <div class="col-2 p-3 ">
            <a href="admin.php?page=add-driver" class="page-title-action">Add Driver</a>
            </div>
        </div>
    
    
    <?php //print_r($results); ?>
    <div class="container-fluid" >
        <div class="row ">            
            <div class="col-3 p-2 ">
            </div>
            <div class="col-6 p-0 ">
            </div>
        </div>
        <div class="no-record-found">There is no driver added!</div>
        <div class="row credit-mv-row" id="list-view-cont">       
        
        </div>
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

    
    loadDrivers();

    function loadDrivers(queryParam = ""){
        $.ajax( {
            url: addressya.ajax_url,
            type: 'GET',
            data: {
                'action': 'get_driver',
                'status': queryParam
            },
            success: function(result) {
                console.log('result');
                console.log(result.success);
                console.log(result.data.data.members);
                if(result.success){
                    
                    let tableData = '';
                    result.data.data.members.forEach(function(item, i){
                        tableData += `<tr class="iedit author-self level-0 post-247 type-addressya_creditapp status-publish hentry">
                            <td><a href="admin.php?page=update-driver&member_id=${item.memberId}" >${item.userName}</a></td>
                            <td>${item.firstName}</td>
                            <td>${item.lastName}</td>
                            <td>${item.working_hours.startTime} - ${item.working_hours.endTime}</td>
                            <td>${item.status}</td>
                            <td><a href="admin.php?page=update-driver&member_id=${item.memberId}" class=""  >Edit</a> | <a href="#" class="delete-driver" id=${item.memberId} >Delete</a> </td>
                        </tr>`;
                    })
                    var tableView =` <table id="dtBasicExample" class="table wp-list-table widefat fixed striped table-view-list posts" width="100%">
                        <thead>
                            <tr>
                            <th scope="col" class=" manage-columnth-sm">Driver Username</th>
                            <th scope="col" class="manage-column th-sm">Firstname</th>
                            <th scope="col" class="manage-column th-sm">Lastname</th>
                            <th scope="col" class="manage-column th-sm">Working hours</th>
                            <th scope="col" class="manage-column th-sm">Status</th>
                            <th scope="col" class="manage-column th-sm">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableData}
                        </tbody>
                    </table> `;

                    $('#list-view-cont').html(tableView);      
                    $('#dtBasicExample').DataTable({
                        "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 5  ] }, 
                        { "bSearchable": false, "aTargets": [ 5 ] }
                    ]
                    });
                    $('.dataTables_length').addClass('bs-select');      
                    
                    $( '.delete-driver' ).on( 'click', function() {
                        var id = $(this).attr('id');
                        console.log(id);
                        if (confirm("Are you sure to delete this driver!") == true) {
                            $.ajax( {
                                url: addressya.ajax_url,
                                type: 'GET',
                                data: {
                                    'action': 'addressya_delete_driver',
                                    'member_id': id
                                },
                                success: function(result) {
                                    console.log(result);
                                    window.location.href = `admin.php?page=driver`;
                                }
                            });
                        } 
                        
                    });
                } 

            },error: function(result) {
                console.warn(result);
            }
        } );
    }

    
});


</script>
