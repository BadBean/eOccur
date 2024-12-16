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

        // Construct the full name with a non-breaking space AND full name with title
        $user_full_name = $row_user['user_first_name'] . " " . $row_user['user_last_name']; // Use regular space, not &nbsp
        $user_full_name_title = $row_user['user_first_name'] . " " . $row_user['user_last_name'] . ", " . $row_user['user_title']; // Use regular space, not &nbsp

?>            

<!-- ============================================  PAGE SPECIFIC CUSTOM CSS  ================================================================= -->

    <!-- Change past due dates to red font -------->

        <style>
            .past-due {
                color: red;
            }
        </style>

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

                    <!-- start page headline -->

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

                       

<!-- ========================================  BOOTSTRAP ROW: COLUMN CHART - TOTAL REPORTS BY MONTH ====================================================== -->


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

                        ?>

<!-- RENDER COLUMN CHART - DETAIL BY MONTH ----- -->

                <!-- Main container -->
                    <!-- NOTE: Wrap in additional "container-fluid div to have cards stacked on the right of the graph/column" -->
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Chart Column (Left side) -->
                            <div class="col-xl-9">
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title me-2">REPORTS BY MONTH</h5>
                                            <div class="ms-auto">
                                                <div>
                                                    <button type="button" class="btn btn-primary btn-sm" data-filter-report-count="ALL">ALL</button>
                                                    <button type="button" class="btn btn-light btn-sm" data-filter-report-count="Patient Care">PT CARE</button>
                                                    <button type="button" class="btn btn-light btn-sm" data-filter-report-count="Safety">SAFETY</button>
                                                    <button type="button" class="btn btn-light btn-sm" data-filter-report-count="Other">OTHER</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="chart_report_count" class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div>




<!-- ========================================  BOOTSTRAP ROW: 3 CARDS    =================================================================== -->

                       

<!-- COLUMN 1: NEW REPORTS  ----------------------- -->

                      
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

                        
                            <!-- Stats Cards Column (Right side) -->
                            <div class="col-xl-3">
                                <!-- New Reports Card -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="#followup">
                                                <div class="avatar-sm mx-auto mb-4">
                                                    <span class="avatar-title rounded-circle bg-light font-size-24">
                                                        <i class="mdi mdi-folder-star text-primary"></i>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">New Reports For Review</p>
                                            <a href="#followup">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0">
                                            <!-- <span class="badge badge-soft-success me-1">
                                                <i class="mdi mdi-arrow-up-bold me-1"></i>2.65%
                                            </span>
                                            --> 
                                           
                                        </p>
                                    </div>
                                </div>
                    

<!-- COLUMN 2: "FOLLOW UP REQUIRED"  ----------------------- -->



                        <?php 
                            //Query to count # of reports submitted but not reviewed by RM
                                $sql =  "SELECT COUNT(*) AS item_count
                                         FROM occur
                                         WHERE manager_followup_name = '$user_full_name'
                                         AND occur_status != 'Closed'
                                         AND manager_status NOT IN ('Complete', 'No Action Needed')
                                        ";

                                $result = mysqli_query($conn, $sql);
                                         if (!$result) 
                                         { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

                                $row = mysqli_fetch_assoc($result);
                                $numrows_submitted = $row['item_count'];
                        ?>

                           
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="#followup">
                                                <div class="avatar-sm mx-auto mb-4">
                                                    <span class="avatar-title rounded-circle bg-light font-size-24">
                                                        <i class="mdi mdi-alert-circle text-danger"></i>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">Follow Up Assigned</p>
                                            <a href="#followup">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0">
                                            <!-- <span class="badge badge-soft-danger me-1">2 Reports</span>
                                            Past Target Date -->
                                        </p>
                                    </div>
                                </div>
                      
                   
<!-- COLUMN 3: FLAGGED FOR USER - INCLUDED IN "OCCUR_NOTIFY"  ----------------------- -->



                        <?php 
                            //Query to count # of reports submitted but not reviewed by RM
                                $sql =  "SELECT COUNT(*) AS item_count
                                        FROM occur
                                        WHERE occur_notify LIKE CONCAT('%', '$user_full_name', '%');
                                        ";

                                $result = mysqli_query($conn, $sql);
                                         if (!$result) 
                                         { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

                                $row = mysqli_fetch_assoc($result);
                                $numrows_submitted = $row['item_count'];
                        ?>

                           
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="#flagged">
                                                <div class="avatar-sm mx-auto mb-4">
                                                   <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation text-warning"></i>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-14">REPORTS FLAGGED FOR YOU
                                                <br>
                                                <span class="text-muted font-size-11"></span>
                                                
                                            </p>

                                            <a href="#flagged">

                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>

                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0">
                                            <!-- <span class="badge badge-soft-danger me-1">2 Reports</span>
                                            Past Target Date -->
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div> <!-- end container -->



<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<?php 
//Query for reports assigned to user and not closed
    $sql = "SELECT *
            FROM occur
            WHERE occur_status <> 'Closed'
            AND manager_followup_name = '$user_full_name'
            AND manager_status NOT IN ('Complete', 'No Action Needed')

    ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysql_error() . "</p>"); }
    $numrows = mysqli_num_rows($result);


    include("datatable_open_mgr.php");
?>





<!-- ================================ BOOTSTRAP ROW:  DATATABLE - ACTION PLANS IN PROGRESS  ========================================= -->

    <?php 
        //Query to count # of reports assigned to user and not closed
                  $sql = "SELECT *
                            FROM occur
                            WHERE occur_status <> 'Closed'
                            AND manager_followup_name = '$user_full_name'
                            AND manager_status NOT IN ('Complete', 'No Action Needed')
                            AND manager_followup_plan IS NOT NULL
                            AND manager_followup_plan <> ''
                            ";

        $result = mysqli_query($conn, $sql);
                 if (!$result) 
                 { die("<p>Error in tables: " . mysql_error() . "</p>"); }
        $numrows = mysqli_num_rows($result);



//<!-- DATATABLE NAME AND INCLUDE FILE ------------------- -->

            //$datatable_name = "Recent Reports";
            //$datatable_name_sub = "All Reports Submitted in the last 30 days";
            /*            
            if ($formatted_end_date) {
                $datatable_name_sub .= " Through " . $formatted_end_date;
            }
            */
            include ("datatable_plan_mgr.php"); 
    ?>

<!-- ================================ BOOTSTRAP ROW:  COLUMN 1 - CATEGORY CHART ========================================================================== -->


<!-- PHP / SQL QUERY FOR CATEGORY CHART  --------------->
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

<!-- RENDER CATEGORY CHART  ------------------------- -->

            <div class="row">
                <div class="col-md-8 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Chart Container -->
                            <div id="chart_category" dir="ltr" ></div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->





<!-- ================================ BOOTSTRAP ROW:  COLUMN 2 - PROGRAM CHART ========================================================================== -->


<!-- DETAIL BY TYPE:  PIE CHART AND TOTALS  COL 2  --------------------- -->

                            <?php
                                // Query to count the number of reports by type within the desired date range
                                    $sql_type_table =  "SELECT occur_type, COUNT(*) as type_table_count 
                                                        FROM occur 
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
                                    $type_styles = [
                                        'Patient Care' => ['icon' => 'mdi mdi-circle', 'color' => 'text-primary'],
                                        'Safety' => ['icon' => 'mdi mdi-circle', 'color' => 'text-danger'],
                                        'Other' => ['icon' => 'mdi mdi-circle', 'color' => 'text-warning'],
                                    ];
                            ?>

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end"></div>
                                        <h5 class="card-title mb-3 me-2">Detail by Type</h5>
                                        <div id="pie-chart" class="apex-charts" dir="ltr"></div>
                                        <div class="mt-4 text-center">
                                            <div>
                                                <?php while ($row_type_table = mysqli_fetch_assoc($result_type_table)): 
                                                    $occur_type = htmlspecialchars($row_type_table['occur_type']);
                                                    $count = htmlspecialchars($row_type_table['type_table_count']);
                                                    $total_type_count += $row_type_table['type_table_count'];

                                                    // Collect data for the pie chart
                                                    $labels[] = $occur_type;
                                                    $series[] = (int)$row_type_table['type_table_count'];

                                                    // Determine the style based on the occur_type
                                                    $icon = $type_styles[$occur_type]['icon'] ?? 'mdi mdi-circle';
                                                    $color = $type_styles[$occur_type]['color'] ?? 'text-secondary';
                                                ?>
                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                                    <div class="d-flex">
                                                        <i class="<?= $icon ?> font-size-12 mt-1 <?= $color ?>"></i>
                                                        <div class="flex-grow-1 ms-2">
                                                            <p class="mb-0"><?= $occur_type ?></p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 font-size-14">
                                                            <a href="drill_type.php?occur_type=<?= urlencode($occur_type) ?>"><?= $count ?></a>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <?php endwhile; ?>

                                                <!-- Total reports section -->
                                                <div class="d-flex justify-content-between align-items-center pt-2">
                                                    <div class="d-flex">
                                                        <i class="mdi mdi-circle font-size-12 mt-1 text-success"></i>
                                                        <div class="flex-grow-1 ms-2">
                                                            <p class="mb-0"><strong>Total</strong></p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_type_count) ?></strong></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Encode the PHP arrays into JSON for JavaScript
                            $labels_json = json_encode($labels);
                            $series_json = json_encode($series);
                            $color_array = [];
                            foreach ($labels as $label) {
                                switch ($type_styles[$label]['color'] ?? 'text-secondary') {
                                    case 'text-primary': $color_array[] = "'#1E90FF'"; break;
                                    case 'text-danger': $color_array[] = "'#dc3545'"; break;
                                    case 'text-warning': $color_array[] = "'#ffc107'"; break;
                                    case 'text-success': $color_array[] = "'#198754'"; break;
                                    default: $color_array[] = "'#6c757d'";
                                }
                            }
                            $colors_json = implode(',', $color_array);
                            ?>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    var options = {
                                        chart: {
                                            type: "pie",
                                            height: 250,
                                            toolbar: { show: true }
                                        },
                                        plotOptions: { pie: { dataLabels: { offset: -10 }}},
                                        labels: <?= $labels_json ?>,
                                        series: <?= $series_json ?>,
                                        colors: [<?= $colors_json ?>],
                                        legend: { position: "bottom" },
                                        dataLabels: {
                                            enabled: true,
                                            formatter: function(val) { return val.toFixed(1) + "%"; },
                                            style: { fontSize: "14px", colors: ["#fff"] }
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
                                                chart: { width: 200 },
                                                legend: { position: "bottom" }
                                            }
                                        }]
                                    };
                                    var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
                                    chart.render();
                                });
                            </script>

                                <?php if (mysqli_num_rows($result_type_table) === 0): ?>
                                    <p>No data available.</p>
                                <?php endif; ?>

                            <br>
                            <br>

















<!-- ========================================  BOOTSTRAP ROW:  DATATABLE -- ALL NOTIFICATIONS / REPORTS   ========================================================= -->

<!-- PHP / SQL QUERY FOR TABLE ------------------------------ -->

            <?php 


                // Query to select reports where occur_status is not 'Closed', user's full name is in occur_notify, 
                // and occur_date is within the last 30 days
                    $sql = "SELECT *
                            FROM occur
                            WHERE occur_notify LIKE '%$user_full_name%'
                            AND occur_status != 'Closed'
                            ORDER BY occur_id DESC
                           ";

                // Execute the query
                    $result = mysqli_query($conn, $sql);
                    if (!$result) { 
                        die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
                    }

                // Fetch number of rows
                    $numrows = mysqli_num_rows($result);


//<!-- DATATABLE NAME AND INCLUDE FILE   -------------------- -->


            $datatable_name = "Your Reports";
            $datatable_name_sub = "All Reports Routed to" . " " . $user_full_name;
            /*            
            if ($formatted_end_date) {
                $datatable_name_sub .= " Through " . $formatted_end_date;
            }
            */

            include ("datatable_standard_mgr.php"); 
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




<!-- ========================================  PAGE SPECIFIC ASSETS   ========================================================== -->

   
    <!-- Core DataTables -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

    <!-- DataTables Extensions -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>

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

    
<!-- ========================================  PAGE SPECIFIC SCRIPTS / GRAPHS   ========================================================== -->


    <!-- CATEGORY BAR CHART:  ---------------------------------- -->

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
                                window.location.href = 'dashboard_category.php?category=' + encodeURIComponent(selectedCategory);
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


    <!-- COLUMN CHART: TOTAL REPORTS BY MONTH ------------------ -->

   
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









    <!-- DATATABLE JS / Change default sort by ID field ------ --->
<script>
$(document).ready(function() {
    try {
        $('#myTableOpen').DataTable({
            "order": [[ 0, "desc" ]]
        });
        console.log('myTableOpen initialized');
    } catch (e) {
        console.error('Error initializing myTableOpen:', e);
    }

    try {
        $('#myTablePlan').DataTable({
            "order": [[ 0, "desc" ]]
        });
        console.log('myTablePlan initialized');
    } catch (e) {
        console.error('Error initializing myTablePlan:', e);
    }

    try {
        $('#myTable').DataTable({
            "order": [[ 0, "desc" ]]
        });
        console.log('myTable initialized');
    } catch (e) {
        console.error('Error initializing myTable:', e);
    }
});
</script>    


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>










