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
// To get the variable passed from bar chart click
    if (isset($_GET['month'])) {
    // Retrieve the month passed in the URL (e.g., '2023-07')
    $selected_month = $_GET['month'];

// Calculate the start date (first day of the selected month)
    $start_date = $selected_month . '-01';
// Calculate the end date (first day of the next month)
    $end_date = date('Y-m-d', strtotime("$start_date +1 month"));
?>


<!-- ============================================  PAGE FORMATTING  ================================================================= -->
<!-- Start right Content here -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- ============================================   PAGE TITLE  ======================================================================== -->
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
                                        <h5 class="mb-sm-0 fw-bold"></h5>
                                        <br>
                                        <!-- Add the first day to create a valid date -->
                                        <?php $formatted_month_name = date("F Y", strtotime($selected_month . "-01")); ?> 
                                        <h4> <?php echo $formatted_month_name; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->



<!-- ========================================  TOP ROW - 4 WIDGETS / CARDS  =================================================================== -->



<!-- PHP / SQL QUERY AND HTML FOR "TOTAL REPORTS: MONTH" - COLUMN 1 ============================ -->

            <br>
            <?php 
            //Query to count # of reports submitted in the selected month
            //Note:  $start_date and $end date are defined at the top of the page along with the retrieval of month for $selected_month

                $sql = "SELECT COUNT(*) AS item_count
                        FROM occur
                        WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";
                
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
                                           <a href="detail_month.php?month=<?= $selected_month ?>#myTable">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-folder-star text-primary"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">TOTAL REPORTS</p>
                                            <a href="detail_month.php?month=<?= $selected_month ?>#myTable">
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


<!-- PHP / SQL QUERY AND HTML FOR HIGH SEVERITY -  COLUMN 2 ============================ -->

            <?php 
            //Query to count # of reports submitted but not reviewed by RM
                $sql =  "SELECT COUNT(*) AS item_count
                         FROM occur
                         WHERE rm_severity IN ('Severe', 'Sentinel')
                         AND occur_date >= '$start_date' AND occur_date < '$end_date'
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
                                           <a href="drill_high_severity.php?month=<?= $selected_month;?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-alert-circle text-danger"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">HIGH SEVERITY</p>
                                              <a href="drill_high_severity.php?month=<?= $selected_month;?>">
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

<!-- PHP / SQL QUERY AND HTML FOR XXXXXX  -  COLUMN 3 ============================ -->

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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">INJURIES</p>
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


<!-- PHP / SQL QUERY AND HTML FOR XXXXXX  -  COLUMN 4 ============================ -->

<?php 
//Query to count # of reports submitted but not reviewed by RM
            $sql =  "SELECT COUNT(*) AS item_count
                     FROM occur
                     WHERE occur_status != 'Closed'
                     AND occur_date >= '$start_date' AND occur_date < '$end_date'
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">OPEN REPORTS</p>
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


<!-- ========================================  BOOTSTRAP ROW: 2 TWO COLUMNS OF CHARTS/TABLES   =================================================================== -->


<!-- PHP / SQL QUERY FOR CATEGORY CHART  -  COLUMN 1  ============================ -->

         <?php
// To get the variable passed from bar chart click
if (isset($_GET['month'])) {
    // Retrieve the month passed in the URL (e.g., '2023-07')
    $selected_month = $_GET['month'];

    // Create DateTime objects for selected month and prior month
    $selected_month_dt = DateTime::createFromFormat('Y-m', $selected_month);
    $prior_month_dt = clone $selected_month_dt;
    $prior_month_dt->modify('-1 month');
    $prior_month = $prior_month_dt->format('Y-m');

    // Query to count the number of reports by category for selected and prior month
    $sql = "SELECT reporter_category, COUNT(*) AS count, DATE_FORMAT(occur_date, '%Y-%m') AS month
            FROM occur
            WHERE DATE_FORMAT(occur_date, '%Y-%m') IN ('$selected_month', '$prior_month')
            GROUP BY reporter_category, month";

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
}
?>

<!-- Pass data to JavaScript so ApexCharts can use it -->
<script> 
    var chartData_category_prior_mo = <?php echo json_encode($data); ?>;
</script>



<!-- RENDER CATEGORY CHART  ================ -->

                      <!-- RENDER CATEGORY CHART  ================ -->
<div class="row">
    <!-- CATEGORY CHART -->
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body pb-2">
                <!-- Header Section -->
                <div class="d-flex flex-wrap align-items-center mb-2">
                    <h5 class="card-title me-2">REPORTS BY CATEGORY: PRIOR MONTH COMPARISON</h5>
                    <!-- Optional Controls -->
                    <div class="ms-auto">
                        <!-- You can add controls here if needed -->
                    </div>
                </div>
                <br>
                <!-- Chart Container -->
                <div id="chart_category_prior_mo" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->




<!-- PHP / SQL QUERY FOR PROGRAM TABLE -  COLUMN 2 ============================ -->

                            <div class="col-xl-3">
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title me-2">REPORTS BY PROGRAM</h5>
                                            <div class="ms-auto">
                                                <div>
                                                <!--
                                                    <button type="button" class="btn btn-warning btn-sm" onclick="updateChart('ALL')">ALL</button>
                                                    <button type="button" class="btn btn-light btn-sm" onclick="updateChart('Patient Care')">PT CARE</button>
                                                    <button type="button" class="btn btn-light btn-sm" onclick="updateChart('Safety')">SAFETY</button>
                                                    <button type="button" class="btn btn-light btn-sm" onclick="updateChart('Other')">OTHER</button>
                                                -->
                                                </div>
                                            </div>
                                        </div>
                                        <br>

<!-- PHP FOR TABLE CONTENT  ==================================================== -->

                                        <?php
                                            $sql_program_table =   "SELECT patient_program, COUNT(*) as program_table_count 
                                                                    FROM occur 
                                                                    WHERE occur_date >= '$start_date' AND occur_date < '$end_date'
                                                                    GROUP BY patient_program";
                        
                                            $result_program_table = mysqli_query($conn, $sql_program_table);

                                            if (!$result_program_table) {
                                                die("Query failed: " . mysqli_error($conn));
                                            }

                                            $total_program_count = 0;
                                            if (mysqli_num_rows($result_program_table) > 0) {
                                                echo "<table class='table table-striped table-bordered table-sm'>";
                                                echo "<thead class='bg-warning text-light'><tr><th>Category</th><th>Total Reports</th></tr></thead>";
                                                echo "<tbody>";
                                                while ($row_program_table = mysqli_fetch_assoc($result_program_table)) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row_program_table['patient_program']) . "</td>";
                                                    echo "<td><a href='drill_program.php?patient_program=" . urlencode($row_program_table['patient_program']) . "'>" . htmlspecialchars($row_program_table['program_table_count']) . "</a></td>";
                                                    echo "</tr>";
                                                    $total_program_count += $row_program_table['program_table_count'];
                                                }
                                                echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_program_count) . "</strong></td></tr>";
                                                echo "</tbody></table>";
                                            } else {
                                                echo "No data available.";
                                            }
                                        ?>

                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <br><br>


<!-- ========================================  BOOTSTRAP ROW: CHARTS - FULL 12 COLUMN  =================================================================== -->


<!-- PHP / SQL QUERY FOR REPORTS BY DAY OF THE MONTH  -   ====================== -->


            <?php
            //PHP to set values for reports by DAY OF THE MONTH

                // Calculate the first and last days of the selected month
                $first_day_of_month = $selected_month . '-01';
                $last_day_of_month = date('Y-m-t', strtotime($first_day_of_month)); // 'Y-m-t' gives the last day of the month
                
                // Calculate the number of days in the selected month
                $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($selected_month)), date('Y', strtotime($selected_month)));

                // Generate day numbers
                $day_numbers = range(1, $days_in_month);
                $day_numbers_js = json_encode($day_numbers);

                // Query to count # of reports by day and type for the selected month
                $sql_report_daily = "
                    SELECT 
                        DATE_FORMAT(occur_date, '%Y-%m-%d') AS day_date,
                        occur_type,
                        COUNT(*) AS item_report_daily
                    FROM 
                        occur
                    WHERE 
                        occur_date BETWEEN '$first_day_of_month' AND '$last_day_of_month'
                    GROUP BY 
                        DATE_FORMAT(occur_date, '%Y-%m-%d'), occur_type
                    ORDER BY 
                        DATE_FORMAT(occur_date, '%Y-%m-%d')
                ";

                // Execute the query
                $result_report_daily = mysqli_query($conn, $sql_report_daily);
                if (!$result_report_daily) {
                    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                }

                // Initialize data array with zeros for all days
                $data_report_daily = [];
                foreach ($day_numbers as $day) {
                    $data_report_daily[$day] = [
                        'ALL' => 0,
                        'Patient Care' => 0,
                        'Safety' => 0,
                        'Other' => 0
                    ];
                }

                // Fill in the data array with actual counts from the query
                while ($row = mysqli_fetch_assoc($result_report_daily)) {
                    $date = $row['day_date'];
                    $day = (int)date('j', strtotime($date)); // Get day number without leading zeros
                    $type = $row['occur_type'];
                    $count = (int)$row['item_report_daily'];
                    
                    // Map database values to our categories
                    $category = 'Other';
                    if ($type == 'Patient Care' || $type == 'PT CARE') {
                        $category = 'Patient Care';
                    } elseif ($type == 'Safety' || $type == 'SAFETY') {
                        $category = 'Safety';
                    }
                    
                    $data_report_daily[$day][$category] += $count;
                    $data_report_daily[$day]['ALL'] += $count;
                }

                // Debugging: Output the data to ensure it is populated correctly
                // echo "<pre>";
                // echo "day_numbers:\n";
                // print_r($day_numbers);
                // echo "</pre>";

                // echo "<pre>";
                // echo "data_report_daily:\n";
                // print_r($data_report_daily);
                // echo "</pre>";

                // Prepare data for ApexCharts
                $chart_data_report_daily = [
                    'ALL' => [],
                    'Patient Care' => [],
                    'Safety' => [],
                    'Other' => []
                ];

                foreach ($day_numbers as $day) {
                    foreach (['ALL', 'Patient Care', 'Safety', 'Other'] as $type) {
                        $chart_data_report_daily[$type][] = $data_report_daily[$day][$type];
                    }
                }

                // echo "<pre>";
                // echo "chart_data_report_daily:\n";
                // print_r($chart_data_report_daily);
                // echo "</pre>";

                $chart_data_js_report_daily = json_encode($chart_data_report_daily);

                // Debugging: Output the JSON-encoded variables
                echo "<script>";
                echo "console.log('day_numbers_js:', " . $day_numbers_js . ");";
                echo "console.log('chart_data_js_report_daily:', " . $chart_data_js_report_daily . ");";
                echo "</script>";
            } else {
                echo "<p>No month selected.</p>";
                exit;
            }
            ?>


<!-- RENDER CHART:  REPORTS BY DAY OF THE MONTH  -   ====================== -->

                                                
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body pb-2">
                                        <div class="d-flex flex-wrap align-items-center mb-2">
                                            <h5 class="card-title me-2">REPORTS BY DAY FOR <?php echo date('F Y', strtotime($selected_month)); ?></h5>
                                            <div class="ms-auto">
                                                <div>
                                                    <!-- Updated buttons with data-filter-report-daily attributes -->
                                                    <button type="button" class="btn btn-primary btn-sm" data-filter-report-daily="ALL">ALL</button>
                                                    <button type="button" class="btn btn-light btn-sm" data-filter-report-daily="Patient Care">PT CARE</button>
                                                    <button type="button" class="btn btn-light btn-sm" data-filter-report-daily="Safety">SAFETY</button>
                                                    <button type="button" class="btn btn-light btn-sm" data-filter-report-daily="Other">OTHER</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <!-- Chart Container -->
                                                        <div id="chart_report_daily" class="apex-charts" dir="ltr"></div>
                                                    </div> <!-- end card-body -->
                                                </div> <!-- end card -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
                                    </div> <!-- end card-body container -->
                                </div> <!-- end card container -->
                            </div> <!-- end column container -->
                        </div> <!-- end row -->


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->



<!-- PHP / SQL QUERY FOR DATATABLE  ============================ -->


            <?php 

            // SQL query to get data for the selected month
            $sql_month_detail = "
                SELECT * FROM occur
                WHERE DATE_FORMAT(occur_date, '%Y-%m') = '$selected_month'
            ";

            // Execute the query
            $result_month_detail = mysqli_query($conn, $sql_month_detail);
            if (!$result_month_detail) {
                die("Query failed: " . mysqli_error($conn));
            }

            // Get the number of rows in the result (must be before the loop)
            $numrows_month_detail = mysqli_num_rows($result_month_detail);

            // Loop through the results and display them in the table
            ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Reports Submitted:&nbsp&nbsp<?php echo $reporter_category ?></h4>
                                <p class="card-title-desc"></p>
                                <br>
                
                                <!-- Disable date filters -- whole page is set to selected month -->
                                <!-- Date filter inputs 
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
                                -->
                                


<!-- DATATABLE  ================================================ -->

                                <table id="myTable" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Pt LName</th>
                                            <th>MRN</th>
                                            <th>Age</th>
                                            <th>M/F</th>
                                            <th>Unit</th>
                                            <th>Category</th>
                                            <th>Severity</th>
                                            <th>Status</th>
                                            <th>Restraint</th>
                                            <th>Seclusion</th>
                                            
                                            <th>Description</th>
                                            <th>Intervention</th>
                                        </tr>
                                    </thead>        

                                    <tbody>
                                    <?php
                                        for ($i = 0; $i < $numrows_month_detail; $i++) {
                                            $row_month_detail = mysqli_fetch_array($result_month_detail);

                                            echo "<tr>";
                                            echo "<td>";
                                                if (!empty($row_month_detail['occur_date'])) {
                                                    echo date("m/d/y", strtotime($row_month_detail['occur_date']));
                                                } else {
                                                    echo ""; // Output blank if the value is null or empty
                                                }
                                            echo "</td>";
                                            echo "<td><a href='edit_occur.php?id={$row_month_detail['occur_id']}'>{$row_month_detail['occur_id']}</a></td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['occur_type']}</td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['patient_last_name']}</td>";
                                            echo "<td>{$row_month_detail['patient_MRN']}</td>";
                                            echo "<td>{$row_month_detail['patient_age']}</td>";
                                            echo "<td>{$row_month_detail['patient_gender']}</td>";
                                            echo "<td>{$row_month_detail['patient_unit']}</td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['reporter_category']}</td>";
                                            echo "<td>{$row_month_detail['rm_severity']}</td>";
                                            echo "<td>{$row_month_detail['rm_status']}</td>";
                                            echo "<td>{$row_month_detail['occur_patient_restraint']}</td>";
                                            echo "<td>{$row_month_detail['occur_patient_seclusion']}</td>";

                                           

                                            echo "<td style='white-space:nowrap'>{$row_month_detail['occur_description']}</td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['occur_intervention']}</td>";

                                            echo "</tr>";
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- close row -->


         
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

    
<!-- ========================================  PAGE SPECIFIC SCRIPTS: CHARTS AND TABLES   ========================================================== -->


<!-- DATATABLES / SET DEFAULT SORT COLUMN/ORDER ======================= -->
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [[ 1, "desc" ]] // Order by the first column (ID) in descending order
        });
    });
</script>



<!-- REPORTS BY DAY OF THE MONTH ======================================= -->
<script>
// Parse the PHP data
var formattedDays = <?php echo $day_numbers_js; ?>;
var chartData_report_daily = <?php echo $chart_data_js_report_daily; ?>;

// Debugging: Log the parsed data
// console.log('formattedDays:', formattedDays);
// console.log('chartData_report_daily:', chartData_report_daily);

document.addEventListener('DOMContentLoaded', function() {
    console.log('Chart container exists:', document.querySelector("#chart_report_daily") !== null);

    // Chart options
    var options_report_daily = {
        chart: {
            type: 'bar',
            height: 400
        },
        series: [{
            name: 'Reports',
            data: chartData_report_daily['ALL']
        }],
        xaxis: {
            categories: formattedDays,
            title: {
                text: 'Day of Month'
            },
            labels: {
                formatter: function(value) {
                    return value;  // value is already the day number
                }
            }
        },
        yaxis: {
            title: {
                text: 'Count'
            }
        },
        title: {
            text: 'Reports by Day - ALL',
            align: 'center'
        },
        tooltip: {
            x: {
                formatter: function(value) {
                    return 'Day ' + value;
                }
            }
        }
    };

    // Debugging: Log the chart options
    // console.log('Chart options:', options_report_daily);

    var chart_report_daily = new ApexCharts(document.querySelector("#chart_report_daily"), options_report_daily);
    chart_report_daily.render().catch(function(err) {
        console.error('Error rendering chart:', err);
    });
    console.log('Chart initialized and render attempted');

    // Function to update the chart
    function updateChart_report_daily(filter) {
        chart_report_daily.updateOptions({
            title: {
                text: 'Reports by Day - ' + filter
            },
            series: [{
                name: 'Reports',
                data: chartData_report_daily[filter]
            }]
        });

        // Update button styles
        updateButtonStyles_report_daily(filter);
    }

    // Function to update button styles
    function updateButtonStyles_report_daily(activeFilter) {
        var buttons = document.querySelectorAll('button[data-filter-report-daily]');
        buttons.forEach(function(button) {
            var buttonFilter = button.getAttribute('data-filter-report-daily');
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
    var buttons = document.querySelectorAll('button[data-filter-report-daily]');
    buttons.forEach(function(button) {
        var filter = button.getAttribute('data-filter-report-daily');
        button.addEventListener('click', function() {
            updateChart_report_daily(filter);
        });
    });

    // Set initial active button
    updateButtonStyles_report_daily('ALL');
});
</script>



<!-- SCRIPT FOR CATEGORY CHART =========================================== -->
<script>
    // Assuming chartData_category_prior_mo is already defined and contains the data passed from PHP
    // e.g., var chartData_category_prior_mo = <?php echo json_encode($data); ?>;

    // Function to format month strings to "MMM-YY"
    function formatMonth_category_prior_mo(monthString) {
        // Parse the month string 'YYYY-MM' into a Date object
        var parts = monthString.split('-');
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10) - 1; // Months are zero-based in JavaScript Date

        var date = new Date(year, month);
        var options = { month: 'short' };
        var monthName = date.toLocaleString('en-US', options);
        var yearShort = date.getFullYear().toString().substr(-2);

        return monthName + '-' + yearShort;
    }

    // Function to process data and prepare it for ApexCharts
    function processChartData_category_prior_mo(chartData_category_prior_mo) {
        var categories_category_prior_mo = [];
        var seriesData_category_prior_mo = {};
        var months_category_prior_mo = [];

        chartData_category_prior_mo.forEach(function(item) {
            var category = item.reporter_category;
            var month = item.month; // 'YYYY-MM' format
            var count = parseInt(item.count);

            if (!categories_category_prior_mo.includes(category)) {
                categories_category_prior_mo.push(category);
            }

            if (!months_category_prior_mo.includes(month)) {
                months_category_prior_mo.push(month);
            }

            if (!seriesData_category_prior_mo[month]) {
                seriesData_category_prior_mo[month] = {};
            }

            seriesData_category_prior_mo[month][category] = count;
        });

        // Sort months chronologically
        months_category_prior_mo.sort();

        // Format months and create a mapping to original month strings
        var formattedMonths_category_prior_mo = months_category_prior_mo.map(function(month) {
            return formatMonth_category_prior_mo(month);
        });

        // Mapping between formatted months and original months
        var monthMapping_category_prior_mo = {};
        formattedMonths_category_prior_mo.forEach(function(formattedMonth, index) {
            monthMapping_category_prior_mo[formattedMonth] = months_category_prior_mo[index];
        });

        // Prepare series data with formatted month names
        var series_category_prior_mo = months_category_prior_mo.map(function(month, index) {
            return {
                name: formattedMonths_category_prior_mo[index], // Use formatted month
                data: categories_category_prior_mo.map(function(category) {
                    return seriesData_category_prior_mo[month][category] || 0;
                })
            };
        });

        return {
            categories: categories_category_prior_mo,
            series: series_category_prior_mo,
            months: months_category_prior_mo,
            formattedMonths: formattedMonths_category_prior_mo,
            monthMapping: monthMapping_category_prior_mo
        };
    }

    // Process the chart data
    var processedData_category_prior_mo = processChartData_category_prior_mo(chartData_category_prior_mo);

    // Chart options
    var options_category_prior_mo = {
        chart: {
            type: 'bar',
            height: 400,
            width: '100%',
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    var selectedCategoryIndex = config.dataPointIndex;
                    var selectedSeriesIndex = config.seriesIndex;
                    var selectedCategory = processedData_category_prior_mo.categories[selectedCategoryIndex];
                    var selectedFormattedMonth = processedData_category_prior_mo.series[selectedSeriesIndex].name;
                    var selectedMonth = processedData_category_prior_mo.monthMapping[selectedFormattedMonth]; // Original month format

                    window.location.href = 'bar_detail_category.php?reporter_category=' + encodeURIComponent(selectedCategory) + '&month=' + encodeURIComponent(selectedMonth);
                }
            }
        },
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '70%',
                barGap: '10%'
            }
        },
        series: processedData_category_prior_mo.series,
        xaxis: {
            categories: processedData_category_prior_mo.categories
        },
        colors: ['#00b33c', '#007bff'], // Adjust colors as needed
        title: {
            text: 'Reports by Category for ' + processedData_category_prior_mo.formattedMonths.join(' and '),
            align: 'center'
        },
        legend: {
            position: 'bottom',            // Move legend to bottom
            horizontalAlign: 'center'      // Center the legend horizontally
        }
    };

    var chart_category_prior_mo = new ApexCharts(document.querySelector("#chart_category_prior_mo"), options_category_prior_mo);
    chart_category_prior_mo.render();
</script>








<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




