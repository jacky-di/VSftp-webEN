<?php 
header("Content-type: text/html; charset=utf-8");
$status = isset($_POST['status'])	? $_POST['status'] : false;
if($status){
	
	//Test database account password
	$mysql = mysql_connect($_POST['db_host'],$_POST['db_user'],$_POST['db_pwd']);
	$link =  $mysql ? 1 : 2;
	if($link == 1){

		//Create database
		$db_name = $_POST['db_name'];
		//If the query database exists, it is deleted and created
		$sel_db = mysql_select_db($_POST['db_name'],$mysql);
		if($sel_db){
			$dbname01 = mysql_query("drop database $db_name");
		}
		$dbname02 = mysql_query("CREATE DATABASE $db_name");
			
		if($dbname02){

			mysql_select_db($_POST['db_name'],$mysql);
			//Create the default data table
			$sql_arr = file_get_contents("vsftpd.sql"); 
$a=explode(";",$sql_arr); //Use explodes the ‍ $SQL string () function to ";"

foreach($a as $b){ //Through the array 
$c=$b.";"; //After segmentation, there is no ";"Because the SQL statement is ";"Finish, so add it before you execute the SQL 
mysql_query($c); //Execute SQL statement
} }			
			$myfile = fopen("../db.php", "w") or die("The current directory cannot be written or the file is occupied");
					//Create the configuration file for the project
					$config ="<?php
							mysql_connect('".$_POST['db_host']."','".$_POST['db_user']."','".$_POST['db_pwd']."');  
            mysql_select_db('".$_POST['db_name']."');";
					
					fwrite($myfile, $config);
					fclose($myfile);

				
            
					
					if($myfile){
						$url = explode('/', $_SERVER['index']);
						echo "<script>alert('Installed successfully, now jump to the login page'); location.href='../';</script>";
					}
				}
			

        else if($link == 2){
}
		echo "<script>alert('The database server or login password is incorrect'); history.go(-1);</script>";
		exit;

	}
$step = isset($_GET['step']) ? $_GET['step'] : 1;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="css/install.css" type="text/css"/>

<script src="js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="js/install.js" language="javascript" type="text/javascript"></script>


</head>
<body class="body">
	<div class="con_bg">
		<div class="con">
			<?php 
				if($step == 1){
			?>
				<div>
					<div class="con_title"><h3><b>Reading license agreement</b></h3></div>
					<div class="con-agreement" >
							<p>Copyright (c) 2012-2018, jakcy-di reserves all rights. </p>
							<p>Thank you for choosing the FTP -web system.</p>
							
							<p>In order for you to use the software correctly and legally, please read the following terms and conditions before using:</p>
						<strong>1. This license agreement is applicable and only applicableftp-web 1.0 version,Jack - di's final interpretation of this licensing agreement。</strong>
						<strong>2.The right to an agreement </strong>
							<p>(1). You can apply this software to non-commercial use on the basis of full compliance with this end-user authorization agreement without payment of software copyright authorization fees.</p>
							<p>(2).You can modify the jakcy-di source code or interface style to suit your site requirements within the constraints and limits set by the protocol. </p>
							<p>(3).You have the ownership of the entire content of the website built with this software, and you have the legal obligation to bear the contents.</p>
							<p>(4).Without official permission, it is not allowed to rent, sell, mortgage or issue sub-licenses to the software or the business license associated with it.</p>
						<strong>3.Limited warranty and disclaimer </strong>
							<p>(1).The software and the accompanying documents are provided in the form of no explicit or implied compensation or guarantee.</p>
							<p>(2).User voluntary and use of this software, you must understand the risk of using the software before the purchase products technical services, we do not promise to free users with any form of technical support, the use of guarantee, also does not undertake any problems by use of this software related responsibility. </p>
							<p>(3).The licensing agreement of electronic text form, like the agreement signed in person, has a complete and equal legal effect.Once you begin to confirm this agreement and install FTP - web system, shall be deemed to have been fully understand and accept the terms and conditions of this agreement, and the power, at the same time enjoy the above terms granted by the relevant constraints and restrictions.Any action beyond the scope of the agreement will directly violate this license agreement and constitute infringement. We have the right to terminate the authority at any time, order to stop the damage and reserve the right to hold the relevant responsible.</p>
							<p>(4).If the integration of the software with other software API example demonstration package, these files are copyright does not belong to this official software, and these files are not authorized to release, please refer to the related software licensing legally use.</p>
							<p><b>Protocol release time：</b> The agreement is released on September 25, 2017</p>
						
					</div>
					<br/>
					<br/>
					<div class="con-base">
						<input name="readpact" type="checkbox" id="readpact" value="" />&nbsp;<label for="readpact"><strong class="con-fc">I have read and agreed to this agreement</strong></label>&nbsp;&nbsp;&nbsp;
						<input type="button" class="btn btn-primary" value="&nbsp;&nbsp;&nbsp;&nbsp;continue&nbsp;&nbsp;&nbsp;&nbsp;" onclick="document.getElementById('readpact').checked ?window.location.href='index.php?step=2' : alert('You must agree to a software license agreement for installation！');" />
					</div>
				</div>
			<?php }?>
			<?php 
				if($step == 2){
			?>
				<div class="con-config">
					<form action="" id='fomr01' class="editprofileform" method="post">
						<input type="hidden" name="status" value="1">
						<div class="con_title"><h3><b>FTP-WEBBasic database configuration</b></h3></div>
						<div class="con-agreement01">
							<div class="con-email">
								<br/>
								<div style="color : #337ab7;"><label>Database configuration</label></div>
								<p>
									<label style="width:150px;">Database host：</label>
									<input type="text" class="input-xlarge" name="db_host" id="db_host" value="localhost" placeholder="" style="width:350px;"/>
								</p>
								<p>
									<label style="width:150px;">Database account：</label>
									<input type="text" class="input-xlarge" name="db_user" id="db_user" value="root" placeholder="" style="width:350px;"/>
								</p>
								<p>
									<label style="width:150px;"></label>
									<font>This user name must have permission to create the database (recommended with the root account).</font>
								</p>
								<p>
									<label style="width:150px;">Database password：</label>
									<input type="text" class="input-xlarge" name="db_pwd" id="db_pwd" value="" placeholder="" style="width:350px;"/>
								</p>
								<!-- <p>
									<label style="width:150px;">Data table prefix：</label>
									<input type="text" class="input-xlarge" name="db_prefix" id="db_prefix" value="" placeholder="如：sz24hours_" style="width:350px;"/>
								</p> -->
								<p>
									<label style="width:150px;">Database name：</label>
									<input type="text" class="input-xlarge" name="db_name" id="db_name" value="vsftpd" readonly="readonly" style="width:350px;"/>
									
								</p>
								<p>
									<label style="width:150px;"></label>
									<font>The default database is unalterable and can be changed to system corruption.</font>
								</p>
								<br/>
								<p>
									<label style="width:150px;">Database encoding:</label>
									<input type="text" class="input-xlarge" name="db_charset" id="db_charset" value="UTF8" readonly="readonly" style="width:350px;"/>
								</p>
							</div>
							
							<br/>
						</div>
						<div class="con-base">
	   						 <input type="button" class="btn btn-primary" value="&nbsp;&nbsp;&nbsp;back&nbsp;&nbsp;&nbsp;" onclick="window.location.href='index.php?step=1';" />&nbsp;&nbsp;&nbsp;
	            			<input type="button" class="btn btn-primary" value="Start the installation" onclick="DoInstall();" />
						</div>
					</form>
					<p>
									<label style="width:150px;"></label>
									<font>After the system is completed, a system user named admin password is also admin (it is recommended to delete the installation directory after installation is completed to prevent multiple initialization)</font>
								</p>
				</div>
			<?php }?>
		</div>
	</div>
</body>
</html>
