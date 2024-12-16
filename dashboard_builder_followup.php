<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<!-- POPULATE DROP DOWNS AT TOP  ================================================================================================== -->

<?php
        //Filter set up:  Get unique values for drop downs
            // Query to get unique months from the occur_date field  ----------------------------------
                $sql_months = "SELECT DISTINCT DATE_FORMAT(occur_date, '%Y-%m') AS month 
                               FROM occur 
                               ORDER BY month DESC";

                $result_months = mysqli_query($conn, $sql_months);

                if (!$result_months) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the months into an array
                $months = [];
                while ($row = mysqli_fetch_assoc($result_months)) {
                    $months[] = $row['month'];
                }

                // If no month is selected, default to the most recent month
                if (isset($_GET['month'])) {
                    // Retrieve the month passed in the URL 
                    $selected_month = $_GET['month'];
                } else {
                    // If not set, use the current month in 'YYYY-MM' format
                    $selected_month = date('Y-m');
                }

                // Calculate the start and end dates as before
                $start_date = $selected_month . '-01';
                $end_date = date('Y-m-d', strtotime("$start_date +1 month"));


            // Query to get unique locations from the occur_location field ----------------------------------
                $sql_locations = "SELECT DISTINCT occur_location 
                                  FROM occur 
                                  WHERE occur_location IS NOT NULL AND occur_location != ''
                                  ORDER BY occur_location ASC";

                $result_locations = mysqli_query($conn, $sql_locations);

                if (!$result_locations) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the locations into an array
                $locations = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_locations)) {
                    $locations[] = $row['occur_location'];
                }

                // Set default to 'All' if no location is selected or if the selected location is invalid
                $selected_location = isset($_GET['location']) && in_array($_GET['location'], $locations) ? $_GET['location'] : 'All';


            // Query to get unique patient level of care from the patient_loc field ----------------------------------
                $sql_locs = "SELECT DISTINCT patient_loc 
                             FROM occur 
                             WHERE patient_loc IS NOT NULL AND patient_loc != ''
                             ORDER BY patient_loc ASC";

                $result_locs = mysqli_query($conn, $sql_locs);

                if (!$result_locs) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the patient locations into an array
                $locs = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_locs)) {
                    $locs[] = $row['patient_loc'];
                }

                // Set default to 'All' if no patient location is selected or if the selected location is invalid
                $selected_loc = isset($_GET['patient_loc']) && in_array($_GET['patient_loc'], $locs) ? $_GET['patient_loc'] : 'All';


            // Query to get unique categories from the reporter_category field ----------------------------------
                $sql_category = "SELECT DISTINCT reporter_category 
                                  FROM occur 
                                  WHERE reporter_category IS NOT NULL AND reporter_category != ''
                                  ORDER BY reporter_category ASC";

                $result_category = mysqli_query($conn, $sql_category);

                if (!$result_category) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the categories into an array
                $categories = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_category)) {
                    $categories[] = $row['reporter_category'];
                }

                // Set default to 'All' if no category is selected or if the selected category is invalid
                $selected_category = isset($_GET['category']) && in_array($_GET['category'], $categories) ? $_GET['category'] : 'All';

            // Query to get unique severities from the rm_severity field ----------------------------------
                $sql_severities = "SELECT DISTINCT rm_severity 
                                   FROM occur 
                                   WHERE rm_severity IS NOT NULL AND rm_severity != ''
                                   ORDER BY rm_severity ASC";

                $result_severities = mysqli_query($conn, $sql_severities);

                if (!$result_severities) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the severities into an array
                $severities = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_severities)) {
                    $severities[] = $row['rm_severity'];
                }

                // Set default to 'All' if no severity is selected or if the selected severity is invalid
                $selected_severity = isset($_GET['rm_severity']) && in_array($_GET['rm_severity'], $severities) ? $_GET['rm_severity'] : 'All';


            // Query to get unique patient programs from the patient_program field --------------------------------------
                $sql_programs = "SELECT DISTINCT patient_program 
                                 FROM occur 
                                 WHERE patient_program IS NOT NULL AND patient_program != ''
                                 ORDER BY patient_program ASC";

                $result_programs = mysqli_query($conn, $sql_programs);

                if (!$result_programs) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the patient programs into an array
                $programs = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_programs)) {
                    $programs[] = $row['patient_program'];
                }

                // Set default to 'All' if no patient program is selected or if the selected program is invalid
                $selected_program = isset($_GET['patient_program']) && in_array($_GET['patient_program'], $programs) ? $_GET['patient_program'] : 'All';

            // Query to get unique patient units from the patient_unit field ------------------------------------------------
                $sql_units = "SELECT DISTINCT patient_unit 
                              FROM occur 
                              WHERE patient_unit IS NOT NULL AND patient_unit != ''
                              ORDER BY patient_unit ASC";

                $result_units = mysqli_query($conn, $sql_units);

                if (!$result_units) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the patient units into an array
                $units = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_units)) {
                    $units[] = $row['patient_unit'];
                }

                // Set default to 'All' if no patient unit is selected or if the selected unit is invalid
                $selected_unit = isset($_GET['patient_unit']) && in_array($_GET['patient_unit'], $units) ? $_GET['patient_unit'] : 'All';



            // Query to get unique flags from the occur_flag field
                $sql_flags = "SELECT DISTINCT occur_flag 
                              FROM occur 
                              WHERE occur_flag IS NOT NULL AND occur_flag != ''
                              ORDER BY occur_flag ASC";

                $result_flags = mysqli_query($conn, $sql_flags);

                if (!$result_flags) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the flags into an array
                $flags = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_flags)) {
                    // Since occur_flag may contain multiple values, split them and merge into the array
                    $individual_flags = explode(',', $row['occur_flag']); // Assuming flags are comma-separated
                    foreach ($individual_flags as $flag) {
                        $flag = trim($flag); // Trim any extra whitespace
                        if (!in_array($flag, $flags)) {
                            $flags[] = $flag; // Add unique flag values to the array
                        }
                    }
                }

                // Set default to 'All' if no flag is selected or if the selected flag is invalid
                $selected_flag = isset($_GET['flag']) && in_array($_GET['flag'], $flags) ? $_GET['flag'] : 'All';





            // Query to get unique areas from the occur_area field
                $sql_areas = "SELECT DISTINCT occur_area 
                              FROM occur 
                              WHERE occur_area IS NOT NULL AND occur_area != ''
                              ORDER BY occur_area ASC";

                $result_areas = mysqli_query($conn, $sql_areas);

                if (!$result_areas) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Fetch the areas into an array
                $areas = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_areas)) {
                    $areas[] = $row['occur_area'];
                }

                // Set default to 'All' if no area is selected or if the selected area is invalid
                $selected_area = isset($_GET['occur_area']) && in_array($_GET['occur_area'], $areas) ? $_GET['occur_area'] : 'All';

        // Clear any existing filters first
            unset($_SESSION['filters']);

        // Set session filters for use on drill-down pages
            if (isset($_GET['month']) || isset($_GET['location']) || isset($_GET['patient_loc']) || 
                isset($_GET['patient_program']) || isset($_GET['patient_unit']) || 
                isset($_GET['occur_area']) || isset($_GET['category']) || 
                isset($_GET['rm_severity']) || isset($_GET['flag'])) {

                $_SESSION['filters'] = [
                    'month' => $selected_month,
                    'location' => $selected_location,
                    'loc' => $selected_loc,
                    'program' => $selected_program,
                    'unit' => $selected_unit,
                    'md' => $selected_md,
                    'area' => $selected_area, 
                    //'mgr' => $selected_mgr,
                    'category' => $selected_category,
                    'severity' => $selected_severity, // Added severity here
                    'flag' => $selected_flag // Added flag here
                ];
            }
?>


<!-- CSS:  MANUAL STYLING FOR DATATABLE / CUSTOM BUTTON  ======================================================================================== -->

        <style>
            table.dataTable thead th {
              background-color: #f2f2f2;
            }
            table.dataTable tbody td {
              color: #555;
            }
        </style>

        <style> 
            .btn-xs {
                padding: 0.25rem 0.4rem; /* Adjust padding as needed */
                font-size: 0.75rem;      /* Adjust font size as needed */
                border-radius: 0.2rem;   /* Optional: Adjust border radius */
            }

            /* Optional: Adjust icon size within the button */
            .btn-xs .mdi {
                font-size: 0.8rem; /* Smaller icon size */
            }
        </style>


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
                                    <h4 class="mb-sm-0 font-size-16 fw-bold"><?php echo $page_title ?></h4>
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


                    <div class="row mt-4">
                        <div class="col-md-12 col-xl-12">
                            <div class="card" style="">
                                <div class="card-body text-center text-black <div class= alert alert-info" style="background-color: #DCDCDC;">
                                    <h5 class="mb-sm-0 fw-bold"></h5>
                                    <h4>
                                        FOLLOW UP / ACTION PLAN REVIEW
                                    </h4>
                                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-4">

                                        <!-- Location Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryLocationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Location:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_location); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryLocationDropdown">
                                                <?php foreach ($locations as $location): ?>
                                                    <a class="dropdown-item <?php echo ($location == $selected_location) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['location' => $location])); ?>">
                                                        <?php echo htmlspecialchars($location); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Level of Care Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryLocDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Level of Care:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_loc); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryLocDropdown">
                                                <?php foreach ($locs as $loc): ?>
                                                    <a class="dropdown-item <?php echo ($loc == $selected_loc) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['patient_loc' => $loc])); ?>">
                                                        <?php echo htmlspecialchars($loc); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Category Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryCategoryDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Category:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_category); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryCategoryDropdown">
                                                <?php foreach ($categories as $category): ?>
                                                    <a class="dropdown-item <?php echo ($category == $selected_category) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['category' => $category])); ?>">
                                                        <?php echo htmlspecialchars($category); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        

                                        <!-- Program Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryProgramDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Program:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_program); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryProgramDropdown">
                                                <?php foreach ($programs as $program): ?>
                                                    <a class="dropdown-item <?php echo ($program == $selected_program) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['patient_program' => $program])); ?>">
                                                        <?php echo htmlspecialchars($program); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Unit Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryUnitDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Unit:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_unit); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryUnitDropdown">
                                                <?php foreach ($units as $unit): ?>
                                                    <a class="dropdown-item <?php echo ($unit == $selected_unit) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['patient_unit' => $unit])); ?>">
                                                        <?php echo htmlspecialchars($unit); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Area Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryAreaDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Area:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_area); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryAreaDropdown">
                                                <?php foreach ($areas as $area): ?>
                                                    <a class="dropdown-item <?php echo ($area == $selected_area) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['occur_area' => $area])); ?>">
                                                        <?php echo htmlspecialchars($area); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Severity Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summarySeverityDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Severity:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_severity); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summarySeverityDropdown">
                                                <?php foreach ($severities as $severity): ?>
                                                    <a class="dropdown-item <?php echo ($severity == $selected_severity) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['rm_severity' => $severity])); ?>">
                                                        <?php echo htmlspecialchars($severity); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Flag Dropdown -->
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-reset" href="#" id="summaryFlagDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">Flag:</span> 
                                                <span class="fw-semibold">
                                                    <?php echo htmlspecialchars($selected_flag); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryFlagDropdown">
                                                <?php foreach ($flags as $flag): ?>
                                                    <a class="dropdown-item <?php echo ($flag == $selected_flag) ? 'active' : ''; ?>" 
                                                       href="?<?php echo http_build_query(array_merge($_GET, ['flag' => $flag])); ?>">
                                                        <?php echo htmlspecialchars($flag); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>


                                        <!-- Reset Filters Button -->
                                        <div class="dropdown">
                                            <a href="?reset_filters=1&month=<?php echo $selected_month; ?>" class="btn btn-secondary btn-sm">
                                                <i class="mdi mdi-refresh me-1"></i>Reset Filters
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->


                    <br>

<!-- SQL QUERY FOR BAR CHART =================================================================================================================== -->

                    <?php 
                        // Query to count the number of reports by category
                            $sql =  "SELECT occur_status, COUNT(*) as count
                                     FROM occur
                                     WHERE occur_status != 'Closed'";

                            // Apply various filters based on selected values
                                if ($selected_location != 'All') {
                                    $sql .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
                                }

                                if ($selected_program != 'All') {
                                    $sql .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
                                }

                                //if ($selected_md != 'All') {
                                //    $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                                //}

                                if ($selected_area != 'All') {
                                    $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                                }

                                if ($selected_category != 'All') {
                                    $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
                                }

                        // Apply flag filter if a specific flag is selected
                            if ($selected_flag != 'All') {
                                // Use FIND_IN_SET to match a selected flag within the comma-separated values in occur_flag
                                $sql .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
                            }

                        // Add GROUP BY clause
                            $sql .= " GROUP BY occur_status";

                            $result = mysqli_query($conn, $sql);
                            if (!$result) { 
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
                            }

                            $data = [];

                        // Fetch data and store in an array
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $data[] = $row;
                                }
                            }    
                            ?>

                        <!-- Pass data to JavaScript so ApexCharts can use it -->
                            <script> 
                                var chartData = <?php echo json_encode($data); ?>; 
                            </script>


<!-- BOOTSTRAP ROW:  COLUMN 1:  STATUS CHART ========================================================================================= -->

                    <div class="row mt-4">
                        <div class="col-md-6 col-xl-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <!-- Chart Container -->
                                    <div id="chart_status" class="apex-charts" dir="ltr"></div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->


<!-- BOOTSTRAP ROW:  COLUMN 2:  MANAGER FOLLOW UP STATUS ============================================================================= -->

<?php
// Query to get counts per manager_followup_name and manager_status
$sql_mgr_table = "SELECT 
                    CASE 
                        WHEN manager_followup_name IS NULL OR manager_followup_name = '' THEN 'Unassigned'
                        ELSE manager_followup_name 
                    END as manager_followup_name,
                    manager_status, 
                    COUNT(*) as status_count 
                  FROM occur 
                  WHERE manager_status IS NOT NULL
                  AND occur_status != 'closed'
                  AND manager_status != ''";

// Apply various filters based on selected values
if ($selected_location != 'All') {
    $sql_mgr_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}

if ($selected_loc != 'All') {
    $sql_mgr_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}

if ($selected_category != 'All') {
    $sql_mgr_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}

if ($selected_program != 'All') {
    $sql_mgr_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}

if ($selected_unit != 'All') {
    $sql_mgr_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}

if ($selected_area != 'All') {
    $sql_mgr_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

if ($selected_severity != 'All') {
    $sql_mgr_table .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
}

// Apply flag filter if a specific flag is selected
if ($selected_flag != 'All') {
    // Use FIND_IN_SET to match a selected flag within the comma-separated values in occur_flag
    $sql_mgr_table .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
}

// Add GROUP BY and ORDER BY clauses
$sql_mgr_table .= " GROUP BY manager_followup_name, manager_status
                    ORDER BY 
                        CASE 
                            WHEN manager_followup_name = 'Unassigned' THEN 1 
                            ELSE 0 
                        END,
                        manager_followup_name, 
                        manager_status";

// Execute the query 
$result_mgr_table = mysqli_query($conn, $sql_mgr_table);
if (!$result_mgr_table) {
    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_mgr_table);
}

// Initialize arrays to hold data
$manager_data = array();
$manager_statuses = array();

// Process the result set
while ($row = mysqli_fetch_assoc($result_mgr_table)) {
    $manager_name = $row['manager_followup_name'];
    $manager_status = $row['manager_status'];
    $status_count = $row['status_count'];

    // Build the data array
    if (!isset($manager_data[$manager_name])) {
        $manager_data[$manager_name] = array();
    }
    $manager_data[$manager_name][$manager_status] = $status_count;

    // Collect unique manager_status values
    $manager_statuses[$manager_status] = true;
}

// Get the list of unique manager_statuses
$manager_statuses_list = array_keys($manager_statuses);
?>

<div class="col-md-6">
    <?php if (!empty($manager_data)) : ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <h5 class="card-title me-2">MANAGER FOLLOW-UP STATUS</h5>
                <br>

                <div class="table-responsive mt-4">
                    <!-- Removed 'table-nowrap' from the class list -->
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="border-top: none; border-bottom: 1px solid #dee2e6; font-size: 13px;">
                               
                                <?php foreach ($manager_statuses_list as $manager_status) : ?>
                                    <!-- Header cells will wrap text if needed -->
                                    <th style="border-top: none; border-bottom: 1px solid #dee2e6; font-size: 13px;;">
                                        <?= htmlspecialchars($manager_status) ?>
                                    </th>
                                <?php endforeach; ?>
                                <th style="border-top: none; border-bottom: 1px solid #dee2e6;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($manager_data as $manager_name => $statuses) : 
                                $row_total = 0;
                            ?>
                                <tr>
                                    <!-- Manager name cell with 'nowrap' style -->
                                    <td style="white-space: nowrap; border-top: 1px solid #dee2e6;font-size: 13px;">
                                        <?= htmlspecialchars($manager_name) ?>
                                    </td>
                                    <?php foreach ($manager_statuses_list as $manager_status) : 
                                        $count = isset($statuses[$manager_status]) ? $statuses[$manager_status] : 0;
                                        $row_total += $count;
                                    ?>
                                        <td style="border-top: 1px solid #dee2e6; text-align: center;">
                                            <a href="drill_status.php?manager_followup_name=<?= urlencode($manager_name) ?>&manager_status=<?= urlencode($manager_status) ?>">
                                                <?= $count ?>
                                            </a>
                                        </td>
                                    <?php endforeach; ?>
                                    <!-- Total column with clickable total count -->
                                    <td style="border-top: 1px solid #dee2e6; text-align: center;">
                                        <a href="drill_status.php?manager_followup_name=<?= urlencode($manager_name) ?>">
                                            <strong><?= $row_total ?></strong>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="border-top: 1px solid #dee2e6;">Total</th>
                                <?php 
                                // Initialize column totals
                                $column_totals = array();
                                foreach ($manager_statuses_list as $manager_status) {
                                    $column_totals[$manager_status] = 0;
                                }
                                $grand_total = 0;

                                // Calculate totals
                                foreach ($manager_data as $statuses) {
                                    foreach ($manager_statuses_list as $manager_status) {
                                        $count = isset($statuses[$manager_status]) ? $statuses[$manager_status] : 0;
                                        $column_totals[$manager_status] += $count;
                                        $grand_total += $count;
                                    }
                                }
                                ?>
                                <?php foreach ($manager_statuses_list as $manager_status) : ?>
                                    <th style="border-top: 1px solid #dee2e6; text-align: center;">
                                        <a href="drill_status.php?manager_status=<?= urlencode($manager_status) ?>">
                                            <?= $column_totals[$manager_status] ?>
                                        </a>
                                    </th>
                                <?php endforeach; ?>
                                <th style="border-top: 1px solid #dee2e6; text-align: center;"><strong><?= $grand_total ?></strong></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    <?php else : ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <h5 class="card-title">MANAGER FOLLOW-UP DETAIL</h5>
                <div class="mt-4 text-center">
                    <p>No data available.</p>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    <?php endif; ?>
</div> <!-- end col -->
</div> <!-- end row -->
<!-- ========================================  BOOTSTRAP ROW: COLUMN CHART   =================================================================== -->

<br>

<!-- PHP / SQL QUERY TO PULL DETAIL BY MONTH ----- -->

<?php 

// Generate a list of the last 12 months.
$months_report_count = [];
for ($i = 11; $i >= 0; $i--) {
    $months_report_count[] = date('Y-m', strtotime("-$i months"));
}


// Query to count # of reports by month and type
// Initialize the base query
$sql_report_count = "
    SELECT 
        DATE_FORMAT(occur_date, '%Y-%m') AS month_year,
        occur_type,
        COUNT(*) AS item_report_count
    FROM 
        occur
    WHERE 
        occur_date >= DATE_FORMAT(CURDATE() - INTERVAL 12 MONTH, '%Y-%m-01')
        AND occur_status != 'Closed'
";

// Apply filters if they are not set to 'All'
if ($selected_location != 'All') {
    $sql_report_count .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_report_count .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_category != 'All') {
    $sql_report_count .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}
if ($selected_program != 'All') {
    $sql_report_count .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_report_count .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
//if ($selected_md != 'All') {
//    $sql_report_count .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
//}
if ($selected_area != 'All') {
    $sql_report_count .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}
if ($selected_severity != 'All') {
    $sql_report_count .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
}

// Apply flag filter if a specific flag is selected
if ($selected_flag != 'All') {
    // Use FIND_IN_SET to match a selected flag within the comma-separated values in occur_flag
    $sql_report_count .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
}

// Add GROUP BY and ORDER BY clauses
$sql_report_count .= "
    GROUP BY 
        DATE_FORMAT(occur_date, '%Y-%m'), occur_type
    ORDER BY 
        DATE_FORMAT(occur_date, '%Y-%m')
";


// Execute the query
$result_report_count = mysqli_query($conn, $sql_report_count);
if (!$result_report_count) {
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
}

// Initialize data array with zeros for all months
$data_report_count = [];
foreach ($months_report_count as $month) {
    $data_report_count[$month] = [
        'ALL' => 0,
        'Patient Care' => 0,
        'Safety' => 0,
        'Other' => 0
    ];
}

// Fill in the data array with actual counts from the query
while ($row = mysqli_fetch_assoc($result_report_count)) {
    $month = $row['month_year'];
    $type = $row['occur_type'];
    $count = (int)$row['item_report_count'];
    
    // Map database values to our categories
    $category = 'Other';
    if ($type == 'Patient Care' || $type == 'PT CARE') {
        $category = 'Patient Care';
    } elseif ($type == 'Safety' || $type == 'SAFETY') {
        $category = 'Safety';
    }
    
    $data_report_count[$month][$category] += $count;
    $data_report_count[$month]['ALL'] += $count;
}

// Prepare data for ApexCharts
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

<!-- BOOTSTRAP ROW:  TOTAL REPORTS BY MONTH    ========================================================================================= -->

            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h5 class="card-title me-2">OPEN REPORTS BY MONTH</h5>
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






<!-- ========================================  TOP ROW - 4 WIDGETS / CARDS  =================================================================== -->




<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<?php 
// Query for reports assigned to user and not closed
$sql = "SELECT *
        FROM occur
        WHERE occur_status != 'Closed'";

// Apply filters
if ($selected_location != 'All') {
    $sql .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_category != 'All') {
    $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}
if ($selected_program != 'All') {
    $sql .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
//if ($selected_md != 'All') {
//    $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
//}
if ($selected_area != 'All') {
    $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}
if ($selected_severity != 'All') {
    $sql .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
}

// Apply flag filter if a specific flag is selected
if ($selected_flag != 'All') {
    // Use FIND_IN_SET to match a selected flag within the comma-separated values in occur_flag
    $sql .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
}

//Print the final SQL query for debugging
//echo $sql;

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); // Corrected to mysqli_error instead of mysql_error
}

$numrows = mysqli_num_rows($result);
?>

        
                       <div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Flex Container for Title and Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">All Open Reports</h4>
                    <div id="buttonsContainer1"></div> <!-- Container for DataTables Buttons -->
                </div>

                <table id="myTable1" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
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
                                                            echo "<td><a href='rm_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
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
                                                           echo "<td style='white-space: nowrap;'>{$row['manager_status']}</td>";
                                                            

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
       WHERE complete_date IS NULL
       AND manager_followup_plan IS NOT NULL
       AND TRIM(manager_followup_plan) != ''
";

// Apply filters
if ($selected_location != 'All') {
   $sql .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
   $sql .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_category != 'All') {
   $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}
if ($selected_program != 'All') {
   $sql .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
   $sql .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
//if ($selected_md != 'All') {
//    $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
//}
if ($selected_area != 'All') {
   $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}
if ($selected_severity != 'All') {
   $sql .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
}
if ($selected_flag != 'All') {
   $sql .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
   die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
}
$numrows = mysqli_num_rows($result);
?>

                       <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Flex Container for Title and Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Action Plans in Progress</h4>
                    <div id="buttonsContainer2"></div> <!-- Container for DataTables Buttons -->
                </div>

                <hr>
                
                <table id="myTable2" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">  
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
                                                            echo "<td><a href='rm_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
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




<!-- ================================================ ACTION PLANS COMPLETED =================== -->

<?php 
$sql = "SELECT *
        FROM occur
        WHERE manager_status = 'Complete'
        AND manager_followup_plan IS NOT NULL
        AND TRIM(manager_followup_plan) != ''
";

// Apply filters
if ($selected_location != 'All') {
    $sql .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_category != 'All') {
    $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
}
if ($selected_program != 'All') {
    $sql .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_area != 'All') {
    $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}
if ($selected_severity != 'All') {
    $sql .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
}
if ($selected_flag != 'All') {
    $sql .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
}
$numrows = mysqli_num_rows($result);
?>
                       <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Flex Container for Title and Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Action Plans Completed</h4>
                    <div id="buttonsContainer3"></div> <!-- Container for DataTables Buttons -->
                </div>

                <hr>
                
                <table id="myTable3" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">   
                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <th>Action Plan</th>
                                                        <th>Implementation Notes</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <th>Description</th>
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <!--<th>Description</th> -->
                                                        <!-- <th>Intervention</th>-->
                                                        <!-- <th>Mgr Status</th> -->

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Complete Date</th>
                                                        
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
                                                            echo "<td><a href='rm_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
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
                                                            echo "<td>{$row['occur_description']}</td>";
                                                            //echo "<td>{$row['occur_intervention']}</td>";
                                                            //echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['complete_date'])) {
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






      


<!-- ========================================  BOOTSTRAP ROW - 4 WIDGETS / CARDS  =================================================================== -->

<div class="row mt-4">
    <?php
    // Manager Status Section
    $sql_mgr_status_table = "SELECT 
        CASE 
            WHEN manager_status IS NULL OR TRIM(manager_status) = '' THEN 'No Manager Status' 
            ELSE manager_status 
        END AS normalized_mgr_status, 
        COUNT(*) as mgr_status_table_count 
        FROM occur 
        WHERE occur_status != 'Closed'";

    // Add filters based on selections
    if ($selected_location != 'All') {
        $sql_mgr_status_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
    }
    if ($selected_loc != 'All') {
        $sql_mgr_status_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
    }
    if ($selected_category != 'All') {
        $sql_mgr_status_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
    }
    if ($selected_program != 'All') {
        $sql_mgr_status_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
    }
    if ($selected_unit != 'All') {
        $sql_mgr_status_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
    }
    if ($selected_area != 'All') {
        $sql_mgr_status_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
    }
    if ($selected_severity != 'All') {
        $sql_mgr_status_table .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
    }
    if ($selected_flag != 'All') {
        $sql_mgr_status_table .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
    }

    $sql_mgr_status_table .= " GROUP BY normalized_mgr_status ORDER BY mgr_status_table_count DESC";

    $result_mgr_status_table = mysqli_query($conn, $sql_mgr_status_table);
    if (!$result_mgr_status_table) {
        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_mgr_status_table);
    }

    // Initialize variables
    $total_mgr_status_table_count = 0;
    $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
    $mgr_status_table_styles = [];

    // Create color styles
    $color_index = 0;
    while ($row = mysqli_fetch_assoc($result_mgr_status_table)) {
        $mgr_status = $row['normalized_mgr_status'];
        if (!isset($mgr_status_table_styles[$mgr_status])) {
            $mgr_status_table_styles[$mgr_status] = [
                'color' => $colors[$color_index % count($colors)]
            ];
            $color_index++;
        }
    }
    mysqli_data_seek($result_mgr_status_table, 0);
    ?>

    <!-- Manager Status Card -->
    <div class="col-md-3">
        <?php if (mysqli_num_rows($result_mgr_status_table) > 0): ?>
            <div class="card h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title me-2">MANAGER STATUS: OPEN REPORTS</h5>
                    </div>

                    <br>
                    <div class="mt-4 text-center">
                        <div>
                            <?php while ($row_mgr_status_table = mysqli_fetch_assoc($result_mgr_status_table)):
                                $manager_status = htmlspecialchars($row_mgr_status_table['normalized_mgr_status']);
                                $count_mgr_status = htmlspecialchars($row_mgr_status_table['mgr_status_table_count']);
                                $total_mgr_status_table_count += $row_mgr_status_table['mgr_status_table_count'];
                                $color_mgr_status = $mgr_status_table_styles[$manager_status]['color'];
                            ?>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <div class="d-flex">
                                        <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_mgr_status; ?>"></i>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0"><?php echo $manager_status; ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 font-size-14">
                                            <a href="drill_mgr_status.php?manager_status=<?php echo urlencode($manager_status); ?>">
                                                <?php echo $count_mgr_status; ?>
                                            </a>
                                        </h5>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="d-flex justify-content-between align-items-center pt-2">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><strong>Total</strong></p>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-size-14">
                                        <strong><?php echo htmlspecialchars($total_mgr_status_table_count); ?></strong>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title me-2">MANAGER STATUS</h5>
                        <a href="drill_mgr_status.php?manager_status=<?php echo htmlspecialchars($selected_month); ?>#myTable" 
                           class="btn btn-sm btn-primary">
                            <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                        </a>
                    </div>
                    <br>
                    <div class="mt-4 text-center">
                        <div>
                            <p>No data available.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php




// Occurrence Status Section
$sql_rm_status_table = "SELECT 
    CASE 
        WHEN occur_status IS NULL OR TRIM(occur_status) = '' THEN 'No QA/Risk Mgmt Status'
        ELSE occur_status 
    END AS normalized_occur_status,
    COUNT(*) as rm_status_table_count 
    FROM occur 
    WHERE occur_status != 'Closed'";

    // Add filters based on selections
    if ($selected_location != 'All') {
        $sql_rm_status_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
    }
    if ($selected_loc != 'All') {
        $sql_rm_status_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
    }
    if ($selected_category != 'All') {
        $sql_rm_status_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
    }
    if ($selected_program != 'All') {
        $sql_rm_status_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
    }
    if ($selected_unit != 'All') {
        $sql_rm_status_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
    }
    if ($selected_area != 'All') {
        $sql_rm_status_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
    }
    if ($selected_severity != 'All') {
        $sql_rm_status_table .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
    }
    if ($selected_flag != 'All') {
        $sql_rm_status_table .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
    }

    $sql_rm_status_table .= " GROUP BY normalized_occur_status ORDER BY rm_status_table_count DESC";

    $result_rm_status_table = mysqli_query($conn, $sql_rm_status_table);
    if (!$result_rm_status_table) {
        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_rm_status_table);
    }

    // Initialize variables
    $total_rm_status_table_count = 0;
    $rm_status_table_styles = [];

    // Create color styles
    $color_index = 0;
    while ($row = mysqli_fetch_assoc($result_rm_status_table)) {
        $occur_status = $row['normalized_occur_status'];  // Changed to normalized_occur_status
        if (!isset($rm_status_table_styles[$occur_status])) {
            $rm_status_table_styles[$occur_status] = [
                'color' => $colors[$color_index % count($colors)]
            ];
            $color_index++;
        }
    }
    mysqli_data_seek($result_rm_status_table, 0);
    ?>

    <!-- QA / Risk Status Card -->
    <div class="col-md-3">
        <?php if (mysqli_num_rows($result_rm_status_table) > 0): ?>
            <div class="card h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title me-2">QA / RISK MGMT STATUS: OPEN REPORTS
                        </h5>
                    </div>

                    <br>
                    <div class="mt-4 text-center">
                        <div>
                            <?php while ($row_rm_status_table = mysqli_fetch_assoc($result_rm_status_table)):
                                $occur_status = htmlspecialchars($row_rm_status_table['normalized_occur_status']); // Changed to normalized_occur_status
                                $count_rm_status = htmlspecialchars($row_rm_status_table['rm_status_table_count']);
                                $total_rm_status_table_count += $row_rm_status_table['rm_status_table_count'];
                                $color_rm_status = $rm_status_table_styles[$occur_status]['color'];
                            ?>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <div class="d-flex">
                                        <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_rm_status; ?>"></i>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0"><?php echo $occur_status; ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 font-size-14">
                                            <a href="drill_risk.php?occur_status=<?php echo urlencode($occur_status); ?>">
                                                <?php echo $count_rm_status; ?>
                                            </a>
                                        </h5>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="d-flex justify-content-between align-items-center pt-2">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="mb-0"><strong>Total</strong></p>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0 font-size-14">
                                        <strong><?php echo htmlspecialchars($total_rm_status_table_count); ?></strong>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card h-100">
                <div class="card-body pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title me-2">QA / RMSTATUS</h5>
                        <a href="drill_risk.php?occur_status=<?php echo htmlspecialchars($selected_month); ?>#myTable" 
                           class="btn btn-sm btn-primary">
                            <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                        </a>
                    </div>
                    <br>
                    <div class="mt-4 text-center">
                        <div>
                            <p>No data available.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div> <!-- end row -->
                              
                                
<!-- ========================================  BOOTSTRAP ROW:     ========================================================= -->



<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  =================================================================== -->




       
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

        <!-- Add these script files -->
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
        
    <!-- Datatables:  Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatables:  Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

    <!-- Apex Charts -->
        <script src="assets/js/app.js"></script>
        

    
<!-- ========================================  PAGE SPECIFIC SCRIPTS: CHARTS AND TABLES   ========================================================== -->

    <!-- Datatables: Configure buttons js -->
        <script>
            $(document).ready(function() {
                // Function to create consistent button configuration
                function getButtonConfig(title) {
                    return [
                        {
                            extend: 'copyHtml5',
                            text: '<i class="fas fa-copy"></i> Copy',
                            className: 'btn btn-info me-2',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-success me-2',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-success me-2',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-warning me-2',
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function (doc) {
                                doc.pageMargins = [40, 60, 40, 60];
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 12;
                                doc.styles.tableHeader.bold = true;
                                doc.content.unshift({
                                    text: title,
                                    style: 'header',
                                    alignment: 'center',
                                    margin: [0, 0, 0, 20]
                                });
                                doc.styles.header = {
                                    fontSize: 18,
                                    bold: true
                                };
                                doc.footer = function(currentPage, pageCount) {
                                    return {
                                        text: currentPage.toString() + ' of ' + pageCount,
                                        alignment: 'right',
                                        margin: [0, 0, 20, 0]
                                    };
                                };
                            }
                        }
                    ];
                }

                // Initialize table1
                    var table1 = $('#myTable1').DataTable({
                        "order": [[1, "desc"]],
                        "dom": 'frtip',
                        "responsive": true,
                        "buttons": getButtonConfig('Open Reports Requiring Followup')
                    });

                // Initialize table2
                    var table2 = $('#myTable2').DataTable({
                        "order": [[1, "desc"]],
                        "dom": 'frtip',
                        "responsive": true,
                        "buttons": getButtonConfig('Action Plans in Progress')
                    });

                // Initialize table3
                    var table3 = $('#myTable3').DataTable({
                        "order": [[1, "desc"]],
                        "dom": 'frtip',
                        "responsive": true,
                        "buttons": getButtonConfig('Action Plans Completed')
                    });

                // Move the buttons to their respective containers
                    table1.buttons().container().appendTo('#buttonsContainer1');
                    table2.buttons().container().appendTo('#buttonsContainer2');
                    table3.buttons().container().appendTo('#buttonsContainer3');
                });
        </script>


    <!-- COLUMN CHART ---------------------------------------------- -->

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
                        window.location.href = 'open_detail.php?month=' + encodeURIComponent(selectedMonth);
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
                text: 'Open Reports by Month - All',
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


    <!-- SCRIPT FOR BAR CHART -->

        <script>
            console.log('ApexCharts loaded:', typeof ApexCharts !== 'undefined');
            console.log('chartData loaded:', chartData);
            console.log('Chart container exists:', document.querySelector("#chart_status") !== null);

            var options = {
                series: [{
                    name: 'Reports Count',
                    data: chartData.map(item => item.count)
                }],
                chart: {
                    type: 'bar',
                    width: '100%',
                    height: '400px',
                    //Within the chart options, include an event object.  This captures user click events on the bar
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            // Get the index of the clicked bar
                            var selectedIndex = config.dataPointIndex;
                            // Get the corresponding status label: last item is name of database column
                            var selectedStatus = chartData[selectedIndex].occur_status;
                            // Redirect to another page with the selected status as a query parameter
                            window.location.href = 'bar_detail_status.php?status=' + encodeURIComponent(selectedStatus);
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

                //item => is followed by "item." and then the name of the field used in the query
                xaxis: {
                    categories: chartData.map(item => item.occur_status)
                },

                //Update Chart Title
                title: {
                    text: 'Open Reports by Status',
                    align: 'top',
                    margin: 20 // Adjust this value to increase or decrease the space below the title
                }
            };

            //Update chart id above to match the name used in statement below
                var chart = new ApexCharts(document.querySelector("#chart_status"), options);
                chart.render();
                </script>



<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




