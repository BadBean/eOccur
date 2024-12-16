<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->


<?php

// to get the variable passed from bar chart click
if (isset($_GET['reporter_category'])) {
    $reporter_category = $_GET['reporter_category'];
   
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

                        <br>

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  =================================================================== -->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="mb-sm-0 fw-bold">CATEGORY DETAIL</h5>
                                        <br>
                                        <h4> <?php echo $reporter_category; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->



<!-- PHP / SQL QUERY FOR "REPORTS BY PROGRAM"    ========================================================================================= -->

<br>
<?php 
//Query to count # of reports submitted but not reviewed by RM
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE occur_status = 'Submitted';
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

<!-- ========================================  BOOTSTRAP ROW: PROGRAM DETAIL   =================================================================== -->

                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="submitted_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-folder-star text-primary"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">PROGRAM DETAIL</p>
                                            <a href="submitted_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>2.65%</span> Pending RM review
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->

<!-- PHP / SQL QUERY FOR "HIGH SEVERITY"    ========================================================================================= -->


<?php 
//Query to count # of reports submitted but not reviewed by RM
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE rm_severity IN ('Severe', 'Sentinel');
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="severity_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-alert-circle text-danger"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">LOCATION DETAIL</p>
                                             <a href="severity_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->

<!-- PHP / SQL QUERY FOR "MTD TOTALS"    ========================================================================================= -->


<?php 
//Query to count # of reports submitted but not reviewed by RM
  $sql = "SELECT COUNT(*) AS item_count
        FROM occur
        WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
          AND occur_date <= LAST_DAY(CURDATE())";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("<p>Error in query: " . mysqli_error($conn) . "</p>");
}

$row = mysqli_fetch_assoc($result);
$numrows_submitted = $row['item_count'];
?>

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="mtd_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">AREA DETAIL</p>
                                             <a href="mtd_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->



<!-- PHP / SQL QUERY FOR "OPEN REPORTS"    ========================================================================================= -->


<?php 
//Query to count # of reports submitted but not reviewed by RM
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE occur_status != 'Closed';
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="open_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation-outline text-warning"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">SEVERITY</p>
                                            <a href="open_detail.php">
                                            <h4 class="mb-1 mt-1">
                                                <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                            </h4>
                                        </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-warning me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>0.55%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
<br>




<!-- PHP / SQL QUERY TO PULL CATEGORY DETAIL    ========================================================================================= -->

<?php 

// Generate a list of the last 12 months
$months = [];
for ($i = 11; $i >= 0; $i--) {
    $months[] = date('Y-m', strtotime("-$i months"));
}

// Query to count # of reports submitted but not reviewed by RM
$sql_count = "
    SELECT 
        DATE_FORMAT(occur_date, '%Y-%m') AS month_year, 
        COUNT(*) AS item_count
    FROM 
        occur
    WHERE 
        reporter_category = '$reporter_category' AND
        occur_date >= DATE_FORMAT(CURDATE() - INTERVAL 12 MONTH, '%Y-%m-01')
    GROUP BY 
        DATE_FORMAT(occur_date, '%Y-%m')
    ORDER BY 
        DATE_FORMAT(occur_date, '%Y-%m')
";

$result_count = mysqli_query($conn, $sql_count);

if (!$result_count) {
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
}

// Initialize data array with zeros for all months
$data = array_fill_keys($months, 0);

// Fill in the data array with actual counts from the query
while ($row_count = mysqli_fetch_assoc($result_count)) {
    $data[$row_count['month_year']] = $row_count['item_count'];
}

// Prepare data for ApexCharts
$months_js = json_encode(array_keys($data));
$counts_js = json_encode(array_values($data));

?>

<!-- =============================================== BOOTSTRAP ROW: CHART RENDER / SCRIPT ========================= -->

                        <div class="row">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $reporter_category; ?></h4>
                                        <p class="card-title-desc"></p>

                                        <div id="chart"></div>

                                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                        <script>
                                            var options = {
                                                chart: {
                                                    type: 'bar',
                                                    width: '100%',  
                                                    height: '400px' 
                                                },
                                                series: [{
                                                    name: 'Reports',
                                                    data: <?php echo $counts_js; ?>
                                                }],
                                                xaxis: {
                                                    categories: <?php echo $months_js; ?>,
                                                    title: {
                                                        text: 'Month'
                                                    }
                                                },
                                                yaxis: {
                                                    title: {
                                                        text: 'Count'
                                                    }
                                                },
                                                title: {
                                                    text: 'Reports Submitted by Month',
                                                    align: 'center'
                                                }
                                            };

                                            var chart = new ApexCharts(document.querySelector("#chart"), options);
                                            chart.render();
                                        </script>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end column -->
                        </div> <!-- end row -->


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<!-- SQL QUERY FOR TABLE =================================================================================================================== -->

<?php 
   $sql =  "SELECT *
             FROM occur
             WHERE reporter_category = '$reporter_category'
            ";

     $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysql_error() . "</p>"); }
    
    $numrows = mysqli_num_rows($result);
?>



                           
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Reports Submitted:&nbsp&nbsp<?php echo $reporter_category ?></h4>
                                        <p class="card-title-desc"></p>

                                        <p class="card-title-desc"></p>
                                        <br>
                                        
                                        <!-- Date filter inputs -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="startDate">Start Date:</label>
                                                <input type="date" id="startDate" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="endDate">End Date:</label>
                                                <input type="date" id="endDate" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        <br>


<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                     <table id="myTable" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>ID</th>
                                                    <!--<th>Time</th> -->
                                                    <th>Type</th>
                                                    <th>Pt LName</th>
                                                    <th>MRN</th>
                                                    <th>Age</th>
                                                    <th>M/F</th>
                                                    <th>Unit</th>
                                                    <!-- <th>Location</th> -->
                                                    <!-- <th>Program</th> -->
                                                    <th>Category</th>
                                                    <th>Severity</th>
                                                    <th>Status</th>
                                                    <!-- <th>Attending</th> -->
                                                    <th>Restraint</th>
                                                    <th>Seclusion</th>
                                                    <th>Actions</th>


                                                    <th>Description</th>
                                                    <th>Intervention</th>       
                                                </tr>
                                            </thead>        

<!--  PHP FOR TABLE OUTPUT  ====================== -->
                                               
                                            <tbody>
                                            
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                            echo "<tr>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td><a href='pdf_report.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            //echo "<td>{$row['occur_time']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_type']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_last_name']}</td>";
                                                            echo "<td>{$row['patient_MRN']}</td>";
                                                            echo "<td>{$row['patient_age']}</td>";
                                                            echo "<td>{$row['patient_gender']}</td>";
                                                            echo "<td>{$row['patient_unit']}</td>";
                                                            //echo "<td style='white-space:nowrap'>{$row['occur_location']}</td>";
                                                            //echo "<td style='white-space:nowrap'>{$row['patient_program']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['rm_severity']}</td>";
                                                            echo "<td>{$row['rm_status']}</td>";
                                                            //echo "<td>{$row['md_attending']}</td>";
                                                            echo "<td>{$row['occur_patient_restraint']}</td>";
                                                            echo "<td>{$row['occur_patient_seclusion']}</td>";


                                                            echo "<td>
                                                                <div class=\"dropdown\">
                                                                    <a class=\"text-muted dropdown-toggle font-size-16\" role=\"button\"
                                                                        data-bs-toggle=\"dropdown\" aria-haspopup=\"true\">
                                                                        <i class=\"mdi mdi-dots-vertical\"></i>
                                                                    </a>
                                                                    <div class=\"dropdown-menu dropdown-menu-end\">
                                                                        <a class=\"dropdown-item\" href=\"occur_pdf.php?id={$row[0]}\">View/Print</a>
                                                                        <a class=\"dropdown-item\" href=\"edit_occur.php?id={$row[0]}\">Edit Report</a>
                                                                        <a class=\"dropdown-item\" href=\"rm_review.php?id={$row[0]}\">Mgmt Review</a>
                                                                        <a class=\"dropdown-item\" href=\"delete_occur.php?id={$row[0]}\">Delete</a>
                                                                    </div>
                                                                </div>
                                                            </td>";

                                                            echo "<td style='white-space:nowrap'>{$row['occur_description']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_intervention']}</td>";

                                                        echo "</tr>";
                                                    }
                                                ?>
<!--  END PHP  ====================== -->
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->


         
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

    <!-- Datatables JS / Original script 
        <script> $(document).ready( function () {
                 $('#myTable').DataTable();
                 } );
        </script>
    -->

    <!-- Datatables JS / Add Order to change default by ID field -->
    <!-- Custom Date Filter Script -->
<script>
    $(document).ready(function() {
        // Initialize DataTable and store the instance in a variable
        var table = $('#myTable').DataTable({
            "order": [[ 0, "desc" ]] // Order by the first column (DATE) in descending order
        });
        
        // Custom filtering function which will search data in the DATE column
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                // Get the start and end dates from the input fields
                var min = $('#startDate').val();
                var max = $('#endDate').val();
                var dateStr = data[0]; // Assuming the DATE column is the first column (index 0)
                
                //If you set to true then rows with blank dates can be part of results        
                if (!dateStr) {
                    return false; // If there's no date, do not include
                }

                // Parse the date from "m/d/y" to a JavaScript Date object
                var dateParts = dateStr.split('/');
                var month = parseInt(dateParts[0], 10);
                var day = parseInt(dateParts[1], 10);
                var year = parseInt(dateParts[2], 10);
                year += (year < 100) ? 2000 : 0; // Adjust for 2-digit year if necessary
                var rowDate = new Date(year, month - 1, day);
                
                // Parse the min and max dates from the input fields
                var minDate = min ? new Date(min) : null;
                var maxDate = max ? new Date(max) : null;
                
                // Compare the row's date with the min and max dates
                if (
                    (!minDate || rowDate >= minDate) &&
                    (!maxDate || rowDate <= maxDate)
                ) {
                    return true;
                }
                return false;
            }
        );
        
        // Event listener for the start and end date inputs to redraw the table on change
        $('#startDate, #endDate').on('change', function() {
            table.draw();
        });
    });
</script>






<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




