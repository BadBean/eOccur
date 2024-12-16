<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");


// ===== SET USER NAME VARIABLES FROM SESSION / LOGIN (SEPARATE INTO INCLUDE FILE LATER?) =========================================================== -->

// Query to select user with the specified email
$sql = "SELECT *
        FROM users
        WHERE user_email = '$email'";

// Execute the query
$result_user = mysqli_query($conn, $sql);
if (!$result_user) { 
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
}

// Fetch the result
$row_user = mysqli_fetch_array($result_user);

// Construct the full name with a non-breaking space
$user_full_name = $row_user['user_first_name'] . " " . $row_user['user_last_name']; // Use regular space, not &nbsp
$user_full_name_title = $row_user['user_first_name'] . " " . $row_user['user_last_name'] . ", " . $row_user['user_title']; // Use regular space, not &nbsp


?>            


<!-- ================================================  HANDLE NULL VALUES FOR DATES  ============================================= -->

<?php
    // List of date fields
    $dateFields = [
        'occur_date',
        'manager_review_date',
        'rm_followup_date',
        'rm_review_date',
        'occur_close_date',
        'patient_dob',
        'admit_date',
        'target_date',
        'complete_date'
    ];

    // Initialize variables
    foreach ($dateFields as $field) {
        if (!empty($row[$field]) && $row[$field] !== '0000-00-00') {
            $$field = date('Y-m-d', strtotime($row[$field]));
        } else {
            $$field = ''; // Set to empty string if NULL or invalid
        }
    }
?>

<!-- =======================================  PAGE SPECIFIC ASSETS ========================================================== -->

 <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
        <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />

 <!-- quill css -->
        <link href="assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/@chenfengyuan/datepicker/datepicker.min.css">

        <!-- flatpickr css -->
        <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

        <!-- File Upload Plugins css -->
        <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

<!-- manually change background color of card  -->
        <style>
          .gainsboro-card {
            background-color: #DCDCDC; /* Gainsboro color */
          }
        </style>


<!-- ================================================  PHP / SQL QUERY  ============================================= -->


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

    //  Explode checkbox arrays to populate existing values
        //$occur_flag = explode(',', $row['occur_flag']);

?>



<!-- =============================================================================================================================== -->

    <!-- Start right Content here -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- ========================================   PAGE TITLE  ======================================================================== -->

                    <!-- start page title -->

                        <!-- Page Title / Top Div with 'no-print' class-->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0 no-print">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">eOccur</h4>
                                </div>
                            </div>
                        </div> <!-- end row -->

                           <!-- Rest of the content -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" id="buttons">
                                    <div class="card-body">
                                        <!-- Wrap the PHP messages in a 'no-print' div -->
                                        <div class="no-print">


                                    <h2 class="mb-sm-0 font-size-16 fw-bold">MANAGEMENT REVIEW</h2>

                                    <!--
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Samply</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>
                                    -->

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<!-- ========================================  BOOTSTRAP ROW:   ==================================================================== -->
<!-- NOTE ******  FOR EACH FIELD ADDED, ADD TO DATABASE, VALUES FILE, INSERT STATEMENT, EDIT FILE, AND UPDATE STATEMENT.  ALSO TO FILTER STATEMENT  -->

                        <div class="row">
                            <!-- First card with a gray background -->
                            <div class="col-12">
                                <div class="card card gainsboro-card  ">
                                    <div class="card-body">
                                        <h4>OCCURRENCE REPORT SUMMARY</h4>
                                         <?php include("pdf_report_detail.php"); ?>
                                    </div> <!-- card body -->
                                </div> <!-- card -->
                            </div> <!-- col -->
                        </div> <!-- close row -->



                       

<!-- FORM: OPENING TAG AND FILE DESTINATION -------------------------------------- -->

                        <form action="update_mgr.php" class="form-floating mb-3" role="form" method="post" enctype=
                            "multipart/form-data">

                    


<!-- FORM:  TIMESTAMP FOR DATE SUBMITTED BY REPORTER   -------------------------------------- -->

                            <!-- Timestamp function is in file with the insert statement
                            <input type="hidden" name="reporting_timestamp" value="<//?php echo time(); ?>">
                            -->                            



<!-- FORM:  MANAGEMENT FOLLOW UP  -------------------------------------- -->

                   <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Management Follow Up</h4>
                                            <p class="card-title-desc">To Notify Manager of initial assignment, set status to "Pending Manager Review"</p>
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="floatingSelect">Assign Manager for Review</label>

                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="manager_followup_name">
                                                            <option value="" selected>Assign Manager</option>

                                                            <?php
                                                            // $row['manager_followup_name'] contains the current value from query in edit file
                                                            $manager_followup_name = $row['manager_followup_name'];

                                                            // Query to fetch users with role 'Management' or 'Leadership'
                                                            $sql = "SELECT * FROM users WHERE user_role IN ('Management', 'Leadership')";
                                                            $result_mgr = mysqli_query($conn, $sql);

                                                            if (!$result_mgr) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_mgr) > 0) {
                                                                // Output data of each row
                                                                while ($row_mgr = mysqli_fetch_assoc($result_mgr)) {
                                                                    // Construct the full name
                                                                    $full_name = htmlspecialchars($row_mgr['user_first_name'] . ' ' . $row_mgr['user_last_name']);

                                                                    // Construct the full name with title for display
                                                                    $full_name_title = $full_name . ', ' . htmlspecialchars($row_mgr['user_title']);
                                                                    
                                                                    // Check if the current option should be selected
                                                                    $selected = $full_name == $manager_followup_name ? ' selected="selected"' : '';
                                                                    
                                                                    // Echo the option tag with the full name (value) and full name with title (display text)
                                                                    echo '<option value="' . $full_name . '"' . $selected . '>' . $full_name_title . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>        
                                                </div> <!-- end column -->

                                               <div class="col-lg-6">
                                                    <label for="floatingTextarea">
                                                        Manager Follow Up Status <span style="color: red; font-size: smaller;"></span>
                                                    </label>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="manager_status">
                                                            <option value="" selected>Status</option>
                                                            
                                                            <?php
                                                            // $row['manager_status'] contains the current value from query in edit file
                                                            $manager_status = $row['manager_status'];

                                                            // If manager_status is empty or not set, default to "Pending Manager Review"
                                                            if (empty($manager_status)) {
                                                                $manager_status = "Pending Manager Review";
                                                            }

                                                            // Query to fetch active manager statuses
                                                            $sql = "SELECT * FROM occur_setup_mgr_status";
                                                            $result_mgr_status = mysqli_query($conn, $sql);

                                                            if (!$result_mgr_status) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_mgr_status) > 0) {
                                                                while ($row_mgr_status = mysqli_fetch_assoc($result_mgr_status)) {
                                                                    // Use the correct column name: mgr_status_name
                                                                    $selected = $row_mgr_status['mgr_status_name'] == $manager_status ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_mgr_status["mgr_status_name"]) . '"' . $selected . '>' . htmlspecialchars($row_mgr_status["mgr_status_name"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> <!-- end column -->


                                                <br>

                                                </div> <!-- close row -->
                                            <br>

                                              <div class="row">
                                                
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Initial Review Date</label>
                                                        <input class="form-control" type="date" value="<?php echo $row['manager_review_date']; ?>" id="example-date-input" name="manager_review_date">
                                                    </div>
                                                </div>
                                                
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <label for="floatingTextarea">Action Assigned</label>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Action Assigned"
                                                            id="floatingTextarea" name="manager_action"><?php echo $row['manager_action']; ?></textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <label for="floatingTextarea">Communication / Notes</label>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Communication / Notes"
                                                            id="floatingTextarea" name="manager_communication"><?php echo $row['manager_communication']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                            </div> <!-- end row -->
                                            <br>

                                            <div class="row">
                                                
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Target Date</label>
                                                        <input class="form-control" type="date" value="<?php echo $row['target_date']; ?>" id="example-date-input" name="target_date">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Completion Date</label>
                                                        <input class="form-control" type="date" value="<?php echo $row['complete_date']; ?>" id="example-date-input" name="complete_date">
                                                    </div>
                                                </div>

                                            </div> <!-- close row -->



                                            <div class="row">
                                                
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <label for="floatingTextarea">Follow Up / Action Plan</label>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Action items"
                                                            id="floatingTextarea" name="manager_followup_plan"><?php echo $row['manager_followup_plan']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <label for="floatingTextarea">Implementation / Completion Notes</label>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Document actions completed"
                                                            id="floatingTextarea" name="manager_followup_notes"><?php echo $row['manager_followup_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->                                         
                                            
                                        </div>
                                    </div> <!-- close card -->
                                </div> 
                            </div> <!-- close row -->
                                            <br>



                            <input name="id" type="hidden" value="<?php echo $row['occur_id']; ?>">

<!-- ========================================  BOOTSTRAP ROW    ================================================================ -->

<!-- FORM: SUBMIT BUTTON AND CLOSING TAG -------------------------------------- -->

                            <p>
                                <br>
                                <div style="margin: 0 auto; text-align: center;">

                                    <input type="submit" name="submit" class="btn btn-warning" value="Update Record">  <br>          
                                </div>
                                <br>
                            </p>
                        </form>

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


<!-- ========================================  PAGE SPECIFIC ASSETS   ======================================================== -->

    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>

    <!--Quill js-->
    <script src="assets/libs/quill/quill.min.js"></script>

    <!--Flatpickr js-->
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

    <!-- Plugins js -->
    <script src="assets/libs/dropzone/min/dropzone.min.js"></script>


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ================================================ -->
    </body>
</html>