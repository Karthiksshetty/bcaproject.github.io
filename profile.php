<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?>-PROFILE</title>

    <style> 
      .h-line{
        width: 150px;
        margin: 0 auto;
        height: 1.7px;
      }
    </style>
</head>
<body class="bg-light">

<?php 
  require('inc/header.php'); 

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }
 $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],'s');

 if(mysqli_num_rows($u_exist)==0){
  redirect('index.php');
 }

 $u_fetch = mysqli_fetch_assoc($u_exist);
?>


  <div class="container" >
        <div class="row">
          <div class="col-12 my-5 px-4">
            <h2 class="fw-bold h-font text-center">PROFILE SETTINGS</h2>
            <div style="font-size: 14px;">
              <a href="index.php" class="text-secondary text-decoration-none">Home</a>
              <span class="text-seconadry"> > </span>
              <a href="bookings.php" class="text-secondary text-decoration-none">Bookings</a>
            </div>
          </div>

          <div class="col-12 mb-5 px-4">
            <div class="bg-white p-3 p-md-4 rounded shadow-sm">
              <form id="info-form">
                <h5 class="mb-3 fw-bold">Basic Information</h5>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Name</label> 
                    <input name="name" type="text" class="form-control shadow-none" value="<?php echo $u_fetch['name'] ?>">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control shadow-none" value="<?php echo $u_fetch['email'] ?>" readonly>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input  name="phonenum" pattern="[6-9]\d{9}" title="Enter a valid phone number" type="number" class="form-control shadow-none" value="<?php echo $u_fetch['phonenum'] ?>">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input name="dob" type="date" class="form-control shadow-none" value="<?php echo $u_fetch['dob'] ?>">
                  </div>
                  <div class="col-md-8 mb-3">
                    <label class="form-label">Address</label>
                    <textarea type="text" name="address" rows="1" class="form-control shadow-none"><?php echo $u_fetch['address'] ?></textarea>
                  </div>
                  <div class="col-md-4 mb-4">
                    <label class="form-label">Pincode </label>
                    <input name="pincode" type="number" class="form-control shadow-none" value="<?php echo $u_fetch['pincode'] ?>">
                  </div>
                </div>
                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
              </form>
            </div>
          </div>

          <div class="col-md-4 mb-5 px-4">
            <div class="bg-white p-3 p-md-4 rounded shadow-sm">
              <form id="profile-form">
                <h5 class="mb-3 fw-bold">New Image</h5>
                <img src="<?php echo USERS_IMG_PATH.$u_fetch['profile']?>" class="rounded-circle img-fluid mb-3">

                <label class="form-label">Profile</label>
                <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none mb-4" required>

                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
              </form>
            </div>
          </div>

          <div class="col-md-4 mb-5 px-4">
            <div class="bg-white p-3 p-md-4 rounded shadow-sm">
              <form id="aadhar-form">
                <h5 class="mb-3 fw-bold">Aadhar Card</h5>
                <img src="<?php echo AADHAR_IMG_PATH.$u_fetch['aadhar']?>" class="rounded img-fluid mb-3">

                <!-- <label class="form-label">Aadhar Image</label>
                <input name="aadhar" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none mb-4" required>

                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button> -->
              </form>
            </div>
          </div>

          <div class="col-md-4 mb-5 px-4">
            <div class="bg-white p-3 p-md-4 rounded shadow-sm">
              <form id="pass-form">
                <h5 class="mb-3 fw-bold">Change Password</h5>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label class="form-label">New Password </label>
                    <input name="new_pass"  pattern="\d{8}" title="Password must be of 8 characters or digits."  type="password" class="form-control shadow-none" >
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input name="confirm_pass" pattern="\d{8}" title="Password must be of 8 characters or digits."  type="password" class="form-control shadow-none">
                  </div>
                </div>
                <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
              </form>
            </div>
          </div>

        </div>
  </div>


<?php require('inc/footer.php');?>

<script>
   
  let info_form = document.getElementById('info-form');
    
  info_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();
    
    data.append('info_form','');
    data.append('name',info_form.elements['name'].value);


    data.append('phonenum',info_form.elements['phonenum'].value);

    data.append('address',info_form.elements['address'].value);
    data.append('pincode',info_form.elements['pincode'].value);


    data.append('dob',info_form.elements['dob'].value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/profile.php",true);


    xhr.onload = function(){
      console.log(this.responseText);
        if(this.responseText == 'phone_alreday'){
          alert('error',"This Phone Number already Exist!");
        }
        else if(this.responseText == 0){
          alert('error',"No Changes Made!")
        }
        else{
          window.location.href = window.location.pathname;
          alert('success',"Changes Saved!");
        }
    }
    xhr.send(data);
  });

  let profile_form = document.getElementById('profile-form');
    
  profile_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();
    
    data.append('profile_form','');
    data.append('profile',profile_form.elements['profile'].files[0]);

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/profile.php",true);


    xhr.onload = function(){
      console.log(this.responseText);
      if(this.responseText == 'inv_img'){
        alert('error',"Only JPG, WEBP & PNG images are allowed!");
      }
      else if(this.responseText == 'upd_failed'){
        alert('error',"Image Upload Fail!");
      }
      else if(this.responseText == 0){
        alert('error',"Updation Failed!")
      }
      else{
        window.location.href = window.location.pathname;
      }
    }
    xhr.send(data);
  });

  let pass_form = document.getElementById('pass-form');
    
  pass_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let new_pass = pass_form.elements['new_pass'].value;
    let confirm_pass = pass_form.elements['confirm_pass'].value;

    if(new_pass!=confirm_pass){
      alert('error',"Password do not match!");
      return false;
    }

    let data = new FormData();
    
    data.append('pass_form','');
    data.append('new_pass',new_pass);
    data.append('confirm_pass',confirm_pass);


    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/profile.php",true);


    xhr.onload = function(){
      console.log(this.responseText);
      if(this.responseText == 'pass_missmatch'){
        alert('error',"Password do not Match!");
      }
      else if(this.responseText == 0){
        alert('error',"Updation Failed!")
      }
      else{
        alert('success',"Changes Saved!");
        pass_form.reset();
      }
    }
    xhr.send(data);
  });


  let aadhar_form = document.getElementById('aadhar-form');
    
    aadhar_form.addEventListener('submit', (e)=>{
      e.preventDefault();
  
      let data = new FormData();
      
      data.append('aadhar_form','');
      data.append('aadhar',aadhar_form.elements['aadhar'].files[0]);
  
      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/profile.php",true);
  
  
      xhr.onload = function(){
        console.log(this.responseText);
        if(this.responseText == 'inv_img'){
          alert('error',"Only JPG, WEBP & PNG images are allowed!");
        }
        else if(this.responseText == 'upd_failed'){
          alert('error',"Image Upload Fail!");
        }
        else if(this.responseText == 0){
          alert('error',"Updation Failed!")
        }
        else{
          window.location.href = window.location.pathname;
        }
      }
      xhr.send(data);
    });

</script>



</body>
</html> 