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
                  FROM occur
                  WHERE occur_id = {$_GET['id']}
                 ";
        $result = mysqli_query($conn, $query);

        //$occur_id = $_GET['id'];

        if (!$result) 
        {
          print'<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
          print'<p> The query being run was:  ' . $query . '</p>';
        }
        else
        {
          $row = mysqli_fetch_array($result);
        }
      
	// Assign row values to variables dynamically
        foreach ($row as $column => $value) {
            $$column = $value;
        }
    	} else {
        	echo '<p>No record found for the given ID.</p>';
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

                                    

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->





                            <div class="card">
                                    <div class="card-body">
                                            


<!-- PHP / SQL QUERY FOR "NEW REPORTS"    ========================================================================================= -->


<?php
	print"<h2>OCCURRENCE REPORT SUMMARY</h2>";
	print"<h7><strong>Occur ID:</h7></strong>" . "  " . "$occur_id" . "&nbsp&nbsp&nbsp&nbsp" . "<strong>Submitted:&nbsp</strong>" . $occur_date . "&nbsp&nbsp&nbsp&nbsp" . "<br>";
	print"<strong>Reporter: </strong>" . $reporter_first_name . " " . $reporter_last_name . " (" . /*$reporter_job . ",&nbsp" .*/ $reporter_dept . ")" . "&nbsp&nbsp&nbsp&nbsp&nbsp" . 
	"<strong>Email: </strong>" . $reporter_email . "<br>";
	print"<strong>Phone: &nbsp</strong>" . $reporter_phone . "<strong>&nbsp&nbsp&nbspCell:&nbsp </strong>" . $reporter_cell; 
	

	print"<hr>";
	
	print "<strong>Patient:</strong>  " . $patient_first_name . "&nbsp" . $patient_last_name . ", " . $patient_age . " year old " . $patient_gender . 
	"&nbsp&nbsp&nbsp&nbsp" . "<strong>MRN:</strong>" . " " . $patient_MRN . "<br>";
	print "<strong>Level of Care: </strong>" . $patient_loc . "<br>";
	print "<strong>Patient Unit / Program:  </strong>" . " " . "$patient_unit" . " / " . "$patient_program" . "<br>";
	print "<strong>Preliminary Severity: </strong>" . $reporter_severity . "&nbsp&nbsp&nbsp&nbsp" . "<strong>RM Severity: &nbsp</strong>" . $rm_severity . "<br>";
	print "<strong>Attending:</strong>" . "&nbsp" . $md_attending . "<br>";
	
	print "<hr>";

	print "<strong>Type:&nbsp</strong>" . $occur_type . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . "<strong>Category:</strong>" . "&nbsp" . $reporter_category . "&nbsp&nbsp&nbsp&nbsp" . "<strong>Subcategory:&nbsp</strong>  $occur_subcategory  <br>";  
	print "<strong>Date / Time of Occurrence:</strong>" . "&nbsp" . $occur_date . "&nbsp" . $occur_time . "<br>";
	print"<strong>Location: </strong>" . $occur_location . "&nbsp&nbsp&nbsp <strong>Area:&nbsp</strong>" . $occur_area . "<br>";
	print "<br>";
	print"<strong>Description: &nbsp&nbsp </strong>";  
	print"$occur_description ". "<br>";
	//print "<br>";
	print"<strong>Intervention: &nbsp </strong>";  
	print"$occur_intervention". "<br>";
	print "<br>";
	print"<strong>Code Called? &nbsp&nbsp</strong>" . $occur_code . "&nbsp&nbsp&nbsp" . "<strong>Notes:&nbsp&nbsp</strong>" . $code_notes . "<br>";
	//print "<br>";
	print"<strong>PRN Meds Given? &nbsp&nbsp</strong>" . "&nbsp" . $occur_PRN;
	print "<br>";
	print"<strong>Staff Present: &nbsp&nbsp</strong>" . $occur_staff;

	print "<hr>";

	print "<strong>Patient Injury ?:</strong>&nbsp;&nbsp" . $occur_patient_injury . "&nbsp;&nbsp;&nbsp;&nbsp;<strong>Notes:</strong>&nbsp;&nbsp;" . $patient_injury_description . "<br>";
	print "<strong>Employee Injury ?:</strong>&nbsp;&nbsp" . $occur_employee_injury . "&nbsp;&nbsp;&nbsp;&nbsp;<strong>Notes:</strong>&nbsp;&nbsp;" . $employee_injury_description . "<br>";
	print $employee_injury_description;
	print "<strong>Other Injury ?:</strong>&nbsp;&nbsp" . $injury_other . "&nbsp;&nbsp;&nbsp;&nbsp;<strong>Notes:</strong>&nbsp;&nbsp;" . $injury_other_notes . "<br>";

	print"<hr>";

	print"<strong>Restraint ?:&nbsp&nbsp</strong>" . $occur_patient_restraint . "&nbsp&nbsp&nbsp&nbsp" . "<strong>Minutes:&nbsp&nbsp</strong>" . $restraint_minutes . "&nbsp&nbsp&nbsp&nbsp" . "<strong>Note:&nbsp&nbsp</strong>" . $patient_restraint_notes ."<br>";
	//print "<br>";
	
	print"<strong>Seclusion ?:&nbsp&nbsp</strong>" . $occur_patient_seclusion . "&nbsp&nbsp&nbsp&nbsp" . "<strong>Minutes:&nbsp&nbsp</strong>" . $seclusion_minutes . "&nbsp&nbsp&nbsp&nbsp" . "<strong>Note:&nbsp&nbsp</strong>" ."$patient_seclusion_notes". "<br>";
	print "<br>";

	print"<strong>Restraint/Seclusion Documentation Completed? :&nbsp&nbsp</strong>" . $rs_documentation . "<br>"; 
	print"<strong>Required Notifications Made? :&nbsp&nbsp</strong>" . $rs_notification . "&nbsp&nbsp&nbsp&nbsp&nbsp" . "<strong>Notes:&nbsp&nbsp</strong>" . $rs_notification_notes . "<br>";  
	
	print"<strong>Additional Notes:&nbsp&nbsp</strong>" . $rs_additional_notes . "<br>";  
	//print "<br>";

	print"<hr>";

     print"<strong>Notification Notes:&nbsp&nbsp</strong>" . $occur_notification_notes . "<br>";  
    //print "<br>";

    print"<strong>Additional Notes:&nbsp&nbsp</strong>" . $occur_additional_notes . "<br>";  
    //print "<br>";

?>









<!-- ========================================  CLOSING TAGS / FOOTER / RIGHT SIDEBAR  =========================================== -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
<?php include ("includes/occur_footer.php"); ?>
            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->
<?php include ("includes/right_sidebar.php"); ?>
<?php include ("includes/footer_scripts.php"); ?>


<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->


   <!-- Datatables:  Required datatable js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>


    <!-- DataTables: Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        
        <script src="assets/libs/datatables.net-keyTable/js/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        
    <!-- Datatables:  Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatables:  Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

        <script src="assets/js/app.js"></script>

    <!-- Datatables JS -->
        <script> $(document).ready( function () {
                 $('#myTable').DataTable();
                 } );
        </script>






<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>


