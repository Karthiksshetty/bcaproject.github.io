<?php 

require('./admin/inc/db_config.php');
require('./admin/inc/essentials.php');

session_start();

  $contact_q = "SELECT * FROM `contact-details` WHERE `sr_no`=? ";
  $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=? ";

  $values = [1];

  $contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i'));       
  $settings_r = mysqli_fetch_assoc(select($settings_q,$values,'i'));     

  if($settings_r['shutdown']){
    echo<<<alertbar
      <div class='bg-danger text-center p-2 fw-bold'>
        <i class="bi bi-exclamation-triangle-fill"></i> 
        Bookings are Temporarily closed!
      </div>
    alertbar;
  }

  if(isset($_POST['check_available']))
  {
      
    $frm_data = filteration($_POST);
    $i =1;
    $returnData ="";

    if($frm_data['startDate'])
    {
      $_SESSION['uStartDate'] = $frm_data['startDate'];
    }
   

    if($frm_data['endDate'])
    {
      $_SESSION['uEndDate'] = $frm_data['endDate'];
    }
 

   $res1 = select("SELECT * FROM `rooms` WHERE (`quantity`>0 AND `removed` = 0)AND (`adult`=? AND `children`=?)",[$frm_data['adult'],$frm_data['children']],'ii');

   
    while($row = mysqli_fetch_assoc($res1)){

      $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
      $img_q = mysqli_query($con,"SELECT * FROM `room_images` 
                WHERE `room_id`='$row[id]' AND `thumb`='1'");
      if(mysqli_num_rows($img_q)>0){
        $thumb_res = mysqli_fetch_assoc($img_q);
        $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
      } 
      
      $book_btn = "";

      if(!$settings_r['shutdown']){
        $book_btn = "<a href='TxnTest.php#$row[id]'class='btn btn-sm text-white custom-bg shadow-none'>Book Now</a>";
      }

       $returnData .="
       <div class='col-lg-4 col-md-6 my-3'>
       <div class='card border-0 shadow' style='max-width: 350px; margin: auto;'>
         <img src='$room_thumb' class='card-img-top'>
         <div class='card-body'>
           <h5>$row[name]</h5>
           <h6 class='mb-4'>₹$row[price] per night</h6>
           <div class='features mb-4'>
               <h6 class='mb-1'>Features</h6>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 2 Rooms
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 1 Bathroom
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 1 Balcony
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 3 Sofa
               </span>
           </div>
           <div class='facilities mb-4'>
             <h6 class='mb-1'>Facilities</h6>
             <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 Wifi
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 Television
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 AC
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 Room Heater
               </span>
           </div>
           <div class='guests mb-4'>
             <h6 class='mb-1'>Guests</h6>
             <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 $row[adult] Adults
               </span>
               <span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                 $row[children] Children
               </span>
           </div>
           <div class='rating mb-4'>
             <h6 class='mb-1'>Rating</h6>
             <span class='badge-rounded-pill bg-light'>
             <i class='bi bi-star-fill text-warning'></i>
             <i class='bi bi-star-fill text-warning'></i>
             <i class='bi bi-star-fill text-warning'></i>
             <i class='bi bi-star-fill text-warning'></i>
             </span> 
           </div>
           <div class='d-flex justify-content-evenly mb-2'>
              $book_btn
             <a href='room_details.php?id=$row[id]' class='btn btn-sm btn-outline-dark shadow-none'>More Details</a>
           </div>
         </div>
       </div>
     </div>
     ";
     $i++;
   }

   echo $returnData;

  }

  if(isset($_POST['check_available2']))
  {

    $frm_data = filteration($_POST);
    $i =1;
    $returnData ="";

    if($frm_data['startDate'])
    {
      $_SESSION['uStartDate'] = $frm_data['startDate'];
    }
   

    if($frm_data['endDate'])
    {
      $_SESSION['uEndDate'] = $frm_data['endDate'];
    }

    $room_res = select("SELECT * FROM `rooms` WHERE (`quantity`>? AND `removed`=?) AND (`adult`=? AND `children`=?)",[0,0,$frm_data['adult'],$frm_data['children']],'iiii');

    while($room_data = mysqli_fetch_assoc($room_res))
    {
      //get features of room

      $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f INNER JOIN `room_features` rfea ON 
      f.id = rfea.features_id WHERE rfea.room_id = '$room_data[id]'");

      $features_data = "";

      while($fea_row = mysqli_fetch_assoc($fea_q)){
        $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>   
                            $fea_row[name]
                          </span>";
      }

      //get facilities of room

      $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f INNER JOIN `room_facilities` rfac ON 
      f.id = rfac.facilities_id WHERE rfac.room_id = '$room_data[id]'");

      $facilities_data = "";

      while($fac_row = mysqli_fetch_assoc($fac_q)){
        $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                            $fac_row[name]
                          </span>";
      }

      // get Thumbnail of image

      $room_thumb = ROOMS_IMG_PATH."1.jpg";
      $thumb_q = mysqli_query($con,"SELECT * FROM `room_images` 
                WHERE `room_id`='$room_data[id]' AND `thumb`='1'");

      if(mysqli_num_rows($thumb_q)>0){
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
      } 


      $book_btn = "";

      if(!$settings_r['shutdown']){
        $book_btn = "<a href='TxnTest.php#$room_data[id]' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</a>";
      }
      //print room card

      echo<<<data
        <div class="card mb-4 border-0 shadow"> 
          <div class="row g-0 p-3  align-items-center">
          
            <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
              <img src="$room_thumb" class="img-fluid rounded">
            </div>

            <div class="col-md-5 px-lg-3 px-md-3 px-0">
              <h5 class="mb-3">$room_data[name]</h5>
              <div class="features mb-3">
                <h6 class="mb-1">Features</h6>
                  $features_data
              </div>
              <div class="facilities mb-3 ">
                <h6 class="mb-1">Facilities</h6>
                  $facilities_data
              </div>
              <div class="guests ">
                <h6 class="mb-1">Guests</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap lh-base">
                    $room_data[adult] Adult
                  </span>
                  <span class="badge rounded-pill bg-light text-dark text-wrap lh-base">
                    $room_data[children] Children
                  </span>
              </div>
            </div>

            <div class="col-md-2 text-center mt-lg-0 mt-md-0 mt-4">
              <h6 class="mb-4">₹$room_data[price] per night</h6>
              $book_btn
              <a href="room_details.php?id=$room_data[id]" class="btn btn-sm w-100 btn-outline-dark shadow-none">More Details</a>
            </div>

          </div>
        </div>
      data;    

    }

   

  
  }
  
?>