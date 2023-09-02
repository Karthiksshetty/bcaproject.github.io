<?php
  require('./admin/inc/db_config.php');
  require('./admin/inc/essentials.php');

  

  if(isset($_POST['reset']))
  {
    $data = filteration($_POST);

    if($data['newpass'] != $data['cpass']){
        echo"
            <script>
                alert('Password Missmatch!');
                window.location.href='index.php';
            </script>
        ";
    }

    $query = "SELECT * FROM `user_cred` WHERE `email`='$data[email]' AND `phonenum`='$data[phonenum]' LIMIT 1";
    $result = mysqli_query($con,$query);
    if($result)
    {
        if(mysqli_num_rows($result)==1)
        {
            // email found

            $enc_pass = password_hash($data['newpass'],PASSWORD_BCRYPT);

            $query = "UPDATE `user_cred` SET `password`= '$enc_pass' WHERE `email`='$data[email]' AND `phonenum`='$data[phonenum]' LIMIT 1 ";
            if(mysqli_query($con,$query))
            {
                echo"
                    <script>
                        alert('Password Reset Success!');
                        window.location.href='index.php';
                    </script>
                ";
            }
            else
            {
                echo"
                    <script>
                        alert('Server Down! Try again later!');
                        window.location.href='index.php';
                    </script>
                ";
            }
        }
        else
        {
            echo"
                <script>
                    alert('Email or Phone Number Not Found');
                    window.location.href='index.php';
                </script>
            ";
        }
    }
    else
    {
        echo"
            <script>
                alert('Cannot run query');
                window.location.href='index.php';
            </script>
        ";
    }
  }


?>