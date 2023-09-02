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
    
    <title><?php echo $settings_r['site_title'] ?>-Home</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
    
    <style>
        .availability-form{
          margin-top: -50px;
          z-index: 2;
          position: relative;
        }
        @media screen and (max-width: 575px) {
          .availability-form{
          margin-top: 25px;
          padding: 0 35px;
        }
        }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>


  <!-- Carousel-->

  <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
        <div class="swiper-wrapper">
          <?php
            $res = selectAll('carousel');

            while($row = mysqli_fetch_assoc($res))
            {
                $path = CAROUSEL_IMG_PATH;
                echo <<<data
                  <div class="swiper-slide">
                    <img src="$path$row[image]" class="w-100 d-block" >
                  </div>
                data;
            }
          ?>  
        </div>
      </div>
  </div>


  <!-- check availability form-->

  <div id ="cont_availability" class="container availability-form">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded">
        <h5 class="mb-4">Check Booking Availablity</h5>
        <form id="check_available_rooms_form">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;" >Check-in</label>
              <input type="date" name = "check_in" class="form-control shadow-none" min = "<?php echo $_SESSION['uStartDate']?>">
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;" >Check-out</label>
              <input type="date" name = "check_out" class="form-control shadow-none" min = "<?php echo $_SESSION['uEndDate']?>">
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;" >Adult</label>
              <select name = "adults" class="form-select shadow-none">
                <option value="0">None</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 500;" >Children</label>
              <select name = "children" class="form-select shadow-none">
              <option value="0">None</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div>
            <div class="col-lg-1 mb-lg-3 mt-2">
              <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Our Rooms  -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our ROOMS</h2>
  <div class="container" id = "roomContainer">
    <div class="row" id="roomHolder">
        <?php 
          

          $res1 = select("SELECT * FROM `rooms` WHERE `quantity`>? AND `removed` = ?",[0,0],'ii');
          
          $ii = 1;
          $returnData ="";
          while($row = mysqli_fetch_assoc($res1))
          {
              
            $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
            $img_q = mysqli_query($con,"SELECT * FROM `room_images` 
                      WHERE `room_id`='$row[id]' AND `thumb`='1'");
            if(mysqli_num_rows($img_q)>0){
              $thumb_res = mysqli_fetch_assoc($img_q);
              $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
            } 

            $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f INNER JOIN `room_features` rfea ON 
            f.id = rfea.features_id WHERE rfea.room_id = '$row[id]'");

            $features_data = "";

            while($fea_row = mysqli_fetch_assoc($fea_q)){
              $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap lh-base'>
                                  $fea_row[name]
                                </span>";
            }

            //get facilities of room

            $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f INNER JOIN `room_facilities` rfac ON 
            f.id = rfac.facilities_id WHERE rfac.room_id = '$row[id]'");

            $facilities_data = "";

            while($fac_row = mysqli_fetch_assoc($fac_q)){
              $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap lh-base me-1 mb-1'>
                                  $fac_row[name]
                                </span>";
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
                      $features_data
                    </div>
                    <div class='facilities mb-4'>
                      <h6 class='mb-1'>Facilities</h6>
                      $facilities_data
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
          $ii++;
          if($ii>=4)
          {
            break;  
          }
          }
          echo $returnData;
        ?>
          
      <div class="col-lg-12 text-center mt-5">
        <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms >>></a>
      </div>
    </div>
  </div>
      


  <!-- Our facilities  -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our FACILITIES</h2>

  <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/wifi.svg" width="80px">
        <h5 class="mt-3">Wifi</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/ac.svg" width="80px">
        <h5 class="mt-3">AC</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/tv.svg" width="80px">
        <h5 class="mt-3">TV</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/heat.svg" width="80px">
        <h5 class="mt-3">Heater</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/spa.svg" width="80px">
        <h5 class="mt-3">Spa</h5>
      </div>
      <div class="col-lg-12 text-center mt-5">
        <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities >>></a>
      </div>
    </div>
  </div>


  <!-- Testimonials  -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php
            $q = "SELECT * FROM `feedback`";
            $data = mysqli_query($con,$q);

            while($row = mysqli_fetch_assoc($data))
            {
                echo<<<data
                    <div class="swiper-slide bg-white p-4 ">
                      <div class="profile d-flex align-items-center mb-3">
                        <h5 class="m-0 ms-2"><i class="bi bi-person-circle"></i> $row[name]</h5>
                      </div>
                        <p>$row[message]</p>
                        <div class="rating">
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                        </div>
                    </div>
                
                data;
            }
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="feedback.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">User Feedback >>></a>
    </div>
  </div>

  <!-- Reach us  -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>

  <div class="container ">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe']?>" loading="lazy"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5>Call Us</h5>
          <a href="tel : +91 <?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +91 <?php echo $contact_r['pn1']?>
          </a>
          <br>
          <?php
            if($contact_r['pn2']!=''){
              echo<<<data
                <a href="tel : +91 $contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
                  <i class="bi bi-telephone-fill"></i> +91 $contact_r[pn2]
                </a>
              data;
            }    
          ?>
        </div>
        <div class="bg-white p-4 rounded mb-4">
          <h5>Follow Us</h5>
          <?php
            if($contact_r['tw']!=''){
              echo<<<data
                <a href="$contact_r[tw]" class="d-inline-block mb-2">
                  <span class="badge bg-light text-dark fs-6 p-2">
                    <i class="bi bi-twitter me-1"></i> Twitter
                  </span>
               </a>
               <br>
              data;
            }
          ?>
          <a href="<?php echo $contact_r['fb']?>" class="d-inline-block mb-2 ">
            <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-facebook me-1"></i> Facebook
            </span>
          </a>
          <br>
          <a href="<?php echo $contact_r['insta']?>" class="d-inline-block ">
            <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-instagram me-1"></i> Instagram
            </span>
          </a>          
        </div>
      </div>
    </div>
  </div>

  <?php require('inc/footer.php'); ?>


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
      

    <script src="admin/scripts/home.js"></script>

</body>
</html> 