<?php  
    if(isset($_POST["submit"]) && $_POST["submit"] == "login")  
    {  
        $user = $_POST["username"];  
        $psw = $_POST["password"];  
        if($user == "" || $psw == "")  
        {  
            echo "<script>alert('Please enter a username password or check the database link for success！'); history.go(-1);</script>";  
        }  
        else  
        {  
            $conn = include 'db.php';
            $sql = "select username,password from user where username = '$_POST[username]' and password = md5('$_POST[password]')";  
            $result = mysql_query($sql);  
            $num = mysql_num_rows($result);  
            if($num)  
            {  
                $row = mysql_fetch_array($result);  //The data is indexed in an array 
                echo  "<script>alert(' Log in OK！ ');window.location='admin.php';</script>";//Jump management page（admin.php) }  
            }  
            else  
            {  
                echo "<script>alert('User name or password is incorrect！');history.go(-1);</script>";  
            }  
        }  
    }  
    else  
    {  
        echo "<script>alert('Submit unsuccessfully！'); history.go(-1);</script>";  
    }  
  
?>  