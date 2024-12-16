<?php

include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

?>

<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->






<!-- ================================================  SQL QUERY  ============================================= -->


<?php
    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0))
      {
        $query = "SELECT *
                  FROM users
                  WHERE user_id = {$_GET['id']}
                 ";
        $result = mysqli_query($conn, $query);

        //$user_id = $_GET['id'];

        if (!$result) 
        {
          print'<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
          print'<p> The query being run was:  ' . $query . '</p>';
        }
        else
        {
          $row = mysqli_fetch_array($result);
        }
      }
?>


<!-- ============================================  PAGE FORMATTING  ================================================================= -->

<!-- Start right Content here -->

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

<!-- ========================================   PAGE TITLE  ======================================================================== -->

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Samply</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<!-- PHP / SQL QUERY FOR "NEW REPORTS"    ========================================================================================= -->


<?php
	print"<h2>OCCURRENCE REPORT SUMMARY</h2>";
	
	print "Patient:  " . $pt_last_name . ", " . $pt_age . " year old " . $gender . "<br>";
	print "Attending MD: " . $pt_md . "<br>";
	print"Incident Type: " . $incident_type . " Location: " . $incident_location . "<br>";
	print"Incident Type Detail: " . $type_tx_detail . $type_physical_detail . $type_sexual . "<br>";
	print"Injury: " . $injury_detail . "<br>";
	print"Restraint: " . $restraint . "  Seclusion: " . $seclusion . "  Emergency Meds: " . $sr_medication . "<br>";
	print"Interventions: " . $tx_intervention . "<br>";
	print"Description of Occurrence: " . $description . "<br>";

?>



