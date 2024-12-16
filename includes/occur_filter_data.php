<?php

//Add my_sqli_real_escape_string to all user input text fields

	$reporter_last_name = mysqli_real_escape_string($conn, $_POST['reporter_last_name']);
	$reporter_first_name = mysqli_real_escape_string($conn, $_POST['reporter_first_name']);
	$reporter_phone = mysqli_real_escape_string($conn, $_POST['reporter_phone']);
	$reporter_email = mysqli_real_escape_string($conn, $_POST['reporter_email']);
	$reporter_dept = mysqli_real_escape_string($conn, $_POST['reporter_dept']);
	$reporter_job = mysqli_real_escape_string($conn, $_POST['reporter_job']);
	$reporter_category = mysqli_real_escape_string($conn, $_POST['reporter_category']);
	$reporter_severity = mysqli_real_escape_string($conn, $_POST['reporter_severity']);

	$patient_last_name = mysqli_real_escape_string($conn, $_POST['patient_last_name']);
	$patient_first_name = mysqli_real_escape_string($conn, $_POST['patient_first_name']);
	$patient_gender = mysqli_real_escape_string($conn, $_POST['patient_gender']);
	$patient_age = mysqli_real_escape_string($conn, $_POST['patient_age']);
	$patient_MRN = mysqli_real_escape_string($conn, $_POST['patient_MRN']);
	$patient_unit = mysqli_real_escape_string($conn, $_POST['patient_unit']);
	$patient_program = mysqli_real_escape_string($conn, $_POST['patient_program']);
	$patient_loc = mysqli_real_escape_string($conn, $_POST['patient_loc']);
	//$patient_dob = mysqli_real_escape_string($conn, $_POST['patient_dob']);
	//$admit_date = mysqli_real_escape_string($conn, $_POST['admit_date']);


	$md_attending = mysqli_real_escape_string($conn, $_POST['md_attending']);

	$occur_type = mysqli_real_escape_string($conn, $_POST['occur_type']);
	//$occur_date = mysqli_real_escape_string($conn, $_POST['occur_date']);
	//$occur_time= mysqli_real_escape_string($conn, $_POST['occur_time']);
	$occur_location = mysqli_real_escape_string($conn, $_POST['occur_location']);
	$occur_description = mysqli_real_escape_string($conn, $_POST['occur_description']);
	$occur_intervention = mysqli_real_escape_string($conn, $_POST['occur_intervention']);

	$occur_employee_injury = mysqli_real_escape_string($conn, $_POST['occur_employee_injury']);
	$occur_patient_injury = mysqli_real_escape_string($conn, $_POST['occur_patient_injury']);
	$employee_injury_description = mysqli_real_escape_string($conn, $_POST['employee_injury_description']);
	$patient_injury_description = mysqli_real_escape_string($conn, $_POST['patient_injury_description']);

	$occur_PRN = mysqli_real_escape_string($conn, $_POST['occur_PRN']);
	$occur_code = mysqli_real_escape_string($conn, $_POST['occur_code']);
	$occur_staff = mysqli_real_escape_string($conn, $_POST['occur_staff']);
	$code_notes = mysqli_real_escape_string($conn, $_POST['code_notes']);
	$clinical_category = mysqli_real_escape_string($conn, $_POST['clinical_category']);
	$occur_area = mysqli_real_escape_string($conn, $_POST['occur_area']);
	$patient_transfer = mysqli_real_escape_string($conn, $_POST['patient_transfer']);
	$patient_transfer_notes = mysqli_real_escape_string($conn, $_POST['patient_transfer_notes']);
	

	$patient_restraint_notes = mysqli_real_escape_string($conn, $_POST['patient_restraint_notes']);
	$patient_seclusion_notes = mysqli_real_escape_string($conn, $_POST['patient_seclusion_notes']);
	$occur_patient_restraint = mysqli_real_escape_string($conn, $_POST['occur_patient_restraint']);
	$occur_patient_seclusion = mysqli_real_escape_string($conn, $_POST['occur_patient_seclusion']);
	$rs_notification_notes = mysqli_real_escape_string($conn, $_POST['rs_notification_notes']);
	$rs_additional_notes = mysqli_real_escape_string($conn, $_POST['rs_additional_notes']);
	$restraint_minutes = mysqli_real_escape_string($conn, $_POST['restraint_minutes']);
	$seclusion_minutes = mysqli_real_escape_string($conn, $_POST['seclusion_minutes']);
	$rs_notification = mysqli_real_escape_string($conn, $_POST['rs_notification']);
	$rs_documentation = mysqli_real_escape_string($conn, $_POST['rs_documentation']);

	$manager_followup_job = mysqli_real_escape_string($conn, $_POST['manager_followup_job']);
	$manager_followup_name = mysqli_real_escape_string($conn, $_POST['manager_followup_name']);
	//$manager_review_date = mysqli_real_escape_string($conn, $_POST['manager_review_date']);
	$manager_followup_notes = mysqli_real_escape_string($conn, $_POST['manager_followup_notes']);
	$manager_communication = mysqli_real_escape_string($conn, $_POST['manager_communication']);


	$rm_followup_name = mysqli_real_escape_string($conn, $_POST['rm_followup_name']);
	$rm_severity = mysqli_real_escape_string($conn, $_POST['rm_severity']);
	$rm_followup_notes = mysqli_real_escape_string($conn, $_POST['rm_followup_notes']);
	$rm_followup_plan = mysqli_real_escape_string($conn, $_POST['rm_followup_plan']);
	//$rm_followup_date = mysqli_real_escape_string($conn, $_POST['rm_followup_date']);
	//$rm_review_date = mysqli_real_escape_string($conn, $_POST['rm_review_date']);

	//$occur_close_date = mysqli_real_escape_string($conn, $_POST['occur_close_date']);
	$occur_status = mysqli_real_escape_string($conn, $_POST['occur_status']);

	$occur_tags = mysqli_real_escape_string($conn, $_POST['occur_tags']);
	$rm_additional_notes = mysqli_real_escape_string($conn, $_POST['rm_additional_notes']);
	$rm_contributing_factors = mysqli_real_escape_string($conn, $_POST['rm_contributing_factors']);
	$occur_flag = mysqli_real_escape_string($conn, $_POST['occur_flag']);
	
	$md_temp = mysqli_real_escape_string($conn, $_POST['md_temp']);
		
	$rm_verify = mysqli_real_escape_string($conn, $_POST['rm_verify']);
	$occur_subcategory = mysqli_real_escape_string($conn, $_POST['occur_subcategory']);
	$injury_other = mysqli_real_escape_string($conn, $_POST['injury_other']);
	$injury_other_notes = mysqli_real_escape_string($conn, $_POST['injury_other_notes']);
	//$target_date = mysqli_real_escape_string($conn, $_POST['target_date']);
	//$complete_date = mysqli_real_escape_string($conn, $_POST['complete_date']);
	$manager_status = mysqli_real_escape_string($conn, $_POST['manager_status']);
	$manager_action = mysqli_real_escape_string($conn, $_POST['manager_action']);
	
	
	$occur_notification_notes = mysqli_real_escape_string($conn, $_POST['occur_notification_notes']);
	$occur_additional_notes = mysqli_real_escape_string($conn, $_POST['occur_additional_notes']);
	
	$audit_trail = mysqli_real_escape_string($conn, $_POST['audit_trail']);

	/*
	$ = mysqli_real_escape_string($conn, $_POST['']);
	$ = mysqli_real_escape_string($conn, $_POST['']);
	*/

//Filter for special characters and tags



function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    
    $input = trim($input);
    $input = strip_tags($input);
    return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// List of fields to sanitize
$fields_to_sanitize = [
    'reporter_last_name', 'reporter_first_name', 'reporter_phone', 'reporter_email',
    'reporter_dept', 'reporter_job', 'reporter_category', 'reporter_severity',
    'patient_last_name', 'patient_first_name', 'patient_gender', 'patient_age',
    'patient_MRN', 'patient_unit', 'patient_program', 'patient_loc', 'md_attending',
    'occur_type', 'occur_location', 'occur_description', 'occur_intervention',
    'occur_employee_injury', 'occur_patient_injury', 'employee_injury_description',
    'patient_injury_description', 'occur_PRN', 'occur_code', 'occur_staff',
    'code_notes', 'clinical_category', 'occur_area', 'patient_transfer',
    'patient_transfer_notes', 'occur_patient_seclusion', 'occur_patient_restraint',
    'restraint_minutes', 'seclusion_minutes', 'patient_restraint_notes',
    'patient_seclusion_notes', 'rs_additional_notes', 'rs_documentation',
    'rs_notification', 'rs_notification_notes', 'manager_followup_job',
    'manager_followup_name', 'manager_communication', 'manager_followup_notes', 'manager_followup_plan',
    'rm_followup_name', 'rm_severity', 'rm_followup_notes', 'rm_followup_plan',
    'occur_status', 'occur_flag', 'occur_tags', 'rm_additional_notes',
    'rm_contributing_factors', 'md_temp', 'rm_verify', 'occur_subcategory',
    'injury_other', 'injury_other_notes', 'manager_status', 'manager_action',
    'occur_notification_notes', 'occur_additional_notes', 'audit_trail'
];

// Apply sanitization to all fields
foreach ($fields_to_sanitize as $field) {
    if (isset($$field)) {
        $$field = sanitizeInput($$field);
    }
}

// Handle numeric fields separately
$numeric_fields = ['restraint_minutes', 'seclusion_minutes', 'patient_age'];
foreach ($numeric_fields as $field) {
    if (isset($$field)) {
        $$field = filter_var($$field, FILTER_SANITIZE_NUMBER_INT);
    }
}

// Date fields don't need HTML escaping, but should be validated
$date_fields = ['patient_dob', 'admit_date', 'occur_date', 'occur_time', 
                'manager_review_date', 'rm_followup_date', 'rm_review_date', 
                'occur_close_date', 'target_date', 'complete_date'];
foreach ($date_fields as $field) {
    if (isset($$field)) {
        // Validate date format here if needed
        // For example: $$field = validateDate($$field);
    }
}
?>















<?php
//Filter for special characters and tags - previous code
/*	
	$reporter_last_name = trim(htmlentities(strip_tags($reporter_last_name)));
	$reporter_first_name = trim(htmlentities(strip_tags($reporter_first_name)));
	$reporter_phone = trim(htmlentities(strip_tags($reporter_phone)));
	$reporter_email = trim(htmlentities(strip_tags($reporter_email)));
	$reporter_dept = trim(htmlentities(strip_tags($reporter_dept)));
	$reporter_job = trim(htmlentities(strip_tags($reporter_job)));
	$reporter_category = trim(htmlentities(strip_tags($reporter_category)));
	$reporter_severity = trim(htmlentities(strip_tags($reporter_severity)));

	$patient_last_name = trim(htmlentities(strip_tags($patient_last_name)));
	$patient_first_name = trim(htmlentities(strip_tags($patient_first_name)));
	$patient_gender = trim(htmlentities(strip_tags($patient_gender)));
	$patient_age = trim(htmlentities(strip_tags($patient_age)));
	$patient_MRN = trim(htmlentities(strip_tags($patient_MRN)));
	$patient_unit = trim(htmlentities(strip_tags($patient_unit)));
	$patient_program = trim(htmlentities(strip_tags($patient_program)));
	$patient_loc = trim(htmlentities(strip_tags($patient_loc)));
	//$patient_dob = trim(htmlentities(strip_tags($patient_dob)));
	//$admit_date = trim(htmlentities(strip_tags($admit_date)));
	$md_attending = trim(htmlentities(strip_tags($md_attending)));

	$occur_type = trim(htmlentities(strip_tags($occur_type)));
	//$occur_date = trim(htmlentities(strip_tags($occur_date)));
	//$occur_time = trim(htmlentities(strip_tags($occur_time)));
	$occur_location = trim(htmlentities(strip_tags($occur_location)));
	$occur_description = trim(htmlentities(strip_tags($occur_description)));
	$occur_intervention = trim(htmlentities(strip_tags($occur_intervention)));

	$occur_employee_injury = trim(htmlentities(strip_tags($occur_employee_injury)));
	$occur_patient_injury = trim(htmlentities(strip_tags($occur_patient_injury)));
	$employee_injury_description = trim(htmlentities(strip_tags($employee_injury_description)));
	$patient_injury_description = trim(htmlentities(strip_tags($patient_injury_description)));

	$occur_PRN = trim(htmlentities(strip_tags($occur_PRN)));
	$occur_code = trim(htmlentities(strip_tags($occur_code)));
	$occur_staff = trim(htmlentities(strip_tags($occur_staff)));
	$code_notes = trim(htmlentities(strip_tags($code_notes)));
	$clinical_category = trim(htmlentities(strip_tags($clinical_category)));
	$occur_area = trim(htmlentities(strip_tags($occur_area)));
	$patient_transfer = trim(htmlentities(strip_tags($patient_transfer)));
	$patient_transfer_notes = trim(htmlentities(strip_tags($patient_transfer_notes)));

	$occur_patient_seclusion = trim(htmlentities(strip_tags($occur_patient_seclusion)));
	$occur_patient_restraint = trim(htmlentities(strip_tags($occur_patient_restraint)));
	$restraint_minutes = trim(htmlentities(strip_tags($restraint_minutes)));
	$seclusion_minutes = trim(htmlentities(strip_tags($seclusion_minutes)));
	$patient_restraint_notes = trim(htmlentities(strip_tags($patient_restraint_notes)));
	$patient_seclusion_notes = trim(htmlentities(strip_tags($patient_seclusion_notes)));

	$rs_additional_notes = trim(htmlentities(strip_tags($rs_additional_notes)));
	$rs_documentation = trim(htmlentities(strip_tags($rs_documentation)));
	$rs_notification = trim(htmlentities(strip_tags($rs_notification)));
	$rs_notification_notes = trim(htmlentities(strip_tags($rs_notification_notes)));
	
	$manager_followup_job = trim(htmlentities(strip_tags($manager_followup_job)));
	$manager_followup_name = trim(htmlentities(strip_tags($manager_followup_name)));
	//$manager_review_date = trim(htmlentities(strip_tags($manager_review_date)));
	$manager_followup_notes = trim(htmlentities(strip_tags($manager_followup_notes)));
	$manager_followup_plan = trim(htmlentities(strip_tags($manager_followup_plan)));

	$rm_followup_name = trim(htmlentities(strip_tags($rm_followup_name)));
	$rm_severity = trim(htmlentities(strip_tags($rm_severity)));
	$rm_followup_notes = trim(htmlentities(strip_tags($rm_followup_notes)));
	$rm_followup_plan = trim(htmlentities(strip_tags($rm_followup_plan)));
	//$rm_followup_date = trim(htmlentities(strip_tags($rm_review_date)));
	//$rm_review_date = trim(htmlentities(strip_tags($rm_review_date)));

	//$occur_close_date = trim(htmlentities(strip_tags($occur_close_date)));
	$occur_status = trim(htmlentities(strip_tags($occur_status)));

	$occur_flag = trim(htmlentities(strip_tags($occur_flag)));
	$occur_tags = trim(htmlentities(strip_tags($occur_tags)));
	$rm_additional_notes = trim(htmlentities(strip_tags($rm_additional_notes)));
	$rm_contributing_factors = trim(htmlentities(strip_tags($rm_contributing_factors)));

	$md_temp = trim(htmlentities(strip_tags($md_temp)));

	$rm_verify = trim(htmlentities(strip_tags($rm_verify)));
	$occur_subcategory = trim(htmlentities(strip_tags($occur_subcategory)));
	$injury_other = trim(htmlentities(strip_tags($injury_other)));
	$injury_other_notes = trim(htmlentities(strip_tags($injury_other_notes)));
	//$target_date = trim(htmlentities(strip_tags($target_date)));
	//$complete_date = trim(htmlentities(strip_tags($complete_date)));
	$manager_status = trim(htmlentities(strip_tags($manager_status)));
	$manager_action = trim(htmlentities(strip_tags($manager_action)));
	
	$occur_notification_notes = trim(htmlentities(strip_tags($occur_notification_notes)));
	$occur_additional_notes = trim(htmlentities(strip_tags($occur_additional_notes)));
	
	$audit_trail = trim(htmlentities(strip_tags($audit_trail)));
*/	


?>

