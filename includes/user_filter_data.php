<?php

//Add my_sqli_real_escape_string to all user input text fields

	$user_last_name = mysqli_real_escape_string($conn, $_POST['user_last_name']);
	$user_first_name = mysqli_real_escape_string($conn, $_POST['user_first_name']);
	$user_title = mysqli_real_escape_string($conn, $_POST['user_title']);
	$user_username = mysqli_real_escape_string($conn, $_POST['user_username']);
	$user_handle = mysqli_real_escape_string($conn, $_POST['user_handle']);
	$user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
	$user_phone = mysqli_real_escape_string($conn, $_POST['user_phone']);
	$user_cell = mysqli_real_escape_string($conn, $_POST['user_cell']);
	$user_password = mysqli_real_escape_string($conn, $_POST['user_password']);
	$user_category = mysqli_real_escape_string($conn, $_POST['user_category']);
	$user_status = mysqli_real_escape_string($conn, $_POST['user_status']);	
	$super_user = mysqli_real_escape_string($conn, $_POST['super_user']);
	$user_access_A = mysqli_real_escape_string($conn, $_POST['user_access_A']);
	

	
	$user_supervisor = mysqli_real_escape_string($conn, $_POST['user_supervisor']);
	$user_department = mysqli_real_escape_string($conn, $_POST['user_department']);
	$user_role = mysqli_real_escape_string($conn, $_POST['user_role']);
	$management_user = mysqli_real_escape_string($conn, $_POST['management_user']);
	

	
	$notify_type = mysqli_real_escape_string($conn, $_POST['notify_type']);
	$notify_category = mysqli_real_escape_string($conn, $_POST['notify_category']);
	$notify_subcategory = mysqli_real_escape_string($conn, $_POST['notify_subcategory']);
	$notify_severity = mysqli_real_escape_string($conn, $_POST['notify_severity']);
	$notify_flag = mysqli_real_escape_string($conn, $_POST['notify_flag']);
	$notify_factor = mysqli_real_escape_string($conn, $_POST['notify_factor']);
	$notify_location = mysqli_real_escape_string($conn, $_POST['notify_location']);
	$notify_tag = mysqli_real_escape_string($conn, $_POST['notify_tag']);
	$notify_group = mysqli_real_escape_string($conn, $_POST['notify_group']);
	
/*
	$ = mysqli_real_escape_string($conn, $_POST['']);
	$ = mysqli_real_escape_string($conn, $_POST['']);
	$ = mysqli_real_escape_string($conn, $_POST['']);
	$ = mysqli_real_escape_string($conn, $_POST['']);
*/	



//Filter for special characters and tags
	
	$user_last_name = trim(htmlentities(strip_tags($user_last_name)));
	$user_first_name = trim(htmlentities(strip_tags($user_first_name)));
	$user_title = trim(htmlentities(strip_tags($user_title)));
	$user_username = trim(htmlentities(strip_tags($user_username)));
	$user_handle = trim(htmlentities(strip_tags($user_handle)));
	$user_email = trim(htmlentities(strip_tags($user_email)));
	$user_phone = trim(htmlentities(strip_tags($user_phone)));
	$user_cell = trim(htmlentities(strip_tags($user_cell)));
	$user_password = trim(htmlentities(strip_tags($user_password)));
	$user_category = trim(htmlentities(strip_tags($user_category)));
	$user_status = trim(htmlentities(strip_tags($user_status)));
	$super_user = trim(htmlentities(strip_tags($super_user)));
	$user_access_A = trim(htmlentities(strip_tags($user_access_A)));
	
	
	$user_supervisor = trim(htmlentities(strip_tags($user_supervisor)));
	$user_department = trim(htmlentities(strip_tags($user_department)));
	$user_role = trim(htmlentities(strip_tags($user_role)));
	$management_user = trim(htmlentities(strip_tags($management_user)));


	$notify_type = trim(htmlentities(strip_tags($notify_type)));
	$notify_cateogry = trim(htmlentities(strip_tags($notify_cateogry)));
	$notify_subcategory = trim(htmlentities(strip_tags($notify_subcategory)));
	$notify_severity = trim(htmlentities(strip_tags($notify_severity));
	$notify_flag = trim(htmlentities(strip_tags($notify_flag)));
	$notify_factor = trim(htmlentities(strip_tags($notify_factor)));
	$notify_location = trim(htmlentities(strip_tags($notify_location)));
	$notify_tag = trim(htmlentities(strip_tags($notify_tag)));
	$notify_group = trim(htmlentities(strip_tags($notify_group)));

	
/*
	$ = trim(htmlentities(strip_tags($)));
	$ = trim(htmlentities(strip_tags($)));
	$ = trim(htmlentities(strip_tags($)));
	$ = trim(htmlentities(strip_tags($)));
	$ = trim(htmlentities(strip_tags($)));
	*/

?>

