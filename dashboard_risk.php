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

<!-- ========================================   PAGE SPECIFIC FILES  ======================================================================== -->

     <!-- CUSTOM STYLING ADDED --------->
        <!-- styling for badges on row one of dashboard.  "badge-pill" is custom class I added -->
            <style>
                .badge-pill {
                    min-width: 30px; /* Set minimum width as needed */
                    padding: 0.5em; /* Add padding for better appearance */
                    text-align: center; /* Center the text */
                    border-radius: 50px; /* Make it pill-shaped */
                }
            </style>

        <!-- Override data label color for the chart with ID 'chart_report_count' -->
            <style>
                #chart_report_count .apexcharts-bar-area .apexcharts-datalabel {
                    fill: #000 !important; /* Set data label color to black */
                }
            </style>

        <!-- use dark blue instead of primary for pie charts -->
            <style>
                .text-darkblue {
                    color: #00008B; /* Replace with your desired dark blue HEX code */
                }
            </style>

<!-- ========================================  PAGE FORMATTING  ================================================================= -->

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

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  =================================================================== -->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card" style="background-color: #DCDCDC;">
                                    <div class="card-body text-center">
                                        <h5 class="mb-sm-0 fw-bold text-black">QA / RISK MANAGEMENT DASHBOARD</h5>
                                        <h4></h4>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->




<!-- ========================================  BOOTSTRAP ROW: COLUMN CHART: TOTAL REPORTS BY MONTH  ===================================================== -->


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

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-9">
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
                    

<!-- ========================================  BOOTSTRAP ROW: 4 CARDS    =================================================================== -->



<!-- PHP/SQL QUERY AND CARD FOR "NEW REPORTS" COL 2 -- ROW 1  --------------------- -->

                        <br>
                        <?php 
                        /*
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
                        */

                        //PDO Version
                            $sql =  "SELECT COUNT(*) AS item_count
                                     FROM occur
                                     WHERE occur_status = :occur_status
                                    ";
                            try
                            {
                                //Prepare the statement
                                $stmt = $pdo->prepare($sql);
                                //Define parameter
                                $occur_status = 'Submitted';
                                //Execute the statement with the bound parameter
                                $stmt->execute([':occur_status'=> $occur_status]);
                            } 
                            catch (PDOException $e)
                            {die("<p>Error Executing query: " . htmlspecialchars($e->getMessage()) . "</p>");}

                            $row = $stmt->fetch(PDO::FETCH_ASSOC);

                            $numrows_submitted = $row['item_count'];


                        //mysqli Query to count # of reports in "submitted" status AND reporter_severity of Severe or Sentinel (for pill)
                        /*
                            $sql =  "SELECT COUNT(*) AS reporter_severity_count
                                     FROM occur
                                     WHERE occur_status = 'Submitted'
                                     AND reporter_severity IN ('Severe', 'Sentinel');
                                    ";

                            $result_reporter_severity = mysqli_query($conn, $sql);
                                     if (!$result_reporter_severity) 
                                     { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

                            $row_reporter_severity = mysqli_fetch_assoc($result_reporter_severity);
                            $numrows_reporter_severity = $row_reporter_severity['reporter_severity_count'];
                        */


                        //PDO Version
                            $sql = "SELECT COUNT(*) AS reporter_severity_count
                                     FROM occur
                                     WHERE occur_status = :occur_status
                                     AND reporter_severity IN (:severities);
                                    ";
                            try
                            {
                                //Prepare the statement
                                $stmt_reporter_severity = $pdo->prepare($sql);
                            
                                //Define parameters
                                $occur_status = 'Submitted';
                                $severities = ['Severe', 'Sentinel'];
                                
                                //Execute the statement with the bound parameter
                                $stmt_reporter_severity->execute([
                                    'occur_status'=> $occur_status,
                                    'severities' => $severities
                                ]);
                            }
                            catch (PDOException $e)
                            {die("<p>Error Executing query: " . htmlspecialchars($e->getMessage()) . "</p>");}

                            $row_reporter_severity = $stmt_reporter_severity->fetch(PDO::FETCH_ASSOC);
                            $numrows_reporter_severity = $row_reporter_severity['reporter_severity_count'];
                        ?>

                       
                          <!-- Stats Cards Column (Right side) -->
                            <div class="col-xl-3">
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">New Reports</p>
                                            <a href="submitted_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-pill badge-soft-primary me-1"><i class=""></i><?php echo " " . $numrows_reporter_severity . " "; ?></span> Preliminary Severity: High
                                        </p>
                                    </div>
                                </div>
                            



<!-- PHP / SQL QUERY FOR "MTD TOTALS"  COLUMN 2 -- ROW 2  --------------------- -->


                            <?php 
                                //Query to count # of occurrences in current month (based on date of occurrence, not submission date)
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


                                //Query to count # of occurrences in current month that were high severity
                                    $sql_mtd_severity = "SELECT COUNT(*) AS mtd_severity_count
                                                         FROM occur
                                                         WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                                                         AND occur_date <= LAST_DAY(CURDATE())
                                                         AND rm_severity IN ('Severe', 'Sentinel')
                                                         ";

                                    $result_mtd_severity = mysqli_query($conn, $sql_mtd_severity);
                                    if (!$result_mtd_severity) {
                                        die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                                    }

                                $row_mtd_severity = mysqli_fetch_assoc($result_mtd_severity);
                                $numrows_mtd_severity = $row_mtd_severity['mtd_severity_count'];

                            ?>

                         
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
                                        <p class="text-muted mt-3 mb-0"><span class="badge  badge-pill badge-soft-secondary me-1"><i class="mdi"></i><?php echo $numrows_mtd_severity; ?></span> High Severity
                                        </p>
                                    </div>
                                </div> <!-- end card -->
                           


<!-- PHP / SQL QUERY FOR "OPEN REPORTS" - COL 2 -- ROW 3  --------------------- -->


                            <?php 
                                //Query to count # of reports not in closed status
                                    $sql =  "SELECT COUNT(*) AS item_count
                                             FROM occur
                                             WHERE occur_status != 'Closed';
                                            ";

                                    $result = mysqli_query($conn, $sql);
                                             if (!$result) 
                                             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

                                    $row = mysqli_fetch_assoc($result);
                                    $numrows_submitted = $row['item_count'];


                                // Query to count the # of reports not closed after 30 days
                                    $sql_30day =  "SELECT COUNT(*) AS 30day_count
                                             FROM occur
                                             WHERE occur_status != 'Closed'
                                             AND occur_date > DATE_SUB(CURDATE(), INTERVAL 30 DAY);";

                                    $result_30day = mysqli_query($conn, $sql_30day);
                                    if (!$result_30day) 
                                    { 
                                        die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
                                    }

                                    $row_30day = mysqli_fetch_assoc($result_30day);
                                    $numrows_30day = $row_30day['30day_count'];
                            ?>


                            
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
                                        <p class="text-muted mt-3 mb-0"><span class="badge  badge-pill badge-soft-warning me-1"><i class="mdi"></i><?php echo $numrows_30day; ?></span> open > 30 days
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                        

<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

<!-- PHP / SQL QUERY FOR TABLE  --------------------- -->

        <?php 
            $sql =  "SELECT *
                     FROM occur
                     WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     ORDER BY occur_id ASC";
            $result = mysqli_query($conn, $sql);
            if (!$result) 
            { 
                die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
            }

            $numrows = mysqli_num_rows($result);
       

// Set datatable titles ----------------------------->
                                
            $datatable_name = "Recent Reports";
            $datatable_name_sub = "All Reports Submitted in the last 30 days";
            /*            
            if ($formatted_end_date) {
                $datatable_name_sub .= " Through " . $formatted_end_date;
            }
            */

            include ("datatable_standard_rm_actions.php"); 
            ?>

<!-- =============================================== BOOTSTRAP ROW: CATEGORY CHART / TYPE PIE CHART ======================================== -->

                   

<!-- PHP / SQL QUERY FOR CATEGORY CHART:  COL 1  --------------------- -->

                        <?php 
                            // Query to count the number of reports by category, grouped by both category and date
                                $sql = "SELECT reporter_category, COUNT(*) AS count, DATE(occur_date) AS occur_date
                                        FROM occur
                                        GROUP BY reporter_category, DATE(occur_date)";

                                // Execute the query
                                $result = mysqli_query($conn, $sql);
                                if (!$result) { 
                                    die("<p>Error in query: " . mysqli_error($conn) . "</p>"); 
                                }

                                $data = [];

                                // Fetch the data and store in the $data array
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $data[] = $row;
                                }
                        ?>

                        <!-- Pass data to JavaScript so ApexCharts can use it -->
                            <script> 
                                var chartData = <?php echo json_encode($data); ?>;
                            </script>


                        <div class="row">
                            <!-- CATEGORY CHART -->
                            <div class="col-xl-9">
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title me-2">REPORTS BY CATEGORY</h5>
                                            <div class="ms-auto">
                                                 <!-- Button group for the Category Chart -->
                                                <div id="chart_category_buttons">
                                                    <button type="button" class="btn btn-primary" data-range="all">ALL</button>
                                                    <button type="button" class="btn btn-light" data-range="12mo">12 MO</button>
                                                    <button type="button" class="btn btn-light" data-range="mtd">MTD</button>
                                                    <button type="button" class="btn btn-light" data-range="24hr">24 HR</button>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div id="chart_category" class="apex-charts" dir="ltr"></div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->


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

                            <div class="col-xl-3">
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
                                                            <a href="drill_type.php?type=<?= urlencode($occur_type) ?>"><?= $count ?></a>
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



<!-- ========================================  MIDDLE ROW - 3 WIDGETS / CARDS  =================================================================== -->


<!-- PHP / SQL QUERY AND HTML FOR "LEVEL OF CARE DETAIL" - COLUMN 1 ============================ -->

                        <?php
                        // Query to count the number of reports by level of care within the desired date range
                        $sql_loc_table = "SELECT patient_loc, COUNT(*) as loc_table_count 
                                          FROM occur 
                                          GROUP BY patient_loc
                                          ORDER BY loc_table_count DESC";

                        // Execute the query
                        $result_loc_table = mysqli_query($conn, $sql_loc_table);

                        // Check for SQL errors
                        if (!$result_loc_table) {
                            die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_loc_table);
                        }

                        // Initialize total count variable
                        $total_loc_table_count = 0;

                        // Predefined set of colors
                        $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

                        // Initialize the dynamic styles array
                        $loc_table_styles = [];
                        $color_index = 0;

                        // Create a dynamic styles array based on query results
                        while ($row = mysqli_fetch_assoc($result_loc_table)) {
                            $loc = $row['patient_loc'];
                            if (!isset($loc_table_styles[$loc])) {
                                $loc_table_styles[$loc] = [
                                    'color' => $colors[$color_index % count($colors)]
                                ];
                                $color_index++;
                            }
                        }

                        // Reset the result pointer
                        mysqli_data_seek($result_loc_table, 0);
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php if (mysqli_num_rows($result_loc_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">LEVEL OF CARE DETAIL</h5>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <?php while ($row_loc_table = mysqli_fetch_assoc($result_loc_table)): 
                                                        $patient_loc_table = htmlspecialchars($row_loc_table['patient_loc']);
                                                        $count_loc_table = htmlspecialchars($row_loc_table['loc_table_count']);
                                                        $total_loc_table_count += $row_loc_table['loc_table_count'];
                                                        $color_loc_table = $loc_table_styles[$patient_loc_table]['color'];
                                                    ?>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                                        <div class="d-flex">
                                                            <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_loc_table ?>"></i>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="mb-0"><?= $patient_loc_table ?></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 font-size-14">
                                                                <a href="drill_loc.php?loc=<?= urlencode($patient_loc_table) ?>"><?= $count_loc_table ?></a>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <?php endwhile; ?>

                                                    <!-- Total reports section -->
                                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                                        <div class="d-flex">
                                                            <div class="flex-grow-1">
                                                                <p class="mb-0"><strong>Total</strong></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_loc_table_count) ?></strong></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php else: ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">LEVEL OF CARE</h5>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <p>No data available.</p>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php endif; ?>
                            </div> <!-- end col -->



<!-- PHP / SQL QUERY AND HTML FOR "LOCATION DETAIL" - COLUMN 2 ============================ -->

                            <?php
                                // Query to count the number of reports by location within the desired date range
                                    $sql_location_table = "SELECT occur_location, COUNT(*) as location_table_count 
                                                            FROM occur 
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
                                    $color_index = 0;

                                // Create a dynamic styles array based on query results
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
                            ?>

                            <div class="col-md-4">
                                <?php if (mysqli_num_rows($result_location_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">LOCATION DETAIL</h5>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <?php while ($row_location_table = mysqli_fetch_assoc($result_location_table)): 
                                                        $occur_location_table = htmlspecialchars($row_location_table['occur_location']);
                                                        $count_location_table = htmlspecialchars($row_location_table['location_table_count']);
                                                        $total_location_table_count += $row_location_table['location_table_count'];
                                                        $color_location_table = $location_table_styles[$occur_location_table]['color'];
                                                    ?>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                                        <div class="d-flex">
                                                            <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_location_table ?>"></i>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="mb-0"><?= $occur_location_table ?></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 font-size-14">
                                                                <a href="drill_location.php?location=<?= urlencode($occur_location_table) ?>"><?= $count_location_table ?></a>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <?php endwhile; ?>

                                                    <!-- Total reports section -->
                                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                                        <div class="d-flex">
                                                            <div class="flex-grow-1">
                                                                <p class="mb-0"><strong>Total</strong></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_location_table_count) ?></strong></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php else: ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">LOCATION DETAIL</h5>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <p>No data available.</p>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php endif; ?>
                            </div> <!-- end col -->


<!-- PHP / SQL QUERY AND HTML FOR "PROGRAM DETAIL" - COLUMN 3 ============================ -->

                            <?php
                                // Query to count the number of reports by program within the desired date range
                                $sql_program_table = "SELECT patient_program, COUNT(*) as program_table_count 
                                                      FROM occur 
                                                      GROUP BY patient_program
                                                      ORDER BY program_table_count DESC";

                                // Execute the query
                                $result_program_table = mysqli_query($conn, $sql_program_table);

                                // Check for SQL errors
                                if (!$result_program_table) {
                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_program_table);
                                }

                                // Initialize total count variable
                                $total_program_table_count = 0;

                                // Predefined set of colors
                                $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

                                // Initialize the dynamic styles array
                                $program_table_styles = [];
                                $color_index = 0;

                                // Create a dynamic styles array based on query results
                                while ($row = mysqli_fetch_assoc($result_program_table)) {
                                    $program = $row['patient_program'];
                                    if (!isset($program_table_styles[$program])) {
                                        $program_table_styles[$program] = [
                                            'color' => $colors[$color_index % count($colors)]
                                        ];
                                        $color_index++;
                                    }
                                }

                                // Reset the result pointer
                                mysqli_data_seek($result_program_table, 0);
                            ?>

                            <div class="col-md-4">
                                <?php if (mysqli_num_rows($result_program_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">PROGRAM DETAIL</h5>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <?php while ($row_program_table = mysqli_fetch_assoc($result_program_table)): 
                                                        $patient_program_table = htmlspecialchars($row_program_table['patient_program']);
                                                        $count_program_table = htmlspecialchars($row_program_table['program_table_count']);
                                                        $total_program_table_count += $row_program_table['program_table_count'];
                                                        $color_program_table = $program_table_styles[$patient_program_table]['color'];
                                                    ?>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                                        <div class="d-flex">
                                                            <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_program_table ?>"></i>
                                                            <div class="flex-grow-1 ms-2">
                                                                <p class="mb-0"><?= $patient_program_table ?></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 font-size-14">
                                                                <a href="drill_program.php?program=<?= urlencode($patient_program_table) ?>"><?= $count_program_table ?></a>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <?php endwhile; ?>

                                                    <!-- Total reports section -->
                                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                                        <div class="d-flex">
                                                            <div class="flex-grow-1">
                                                                <p class="mb-0"><strong>Total</strong></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_program_table_count) ?></strong></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php else: ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">PROGRAM DETAIL</h5>
                                                <a href="detail_month.php?month=<?= htmlspecialchars($selected_month) ?>#myTable" class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                                </a>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <p>No data available.</p>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php endif; ?>
                            </div> <!-- end col -->
                        </div> <!-- end of row -->




<!-- ========================================  BOOTSTRAP ROW:  HOURLY CHART   ========================================================= -->

<!-- PHP / SQL QUERY FOR REPORTS BY HOUR OF THE DAY -->
                        <?php
                            // PHP to set values for reports by HOUR OF THE DAY

                            $sql_report_hourly = "
                                SELECT 
                                    HOUR(occur_time) AS hour,
                                    COUNT(*) AS item_report_hourly
                                FROM 
                                    occur
                                GROUP BY 
                                    HOUR(occur_time)
                                ORDER BY 
                                    HOUR(occur_time)
                            ";

                            $result_report_hourly = mysqli_query($conn, $sql_report_hourly);
                            if (!$result_report_hourly) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }

                            $data_report_hourly = array_fill(0, 24, 0);

                            while ($row = mysqli_fetch_assoc($result_report_hourly)) {
                                $hour = (int)$row['hour'];
                                $count = (int)$row['item_report_hourly'];
                                $data_report_hourly[$hour] = $count;
                            }

                            $chart_data_js_report_hourly = json_encode(array_values($data_report_hourly));
                            $hour_labels = json_encode(range(0, 23));
                        ?>


                        <div class="row mt-4">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title me-2">REPORTS BY HOUR OF THE DAY</h5>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div id="chart_report_hourly" class="apex-charts" dir="ltr"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->

                        <script>
                            var chartData_report_hourly = <?php echo $chart_data_js_report_hourly; ?>;
                            var hourLabels = <?php echo $hour_labels; ?>;
                        </script>




<!-- ========================================  BOOTSTRAP ROW:  AREA TABLE / DAY OF THE WEEK CHART   ========================================================= -->


<!-- PHP / SQL QUERY AND HTML FOR "AREA" - COLUMN 1 ============================ -->

                        <?php
                            // Query to count the number of reports by area within the desired date range
                            $sql_area_table = "SELECT occur_area, COUNT(*) as area_table_count 
                                               FROM occur 
                                               GROUP BY occur_area
                                               ORDER BY area_table_count DESC";

                            // Execute the query
                            $result_area_table = mysqli_query($conn, $sql_area_table);

                            // Check for SQL errors
                            if (!$result_area_table) {
                                die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_area_table);
                            }

                            // Initialize total count variable
                            $total_area_table_count = 0;

                            // Predefined set of colors
                            $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

                            // Initialize the dynamic styles array
                            $area_table_styles = [];
                            $color_index = 0;

                            // Create a dynamic styles array based on query results
                            while ($row = mysqli_fetch_assoc($result_area_table)) {
                                $area = $row['occur_area'];
                                if (!isset($area_table_styles[$area])) {
                                    $area_table_styles[$area] = [
                                        'color' => $colors[$color_index % count($colors)]
                                    ];
                                    $color_index++;
                                }
                            }

                            // Reset the result pointer
                            mysqli_data_seek($result_area_table, 0);
                        ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?php if (mysqli_num_rows($result_area_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">AREA DETAIL</h5>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div class="row">
                                                    <?php while ($row_area_table = mysqli_fetch_assoc($result_area_table)): 
                                                        $occur_area_table = htmlspecialchars($row_area_table['occur_area']);
                                                        $count_area_table = htmlspecialchars($row_area_table['area_table_count']);
                                                        $total_area_table_count += $row_area_table['area_table_count'];
                                                        $color_area_table = $area_table_styles[$occur_area_table]['color'];
                                                    ?>
                                                    <div class="col-md-6">
                                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                                            <div class="d-flex">
                                                                <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_area_table ?>"></i>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <p class="mb-0"><?= $occur_area_table ?></p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0 font-size-14">
                                                                    <a href="drill_area.php?area=<?= urlencode($occur_area_table) ?>"><?= $count_area_table ?></a>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endwhile; ?>

                                                    <!-- Total reports section -->
                                                    <div class="col-md-6">
                                                        <div class="d-flex justify-content-between align-items-center pt-2">
                                                            <div class="d-flex">
                                                                <div class="flex-grow-1">
                                                                    <p class="mb-0"><strong>Total</strong></p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_area_table_count) ?></strong></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php else: ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">AREA DETAIL</h5>
                                                <a href="detail_month.php?month=<?= htmlspecialchars($selected_month) ?>#myTable" class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                                </a>
                                            </div>
                                            <br>
                                            <div class="mt-4 text-center">
                                                <div>
                                                    <p>No data available.</p>
                                                </div>
                                            </div>
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card -->
                                <?php endif; ?>
                            </div> <!-- end col -->


<!--  DAY OF THE WEEK CHART: COLUMN 2  ====================== -->

                            <?php
                            // PHP to set values for reports by DAY OF THE WEEK

                            $sql_report_daily = "
                                SELECT 
                                    DAYOFWEEK(occur_date) AS day_of_week,
                                    COUNT(*) AS item_report_daily
                                FROM 
                                    occur
                                GROUP BY 
                                    DAYOFWEEK(occur_date)
                                ORDER BY 
                                    DAYOFWEEK(occur_date)
                            ";

                            $result_report_daily = mysqli_query($conn, $sql_report_daily);
                            if (!$result_report_daily) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }

                            // Initialize an array with zeros for each day of the week (Sunday to Saturday)
                            $data_report_daily = array_fill(0, 7, 0);

                            while ($row = mysqli_fetch_assoc($result_report_daily)) {
                                $day_of_week = (int)$row['day_of_week'] - 1; // Adjust to zero-indexed (Sunday=0, Monday=1, ...)
                                $count = (int)$row['item_report_daily'];
                                $data_report_daily[$day_of_week] = $count;
                            }

                            // Prepare JavaScript-friendly data
                            $chart_data_js_report_daily = json_encode(array_values($data_report_daily));
                            $day_labels = json_encode(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
                            ?>

                            <!-- RENDER CHART: REPORTS BY DAY OF THE WEEK -->

                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title me-2">REPORTS BY DAY OF THE WEEK</h5>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div id="chart_report_daily" class="apex-charts" dir="ltr"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <script>
                            var chartData_report_daily = <?php echo $chart_data_js_report_daily; ?>;
                            var dayLabels = <?php echo $day_labels; ?>;
                        </script>


     
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



<!-- ========================================  PAGE SPECIFIC SCRIPTS / GRAPHS   ========================================================== -->


<!-- CONFIGURE DATATABLES / SET DEFAULT SORT COLUMN/ORDER  --------------------->

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [[ 1, "desc" ]] // Order by the first column (ID) in descending order
            });
        });
    </script>


<!-- CONFIGURE CATEGORY CHART ----------------------------------------->

    <script>
       // Function to aggregate data
    function aggregateData(data) {
        return data.reduce((acc, item) => {
            if (!acc[item.reporter_category]) {
                acc[item.reporter_category] = 0;
            }
            acc[item.reporter_category] += parseInt(item.count);
            return acc;
        }, {});
    }

    // Initial data aggregation
    let initialAggregatedData = aggregateData(chartData);
    let initialCategories = Object.keys(initialAggregatedData);
    let initialCounts = Object.values(initialAggregatedData);

    var options = {
        series: [{
            name: 'Reports Count',
            data: initialCounts
        }],
        chart: {
            type: 'bar',
            width: '100%',
            height: '400px',
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    var selectedIndex = config.dataPointIndex;
                    var selectedCategory = initialCategories[selectedIndex];
                    window.location.href = 'dashboard_category.php?reporter_category=' + encodeURIComponent(selectedCategory);
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                borderRadiusApplication: 'end',
                horizontal: true,
                barHeight: '70%',
                barGap: '10%'
            }
        },
        colors: ['#00b33c'],
        xaxis: {
            categories: initialCategories
        },
        title: {
            text: '',
            align: 'top',
            margin: 0
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart_category"), options);
    chart.render();

    function updateChart(range) {
        let newData;
        const currentDate = new Date();
        
        switch (range) {
            case 'all':
                newData = chartData;
                break;
            case '12mo':
                newData = chartData.filter(item => {
                    if (item.occur_date) {
                        let itemDate = new Date(item.occur_date + 'T00:00:00');
                        const oneYearAgo = new Date(currentDate);
                        oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
                        return itemDate >= oneYearAgo;
                    }
                    return false;
                });
                break;
            case 'mtd':
                newData = chartData.filter(item => {
                    if (item.occur_date) {
                        let itemDate = new Date(item.occur_date + 'T00:00:00');
                        return itemDate.getMonth() === currentDate.getMonth() && 
                               itemDate.getFullYear() === currentDate.getFullYear();
                    }
                    return false;
                });
                break;
            case '24hr':
                newData = chartData.filter(item => {
                    if (item.occur_date) {
                        let itemDate = new Date(item.occur_date + 'T00:00:00');
                        const last24Hours = new Date(currentDate.getTime() - 24 * 60 * 60 * 1000);
                        return itemDate >= last24Hours;
                    }
                    return false;
                });
                break;
            default:
                newData = [];
        }

        // Aggregate data by category
        let aggregatedData = aggregateData(newData);
        let categories = Object.keys(aggregatedData);
        let counts = Object.values(aggregatedData);

        // Update the global variables so dataPointSelection uses the correct indices
        initialCategories = categories;
        initialCounts = counts;

        if (counts.length > 0) {
            chart.updateSeries([{
                name: 'Reports Count',
                data: counts
            }]);
            chart.updateOptions({
                xaxis: {
                    categories: categories
                }
            });
        } else {
            chart.updateSeries([{
                name: 'Reports Count',
                data: []
            }]);
            chart.updateOptions({
                xaxis: {
                    categories: []
                }
            });
        }

        // Update button styles
        updateButtonStyles(range);
    }

    function updateButtonStyles(activeRange) {
        const buttons = document.querySelectorAll('button[data-range]');
        buttons.forEach(button => {
            button.classList.remove('btn-primary', 'btn-light');
            if (button.getAttribute('data-range') === activeRange) {
                button.classList.add('btn-primary');
            } else {
                button.classList.add('btn-light');
            }
        });
    }

    // Attach button click events after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('button[data-range]');
        buttons.forEach(button => {
            const range = button.getAttribute('data-range');
            button.addEventListener('click', () => updateChart(range));
        });

        // Set initial active button and chart data
        updateButtonStyles('all');
        updateChart('all');
    });
    </script>


<!-- CONFIGURE COLUMN CHART / REPORTS BY MONTH  ----------------------------------------->

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


<!-- CONFIGURE CHART OF REPORTS BY HOUR OF THE DAY ----------------------------------- -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var options_report_hourly = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Reports',
                data: chartData_report_hourly
            }],
            xaxis: {
                categories: hourLabels,
                title: {
                    text: 'Hour of Day'
                },
                labels: {
                    formatter: function(value) {
                        return value + ':00';
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Reports'
                }
            },
            title: {
                text: 'Reports by Hour of the Day',
                align: 'center'
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + ' reports';
                    }
                }
            },
            colors: ['#008FFB']  // Single color for all bars
        };

        var chart_report_hourly = new ApexCharts(document.querySelector("#chart_report_hourly"), options_report_hourly);
        chart_report_hourly.render();
    });
    </script>


<!-- CONFIGURE CHART OF REPORTS BY DAY OF THE WEEK ----------------------------------- -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var options_report_daily = {
            chart: {
                type: 'bar',
                height: 400
            },
            series: [{
                name: 'Reports',
                data: chartData_report_daily
            }],
            xaxis: {
                categories: dayLabels,
                title: {
                    text: 'Day of the Week'
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Reports'
                }
            },
            title: {
                text: 'Reports by Day of the Week',
                align: 'center'
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + ' reports';
                    }
                }
            },
            colors: ['#008FFB']  // Single color for all bars
        };

        var chart_report_daily = new ApexCharts(document.querySelector("#chart_report_daily"), options_report_daily);
        chart_report_daily.render();
    });
    </script>



<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>







