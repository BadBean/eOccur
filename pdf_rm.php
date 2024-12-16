

<!-- PHP / SQL QUERY FOR "NEW REPORTS"    ========================================================================================= -->

<?php

//========================================== RISK MANAGEMENT / FOLLOW UP ====================================================
	print"<h4>QA / RISK MANAGEMENT REVIEW</h4>";

	print"<strong>Risk Management Review By:&nbsp&nbsp</strong>" . $rm_followup_name . "&nbsp&nbsp&nbsp" . "<strong>On:</strong>&nbsp" . $rm_review_date; 
	print "<br>";
	print"<strong>Status:&nbsp&nbsp</strong>" . $occur_status . "&nbsp&nbsp&nbsp" . "<strong>Close Date:&nbsp&nbsp</strong>" . $occur_close_date;
	print "<br>";
	print"<hr>";
	print"<strong>Severity:&nbsp&nbsp</strong>" . "&nbsp&nbsp&nbsp" . $rm_severity;
	print "<br>";
	
	print"<strong>Plan:&nbsp&nbsp</strong>" . $rm_followup_plan;
	print "<br>";

	
	print"<strong>Risk Management confirmation of follow up / plan completion:&nbsp&nbsp</strong>" . $rm_followup_date;
	print "<br>";
	print"<strong>RM Additional Notes:&nbsp&nbsp</strong>" . $rm_followup_notes;
	print "<br>";
	print"<hr>";

	
	print"<strong>Flags:&nbsp&nbsp</strong>" . $occur_flag . "<br>";
	print"<strong>Contributing Factors:&nbsp&nbsp</strong>" . $rm_contributing_factors . "<br>";
	print"<strong>Key Words / Tags:&nbsp&nbsp</strong>" . $occur_tags . "<br>";
	
	print"<hr>";

	



//========================================== NOTIFICATIONS ====================================================

$occur_notify = str_replace(",", ", ", $occur_notify); // Adds space after each comma
print "<strong>Notifications:&nbsp;&nbsp;</strong>" . $occur_notify;

?>

