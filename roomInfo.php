<?php
    
require('./admin/inc/db_config.php');
require('./admin/inc/essentials.php');
session_start();


if(isset($_POST['BookRoom']))
{

	$frmData = filteration($_POST); 
    
	$res1 = select("SELECT * FROM `rooms` WHERE `id`=?",[$frmData['id']],'i');


        while($row = mysqli_fetch_assoc($res1)){
            echo $row['price'];
			
        }
}
if(isset($_POST['BookRoom2']))
{

	$frmData = filteration($_POST); 
    
	$res1 = select("SELECT * FROM `rooms` WHERE `id`=?",[$frmData['id']],'i');


        while($row = mysqli_fetch_assoc($res1)){
            echo $row['name'];
			
        }
}
if(isset($_POST['getDatesCount']))
{
    $day1 =  date_create($_SESSION['uStartDate']);
    $day2 = date_create($_SESSION['uEndDate']);
	$dayCount =date_diff($day1,$day2);
    echo $dayCount->format('%a');
}
if(isset($_POST['insertSession']))
{
    $q = "INSERT INTO `sessions`(`userID`) VALUES (?)";
    $values = [$_SESSION['uId']];
    $res = insert($q,$values,'i');
    echo 1;
}
if(isset($_POST['getTotalAmount']))
{
    $frmData = filteration($_POST); 
    $_SESSION['totalAmount'] = $frmData['totalAmount'];
    $_SESSION['uRoomId'] = $frmData['roomId'];
    echo $_SESSION['totalAmount'];
}


if(isset($_POST['get_booking']))
{
    $res = select("SELECT * FROM `bookings` WHERE `user_id` =?",[$_SESSION['uId']],'i');
		
        $i = 1;
        $data = "";
        while($row = mysqli_fetch_assoc($res))
        {
    
            $query1 = select("SELECT * FROM `rooms` WHERE `id`=?",[$row['room_id']],'i');
            $roomName =  mysqli_fetch_assoc($query1);
            $data.="
            <tr class='align-middle'>
                <td>$i</td>
                <td>$row[user_id]</td>
                <td>$row[room_id]</td>
                <td> 
                    <span class='badge rounded-pill bg-light text-dark'>
                        Check-in : $row[start_date]
                    </span><br>
                    <span class='badge rounded-pill bg-light text-dark'>
                        Check-out : $row[end_date]
                    </span>
                </td>
                <td>₹$row[total_amount]</td>
                <td>$roomName[name]</td>
                <td>

                <button type='button' onclick='remove_booking($row[id])' class='btn btn-danger shadow-none  text-dark btn-sm' data-bs-toggle='modal'>
                    <i class='bi bi-trash me-1'></i>Cancel
                </button>
            </td>
             
            </tr>
            ";
            $i++;
        }
        echo $data;


}

if(isset($_POST['remove_booking']))
{
    $frm_data = filteration($_POST);

    $res2 = delete("DELETE FROM `bookings` WHERE `id`=?", [$frm_data['booking_id']],'i');

    if($res2 || $res3 || $res4 || $res5){
        echo 1;
    }
    else{
        echo 0;
    }


}

?> 