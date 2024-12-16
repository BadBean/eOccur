<?php
// Start the output buffer if needed
// ob_start();
?>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>

<!-- Report Content Starts Here 
<div style="background-color: gainsboro; padding: 15px;">
    <h2>OCCURRENCE REPORT SUMMARY</h2>
-->
    

    <?php
    print "<h7><strong>Occur ID:</strong></h7> $occur_id &nbsp;&nbsp;&nbsp;&nbsp; <strong>Submitted:&nbsp;</strong> $occur_date <br>";
    print "<strong>Reporter: </strong> $reporter_first_name $reporter_last_name ($reporter_dept) &nbsp;&nbsp;&nbsp;&nbsp; 
    <strong>Email: </strong> $reporter_email &nbsp;&nbsp;&nbsp;&nbsp; <strong>Phone: &nbsp;</strong> $reporter_phone <strong>&nbsp;&nbsp;&nbsp;Cell:&nbsp; </strong> $reporter_cell <br>";

    print "<hr>";

    print "<strong>Type:&nbsp;</strong> $occur_type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Category:</strong> $reporter_category &nbsp;&nbsp;&nbsp;&nbsp; <strong>Subcategory:&nbsp;</strong> $occur_subcategory <br>";
    print "<strong>Date / Time of Occurrence:</strong> $occur_date $occur_time <br>";
    print "<strong>Location: </strong> $occur_location &nbsp;&nbsp;&nbsp; <strong>Area:&nbsp;</strong> $occur_area <br>";

    print "<hr>";

    print "<strong>Patient:</strong> $patient_first_name $patient_last_name, $patient_age year old $patient_gender &nbsp;&nbsp;&nbsp; <strong>Attending:</strong> $md_attending <br>";
    print "<strong>MRN:</strong> $patient_MRN <br>";
    print "<strong>Level of Care: </strong> $patient_loc <br>";
    print "<strong>Patient Unit / Program: </strong> $patient_unit / $patient_program <br>";
    print "<strong>Preliminary Severity: </strong> $reporter_severity &nbsp;&nbsp;&nbsp;&nbsp; <strong>RM Severity: &nbsp;</strong> $rm_severity <br>";

    print "<hr>";

    print "<strong>Description:&nbsp;&nbsp;</strong> $occur_description <br>";
    print "<strong>Intervention:&nbsp;</strong> $occur_intervention <br>";

    print "<hr>";

    print "<strong>Code Called?&nbsp;&nbsp;</strong> $occur_code &nbsp;&nbsp;&nbsp; <strong>Notes:&nbsp;&nbsp;</strong> $code_notes <br>";

    // Only print the PRN line if occur_type is "Patient Care"
    if ($occur_type === "Patient Care") {
        print "<strong>PRN Meds Given?&nbsp;&nbsp;</strong> $occur_PRN <br>";
    }

    print "<strong>Staff Present:&nbsp;&nbsp;</strong> $occur_staff <br>";

    print "<hr>";

    print "<strong>Patient Injury?:&nbsp;&nbsp;</strong> $occur_patient_injury &nbsp;&nbsp;&nbsp;&nbsp; <strong>Notes:&nbsp;&nbsp;</strong> $patient_injury_description <br>";
    print "<strong>Employee Injury?:&nbsp;&nbsp;</strong> $occur_employee_injury &nbsp;&nbsp;&nbsp;&nbsp; <strong>Notes:&nbsp;&nbsp;</strong> $employee_injury_description <br>";
    print "<strong>Other Injury?:&nbsp;&nbsp;</strong> $injury_other &nbsp;&nbsp;&nbsp;&nbsp; <strong>Notes:&nbsp;&nbsp;</strong> $injury_other_notes <br>";

    print "<hr>";

    // Only print the R/S section if occur_type is "Patient Care"
    if ($occur_type === "Patient Care") {
        print "<strong>Restraint?:&nbsp;&nbsp;</strong> $occur_patient_restraint &nbsp;&nbsp;&nbsp;&nbsp; <strong>Minutes:&nbsp;&nbsp;</strong> $restraint_minutes &nbsp;&nbsp;&nbsp;&nbsp; <strong>Note:&nbsp;&nbsp;</strong> $patient_restraint_notes <br>";
        print "<strong>Seclusion?:&nbsp;&nbsp;</strong> $occur_patient_seclusion &nbsp;&nbsp;&nbsp;&nbsp; <strong>Minutes:&nbsp;&nbsp;</strong> $seclusion_minutes &nbsp;&nbsp;&nbsp;&nbsp; <strong>Note:&nbsp;&nbsp;</strong> $patient_seclusion_notes <br>";

        print "<strong>Restraint/Seclusion Documentation Completed?:&nbsp;&nbsp;</strong> $rs_documentation <br>";
        print "<strong>Required Notifications Made?:&nbsp;&nbsp;</strong> $rs_notification &nbsp;&nbsp;&nbsp;&nbsp; <strong>Notes:&nbsp;&nbsp;</strong> $rs_notification_notes <br>";

        print "<strong>Additional R/S Notes:&nbsp;&nbsp;</strong> $rs_additional_notes <br>";

        print "<hr>";
    }

    print "<strong>Notification Notes:&nbsp;&nbsp;</strong> $occur_notification_notes <br>";
    print "<strong>Additional Notes:&nbsp;&nbsp;</strong> $occur_additional_notes <br>";

    print "<hr>";

    print "<strong>Status:&nbsp;&nbsp;</strong> $occur_status <br>";
    print "<strong>Routing:&nbsp;&nbsp;</strong> $occur_notify <br>";
    print "<hr>";
    print "<br>";

    ?>

<!-- Report Content Ends Here -->

<?php
// End the output buffer if started
// $html = ob_get_clean();
?>
