<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->




<!-- POPULATE DROP DOWNS AT TOP  ================================================================================================== -->

<?php
//  Query to get unique months from the occur_date field  ----------------------------------
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




// Query to get unique attending physicians from the md_attending field
$sql_mds = "SELECT DISTINCT md_attending 
            FROM occur 
            WHERE md_attending IS NOT NULL AND md_attending != ''
            ORDER BY md_attending ASC";

$result_mds = mysqli_query($conn, $sql_mds);

if (!$result_mds) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch the attending physicians into an array
$mds = ['All']; // Start with 'All' as the first option
while ($row = mysqli_fetch_assoc($result_mds)) {
    $mds[] = $row['md_attending'];
}

// Set default to 'All' if no attending physician is selected or if the selected physician is invalid
$selected_md = isset($_GET['md_attending']) && in_array($_GET['md_attending'], $mds) ? $_GET['md_attending'] : 'All';



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

//Set Session filters for use on drill down pages

if (isset($_GET['month']) || isset($_GET['location']) || isset($_GET['patient_loc']) || 
    isset($_GET['patient_program']) || isset($_GET['patient_unit']) || isset($_GET['md_attending']) ||
    isset($_GET['occur_area'])) {    

    $_SESSION['filters'] = [
        'month' => $selected_month,
        'location' => $selected_location,
        'loc' => $selected_loc,
        'program' => $selected_program,
        'unit' => $selected_unit,
        'md' => $selected_md,
        'area' => $selected_area 
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

<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card" style="background-color: #DCDCDC;">
            <div class="card-body text-center text-black">
                <h5 class="mb-sm-0 fw-bold"></h5>
                
                <!-- Month dropdown -->
                <h4>
                    <div class="dropdown d-inline-block">
                        <a class="dropdown-toggle text-reset text-decoration-none" href="#" id="summaryMonthDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php 
                            // Display the session 'month' filter or default to the current month if not set
                            $display_month = $_SESSION['filters']['month'] ?? date('Y-m');
                            echo strtoupper(date("F Y", strtotime($display_month . "-01"))); 
                            ?>
                            <i class="mdi mdi-chevron-down ms-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryMonthDropdown">
                            <?php foreach ($months as $month): ?>
                                <a class="dropdown-item <?php echo ($month == $display_month) ? 'active' : ''; ?>" 
                                   href="?<?php echo http_build_query(array_merge($_GET, ['month' => $month])); ?>">
                                    <?php echo date('F Y', strtotime($month . '-01')); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
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

                    <!-- Attending Dropdown -->
                    <div class="dropdown">
                        <a class="dropdown-toggle text-reset" href="#" id="summaryMdDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted">Attending:</span> 
                            <span class="fw-semibold">
                                <?php echo htmlspecialchars($selected_md); ?>
                                <i class="mdi mdi-chevron-down ms-1"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryMdDropdown">
                            <?php foreach ($mds as $md): ?>
                                <a class="dropdown-item <?php echo ($md == $selected_md) ? 'active' : ''; ?>" 
                                   href="?<?php echo http_build_query(array_merge($_GET, ['md_attending' => $md])); ?>">
                                    <?php echo htmlspecialchars($md); ?>
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
    </div>
</div> <!-- end row -->

<!-- ========================================  TOP ROW - 4 WIDGETS / CARDS  =================================================================== -->


<!-- PHP / SQL QUERY AND HTML FOR "TOTAL REPORTS: MONTH" - COLUMN 1 ============================ -->

            <br>
            <?php 

            // Query to count # of reports submitted in the selected month
            // Note: $start_date and $end_date are defined at the top of the page along with the retrieval of month for $selected_month
            $sql = "SELECT COUNT(*) AS item_count
                    FROM occur
                    WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

            // Add conditions for each dropdown if a specific option is selected
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
            if ($selected_md != 'All') {
                $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
            }
            if ($selected_area != 'All') {
                $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
            }

            $result = mysqli_query($conn, $sql);
            if (!$result) {
                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
            }

            $row = mysqli_fetch_assoc($result);
            $numrows_submitted = $row['item_count'];

            ?>

                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                          <a href="drill_table.php?month=<?= $selected_month ?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-folder-star text-primary"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">TOTAL REPORTS</p>
                                            <a href="drill_table.php?month=<?= $selected_month ?>">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <!--
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>2.65%</span> Pending RM review
                                        </p>
                                        -->
                                    </div>
                                </div>
                            </div> <!-- end col-->


<!-- PHP / SQL QUERY AND HTML FOR HIGH SEVERITY -  COLUMN 2 ============================ -->

                <?php
                        $sql =  "SELECT COUNT(*) AS item_count
                         FROM occur
                         WHERE rm_severity IN ('Severe', 'Sentinel')
                           AND occur_date >= '$start_date' AND occur_date < '$end_date'";

                // Add conditions for each dropdown if a specific option is selected
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
                if ($selected_md != 'All') {
                    $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                }
                if ($selected_area != 'All') {
                    $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                }

                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                }
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
                                        <!--
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                        -->
                                    </div>
                                </div>
                            </div> <!-- end col-->

<!-- PHP / SQL QUERY AND HTML FOR FLAGS  -  COLUMN 3 ============================ -->
            <?php

                // Query to count # of reports submitted but not reviewed by RM
                    $sql = "SELECT COUNT(*) AS item_count
                            FROM occur
                            WHERE occur_date >= '$start_date' 
                              AND occur_date < '$end_date'
                              AND occur_flag IS NOT NULL 
                              AND occur_flag != ''";

                    // Add conditions for each dropdown if a specific option is selected
                    if ($selected_area != 'All') {
                        $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                    }

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

                    if ($selected_md != 'All') {
                        $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                    }

                    // Execute the query
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
                                            <a href="drill_flag.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-flag text-warning"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">FLAGS</p>
                                             <a href="drill_flag.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <!--
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                        -->
                                    </div>
                                </div>
                            </div> <!-- end col-->


<!-- PHP / SQL QUERY AND HTML FOR OPEN REPORTS  -  COLUMN 4 ============================ -->

            <?php 
            //Query to count # of reports submitted but not reviewed by RM
              $sql = "SELECT COUNT(*) AS item_count
                    FROM occur
                    WHERE occur_status != 'Closed'
                    AND occur_date >= '$start_date' 
                    AND occur_date < '$end_date'
                    ";

            // Add conditions for each dropdown if a specific option is selected
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
                        if ($selected_md != 'All') {
                            $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                        }
                        if ($selected_area != 'All') {
                            $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                        }

                   
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
                                            <a href="filter_open_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">OPEN REPORTS</p>
                                             <a href="filter_open_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <!--
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                        -->
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div>


<!-- ========================================  BOOTSTRAP ROW: 2 TWO COLUMNS OF CHARTS/TABLES   ================================================= -->


<!-- PHP / SQL QUERY FOR CATEGORY CHART  -  COLUMN 1  ============================ -->
<?php

    // Create DateTime objects for selected month and prior month
    $selected_month_dt = DateTime::createFromFormat('Y-m', $selected_month);
    $prior_month_dt = clone $selected_month_dt;
    $prior_month_dt->modify('-1 month');
    $prior_month = $prior_month_dt->format('Y-m');

   // Query to count the number of reports by category for selected and prior month

           $sql = "SELECT reporter_category, COUNT(*) AS count, DATE_FORMAT(occur_date, '%Y-%m') AS month
                FROM occur
                WHERE DATE_FORMAT(occur_date, '%Y-%m') IN ('$selected_month', '$prior_month')";
           

            // Add conditions for each dropdown if a specific option is selected
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
            if ($selected_md != 'All') {
                $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
            }
            if ($selected_area != 'All') {
                $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
            }


            // Add the GROUP BY clause after all WHERE conditions
            $sql .= " GROUP BY reporter_category, month";

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
    var chartData_category_prior_mo = <?php echo json_encode($data); ?>;
</script>



<!-- RENDER CATEGORY CHART  ======================================================== -->

                      
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


<!-- PHP / SQL QUERY FOR CHART TYPE  -  COLUMN 2  ============================ -->
 

            <?php
                // Query to count the number of reports by type within the desired date range
                    $sql_type_table = "SELECT occur_type, COUNT(*) as type_table_count 
                                       FROM occur 
                                       WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

                  
                        // Filters in standard order
                        // 1. Location
                        if ($selected_location != 'All') {
                            $sql_type_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                        }

                        // 2. Level of Care
                        if ($selected_loc != 'All') {
                            $sql_type_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
                        }

                        // 3. Program
                        if ($selected_program != 'All') {
                            $sql_type_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                        }

                        // 4. Unit
                        if ($selected_unit != 'All') {
                            $sql_type_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
                        }

                        // 5. MD Attending
                        if ($selected_md != 'All') {
                            $sql_type_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                        }

                        // 6. Area
                        if ($selected_area != 'All') {
                            $sql_type_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                        }

                    // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                    $sql_type_table .= " GROUP BY occur_type
                                         ORDER BY type_table_count DESC";

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
                    'Patient Care' => ['icon' => 'mdi mdi-circle', 'color' => 'text-primary'],
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
    echo '                            <h5 class="mb-0 font-size-14"><a href="drill_type.php?occur_type=' . urlencode($occur_type) . '&selected_month=' . urlencode($selected_month) . '">' . $count . '</a></h5>';
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
                    case 'text-primary':
                        $color_array[] = "'#1E90FF'"; // Dark blue
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

</div>



<!-- ========================================  BOOTSTRAP ROW: CHARTS - FULL 12 COLUMN  ============================================================= -->


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
                    $sql_report_daily = "SELECT 
                        DATE_FORMAT(occur_date, '%Y-%m-%d') AS day_date,
                        occur_type,
                        COUNT(*) AS item_report_daily
                    FROM 
                        occur
                    WHERE 
                        occur_date BETWEEN '$first_day_of_month' AND '$last_day_of_month'";

                    // 1. Location
                    if ($selected_location != 'All') {
                        $sql_report_daily .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                    }

                    // 2. Level of Care
                    if ($selected_loc != 'All') {
                        $sql_report_daily .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
                    }

                    // 3. Program
                    if ($selected_program != 'All') {
                        $sql_report_daily .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                    }

                    // 4. Unit
                    if ($selected_unit != 'All') {
                        $sql_report_daily .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
                    }

                    // 5. MD Attending
                    if ($selected_md != 'All') {
                        $sql_report_daily .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                    }

                    // 6. Area
                    if ($selected_area != 'All') {
                        $sql_report_daily .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                    }

                // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                $sql_report_daily .= "
                    GROUP BY 
                        DATE_FORMAT(occur_date, '%Y-%m-%d'), occur_type
                    ORDER BY 
                        DATE_FORMAT(occur_date, '%Y-%m-%d')";


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


<!-- ========================================  TOP ROW - 4 WIDGETS / CARDS  =================================================================== -->

<div class="row">

<?php
// Query to count the number of reports by level of care within the desired date range
$sql_loc_table = "SELECT patient_loc, COUNT(*) as loc_table_count 
                  FROM occur 
                  WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_loc_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_loc_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_loc_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_loc_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_loc_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_loc_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_loc_table .= " GROUP BY patient_loc
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
                                    <a href="drill_loc.php?loc=<?= urlencode($patient_loc_table) ?>&month=<?= urlencode($selected_month) ?>">
                                        <?= $count_loc_table ?>
                                    </a>
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


    <!-- PHP / SQL QUERY AND HTML FOR "LOCATION DETAIL" - COLUMN 2 ============================ -->
<?php
// Query to count the number of reports by location within the desired date range
$sql_location_table = "SELECT occur_location, COUNT(*) as location_table_count 
                       FROM occur 
                       WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_location_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_location_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_location_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_location_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_location_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_location_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_location_table .= " GROUP BY occur_location
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
                                    <a href="drill_location.php?location=<?= urlencode($occur_location_table) ?>&month=<?= urlencode($selected_month) ?>">
                                        <?= $count_location_table ?>
                                    </a>
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


    <!-- PHP / SQL QUERY AND HTML FOR "PROGRAM DETAIL" - COLUMN 3 ============================ -->


<?php
// Query to count the number of reports by program within the desired date range
$sql_program_table = "SELECT patient_program, COUNT(*) as program_table_count 
                      FROM occur 
                      WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_program_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_program_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_program_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_program_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_program_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_program_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_program_table .= " GROUP BY patient_program
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
                                    <a href="drill_program.php?program=<?= urlencode($patient_program_table) ?>&month=<?= urlencode($selected_month) ?>">
                                        <?= $count_program_table ?>
                                    </a>
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

      

<!-- ========================================  BOOTSTRAP ROW:     ========================================================= -->



<!-- PHP / SQL QUERY   ============================ -->


            <?php 

            // SQL query to get data for the selected month

                $sql_month_detail = "SELECT * FROM occur
                                     WHERE occur_date >= '$selected_month-01' 
                                     AND occur_date < DATE_ADD('$selected_month-01', INTERVAL 1 MONTH)";

                // Add conditions for each dropdown if a specific option is selected
                if ($selected_location != 'All') {
                    $sql_month_detail .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                }
                if ($selected_loc != 'All') {
                    $sql_month_detail .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
                }
                if ($selected_program != 'All') {
                    $sql_month_detail .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                }
                if ($selected_unit != 'All') {
                    $sql_month_detail .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
                }
                if ($selected_md != 'All') {
                    $sql_month_detail .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                }
                if ($selected_area != 'All') {
                    $sql_month_detail .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                }

                // Execute the query
                $result_month_detail = mysqli_query($conn, $sql_month_detail);
                if (!$result_month_detail) {
                    die("Query failed: " . mysqli_error($conn));
                }

                // Get the number of rows in the result (must be before the loop)
                $numrows_month_detail = mysqli_num_rows($result_month_detail);

                // For debugging purposes, you can uncomment these lines:
                // echo "Debug: SQL Query = $sql_month_detail<br>";
                // echo "Debug: Number of rows = $numrows_month_detail<br>";
                ?>

              
                                
<!-- ========================================  BOOTSTRAP ROW:  HOURLY CHART   ========================================================= -->

            <?php
                // PHP to set values for reports by HOUR OF THE DAY
                $sql_report_hourly = "SELECT 
                    HOUR(occur_time) AS hour,
                    COUNT(*) AS item_report_hourly
                FROM 
                    occur
                WHERE 
                    occur_date >= '$start_date' AND occur_date < '$end_date'";

                // 1. Location
                if ($selected_location != 'All') {
                    $sql_report_hourly .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                }

                // 2. Level of Care
                if ($selected_loc != 'All') {
                    $sql_report_hourly .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
                }

                // 3. Program
                if ($selected_program != 'All') {
                    $sql_report_hourly .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                }

                // 4. Unit
                if ($selected_unit != 'All') {
                    $sql_report_hourly .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
                }

                // 5. MD Attending
                if ($selected_md != 'All') {
                    $sql_report_hourly .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                }

                // 6. Area
                if ($selected_area != 'All') {
                    $sql_report_hourly .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                }


                // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                $sql_report_hourly .= "
                    GROUP BY 
                        HOUR(occur_time)
                    ORDER BY 
                        HOUR(occur_time)";

                $result_report_hourly = mysqli_query($conn, $sql_report_hourly);
                if (!$result_report_hourly) {
                    die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                }

                // Initialize array with zeros for all 24 hours
                $data_report_hourly = array_fill(0, 24, 0);

                // Fill in the actual counts
                while ($row = mysqli_fetch_assoc($result_report_hourly)) {
                    $hour = (int)$row['hour'];
                    $count = (int)$row['item_report_hourly'];
                    $data_report_hourly[$hour] = $count;
                }

                // Prepare data for JavaScript
                $chart_data_js_report_hourly = json_encode(array_values($data_report_hourly));
                $hour_labels = json_encode(range(0, 23));

                // For debugging purposes, you can uncomment these lines:
                // echo "Debug: SQL Query = $sql_report_hourly<br>";
                // echo "Debug: Data = " . print_r($data_report_hourly, true) . "<br>";
                ?>

                <!-- RENDER CHART: REPORTS BY HOUR OF THE DAY -->
                  <div class="row mt-4"> <!-- Add mt-4 for top margin -->
                    <div class="col-12">
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
                </div>

                <script>
                    var chartData_report_hourly = <?php echo $chart_data_js_report_hourly; ?>;
                    var hourLabels = <?php echo $hour_labels; ?>;
                </script>



                                
<!-- ========================================  BOOTSTRAP ROW:  DATATABLE  ========================================================= -->


<!-- DATATABLE: SET NAME  ================================================ -->

            <?php
            // Set names for title/subtitle on Datatables
                    $datatable_name = "Report Detail";
            ?>   


<!-- DATATABLE  ================================================ -->

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $datatable_name; ?></h4>
                                        <p class="card-title-desc"><?php echo $datatable_name_sub; ?>
                                        </p>
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
                                        -->

                                      
                                        <hr style="height: 3px; background-color: black; border: none;">

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
                                            echo "<td><a href='pdf_report.php?id={$row_month_detail['occur_id']}'>{$row_month_detail['occur_id']}</a></td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['occur_type']}</td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['patient_last_name']}</td>";
                                            echo "<td>{$row_month_detail['patient_MRN']}</td>";
                                            echo "<td>{$row_month_detail['patient_age']}</td>";
                                            echo "<td>{$row_month_detail['patient_gender']}</td>";
                                            echo "<td>{$row_month_detail['patient_unit']}</td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['reporter_category']}</td>";
                                            echo "<td>{$row_month_detail['rm_severity']}</td>";
                                            echo "<td>{$row_month_detail['occur_status']}</td>";
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


<!--  END PHP  ====================== -->



                              
                                
<!-- ========================================  BOOTSTRAP ROW:     ========================================================= -->


<div class="row mt-4">

<!-- PHP / SQL QUERY AND HTML FOR "AREA" - COLUMN 1 ============================ -->

<?php
// Query to count the number of reports by area within the desired date range
$sql_area_table = "SELECT occur_area, COUNT(*) as area_table_count 
                   FROM occur 
                   WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_area_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_area_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_area_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_area_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_area_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_area_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_area_table .= " GROUP BY occur_area
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

<div class="col-md-5">
    <?php if (mysqli_num_rows($result_area_table) > 0): ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title me-2">AREA DETAIL</h5>
                </div>
                <br>
                <div class="mt-3 text-center">
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
                                        <a href="drill_area.php?area=<?= urlencode($occur_area_table) ?>&month=<?= urlencode($selected_month) ?>">
                                            <?= $count_area_table ?>
                                        </a>
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
                    </div> <!-- end of row -->
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    <?php else: ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title me-2">AREA DETAIL</h5>
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
                                         




<!-- PHP / SQL QUERY AND HTML FOR "MD ATTENDING DETAIL" - COLUMN 2 ============================ -->

<?php
// Query to count the number of reports by MD attending within the desired date range
$sql_md_table = "SELECT md_attending, COUNT(*) as md_table_count 
                 FROM occur 
                 WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_md_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_md_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_md_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_md_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_md_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_md_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_md_table .= " GROUP BY md_attending
                   ORDER BY md_table_count DESC";

// Execute the query
$result_md_table = mysqli_query($conn, $sql_md_table);

// Check for SQL errors
if (!$result_md_table) {
    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_md_table);
}

// Initialize total count variable
$total_md_table_count = 0;

// Predefined set of colors
$colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

// Initialize the dynamic styles array
$md_table_styles = [];
$color_index = 0;

// Create a dynamic styles array based on query results
while ($row = mysqli_fetch_assoc($result_md_table)) {
    $md = $row['md_attending'];
    if (!isset($md_table_styles[$md])) {
        $md_table_styles[$md] = [
            'color' => $colors[$color_index % count($colors)]
        ];
        $color_index++;
    }
}

// Reset the result pointer
mysqli_data_seek($result_md_table, 0);
?>

<div class="col-md-2">
    <?php if (mysqli_num_rows($result_md_table) > 0): ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title me-2">MD ATTENDING DETAIL</h5>
                </div>
                <br>
                <div class="mt-4 text-center">
                    <div>
                        <?php while ($row_md_table = mysqli_fetch_assoc($result_md_table)): 
                            $md_attending_table = htmlspecialchars($row_md_table['md_attending']);
                            $count_md_table = htmlspecialchars($row_md_table['md_table_count']);
                            $total_md_table_count += $row_md_table['md_table_count'];
                            $color_md_table = $md_table_styles[$md_attending_table]['color'];
                        ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <div class="d-flex">
                                <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_md_table ?>"></i>
                                <div class="flex-grow-1 ms-2">
                                    <p class="mb-0"><?= $md_attending_table ?></p>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0 font-size-14">
                                    <a href="drill_md.php?md=<?= urlencode($md_attending_table) ?>&month=<?= urlencode($selected_month) ?>">
                                        <?= $count_md_table ?>
                                    </a>
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
                                <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_md_table_count) ?></strong></h5>
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
                    <h5 class="card-title me-2">MD ATTENDING</h5>
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


<!-- PHP / SQL QUERY AND HTML FOR "UNIT DETAIL" - COLUMN 2 ============================ -->

<?php
// Query to count the number of reports by unit within the desired date range
$sql_unit_table = "SELECT patient_unit, COUNT(*) as unit_table_count 
                   FROM occur 
                   WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_unit_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_unit_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_unit_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_unit_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_unit_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_unit_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_unit_table .= " GROUP BY patient_unit
                     ORDER BY unit_table_count DESC";

// Execute the query
$result_unit_table = mysqli_query($conn, $sql_unit_table);

// Check for SQL errors
if (!$result_unit_table) {
    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_unit_table);
}

// Initialize total count variable
$total_unit_table_count = 0;

// Predefined set of colors
$colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

// Initialize the dynamic styles array
$unit_table_styles = [];
$color_index = 0;

// Create a dynamic styles array based on query results
while ($row = mysqli_fetch_assoc($result_unit_table)) {
    $unit = $row['patient_unit'];
    if (!isset($unit_table_styles[$unit])) {
        $unit_table_styles[$unit] = [
            'color' => $colors[$color_index % count($colors)]
        ];
        $color_index++;
    }
}

// Reset the result pointer
mysqli_data_seek($result_unit_table, 0);
?>

<div class="col-md-3">
    <?php if (mysqli_num_rows($result_unit_table) > 0): ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title me-2">UNIT DETAIL</h5>
                </div>
                <br>
                <div class="mt-4 text-center">
                    <div>
                        <?php while ($row_unit_table = mysqli_fetch_assoc($result_unit_table)): 
                            $patient_unit_table = htmlspecialchars($row_unit_table['patient_unit']);
                            $count_unit_table = htmlspecialchars($row_unit_table['unit_table_count']);
                            $total_unit_table_count += $row_unit_table['unit_table_count'];
                            $color_unit_table = $unit_table_styles[$patient_unit_table]['color'];
                        ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <div class="d-flex">
                                <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_unit_table ?>"></i>
                                <div class="flex-grow-1 ms-2">
                                    <p class="mb-0"><?= $patient_unit_table ?></p>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0 font-size-14">
                                    <a href="drill_unit.php?unit=<?= urlencode($patient_unit_table) ?>&month=<?= urlencode($selected_month) ?>">
                                        <?= $count_unit_table ?>
                                    </a>
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
                                <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_unit_table_count) ?></strong></h5>
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
                    <h5 class="card-title me-2">UNIT</h5>
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


     

<?php
// Query to count the number of reports by severity within the desired date range
$sql_severity_table = "SELECT rm_severity, COUNT(*) as severity_table_count 
                       FROM occur 
                       WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

// Apply filters based on selected options
if ($selected_location != 'All') {
    $sql_severity_table .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
}
if ($selected_loc != 'All') {
    $sql_severity_table .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
}
if ($selected_program != 'All') {
    $sql_severity_table .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
}
if ($selected_unit != 'All') {
    $sql_severity_table .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
}
if ($selected_md != 'All') {
    $sql_severity_table .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
}
if ($selected_area != 'All') {
    $sql_severity_table .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
}

// Add GROUP BY and ORDER BY clauses
$sql_severity_table .= " GROUP BY rm_severity
                         ORDER BY severity_table_count DESC";

// Execute the query
$result_severity_table = mysqli_query($conn, $sql_severity_table);

// Check for SQL errors
if (!$result_severity_table) {
    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_severity_table);
}

// Initialize total count variable
$total_severity_table_count = 0;

// Predefined set of colors
$colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

// Initialize the dynamic styles array
$severity_table_styles = [];
$color_index = 0;

// Create a dynamic styles array based on query results
while ($row = mysqli_fetch_assoc($result_severity_table)) {
    $severity = $row['rm_severity'];
    if (!isset($severity_table_styles[$severity])) {
        $severity_table_styles[$severity] = [
            'color' => $colors[$color_index % count($colors)]
        ];
        $color_index++;
    }
}

// Reset the result pointer
mysqli_data_seek($result_severity_table, 0);
?>

<div class="col-md-2">
    <?php if (mysqli_num_rows($result_severity_table) > 0): ?>
        <div class="card h-100">
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title me-2">SEVERITY DETAIL</h5>
                </div>
                <br>
                <div class="mt-4 text-center">
                    <div>
                        <?php while ($row_severity_table = mysqli_fetch_assoc($result_severity_table)): 
                            $rm_severity_table = htmlspecialchars($row_severity_table['rm_severity']);
                            $count_severity_table = htmlspecialchars($row_severity_table['severity_table_count']);
                            $total_severity_table_count += $row_severity_table['severity_table_count'];
                            $color_severity_table = $severity_table_styles[$rm_severity_table]['color'];
                        ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <div class="d-flex">
                                <i class="mdi mdi-circle font-size-12 mt-1 <?= $color_severity_table ?>"></i>
                                <div class="flex-grow-1 ms-2">
                                    <p class="mb-0"><?= $rm_severity_table ?></p>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0 font-size-14">
                                    <a href="drill_severity.php?severity=<?= urlencode($rm_severity_table) ?>&month=<?= urlencode($selected_month) ?>">
                                        <?= $count_severity_table ?>
                                    </a>
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
                                <h5 class="mb-0 font-size-14"><strong><?= htmlspecialchars($total_severity_table_count) ?></strong></h5>
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
                    <h5 class="card-title me-2">SEVERITY DETAIL</h5>
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

</div>
                                          

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  =================================================================== -->

                        <div class="row mt-4">
                            <div class="col-md-12 col-xl-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="mb-sm-0 fw-bold"></h5>
                                        <br>
                                        <!-- Add the first day to create a valid date -->
                                        <?php $formatted_month_name = date("F Y", strtotime($selected_month . "-01")); ?> 
                                        <h5> <?php echo "FOCUS AREAS:  MTD: &nbsp&nbsp" . $formatted_month_name; ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->




<!-- ========================================  BOTTOM ROWS - 2 WIDGETS / CARDS PER ROW  =================================================================== -->



<!-- PHP / SQL QUERY AND HTML FOR RESTRAINT / SECLUSION -  COLUMN 1 ============================ -->

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
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13"> RESTRAINT / SECLUSION </p>
                                             <a href="drill_severity.php">
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

<!-- PHP / SQL QUERY AND HTML FOR CODES -  COLUMN 2 ============================ -->

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

                            <div class="col-md-6 col-xl-6">
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">CODES</p>
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
                        </div> <!-- close row -->


<!-- ========================================  BOTTOM ROWS - 2 WIDGETS / CARDS PER ROW  =================================================================== -->



<!-- PHP / SQL QUERY AND HTML FOR AMA'S -  COLUMN 1 ============================ -->

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
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">AMA </p>
                                             <a href="drill_severity.php">
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

<!-- PHP / SQL QUERY AND HTML FOR TRANSFERS -  COLUMN 2 ============================ -->

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

                            <div class="col-md-6 col-xl-6">
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">TRANSFERS</p>
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

    <!-- Apex Charts -->
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
            height: 400,
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    // Get the clicked data point (day of the month)
                    var selectedDay = config.dataPointIndex + 1; // dataPointIndex starts from 0, so add 1 for the actual day
                    
                    // Redirect to drill_day.php with the selected day as a query parameter
                    window.location.href = 'drill_day.php?day=' + selectedDay + '&month=<?php echo $selected_month; ?>';
                }
            }
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
            text: '' + processedData_category_prior_mo.formattedMonths.join(' vs ') + ' MTD',
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

<!-- CONFIGURE CHART OF REPORTS BY HOUR (TIME OF OCCURRENCE, NOT SUBMISSION TIME) ------------------------------------------- -->

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







<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




