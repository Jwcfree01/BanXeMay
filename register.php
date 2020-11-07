<?php
session_start();//Bắt đầu session
include "db.php";//Kết nối database
if (isset($_POST["f_name"])) {
    //Lấy thông tin nhập vào
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $mobile = $_POST['mobile'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    
    //Định dạng cho dữ liệu nhập vào
    $name = "/^[a-zA-Z ]+$/";
    $emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
    $number = "/^[0-9]+$/";

    //Kiểm tra dữ liệu rỗng
    if(empty($f_name) || empty($l_name) || empty($email) || empty($password) || empty($repassword) || empty($mobile) || empty($address1) || empty($address2)){		
        echo "
            <div class='alert alert-warning'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><b>Please fill all fields!</b>
            </div>
        ";
        exit();
    } else {//Dữ liệu không rỗng
        if(!preg_match($name,$f_name)){//Kiểm tra định dạng first name
            echo "
                <div class='alert alert-warning'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>this $f_name is not valid!</b>
                </div>
            ";
            exit();
	}
	if(!preg_match($name,$l_name)){//Kiểm tra định dạng last name
            echo "
                <div class='alert alert-warning'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>this $l_name is not valid!</b>
                </div>
            ";
            exit();
	}
	if(!preg_match($emailValidation,$email)){//Kiểm tra định dạng email
            echo "
                <div class='alert alert-warning'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <b>this $email is not valid!</b>
                </div>
            ";
            exit();
	}
	if(strlen($password) < 9 ){//Kiểm tra password, yêu cầu dài >=9
            echo "
                <div class='alert alert-warning'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Password is weak</b>
                </div>
            ";
            exit();
	}	
	if($password != $repassword){//Kiểm tra trùng password và repassword
            echo "
                <div class='alert alert-warning'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>password is not same</b>
                </div>
            ";
	}        
	if(!preg_match($number,$mobile)){//Kiểm tra định dạng số mobile
            echo "
                <div class='alert alert-warning'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <b>Mobile number $mobile is not valid</b>
                </div>
            ";
            exit();
	}
	if(!(strlen($mobile) == 10)){//mobile phải 10 số
            echo "
                <div class='alert alert-warning'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        <b>Mobile number must be 10 digit</b>
                </div>
            ";
            exit();
	}
	//Kiểm tra địa chỉ email đã đăng ký trong database chưa
	$sql = "SELECT user_id FROM user_info WHERE email = '$email' LIMIT 1" ;
	$check_query = mysqli_query($con,$sql);
	$count_email = mysqli_num_rows($check_query);
	if($count_email > 0){//Nếu đã có
            echo "
                <div class='alert alert-danger'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Email address is already available. Try another email address</b>
                </div>
            ";
            exit();
	} else {//Nếu chưa có
            //Insert đăng ký
            $sql = "INSERT INTO user_info(user_id,first_name,last_name,email,password,mobile,address1,address2) VALUES (NULL, '$f_name', '$l_name', '$email','$password', '$mobile', '$address1', '$address2')";
            $run_query = mysqli_query($con,$sql);
            //Cập nhật lại thông tin cart
            $_SESSION["uid"] = mysqli_insert_id($con);
            $_SESSION["name"] = $f_name;
            $ip_add = getenv("REMOTE_ADDR");
            $sql = "UPDATE cart SET user_id = '$_SESSION[uid]' WHERE ip_add='$ip_add' AND user_id = -1";
            if(mysqli_query($con,$sql)){
                echo "register_success";
                echo "<script> location.href='store.php'; </script>";
                exit;
            }
	}
    }	
}
?>






















































