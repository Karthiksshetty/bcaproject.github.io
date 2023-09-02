<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?>-BOOKINGS</title>
</head>
<body class="bg-light">
<?php require('inc/header.php'); ?>
<div class="container" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">BOOKED ROOMS</h3>


                <!-- Room -->

                <div class="card bg-dark text-white border-0 shadow mb-4">
                    <div class="card-body">

                        <div class="text-end  mb-4">
                        
                        </div>
                       
                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table text-dark bg-white table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">User ID</th>
                                        <th scope="col">Room ID</th>
                                        <th scope="col">Check-in/Check-out</th>
                                        <th scope="col">Bill Amount</th>
                                        <th scope="col">Room Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="booking-data">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

               

                
                
            </div>
        </div>
    </div>


<?php require('inc/footer.php'); ?>
<script src="admin/scripts/bookings.js"></script>
</body>
</html>

