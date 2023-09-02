<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?>-FEEDBACK</title>

    <style> 
      .h-line{
        width: 150px;
        margin: 0 auto;
        height: 1.7px;
      }
      .a{
        margin: 100px;
      }
      
      
    </style>
</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>

<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">USER FEEDBACK</h2>
  <div class="h-line bg-dark"></div>

</div>



<div class="container">
  <div class="row ">

  
  
  <div class="col-lg-10 col-md-6 mt-2 mb-1 a">
    <div class="bg-white rounded shadow p-4">
      <form method="POST">
        <h5>Send a Message</h5>
        <div class="mt-3">
          <label class="form-label" style="font-weight: 500;">Name</label>
          <input name="name" required type="text" class="form-control shadow-none" >
        </div>
  
        <div class="mt-3">
          <label class="form-label" style="font-weight: 500;">Message</label>
          <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
        </div>
        <button type="submit" name="submit" class="btn text-white custom-bg mt-3">SEND</button>
      </form>
    </div>
  </div>

  
  


  </div>
</div>


<?php
  if(isset($_POST['submit']))
  {
    $frm_data = filteration($_POST);


    $q = "INSERT INTO `feedback`(`name`,`message`) VALUES (?,?)";
    $values = [$frm_data['name'],$frm_data['message']];

    $res = insert($q,$values,'ss');
    if($res==1)
    {
      alert('success','Feedback Sent Successfully!');
    }
    else
    {
      alert('error','Server Down! Try again Later');
    }
    function remAlert(){
        document.getElementsByClassName('alert')[0].remove();
    }
  }
?>


<?php require('inc/footer.php'); ?>



</body>
</html> 