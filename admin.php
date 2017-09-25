<?php
$fromurl="http://192.168.191.49/"; //Direct access to this address is prohibited.
if( $_SERVER['HTTP_REFERER'] == "" )
{
header("Location:".$fromurl); exit;
}
error_reporting(E_ALL & ~ E_NOTICE);
//Prompt window
function alertExit($msg,$flush=0){
    // echo "<script language='javascript'>alert($msg);</script>";
    echo "<script type='text/javascript' language='javascript'>alert('$msg');</script>";
    if ($flush == 1) {
        echo "<script type='text/javascript' language='javascript'>window.location.href='admin.php'</script>";
    }elseif ($flush == 2) {
        echo "<script type='text/javascript' language='javascript'>history.back();self.location.reload();</script>";
    }
}
//Output the head
function htmlheader($title){
echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8">
<title>{$title}</title>
<meta name="description" content="vsftpManagementtool">
<meta name="keywords" content="linux,ftp,vsftp,mysql,pam_mysql,php">
<style type="text/css">
*{margin:0; padding:0; font-size:11px;}
ul,li{ list-style:none;}
a {text-decoration:none;color:#37a}
a:hover { background:#37a;color:white;padding:2px;}
img { border:0;}
.clear{ clear:both;}
.right{ float:right;}
.left{ float:left;}
#content{width:560px;margin:0 auto;text-align:center;margin-top:40px;}
.admin{border:1px solid #ccc; padding:30px;text-align:left;}
.admin h4{margin:10px 0;}
.admin p{margin:10px 0;}
.admin p .btn{padding:2px 4px;}
</style>
</head>
<body>
<div id="content">
    <div class="admin">
        <h4>{$title}</h4>
        <p class="admin_btn">Administration Menu：<a href="?ac=ftp">ftp state</a>&nbsp;&nbsp;<a href="admin.php">List of users</a>&nbsp;&nbsp;<a href="?ac=add">Add user</a></p>
EOF;
}
//The output end
function htmlfooter(){
echo <<<EOF
    </div>
</div>
</body>
</html>
EOF;
}
//enquiries
function query($sql){
                 
    $result = mysql_query($sql)  or die ("SQL Statement query error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
            die('SQL: '.$sql.'<br>No relevant data was found');
    }
    $arrReturn = array();
    $index = 0;
    while($arr = mysql_fetch_assoc($result)){
        $arrReturn[$index] = $arr;
        $index++;
    }
    return $arrReturn;
}
//add
function adduser($name,$password){
    $sql = "select * from users where name='$name'";
    $result = mysql_query($sql)  or die ("SQL Statement query error: " . mysql_error());
    if (mysql_num_rows($result) > 0) {
            alertExit('Users already exist',1);
    }else{
        $sql = "insert into users(name,password) values('$name',md5('$password'))";
        $result = mysql_query($sql)  or die ("An error was added to the Angle house: " . mysql_error());
        // echo mysql_affected_rows();
        if(mysql_affected_rows()==1){
            alertExit("Add user success!",2);
        }
    } 
}
//deleting
function deluser($id){
    $sql = 'select * from users where id='.$id;
    $result = mysql_query($sql)  or die ("SQL Statement query error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
            alertExit('user no exist',1);
    }else{
        $sql = 'delete from users where id='.$id;
        $result = mysql_query($sql)  or die ("The removal of the corner house was an error: " . mysql_error());
        if(mysql_affected_rows()==1){
            alertExit("Delete user success!",1);
        }
    }
}
//modification
function moduser($id,$name,$password){
    $sql = "select * from users where id=$id";
    $result = mysql_query($sql)  or die ("SQL Statement query error: " . mysql_error());
    if (mysql_num_rows($result) == 0) {
            alertExit('user no exist',1);
    }else{
        $sql = "select * from users where name='$name' and id!=$id";
        $result = mysql_query($sql)  or die ("SQL Statement query error: " . mysql_error());
        if (mysql_num_rows($result) > 0) {
                alertExit('Users already exist',1);
        }else{    
            $sql = "update users set name='$name', password='$password' where id=$id";
            $result = mysql_query($sql)  or die ("Error to modify the corner door: " . mysql_error());
            if(mysql_affected_rows()==1){
                alertExit("Modify user success!",1);
            }
            alertExit("No operation is done!",1);
        }
    }
}
//ftp State management
function ftpadmin($service='status'){
                 
    $arrFtp = array();
    $result = mysql_query("SELECT id FROM users");
    $num_rows = mysql_num_rows($result);
    $arrFtp['usercount'] = $num_rows;
                 
    if($service=='status'){
                     
        $arrFtp['status'] = `service vsftpd status`;
                     
    }elseif($service=='restart'){
                     
        $arrFtp['status'] = `service vsftpd restart`;
                     
    }elseif ($service=='stop') {
                     
        $arrFtp['status'] = `service vsftpd stop`;
                     
    }
                 
    if(empty($arrFtp['status'])){
        $arrFtp['status'] = 'Unknow';
    }
    return $arrFtp;
                 
}
$conn = include 'db.php';
$strAction = htmlspecialchars($_GET['ac']);
$arrAction = array('del', 'mod', 'add', 'ftp');
if (! in_array($strAction, $arrAction)) {
        htmlheader('Vsftp administer');
        $arrUserList = query('select * from users');
        foreach ($arrUserList as $key => $value) {
        ?>
        <form action='./admin.php?ac=mod' method='post'>
        <p><label>name:</label> <input type="text" name="name" value="<?php echo $value['name'];?>">
            <label>password:</label> <input type="password" name="password" value="<?php echo $value["password"];?>">
            <input type='hidden' name='id' value="<?php echo $value["id"];?>">
             <input type="submit" value="edit" class="btn">&nbsp;<input type="button" onclick="window.location.href='?id=<?php echo $value["id"];?>&ac=del'" value="delete" class="btn"></p>
        </form>
        <?php }
        htmlfooter();
}else{
                 
    if ($strAction=='add') {
                     
        if ($_SERVER['REQUEST_METHOD']=='POST') {
                         
            $name = htmlspecialchars($_POST['name']);
            $password = htmlspecialchars($_POST['password']);
                         
            adduser($name,$password);
                         
        }else{
            htmlheader('adduser');
            ?>
        <form action='./admin.php?ac=add' method='post'>
        <p><label>name:</label> <input type="text" name="name" value="">
            <label>password:</label> <input type="password" name="password" value="">
            <input type="submit" value="submit" class="btn">&nbsp;<input type="reset" value="reset" class="btn"></p>
        </form>
<?php      
            htmlfooter();
        }
    }elseif ($strAction=='del') {
                     
        $intId = intval($_GET['id']);
                     
        if($intId!=''){
            deluser($intId);
        }else{
            alertExit('Parameter error！',1);
        }
                     
    }elseif ($strAction=='mod') {
                     
        $intId = intval($_POST['id']);
                     
        $name = htmlspecialchars($_POST['name']);
        $password = htmlspecialchars($_POST['password']);
                                 
        if($name!='' && $password!=''){
            moduser($intId,$name,$password);
        }else{
            alertExit('Parameter error！',1);
        }
    }elseif($strAction=='ftp'){
            htmlheader('FTP state');
            $arrFtp = array();
            $status = htmlspecialchars($_GET['status']);
            if($status=='restart')$arrFtp = ftpadmin('restart');
            elseif($status=='stop')$arrFtp = ftpadmin('stop');
            else $arrFtp = ftpadmin();
            ?>
        <p><label>Total number of users：</label> <?php echo $arrFtp['usercount'];?> <br>
            <label>vsftp state：</label> <?php echo $arrFtp['status'];?>
            <input type="button" onclick="window.location.href='?ac=ftp&status=restart'" value="restart" class="btn">&nbsp;<input type="button" onclick="window.location.href='?ac&status=stop'" value="stop" class="btn"></p>
<?php      
            htmlfooter();
                     
    }else{
        die('Invalid address！');
    }
}