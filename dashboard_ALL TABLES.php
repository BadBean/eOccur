<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

// Clear any existing filters
unset($_SESSION['filters']);
?>



<!-- ============================================ USER NAME / EMAIL  ================================================================= -->

<?php

//$email variable from the session is set in the included file "auth.php"

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

<!-- ============================================  PAGE SPECIFIC CUSTOM CSS  ================================================================= -->



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
                                    <?php // echo $user_full_name_title; ?>
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

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  =================================================================== -->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                               <div class="card" style="background-color: #DCDCDC;">
                                    <div class="card-body text-center text-black">
                                        <h5 class="mb-sm-0 fw-bold">MANAGER DASHBOARD</h5>
                                        <!-- Add the first day to create a valid date -->
                                        <h4></h4>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

<!-- ========================================  BOOTSTRAP ROW: 4 CARDS    =================================================================== -->



<!-- PHP / SQL QUERY FOR "NEW REPORTS"    ================ -->

<br>
<?php 
//Query to count # of reports pending for manager logged in
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE manager_status = 'Pending Manager Review'
             AND manager_followup_name = '$user_full_name'
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">New Reports For Review</p>
                                            <a href="submitted_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>2.65%</span> Pending Manager review
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->

                            

<!-- PHP / SQL QUERY FOR "FOLLOW UP REQUIRED"    ================ -->


<?php 
//Query to count # of reports submitted but not reviewed by RM
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE manager_followup_name = '$user_full_name';
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
                                            <a href="mgr_followup_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-alert-circle text-danger"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">Follow Up Assigned</p>
                                             <a href="mgr_followup_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class=""></i>2 Reports</span>  Past Target Date
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->


<!-- PHP / SQL QUERY FOR "MTD TOTALS"    ================ -->


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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">MTD Reports</p>
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



<!-- PHP / SQL QUERY FOR "OPEN REPORTS"    ================ -->


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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">Open Reports</p>
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



<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

<!-- PHP / SQL QUERY FOR TABLE  ================ -->

<?php 


// Query to select reports where occur_status is not 'Closed', user's full name is in occur_notify, 
// and occur_date is within the last 30 days
$sql = "SELECT *
        FROM occur
        WHERE occur_status <> 'Closed'
        AND occur_notify LIKE '%" . mysqli_real_escape_string($conn, $user_full_name) . "%'
        AND occur_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ORDER BY occur_id ASC
       ";

// Execute the query
$result = mysqli_query($conn, $sql);
if (!$result) { 
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
}

// Fetch number of rows
$numrows = mysqli_num_rows($result);

?>

<!-- ========================================  BOOTSTRAP ROW: COLUMN CHART   =================================================================== -->


<!-- PHP / SQL QUERY TO PULL DETAIL BY MONTH ----- -->

<?php 
// Generate a list of the last 12 months with proper sorting - FIXED VERSION
$months_report_count = [];
$current_month = new DateTime();
$current_month->modify('first day of this month');

// Start from 11 months ago
$start_month = clone $current_month;
$start_month->modify('-11 months');

// Generate array of months
while ($start_month <= $current_month) {
    $months_report_count[] = $start_month->format('Y-m');
    $start_month->modify('+1 month');
}

// Modified query to ensure consistent date formatting and ordering
$sql_report_count = "
    SELECT 
        DATE_FORMAT(occur_date, '%Y-%m') AS month_year,
        occur_type,
        COUNT(*) AS item_report_count
    FROM 
        occur
    WHERE 
        occur_date >= DATE_FORMAT(CURDATE() - INTERVAL 12 MONTH, '%Y-%m-01')
    GROUP BY 
        DATE_FORMAT(occur_date, '%Y-%m'), occur_type
    ORDER BY 
        month_year ASC
";

// Execute the query
$result_report_count = mysqli_query($conn, $sql_report_count);
if (!$result_report_count) {
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
}

// Initialize data array with zeros for all months
$data_report_count = array_fill_keys($months_report_count, [
    'ALL' => 0,
    'Patient Care' => 0,
    'Safety' => 0,
    'Other' => 0
]);

// Fill in the data array with actual counts from the query
while ($row = mysqli_fetch_assoc($result_report_count)) {
    $month = $row['month_year'];
    $type = $row['occur_type'];
    $count = (int)$row['item_report_count'];
    
    // Map database values to categories
    $category = 'Other';
    if ($type == 'Patient Care' || $type == 'PT CARE') {
        $category = 'Patient Care';
    } elseif ($type == 'Safety' || $type == 'SAFETY') {
        $category = 'Safety';
    }
    
    if (isset($data_report_count[$month])) {
        $data_report_count[$month][$category] += $count;
        $data_report_count[$month]['ALL'] += $count;
    }
}

// Prepare chart data while maintaining month order
$chart_data_report_count = [
    'ALL' => [],
    'Patient Care' => [],
    'Safety' => [],
    'Other' => []
];

foreach ($months_report_count as $month) {
    foreach (['ALL', 'Patient Care', 'Safety', 'Other'] as $type) {
        $chart_data_report_count[$type][] = $data_report_count[$month][$type];
    }
}

$months_js_report_count = json_encode($months_report_count);
$chart_data_js_report_count = json_encode($chart_data_report_count);

// Optional: Debug output to verify correct month generation
/*
echo "<pre>";
print_r($months_report_count);
print_r($data_report_count);
echo "</pre>";
*/
?>

<!-- BOOTSTRAP ROW:  TOTAL REPORTS BY MONTH    ========================================================================================= -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h5 class="card-title me-2">REPORTS BY MONTH</h5>
                                <div class="ms-auto">
                                    <div>
                                        <!-- Updated buttons with data-filter-report-count attributes -->
                                        <button type="button" class="btn btn-primary btn-sm" data-filter-report-count="ALL">ALL</button>
                                        <button type="button" class="btn btn-light btn-sm" data-filter-report-count="Patient Care">PT CARE</button>
                                        <button type="button" class="btn btn-light btn-sm" data-filter-report-count="Safety">SAFETY</button>
                                        <button type="button" class="btn btn-light btn-sm" data-filter-report-count="Other">OTHER</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Chart Container -->
                                            <div id="chart_report_count" class="apex-charts" dir="ltr"></div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                        </div> <!-- end card-body container -->
                    </div> <!-- end card container -->
                </div> <!-- end column container -->

<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Recent Reports / Notifications for <?php echo $user_full_name; ?></h4>
                                        <p class="card-title-desc">Last 30 days</p>
        
                                          <table id="myTable" class="table table-bordered dt-responsive table-hover table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>ID</th>
                                                    <!--<th>Time</th> -->
                                                    <th>Type</th>
                                                    <th>Pt LName</th>
                                                    <th>MRN</th>
                                                    <th>Age</th>
                                                    <th>M/F</th>
                                                    <th>Unit</th>
                                                    <th>Location</th>
                                                    <th>Program</th>
                                                    <th>Category</th>
                                                    <th>Severity</th>
                                                    <th>Attending</th> 
                                                    <th>Restraint</th>
                                                    <th>Seclusion</th>


                                                    <th>Notes</th>
                                                    <th>Intervention</th>       
                                                </tr>
                                            </thead>        

<!--  PHP FOR TABLE OUTPUT  ====================== -->
                                               
                                            <tbody>
                                            
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);
                                                        //Have table display date only without time
                                                        $formatted_date = date("m-d-Y", strtotime($row['occur_date']));

                                                        echo "<tr>";
                                                            echo "<td style='white-space:nowrap'>{$formatted_date}</td>";
                                                             echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            
                                                            //echo "<td>{$row['occur_time']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_type']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_last_name']}</td>";
                                                            echo "<td>{$row['patient_MRN']}</td>";
                                                            echo "<td>{$row['patient_age']}</td>";
                                                            echo "<td>{$row['patient_gender']}</td>";
                                                            echo "<td>{$row['patient_unit']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_location']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_program']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['reporter_severity']}</td>";
                                                            echo "<td>{$row['md_attending']}</td>";
                                                            echo "<td>{$row['occur_patient_restraint']}</td>";
                                                            echo "<td>{$row['occur_patient_seclusion']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_description']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_intervention']}</td>";

                                                        echo "</tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->


<!-- QUERY TO PUT SAME RESULTS INTO A TABLE  ====================== -->























<!-- PHP / SQL QUERY FOR CATEGORY CHART    ========================================================================================= -->

            <?php 
            //Query to count # of reports by category

                $sql =  "SELECT reporter_category, COUNT(*) as count
                         FROM occur
                         GROUP BY reporter_category
                        ";

                $result = mysqli_query($conn, $sql);
                         if (!$result) 
                         { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

                $data = [];

            // Fetch data and store in an array
                        if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                }
            }    
            ?>

            <!-- Pass data to JavaScript so Apex can use it.  This converts PHP array into a JS variable that can be used to create the chart   -->
            <script> var chartData = <?php echo json_encode($data); ?>; </script>




<!-- BOOTSTRAP ROW:  COLUMN 1:  CATEGORY CHART    ========================================================================================= -->


            <div class="row">
                <div class="col-md-7 col-xl-7">
                    <div class="card">
                        <div class="card-body">
                            <!-- Chart Container -->
                            <div id="chart_category" dir="ltr" ></div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->

                
<!-- PHP / SQL QUERY FOR PROGRAM CHART    ========================================================================================= -->

 <?php 
// Query to count # of reports by program
$sql =  "SELECT patient_program, COUNT(*) as count
         FROM occur
         GROUP BY patient_program";

$result_program = mysqli_query($conn, $sql);
if (!$result_program) { 
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
}

$counts = [];
$labels = [];

// Fetch data and store in separate arrays for counts and labels
if (mysqli_num_rows($result_program) > 0) {
    while($row_program = mysqli_fetch_assoc($result_program)) {
        $counts[] = $row_program['count'];
        $labels[] = $row_program['patient_program'];
    }
}
?>

<!-- Pass data to JavaScript -->
<script> 
     // Passing data from PHP to JavaScript
    var counts = <?php echo json_encode($counts); ?>; 
    var labels = <?php echo json_encode($labels); ?>; 

    // Debugging: Log the contents of counts and labels to the console
    console.log("Counts Array (Before Conversion):", counts);  // This will show the counts as strings

    // Convert counts to an array of numbers
    counts = counts.map(function(count) {
        return parseInt(count, 10);  // Convert each count to an integer
    });

    console.log("Counts Array (After Conversion):", counts);  // This should show the counts as numbers

</script>



<!-- BOOTSTRAP ROW:  COLUMN 2:  PROGRAM CHART    ========================================================================================= -->

                <div class="col-md-5 col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <!-- Chart Container -->
                            <div id="chart_program" class="apex-charts" dir="ltr" ></div>

    <style>
        .apexcharts-legend {
            top: 100px !important; /* Adjust this value to move the legend down */
        }
    </style>


            <script>
                var options_program = {
                    series: counts,  // The counts array holds the data for the donut chart
                    chart: {
                        type: 'donut',
                        width: '150%',  
                        height: '400px', 
                    },
                    labels: labels,  // The labels array holds the categories/program names
                    title: {
                        text: 'Number of Reports by Program',
                        align: 'top',
                        margin: 30 // Adjust this value to increase or decrease the space below the title
                    },
                   
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 300
                            },
                            legend: {
                            position: 'bottom', // Position the legend at the bottom
                            
                            },
                        }
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#chart_program"), options_program);
                chart.render();

            </script>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            <div> <!-- end row -->

            <br>
            <br>


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<?php 
//Query for reports assigned to user and not closed
  $sql = "SELECT *
          FROM occur
          WHERE occur_status <> 'Closed'
          AND manager_followup_name = '$user_full_name'
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
                                        <h4 class="card-title">Open Reports Requiring Followup</h4>
                                        <p class="card-title-desc">Assigned to: <?php echo " " . $user_full_name_title; ?></p>

<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table class="myTable table table-bordered table-condensed dt-responsive nowrap w-100">
                                                
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <!-- <th>Description</th> -->
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <th>Description</th>
                                                        <!-- <th>Intervention</th>-->
                                                         <th>Mgr Status</th>

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Target Date</th>
                                                        
                                                        <!-- <th>Print</th> -->
                                                        <!-- <th>Review</th> -->
                                                        <!-- <th>Edit</th> -->
                                                        <!-- <th>Delete</th> -->
                                                    </tr>
                                                </thead>        

                                            <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            //echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            // echo "<td>{$row['patient_last_name']}</td>";
                                                            // echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            echo "<td>{$row['occur_description']}</td>";
                                                            //echo "<td>{$row['occur_intervention']}</td>";
                                                            echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                           echo "<td>";
                                                                if (!empty($row['target_date'])) {
                                                                    echo date("m/d/y", strtotime($row['target_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            
                                                            //echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            //echo "<td><a href=\"mgr_review.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            //echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            //echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
                                                            //echo "<td>{$row['reporter_severity']}</td>";

                                                            //fas fa-pencil-alt
                                                            
                                                        echo "</tr>";
                                                    }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <br>
                        <br>


<!-- ================================================ ACTION PLANS IN PROGRESS  =================== -->

<?php 
//Query to count # of reports assigned to user and not closed
  $sql = "SELECT *
          FROM occur
          WHERE occur_status <> 'Closed'
          AND manager_followup_name = '$user_full_name'
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
                                        
                                        <div card bg-primary border-primary>
                                        <h4 class="card-title">Action Plans in Progress</h4>
                                        <p class="card-title-desc">Assigned to: <?php echo " " . $user_full_name_title; ?></p>
                                        </div> <!-- close card color -->
<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table class="myTable table table-bordered table-condensed dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <th>Action Plan</th>
                                                        <th>Notes</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <!-- <th>Description</th> -->
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <!--<th>Description</th> -->
                                                        <!-- <th>Intervention</th>-->
                                                        <!-- <th>Mgr Status</th> -->

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Target Date</th>
                                                        
                                                        <!-- <th>Print</th> -->
                                                        <!-- <th>Review</th> -->
                                                        <!-- <th>Edit</th> -->
                                                        <!-- <th>Delete</th> -->
                                                    </tr>
                                                </thead>        

                                           <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            //echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            // echo "<td>{$row['patient_last_name']}</td>";
                                                            // echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['manager_followup_plan']}</td>";
                                                            echo "<td>{$row['manager_followup_notes']}</td>";                                                            
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            //echo "<td>{$row['occur_intervention']}</td>";
                                                            //echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['target_date'])) {
                                                                    echo date("m/d/y", strtotime($row['target_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            
                                                            //echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            //echo "<td><a href=\"mgr_review.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            //echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            //echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
                                                            //echo "<td>{$row['reporter_severity']}</td>";

                                                            //fas fa-pencil-alt
                                                            
                                                        echo "</tr>";
                                                    }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->



<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>



<!-- ========================================  BOOTSTRAP ROW: TOTAL COUNT TABLES  ========================================================= -->


            <div class="row">
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Category </h4>
                                        <p class="card-title-desc">
                                        </p>


                                <?php
                                // Query to count the number of reports by category
                                $sql_category_table = "SELECT reporter_category, COUNT(*) as category_table_count FROM occur GROUP BY reporter_category";

                                // Execute the query 
                                $result_category_table = mysqli_query($conn, $sql_category_table);

                                // Initialize total count variable
                                $total_category_count = 0;

                                // Check if there are results
                                if ($result_category_table && mysqli_num_rows($result_category_table) > 0) {
                                    // Add Bootstrap table classes
                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                   echo "<thead class='bg-warning text-dark'><tr><th>Category</th><th>Total Reports</th></tr></thead>";
                                    echo "<tbody>";
                                    
                                    // Fetch each row and display in table format
                                    while ($row_category_table = mysqli_fetch_assoc($result_category_table)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row_category_table['reporter_category']) . "</td>";
                                          // Make the count clickable and pass occur_location
                                        echo "<td><a href='detail_category.php?reporter_category=" . urlencode($row_category_table['reporter_category']) . "'>" .htmlspecialchars($row_category_table['category_table_count']) . "</a></td>";
                                       
                                        echo "</tr>";
                                        // Add to total count
                                            $total_category_count += $row_category_table['category_table_count'];
                                        }

                                        // Add total row
                                        echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_category_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Program </h4>
                                        <p class="card-title-desc">
                                        </p>


                              <?php
                                    // Query to count the number of reports by program
                                    $sql_program_table = "SELECT patient_program, COUNT(*) as program_table_count FROM occur GROUP BY patient_program";

                                    // Execute the query 
                                    $result_program_table = mysqli_query($conn, $sql_program_table);

                                    // Check for SQL errors
                                    if (!$result_program_table) {
                                        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql);
                                    }

                                    // Initialize total count variable
                                    $total_program_count = 0;


                                    // Check if there are results
                                    if (mysqli_num_rows($result_program_table) > 0) {
                                        // Add Bootstrap table classes
                                        echo "<table class='table table-striped table-bordered table-sm'>";
                                        echo "<thead class='bg-primary text-light'><tr><th>Category</th><th>Total Reports</th></tr></thead>";
                                        echo "<tbody>";

                                        // Fetch each row and display in table format
                                        while ($row_program_table = mysqli_fetch_assoc($result_program_table)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row_program_table['patient_program']) . "</td>";
                                            // Make the count clickable and pass occur_location
                                            echo "<td><a href='drill_program.php?patient_program=" . urlencode($row_program_table['patient_program']) . "'>" .htmlspecialchars($row_program_table['program_table_count']) . "</a></td>";
                                            echo "</tr>";

                                            // Add to total count
                                            $total_program_count += $row_program_table['program_table_count'];
                                        }

                                        // Add total row
                                         echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_program_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>
                                      

                                    </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->



                
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Location </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by location
                                                $sql_location_table = "SELECT occur_location, COUNT(*) as location_table_count FROM occur GROUP BY occur_location";

                                                // Execute the query 
                                                $result_location_table = mysqli_query($conn, $sql_location_table);

                                                // Check for SQL errors
                                                if (!$result_location_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql);
                                                }

                                                // Initialize total count variable
                                                $total_location_count = 0;

                                                // Check if there are results
                                                if (mysqli_num_rows($result_location_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-success text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_location_table = mysqli_fetch_assoc($result_location_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_location_table['occur_location']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_table.php?occur_location=" . urlencode($row_location_table['occur_location']) . "'>" . htmlspecialchars($row_location_table['location_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_location_count += $row_location_table['location_table_count'];
                                                    }

                                                    // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_location_count) . "</strong></td></tr>";

                                                    echo "</tbody></table>";
                                                } else {
                                                    // No results found
                                                    echo "No data available.";
                                                }

                                                // Free result set and close connection
                                                //mysqli_free_result($result_location_table);
                                                //mysqli_close($conn);
                                            ?>


                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Status </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by location
                                                $sql_status_table = "SELECT occur_status, COUNT(*) as status_table_count FROM occur GROUP BY occur_status";

                                                // Execute the query 
                                                $result_status_table = mysqli_query($conn, $sql_status_table);

                                                // Check for SQL errors
                                                if (!$result_status_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql);
                                                }

                                                // Initialize total count variable
                                                $total_status_count = 0;

                                                // Check if there are results
                                                if (mysqli_num_rows($result_status_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-light text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_status_table = mysqli_fetch_assoc($result_status_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_status_table['occur_status']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_table.php?occur_status=" . urlencode($row_status_table['occur_status']) . "'>" . htmlspecialchars($row_status_table['status_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_status_count += $row_status_table['status_table_count'];
                                                    }

                                                    // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_status_count) . "</strong></td></tr>";

                                                    echo "</tbody></table>";
                                                } else {
                                                    // No results found
                                                    echo "No data available.";
                                                }

                                                // Free result set and close connection
                                                //mysqli_free_result($result_location_table);
                                                //mysqli_close($conn);
                                            ?>


                        </div> <!-- end card-body -->
                    </div> <!-- end card -->


                </div> <!-- end col -->

                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Severity </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_severity_table = "SELECT rm_severity, COUNT(*) as severity_table_count FROM occur GROUP BY rm_severity";

                                                // Execute the query 
                                                $result_severity_table = mysqli_query($conn, $sql_severity_table);

                                                // Check for SQL errors
                                                if (!$result_severity_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_severity_table);
                                                }

                                                // Initialize total count variable
                                                $total_severity_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_severity_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_severity_table = mysqli_fetch_assoc($result_severity_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_severity_table['rm_severity']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_severity.php?rm_severity=" . urlencode($row_severity_table['rm_severity']) . "'>" . htmlspecialchars($row_severity_table['severity_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_severity_count += $row_severity_table['severity_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_severity_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Type </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_type_table = "SELECT occur_type, COUNT(*) as type_table_count FROM occur GROUP BY occur_type";

                                                // Execute the query 
                                                $result_type_table = mysqli_query($conn, $sql_type_table);

                                                // Check for SQL errors
                                                if (!$result_type_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_type_table);
                                                }

                                                // Initialize total count variable
                                                $total_type_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_type_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-dark text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_type_table = mysqli_fetch_assoc($result_type_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_type_table['occur_type']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_type.php?occur_type=" . urlencode($row_type_table['occur_type']) . "'>" . htmlspecialchars($row_type_table['type_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_type_count += $row_type_table['type_table_count'];
                                                    }

                                                // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_type_count) . "</strong></td></tr>";

                                                    echo "</tbody></table>";
                                                } else {
                                                    // No results found
                                                    echo "No data available.";
                                                }

                                            ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
      </div> <!-- end row -->

      <div class="row">
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Unit </h4>
                                        <p class="card-title-desc">
                                        </p>


                                <?php
                                // Query to count the number of reports by category
                                $sql_unit_table = "SELECT patient_unit, COUNT(*) as unit_table_count FROM occur GROUP BY patient_unit";

                                // Execute the query 
                                $result_unit_table = mysqli_query($conn, $sql_unit_table);

                                // Initialize total count variable
                                $total_unit_count = 0;

                                // Check if there are results
                                if ($result_unit_table && mysqli_num_rows($result_unit_table) > 0) {
                                    // Add Bootstrap table classes
                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                   echo "<thead class='bg-warning text-dark'><tr><th>Category</th><th>Total Reports</th></tr></thead>";
                                    echo "<tbody>";
                                    
                                    // Fetch each row and display in table format
                                    while ($row_unit_table = mysqli_fetch_assoc($result_unit_table)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row_unit_table['patient_unit']) . "</td>";
                                          // Make the count clickable and pass occur_location
                                        echo "<td><a href='drill_unit.php?patient_unit=" . urlencode($row_unit_table['patient_unit']) . "'>" .htmlspecialchars($row_unit_table['unit_table_count']) . "</a></td>";
                                       
                                        echo "</tr>";
                                        // Add to total count
                                            $total_unit_count += $row_unit_table['unit_table_count'];
                                        }

                                        // Add total row
                                        echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_unit_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Attending MD </h4>
                                        <p class="card-title-desc">
                                        </p>


                              <?php
                                    // Query to count the number of reports by program
                                    $sql_md_table = "SELECT md_attending, COUNT(*) as md_table_count FROM occur GROUP BY md_attending";

                                    // Execute the query 
                                    $result_md_table = mysqli_query($conn, $sql_md_table);

                                    // Check for SQL errors
                                    if (!$result_md_table) {
                                        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql);
                                    }

                                    // Initialize total count variable
                                    $total_md_count = 0;


                                    // Check if there are results
                                    if (mysqli_num_rows($result_md_table) > 0) {
                                        // Add Bootstrap table classes
                                        echo "<table class='table table-striped table-bordered table-sm'>";
                                        echo "<thead class='bg-primary text-light'><tr><th>Category</th><th>Total Reports</th></tr></thead>";
                                        echo "<tbody>";

                                        // Fetch each row and display in table format
                                        while ($row_md_table = mysqli_fetch_assoc($result_md_table)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row_md_table['md_attending']) . "</td>";
                                            // Make the count clickable and pass occur_location
                                            echo "<td><a href='drill_md.php?md_attending=" . urlencode($row_md_table['md_attending']) . "'>" .htmlspecialchars($row_md_table['md_table_count']) . "</a></td>";
                                            echo "</tr>";

                                            // Add to total count
                                            $total_md_count += $row_md_table['md_table_count'];
                                        }

                                        // Add total row
                                         echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_md_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>
                                      

                                    </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->



                
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Area </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by area
                                                $sql_area_table = "SELECT occur_area, COUNT(*) as area_table_count FROM occur GROUP BY occur_area";

                                                // Execute the query 
                                                $result_area_table = mysqli_query($conn, $sql_area_table);

                                                // Check for SQL errors
                                                if (!$result_area_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql);
                                                }

                                                // Initialize total count variable
                                                $total_area_count = 0;

                                                // Check if there are results
                                                if (mysqli_num_rows($result_area_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-success text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_area_table = mysqli_fetch_assoc($result_area_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_area_table['occur_area']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_area.php?occur_area=" . urlencode($row_area_table['occur_area']) . "'>" . htmlspecialchars($row_area_table['area_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_area_count += $row_area_table['area_table_count'];
                                                    }

                                                    // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_area_count) . "</strong></td></tr>";

                                                    echo "</tbody></table>";
                                                } else {
                                                    // No results found
                                                    echo "No data available.";
                                                }

                                                // Free result set and close connection
                                                //mysqli_free_result($result_location_table);
                                                //mysqli_close($conn);
                                            ?>


                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->



                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Code </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_code_table = "SELECT occur_code, COUNT(*) as code_table_count FROM occur GROUP BY occur_code";

                                                // Execute the query 
                                                $result_code_table = mysqli_query($conn, $sql_code_table);

                                                // Check for SQL errors
                                                if (!$result_code_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_code_table);
                                                }

                                                // Initialize total count variable
                                                $total_code_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_code_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_code_table = mysqli_fetch_assoc($result_code_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_code_table['occur_code']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_code.php?occur_code=" . urlencode($row_code_table['occur_code']) . "'>" . htmlspecialchars($row_code_table['code_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_code_count += $row_code_table['code_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_code_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Level of Care </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_loc_table = "SELECT patient_loc, COUNT(*) as loc_table_count FROM occur GROUP BY patient_loc";

                                                // Execute the query 
                                                $result_loc_table = mysqli_query($conn, $sql_loc_table);

                                                // Check for SQL errors
                                                if (!$result_loc_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_loc_table);
                                                }

                                                // Initialize total count variable
                                                $total_loc_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_loc_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_loc_table = mysqli_fetch_assoc($result_loc_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_loc_table['patient_loc']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_loc.php?occur_code=" . urlencode($row_loc_table['patient_loc']) . "'>" . htmlspecialchars($row_loc_table['loc_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_loc_count += $row_loc_table['loc_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_loc_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
      </div> <!-- end row -->

   
 
<!-- ========================================  BOOTSTRAP ROW:  CHARTS IN CARD CONTAINERS   ========================================================= -->

<!-- PHP Code to generate card with totals by type 3 column with pie on top ==================== -->

                <?php
                // Query to count the number of reports by type within the desired date range
                $sql_type_table =  "SELECT occur_type, COUNT(*) as type_table_count 
                                    FROM occur 
                                    WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                                    AND occur_date < DATE_FORMAT(DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY), '%Y-%m-01')
                                    GROUP BY occur_type
                                    ORDER BY type_table_count DESC"; // Added ORDER BY clause

                // Execute the query 
                $result_type_table = mysqli_query($conn, $sql_type_table);

                // Check for SQL errors
                if (!$result_type_table) {
                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_type_table);
                }

                // Initialize total count variable and arrays for chart data
                $total_type_count = 0;
                $labels = [];
                $series = [];

                // Define a mapping of occur_type to icons and colors
                // Adjust the mapping based on your actual occur_type values


               $type_styles = [
                'Patient Care' => ['icon' => 'mdi mdi-circle', 'color' => 'text-darkblue'],
                'Safety' => ['icon' => 'mdi mdi-circle', 'color' => 'text-danger'],
                'Other' => ['icon' => 'mdi mdi-circle', 'color' => 'text-warning'],
                ];

                // Check if there are results
                if (mysqli_num_rows($result_type_table) > 0) {
                    echo '<div class="col-xl-3">';
                    echo '    <div class="card">';
                    echo '        <div class="card-body">';
                    echo '            <div class="float-end">';
                    // You can add additional elements here if needed
                    echo '            </div>';
                    echo '            <h5 class="card-title mb-3 me-2">MTD Reports</h5>';

                    // Placeholder for the pie chart
                    echo '            <div id="pie-chart" class="apex-charts" dir="ltr"></div>';

                    echo '            <div class="mt-4 text-center">';
                    echo '                <div>';

                    // Iterate through each report type and display it
                    while ($row_type_table = mysqli_fetch_assoc($result_type_table)) {
                        $occur_type = htmlspecialchars($row_type_table['occur_type']);
                        $count = htmlspecialchars($row_type_table['type_table_count']);
                        $total_type_count += $row_type_table['type_table_count'];

                        // Collect data for the pie chart
                        $labels[] = $occur_type;
                        $series[] = (int)$row_type_table['type_table_count'];

                        // Determine the style based on the occur_type
                        // Default style if occur_type not mapped
                        $icon = 'mdi mdi-circle';
                        $color = 'text-secondary';

                        if (array_key_exists($occur_type, $type_styles)) {
                            $icon = $type_styles[$occur_type]['icon'];
                            $color = $type_styles[$occur_type]['color'];
                        }

                    // Output the report type block
                        echo '                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">';
                        echo '                        <div class="d-flex">';
                        echo '                            <i class="' . $icon . ' font-size-12 mt-1 ' . $color . '"></i>';
                        echo '                            <div class="flex-grow-1 ms-2">';
                        echo '                                <p class="mb-0">' . $occur_type . '</p>';
                        echo '                            </div>';
                        echo '                        </div>';
                        echo '                        <div>';
                        echo '                            <h5 class="mb-0 font-size-14"><a href="drill_type.php?occur_type=' . urlencode($occur_type) . '">' . $count . '</a></h5>';
                        echo '                        </div>';
                        echo '                    </div>';
                    }

                    // Optionally, display the total reports
                        echo '                    <div class="d-flex justify-content-between align-items-center pt-2">';
                        echo '                        <div class="d-flex">';
                        echo '                            <i class="mdi mdi-circle font-size-12 mt-1 text-success"></i>';
                        echo '                            <div class="flex-grow-1 ms-2">';
                        echo '                                <p class="mb-0"><strong>Total</strong></p>';
                        echo '                            </div>';
                        echo '                        </div>';
                        echo '                        <div>';
                        echo '                            <h5 class="mb-0 font-size-14"><strong>' . htmlspecialchars($total_type_count) . '</strong></h5>';
                        echo '                        </div>';
                        echo '                    </div>';

                        echo '                </div>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';

                    // Encode the PHP arrays into JSON for JavaScript
                    $labels_json = json_encode($labels);
                    $series_json = json_encode($series);

                    // Initialize the Apex Charts pie chart
                    echo '
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var options = {
                                    chart: {
                                        type: "pie",
                                        height: 250
                                    },
                                    plotOptions: {
                                        pie: {
                                            dataLabels: {
                                                offset: -10 // Adjust this value to move labels towards the center
                                            }
                                        }
                                    },
                                    labels: ' . $labels_json . ',
                                    series: ' . $series_json . ',
                                    colors: [';

                    // Dynamically assign colors based on type_styles mapping
                    $color_array = [];
                    foreach ($labels as $label) {
                        if (isset($type_styles[$label]['color'])) {
                            // Map Bootstrap text color classes to actual colors
                            // Adjust these mappings based on your Bootstrap theme
                            switch ($type_styles[$label]['color']) {
                                case 'text-darkblue':
                                    $color_array[] = "'#00008B'"; // Dark blue
                                    break;
                                case 'text-danger':
                                    $color_array[] = "'#dc3545'";
                                    break;
                                case 'text-warning':
                                    $color_array[] = "'#ffc107'";
                                    break;
                                case 'text-success':
                                    $color_array[] = "'#198754'";
                                    break;
                                default:
                                    $color_array[] = "'#6c757d'"; // text-secondary

                            }
                        } else {
                            $color_array[] = "'#6c757d'"; // default color
                        }
                    }
                    echo implode(',', $color_array);
                    echo '],
                                    legend: {
                                        position: "bottom"
                                    },
                                    dataLabels: {
                                        enabled: true,
                                        formatter: function(val, opts) {
                                            return val.toFixed(1) + "%"; // Display only percentage with one decimal
                                        },
                                        style: {
                                            fontSize: "14px",
                                            colors: ["#fff"] // Ensure text is readable against slice colors
                                        }
                                    },
                                    tooltip: {
                                        y: {
                                            formatter: function(val, opts) {
                                                return val + " Reports (" + opts.w.globals.seriesPercent[opts.seriesIndex].toFixed(1) + "%)";
                                            }
                                        }
                                    },
                                    responsive: [{
                                        breakpoint: 480,
                                        options: {
                                            chart: {
                                                width: 200
                                            },
                                            legend: {
                                                position: "bottom"
                                            }
                                        }
                                    }]
                                };

                                var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
                                chart.render();
                            });
                        </script>
                    ';
                } else {
                    // No results found
                    echo "No data available.";
                }
            ?>


<!-- PHP Code to generate card with totals by type ==================== -->

<?php
    // Query to count the number of reports by type within the desired date range
    $sql_type_pie_table =  "SELECT occur_type, COUNT(*) as type_pie_table_count 
                            FROM occur 
                            WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            AND occur_date < DATE_FORMAT(DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY), '%Y-%m-01')
                            GROUP BY occur_type
                            ORDER BY type_pie_table_count DESC"; // Added ORDER BY clause

    // Execute the query 
    $result_type_pie_table = mysqli_query($conn, $sql_type_pie_table);

    // Check for SQL errors
    if (!$result_type_pie_table) {
        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_type_pie_table);
    }

    // Initialize total count variable and arrays for chart data
    $total_type_pie_table_count = 0;
    $labels_pie_table = [];
    $series_pie_table = [];

    // Define a mapping of occur_type to icons and colors
    // Adjust the mapping based on your actual occur_type values
    $type_pie_table_styles = [
        'Patient Care' => ['icon' => 'mdi mdi-circle', 'color' => 'text-darkblue'],
        'Safety' => ['icon' => 'mdi mdi-circle', 'color' => 'text-danger'],
        'Other' => ['icon' => 'mdi mdi-circle', 'color' => 'text-warning'],
        // Add more mappings as needed
    ];

    // Check if there are results
    if (mysqli_num_rows($result_type_pie_table) > 0) {
        echo '<div class="col-xl-6">'; // Changed from col-xl-3 to col-xl-6
        echo '    <div class="card">';
        echo '        <div class="card-body">';
        echo '            <h5 class="card-title mb-4">MTD Reports</h5>';

        // Create a Bootstrap row inside the card for two columns: chart and totals
        echo '            <div class="row">';
        
        // Left Column: Pie Chart
        // Ensure unique ID for the pie chart, e.g., pie-chart-1
        echo '                <div class="col-md-6 d-flex align-items-center justify-content-center">';
        echo '                    <div id="pie-chart-type-pie-table" class="apex-charts" dir="ltr"></div>';
        echo '                </div>';

        // Right Column: Totals
        echo '                <div class="col-md-6">';
        echo '                    <div class="mt-4 mt-md-0 text-center text-md-start">';
        echo '                        <div>';

        // Iterate through each report type and display it
        while ($row_type_pie_table = mysqli_fetch_assoc($result_type_pie_table)) {
            $occur_type_pie_table = htmlspecialchars($row_type_pie_table['occur_type']);
            $count_pie_table = htmlspecialchars($row_type_pie_table['type_pie_table_count']);
            $total_type_pie_table_count += $row_type_pie_table['type_pie_table_count'];

            // Collect data for the pie chart
            $labels_pie_table[] = $occur_type_pie_table;
            $series_pie_table[] = (int)$row_type_pie_table['type_pie_table_count'];

            // Determine the style based on the occur_type
            // Default style if occur_type not mapped
            $icon_pie_table = 'mdi mdi-circle';
            $color_pie_table = 'text-secondary';

            if (array_key_exists($occur_type_pie_table, $type_pie_table_styles)) {
                $icon_pie_table = $type_pie_table_styles[$occur_type_pie_table]['icon'];
                $color_pie_table = $type_pie_table_styles[$occur_type_pie_table]['color'];
            }

            // Output the report type block
            echo '                            <div class="d-flex justify-content-center justify-content-md-between align-items-center border-bottom pb-2 mb-2">';
            echo '                                <div class="d-flex align-items-center">';
            echo '                                    <i class="' . $icon_pie_table . ' font-size-12 me-2 ' . $color_pie_table . '"></i>';
            echo '                                    <p class="mb-0">' . $occur_type_pie_table . '</p>';
            echo '                                </div>';
            echo '                                <div>';
            echo '                                    <h5 class="mb-0 font-size-14"><a href="drill_type.php?occur_type=' . urlencode($occur_type_pie_table) . '">' . $count_pie_table . '</a></h5>';
            echo '                                </div>';
            echo '                            </div>';
        }

        // Optionally, display the total reports
        echo '                            <div class="d-flex justify-content-center justify-content-md-between align-items-center pt-2">';
        echo '                                <div class="d-flex align-items-center">';
        echo '                                    <i class="mdi mdi-circle font-size-12 me-2 text-success"></i>';
        echo '                                    <p class="mb-0"><strong>Total</strong></p>';
        echo '                                </div>';
        echo '                                <div>';
        echo '                                    <h5 class="mb-0 font-size-14"><strong>' . htmlspecialchars($total_type_pie_table_count) . '</strong></h5>';
        echo '                                </div>';
        echo '                            </div>';

        echo '                        </div>';
        echo '                    </div>';
        echo '                </div>'; // End of Right Column
        echo '            </div>'; // End of Inner Row
        echo '        </div>'; // End of Card Body
        echo '    </div>'; // End of Card
        echo '</div>'; // End of Column

        // Encode the PHP arrays into JSON for JavaScript
        $labels_pie_table_json = json_encode($labels_pie_table);
        $series_pie_table_json = json_encode($series_pie_table);

        // Initialize the Apex Charts pie chart
        echo '
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var options = {
                        chart: {
                            type: "pie",
                            height: 250
                        },
                        plotOptions: {
                            pie: {
                                dataLabels: {
                                    offset: -10 // Adjust this value to move labels towards the center
                                }
                            }
                        },
                        labels: ' . $labels_pie_table_json . ',
                        series: ' . $series_pie_table_json . ',
                        colors: [';

        // Dynamically assign colors based on type_pie_table_styles mapping
        $color_pie_table_array = [];
        foreach ($labels_pie_table as $label_pie_table) {
            if (isset($type_pie_table_styles[$label_pie_table]['color'])) {
                // Map Bootstrap text color classes to actual colors
                // Adjust these mappings based on your Bootstrap theme
                switch ($type_pie_table_styles[$label_pie_table]['color']) {
                    case 'text-darkblue':
                        $color_pie_table_array[] = "'#00008B'";
                        break;
                    case 'text-danger':
                        $color_pie_table_array[] = "'#dc3545'";
                        break;
                    case 'text-warning':
                        $color_pie_table_array[] = "'#ffc107'";
                        break;
                    case 'text-success':
                        $color_pie_table_array[] = "'#198754'";
                        break;
                    default:
                        $color_pie_table_array[] = "'#6c757d'"; // text-secondary
                }
            } else {
                $color_pie_table_array[] = "'#6c757d'"; // default color
            }
        }
        echo implode(',', $color_pie_table_array);
        echo '],
                        legend: {
                            position: "bottom"
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val, opts) {
                                return val.toFixed(1) + "%"; // Display only percentage with one decimal
                            },
                            style: {
                                fontSize: "14px",
                                colors: ["#fff"] // Ensure text is readable against slice colors
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function(val, opts) {
                                    return val + " Reports (" + opts.w.globals.seriesPercent[opts.seriesIndex].toFixed(1) + "%)";
                                }
                            }
                        },
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                                legend: {
                                    position: "bottom"
                                }
                            }
                        }]
                    };

                    var chart = new ApexCharts(document.querySelector("#pie-chart-type-pie-table"), options);
                    chart.render();
                });
            </script>
        ';
    } else {
        // No results found
        echo '<div class="col-xl-6">'; // Changed from col-xl-3 to col-xl-6
        echo '    <div class="card">';
        echo '        <div class="card-body">';
        echo '            <h5 class="card-title mb-3">MTD Reports</h5>';
        echo '            <p>No data available.</p>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
?>

<?php
    // Query to count the number of reports by location within the desired date range
    $sql_location_table =  "SELECT occur_location, COUNT(*) as location_table_count 
                            FROM occur 
                            WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            AND occur_date < DATE_FORMAT(DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY), '%Y-%m-01')
                            GROUP BY occur_location
                            ORDER BY location_table_count DESC";

    // Execute the query 
    $result_location_table = mysqli_query($conn, $sql_location_table);

    // Check for SQL errors
    if (!$result_location_table) {
        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_location_table);
    }

    // Initialize total count variable
    $total_location_table_count = 0;

    // Predefined set of colors
    $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

    // Initialize the dynamic styles array
    $location_table_styles = [];

    // Create a dynamic styles array based on query results
    $color_index = 0;
    while ($row = mysqli_fetch_assoc($result_location_table)) {
        $location = $row['occur_location'];
        if (!isset($location_table_styles[$location])) {
            $location_table_styles[$location] = [
                'color' => $colors[$color_index % count($colors)]
            ];
            $color_index++;
        }
    }

    // Reset the result pointer
    mysqli_data_seek($result_location_table, 0);

    // Check if there are results
    if (mysqli_num_rows($result_location_table) > 0) {
        echo '<div class="col-xl-3">';
        echo '    <div class="card">';
        echo '        <div class="card-body pb-2">';
        echo '            <div class="d-flex flex-wrap align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">REPORTS BY LOCATION</h5>';
        echo '            </div>';
        echo '            <br>';

        echo '            <div class="mt-4 text-center">';
        echo '                <div>';

        // Iterate through each report location and display it
        while ($row_location_table = mysqli_fetch_assoc($result_location_table)) {
            $occur_location_table = htmlspecialchars($row_location_table['occur_location']);
            $count_location_table = htmlspecialchars($row_location_table['location_table_count']);
            $total_location_table_count += $row_location_table['location_table_count'];

            // Get the color for this location
            $color_location_table = $location_table_styles[$occur_location_table]['color'];

            // Output the report location block
            echo '                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">';
            echo '                        <div class="d-flex">';
            echo '                            <i class="mdi mdi-circle font-size-12 mt-1 ' . $color_location_table . '"></i>';
            echo '                            <div class="flex-grow-1 ms-2">';
            echo '                                <p class="mb-0">' . $occur_location_table . '</p>';
            echo '                            </div>';
            echo '                        </div>';
            echo '                        <div>';
            echo '                            <h5 class="mb-0 font-size-14"><a href="drill_location.php?occur_location=' . urlencode($occur_location_table) . '">' . $count_location_table . '</a></h5>';
            echo '                        </div>';
            echo '                    </div>';
        }

        // Display the total reports
        echo '                    <div class="d-flex justify-content-between align-items-center pt-2">';
        echo '                        <div class="d-flex">';
        echo '                            <div class="flex-grow-1">';
        echo '                                <p class="mb-0"><strong>Total</strong></p>';
        echo '                            </div>';
        echo '                        </div>';
        echo '                        <div>';
        echo '                            <h5 class="mb-0 font-size-14"><strong>' . htmlspecialchars($total_location_table_count) . '</strong></h5>';
        echo '                        </div>';
        echo '                    </div>';

        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
        echo '</div>'; // end col
    } else {
        // No results found
        echo '<div class="col-xl-3">';
        echo '    <div class="card">';
        echo '        <div class="card-body pb-2">';
        echo '            <div class="d-flex flex-wrap align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">REPORTS BY LOCATION</h5>';
        echo '            </div>';
        echo '            <br>';

        echo '            <div class="mt-4 text-center">';
        echo '                <div>';
        echo '                    <p>No data available.</p>';
        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
        echo '</div>'; // end col
    }
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


<!-- ========================================  PAGE SPECIFIC SCRIPTS / GRAPHS   ========================================================== -->

<!-- SCRIPT FOR CATEGORY CHART -->



<!-- The JS to make the chart clickable is set within the variables for the chart in "events:"  -->
   <script>
    var options = {
        series: [{
            name: 'Reports Count',
            data: chartData.map(item => item.count)
        }],
        chart: {
            type: 'bar',
            width: '100%',  // Set width (can use '100%' or a specific pixel value like '600px')
            height: '400px',  // Set height (use any pixel value like '400px')
            //Nested within the chart options, include an event object.  This captures user click events on the bar
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    // Get the index of the clicked bar (Text always the same)
                    var selectedIndex = config.dataPointIndex;
                    // Get the corresponding status label.  CHANGES -- Use unique name selectedNEWNAME = chartData[selectedIndex].COLUMN_NAME (Variable name)
                    var selectedCategory = chartData[selectedIndex].reporter_category;
                    // Redirect to another page with the selected status as a query parameter. CHANGES -- (change last text to selectedNEWNAME)
                    window.location.href = 'bar_detail_category.php?category=' + encodeURIComponent(selectedCategory);
                }
            }
        },

        plotOptions: {
            bar: {
                borderRadius: 4,
                borderRadiusApplication: 'end',
                horizontal: true,
                barHeight: '70%',  // Adjust bar thickness
                barGap: '10%'      // Adjust the gap between bars
            }
        },

        colors: ['#00b33c'],

        xaxis: {
            categories: chartData.map(item => item.reporter_category)
        },

        title: {
            text: 'Number of Reports by Category',
            align: 'top',
            margin: 20 // Adjust this value to increase or decrease the space below the title
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart_category"), options);
    chart.render();
</script>


<!-- COLUMN CHART: TOTAL REPORTS BY MONTH ----------------------------------------------------------------------- -->

<script>
    // Parse the PHP data
    var months_report_count = <?php echo $months_js_report_count; ?>;
    var chartData_report_count = <?php echo $chart_data_js_report_count; ?>;

    // Chart options
    var options_report_count = {
    chart: {
        type: 'bar',
        height: 400,
        events: {
            dataPointSelection: function(event, chartContext, config) {
                var selectedIndex = config.dataPointIndex;
                var selectedMonth = months_report_count[selectedIndex];
                window.location.href = 'dashboard_period.php?month=' + encodeURIComponent(selectedMonth);
            }
        }
    },
    series: [{
        name: 'Reports',
        data: chartData_report_count['ALL']
    }],
    xaxis: {
        categories: months_report_count,
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
        text: 'Reports by Month - ALL',
        align: 'center'
    },
    dataLabels: {
        enabled: true,
        style: {
            fontSize: '14px',
            fontWeight: 'bold',
            colors: ['#000000'] // Set data label color to black
        },
        dropShadow: {
            enabled: false
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                position: 'top' // Adjust the position if needed
            }
        }
    }
};

    // Initialize the chart
    var chart_report_count = new ApexCharts(document.querySelector("#chart_report_count"), options_report_count);
    chart_report_count.render();

    // Function to update the chart
    function updateChart_report_count(filter) {
        chart_report_count.updateOptions({
            title: {
                text: 'Reports by Month - ' + filter
            }
        });
        
        chart_report_count.updateSeries([{
            name: 'Reports',
            data: chartData_report_count[filter]
        }]);

        // Update button styles
        updateButtonStyles_report_count(filter);
    }

    // Function to update button styles
    function updateButtonStyles_report_count(activeFilter) {
        var buttons = document.querySelectorAll('button[data-filter-report-count]');
        buttons.forEach(function(button) {
            var buttonFilter = button.getAttribute('data-filter-report-count');
            if (buttonFilter === activeFilter) {
                button.classList.remove('btn-light');
                button.classList.add('btn-primary');
            } else {
                button.classList.remove('btn-primary');
                button.classList.add('btn-light');
            }
        });
    }

    // Event listeners for filter buttons
    document.addEventListener('DOMContentLoaded', function() {
        var buttons = document.querySelectorAll('button[data-filter-report-count]');
        buttons.forEach(function(button) {
            var filter = button.getAttribute('data-filter-report-count');
            button.addEventListener('click', function() {
                updateChart_report_count(filter);
            });
        });

        // Set initial active button
        updateButtonStyles_report_count('ALL');
    });
</script>



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
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [[ 0, "desc" ]] // Order by the first column (ID) in descending order
            });
        });
    </script>





<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>










