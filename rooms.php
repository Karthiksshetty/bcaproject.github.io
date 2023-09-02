<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); 
  $_SESSION['uStartDate'] = date("Y-m-d");
  $_SESSION['uEndDate'] = date("Y-m-d", strtotime('tomorrow'));
  ?>
  <title><?php echo $settings_r['site_title'] ?>-ROOM</title>

  <style> 
    .h-line{
      width: 150px;
      margin: 0 auto;
      height: 1.7px;
    }
  </style>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
    <div class="h-line bg-dark"></div>

  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3  col-md-12 mb-lg-0 mb-4 ps-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
          <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2">FILTERS</h4>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
              <div class="border mb-3 rounded bg-light p-3 rounded mb-3">
                <h5 class="mb-3" style="font-size: 18px; ">CHECK AVAILABLITY</h5>
                <label class="form-label" >Check-in</label>
                <input id="filterFormElem1" type="date" class="form-control shadow-none mb-3" min = "<?php echo $_SESSION['uStartDate']?>">
                <label class="form-label" >Check-out</label>
                <input id="filterFormElem2" type="date" class="form-control shadow-none" min = "<?php echo $_SESSION['uEndDate']?>">
              </div>
        
              <div class="border mb-3 rounded bg-light p-3 rounded mb-3">
                <h5 class="mb-3" style="font-size: 18px; ">GUESTS</h5>
                <div class="d-flex">
                  <div class="me-2">
                    <label class="form-check-label" >Adults</label>
                    <input id="filterFormElem3" type="number" class="form-control shadow-none" value =0>
                  </div>
                  <div>
                    <label class="form-check-label" >Childrens</label>
                    <input id="filterFormElem4" type="number" class="form-control shadow-none"  value =0>
                  </div> 
                </div>     
              </div>
              <div class="col-lg-1 mb-lg- mt-2">
              <button type="submit" class="btn text-white shadow-none custom-bg" id = "btnFilterSubmit">Submit</button>
            </div>
            </div>
          </div>
        </nav>
      </div>


      <div class="col-lg-9 col-md-12 px-4" id = "roomHolder2">

      <?php
        $room_res = select("SELECT * FROM `rooms` WHERE (`quantity`>0 AND `status`=?) AND `removed`=?",[1,0],'ii');

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
            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap lh-base me-1 mb-1'>
                                $fac_row[name]
                              </span>";
          }

          // get Thumbnail of image

          $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
          $img_q = mysqli_query($con,"SELECT * FROM `room_images` 
                    WHERE `room_id`='$room_data[id]' AND `thumb`='1'");

          if(mysqli_num_rows($img_q)>0){
            $thumb_res = mysqli_fetch_assoc($img_q);
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
       
      ?>
        
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script>
      var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
      });
      
      var swiper = new Swiper(".swiper-testimonials", {
          effect: "coverflow",
          grabCursor: true,
          centeredSlides: true,
          slidesPerView: "auto",
          slidesPerView: "3",
          loop: true,
          coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
          },
          pagination: {
            el: ".swiper-pagination",
          },
          breakpoints: {
            320: {
              slidesPerView: 1,
            },
            640: {
              slidesPerView: 1,
            },
            768: {
              slidesPerView: 2,
            },
            1024: {
              slidesPerView: 3,
            },
          }
        });
    </script>
  <?php require('inc/footer.php'); ?>
  <script src="admin/scripts/userRooms.js"></script>


</body>
</html> 