function DoInstall(){

	if($("#db_host").val() == ""){
		alert("database host cannot be empty");
		$("#db_host").focus();
		return false;
	}
	
	if($("#db_user").val() == ""){
		alert("database account cannot be empty");
		$("#db_user").focus();
		return false;
	}

	/*if($("#db_pwd").val() == ""){
		alert("database password cannot be empty");
		$("#db_pwd").focus();
		return false;
	}*/

	if($("#db_prefix").val() == ""){
		alert("The data table prefix cannot be empty");
		$("#db_prefix").focus();
		return false;
	}

	if($("#db_name").val() == ""){
		alert("The database name cannot be empty");
		$("#db_name").focus();
		return false;
	}

	if($("#db_charset").val() == ""){
		alert("Database encoding cannot be empty");
		$("#db_charset").focus();
		return false;
	}

	if($("#userid").val() == ""){
		alert("The administrator account cannot be empty");
		$("#userid").focus();
		return false;
	}

	if($("#pwd").val() == ""){
		alert("Administrator password cannot be empty");
		$("#pwd").focus();
		return false;
	}

	if($("#email").val() == ""){
		alert("Administrator mailbox cannot be empty");
		$("#email").focus();
		return false;
	}
	$("#fomr01").submit();
}