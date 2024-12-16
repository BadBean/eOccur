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

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->

    <?php

    /*
        // to get the variable passed from bar chart click
        if (isset($_GET['category'])) {
            $reporter_category = $_GET['category'];
        } else if (isset($_GET['reporter_category'])) {  // For backward compatibility
            $reporter_category = $_GET['reporter_category'];
        }
    */

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

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  ========================================================== -->

 
<!-- Dynamically generate drop down values for all filters -------->


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


                                // Query to get unique attending physicians from the md_attending field
                                    $sql_attending = "SELECT DISTINCT md_attending 
                                                      FROM occur 
                                                      WHERE md_attending IS NOT NULL AND md_attending != ''
                                                      ORDER BY md_attending ASC";
                                    $result_attending = mysqli_query($conn, $sql_attending);
                                    if (!$result_attending) {
                                        die("Query failed: " . mysqli_error($conn));
                                    }
                                    // Fetch the attending physicians into an array
                                    $attending = ['All']; // Start with 'All' as the first option
                                    while ($row = mysqli_fetch_assoc($result_attending)) {
                                        $attending[] = $row['md_attending'];
                                    }
                                    // Set default to 'All' if no attending physician is selected or if the selected physician is invalid
                                    $selected_md = isset($_GET['md_attending']) && in_array($_GET['md_attending'], $attending) ? $_GET['md_attending'] : 'All';


                            // Clear any existing filters first
                                unset($_SESSION['filters']);

                            // Set session filters for use on drill-down pages
                                if (isset($_GET['month']) || isset($_GET['location']) || isset($_GET['patient_loc']) || 
                                    isset($_GET['patient_program']) || isset($_GET['patient_unit']) || 
                                    isset($_GET['occur_area']) || isset($_GET['category']) || 
                                    isset($_GET['rm_severity']) || isset($_GET['flag']) || isset($_GET['md_attending'])) {

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


<!-- Main Drop Down Menu for Page:  Category ------------------------->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card" style="background-color: #DCDCDC;">
                                    <div class="card-body text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <h3 class="mb-sm-0 font-size-20 fw-bold text-black me-2">CATEGORY DETAIL:</h3>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle text-black fw-bold font-size-20" href="#" id="categoryDropdown" 
                                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?php echo strtoupper($selected_category); ?>
                                                    <i class="mdi mdi-chevron-down ms-1"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                                                    <?php foreach ($categories as $category): ?>
                                                        <a class="dropdown-item <?php echo ($category == $selected_category) ? 'active' : ''; ?>" 
                                                           href="?category=<?php echo urlencode($category); ?>">
                                                            <?php echo htmlspecialchars($category); ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                   
                           
<!-- Display filter options / Second row under main page title -------->

                                       
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

                                        <!-- Attending Physician Dropdown -->
                                            <div class="dropdown">
                                                <a class="dropdown-toggle text-reset" href="#" id="summaryAttendingDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted">MD:</span> 
                                                    <span class="fw-semibold">
                                                        <?php echo htmlspecialchars($selected_md); ?>
                                                        <i class="mdi mdi-chevron-down ms-1"></i>
                                                    </span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="summaryAttendingDropdown">
                                                    <?php foreach ($attending as $md): ?>
                                                        <a class="dropdown-item <?php echo ($md == $selected_md) ? 'active' : ''; ?>" 
                                                           href="?<?php echo http_build_query(array_merge($_GET, ['md_attending' => $md])); ?>">
                                                            <?php echo htmlspecialchars($md); ?>
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


<!-- ========================================  BOOTSTRAP ROW: TOP ROW OF 3 CARDS   =================================================================== -->



<!--  TOTAL CATEGORY REPORT COUNT:  COLUMN 1 --------------------- -->

                        <br>
                        <?php
                                                        
                            // Base query

                                $sql = "SELECT COUNT(*) AS item_count 
                                        FROM occur
                                        WHERE 1=1";  // Start with a true condition

                                    
                            // Add other filters
                                if ($selected_location != 'All') {
                                    $sql .= " AND occur_location = '$selected_location'";
                                }
                                if ($selected_loc != 'All') {
                                    $sql .= " AND patient_loc = '$selected_loc'";
                                }
                                if ($selected_program != 'All') {
                                    $sql .= " AND patient_program = '$selected_program'";
                                }
                                if ($selected_unit != 'All') {
                                    $sql .= " AND patient_unit = '$selected_unit'";
                                }
                                if ($selected_area != 'All') {
                                    $sql .= " AND occur_area = '$selected_area'";
                                }
                                if ($selected_severity != 'All') {
                                    $sql .= " AND rm_severity = '$selected_severity'";
                                }
                                if ($selected_flag != 'All') {
                                    $sql .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }
                                if ($selected_md != 'All') {
                                    $sql .= " AND md_attending = '$selected_md'";
                                }
                                if ($selected_category != 'All') {
                                    $sql .= " AND reporter_category = '$selected_category'";
                                }

                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("Query error: " . mysqli_error($conn));
                            }
                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];
                        ?>


                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="drill_category.php?category=<?= $selected_category;?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-folder-star text-primary"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">CATEGORY TOTAL</p>
                                            <a href="drill_category.php">
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

<!--  CATEGORY - HIGH SEVERITY:  COLUMN 2 --------------------- -->

                        <?php 
                            // Base Query
                                $sql = "SELECT COUNT(*) AS item_count 
                                FROM occur 
                                WHERE rm_severity IN ('Severe', 'Sentinel')";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                    $sql .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                    $sql .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                    $sql .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                    $sql .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                    $sql .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                    $sql .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                    $sql .= " AND reporter_category = '$selected_category'";
                                }


                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }
                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];
                        ?>

                            <div class="col-md-6 col-xl-4">
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
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">HIGH SEVERITY</p>
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

<!--  TOTAL CATEGORY: INJURIES  COLUMN 3 --------------------- -->

                        <?php 
                            $sql = "SELECT COUNT(*) AS item_count
                                    FROM occur
                                    WHERE occur_patient_injury = 'Yes'
                                    ";

                            if ($selected_location != 'All') {
                                $sql .= " AND occur_location = '$selected_location'";
                            }

                            if ($selected_loc != 'All') {
                                $sql .= " AND patient_loc = '$selected_loc'";
                            }

                            if ($selected_program != 'All') {
                                $sql .= " AND patient_program = '$selected_program'";
                            }

                            if ($selected_unit != 'All') {
                                $sql .= " AND patient_unit = '$selected_unit'";
                            }
                            if ($selected_category != 'All') {
                                $sql .= " AND reporter_category = '$selected_category'";
                            }

                            if ($selected_area != 'All') {
                                $sql .= " AND occur_area = '$selected_area'";
                            }

                            if ($selected_severity != 'All') {
                                $sql .= " AND rm_severity = '$selected_severity'";
                            }

                            if ($selected_flag != 'All') {
                                $sql .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                            }

                            if ($selected_md != 'All') {
                                $sql .= " AND md_attending = '$selected_md'";
                            }

                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }
                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];
                        ?>

                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="drill_injury.php?pt_injury=Yes">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">INJURIES</p>
                                              <a href="drill_injury.php?pt_injury=Yes">
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
                        </div> <!-- end row -->

                        <br>


<!-- =============================================== BOOTSTRAP ROW: CATEGORY TREND CHART  ================================================= -->



<!--  COLUMN CHART - TOTAL CATEGORY REPORTS BY MONTH:  COLUMN 1 --------------------- -->

                        <?php 
                            // Generate a list of the last 12 months with proper sorting
                                $months = [];
                                $current_month = new DateTime();
                                $current_month->modify('first day of this month');
                            // Start from 11 months ago
                                $start_month = clone $current_month;
                                $start_month->modify('-11 months');
                            // Generate array of months
                                while ($start_month <= $current_month) {
                                   $months[] = $start_month->format('Y-m');
                                   $start_month->modify('+1 month');
                                }

                            // Query to count # of reports 
                                $sql_count = "
                                   SELECT 
                                       DATE_FORMAT(occur_date, '%Y-%m') AS month_year, 
                                       COUNT(*) AS item_count
                                   FROM 
                                       occur
                                   WHERE 1=1 AND
                                       occur_date >= DATE_FORMAT(CURDATE() - INTERVAL 12 MONTH, '%Y-%m-01')";

                            // Add category filter
                                if ($selected_category != 'All') {
                                   $sql_count .= " AND reporter_category = '$selected_category'";
                                }

                            // Add other filters
                                if ($selected_location != 'All') {
                                   $sql_count .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                   $sql_count .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                   $sql_count .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                   $sql_count .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                   $sql_count .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                   $sql_count .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                   $sql_count .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                    $sql_count .= " AND md_attending = '$selected_md'";
                                }

                            // Add GROUP BY and ORDER BY after filters
                                $sql_count .= " GROUP BY DATE_FORMAT(occur_date, '%Y-%m')
                                               ORDER BY month_year ASC";

                                $result_count = mysqli_query($conn, $sql_count);
                                if (!$result_count) {
                                   die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                }

                            // Initialize data array with zeros for all months
                                $data = array_fill_keys($months, 0);

                            // Fill in the data array with actual counts from the query
                                while ($row_count = mysqli_fetch_assoc($result_count)) {
                                   if (isset($data[$row_count['month_year']])) {
                                       $data[$row_count['month_year']] = (int)$row_count['item_count'];
                                   }
                                }

                                // Prepare data for ApexCharts
                                $months_js = json_encode(array_keys($data));
                                $counts_js = json_encode(array_values($data));

                        ?>                        

    <!-- RENDER CHART / CHART OPTIONS -->

                        <div class="row">
                            <div class="col-9">
                                <div class="card" style="height: 400px;">
                                    <div class="card-body pb-2">
                                        <div id="chart" style="height: 100%;"></div>
                                        <script>
                                            var options = {
                                                chart: {
                                                    type: 'bar',
                                                    height: '100%'
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
                                                    text: '<?php echo 'MONTHLY TREND: ' . strtoupper($reporter_category); ?>',
                                                    align: 'center'
                                                }
                                            };
                                            var chart = new ApexCharts(document.querySelector("#chart"), options);
                                            chart.render();
                                        </script>
                                    </div>
                                </div> 
                            </div> <!-- end col -->
                                                         

<!--  SUBCATEGORY REPORTS:  TABLE -  COLUMN 2 --------------------- -->
                
    <!-- Query for Table ------------------------>
        
                        <?php
                                if ($selected_category != '' && $selected_category != 'All') {
                                    $sql_subcategory_table = "SELECT occur_subcategory, COUNT(*) as subcategory_table_count 
                                                             FROM occur 
                                                             WHERE 1=1";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                    $sql_subcategory_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                    $sql_subcategory_table .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql_subcategory_table .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                    $sql_subcategory_table .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql_subcategory_table .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                    $sql_subcategory_table .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql_subcategory_table .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                    $sql_subcategory_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                    $sql_subcategory_table .= " AND md_attending = '$selected_md'";
                                }

                                $sql_subcategory_table .= " AND reporter_category = '$selected_category'";

                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                                $sql_subcategory_table .= " GROUP BY occur_subcategory
                                                           ORDER BY subcategory_table_count DESC";

                                $result_subcategory_table = mysqli_query($conn, $sql_subcategory_table);
                                if (!$result_subcategory_table) {
                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_subcategory_table);
                                }

                            // Initialize total count variable
                                $total_subcategory_table_count = 0;

                            // Predefined set of colors
                                $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                                
                            // Initialize the dynamic styles array
                                $subcategory_table_styles = [];
                                
                            // Create a dynamic styles array based on query results
                                $color_index = 0;
                                while ($row = mysqli_fetch_assoc($result_subcategory_table)) {
                                    $subcategory = $row['occur_subcategory'];
                                    if (!isset($subcategory_table_styles[$subcategory])) {
                                        $subcategory_table_styles[$subcategory] = [
                                            'color' => $colors[$color_index % count($colors)]
                                        ];
                                        $color_index++;
                                    }
                                }

                            // Reset the result pointer
                                mysqli_data_seek($result_subcategory_table, 0);
                        
                            } else {
                                $result_subcategory_table = false;
                                $total_subcategory_table_count = 0;
                            }
                        ?>

    <!-- Render Table ------------------------>
                            <div class="col-md-3">
                                <div class="card h-100">
                                    <div class="card-body pb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="card-title me-2">SUBCATEGORY</h5>
                                        </div>
                                        <div class="mt-2 flex-grow-1 overflow-auto">
                                            <?php if ($result_subcategory_table && mysqli_num_rows($result_subcategory_table) > 0): ?>
                                                <?php while ($row_subcategory_table = mysqli_fetch_assoc($result_subcategory_table)):
                                                    $occur_subcategory_table = htmlspecialchars($row_subcategory_table['occur_subcategory']);
                                                    $count_subcategory_table = htmlspecialchars($row_subcategory_table['subcategory_table_count']);
                                                    $total_subcategory_table_count += $row_subcategory_table['subcategory_table_count'];
                                                    $color_subcategory_table = $subcategory_table_styles[$occur_subcategory_table]['color'];
                                                ?>
                                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <i class="mdi mdi-circle font-size-10 me-2 <?php echo $color_subcategory_table; ?>"></i>
                                                            <p class="mb-0" style="font-size: 0.9rem;"><?php echo $occur_subcategory_table; ?></p>
                                                        </div>
                                                        <h6 class="mb-0 font-size-14">
                                                            <a href="drill_subcategory.php?subcategory=<?php echo urlencode($occur_subcategory_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                <?php echo $count_subcategory_table; ?>
                                                            </a>
                                                        </h6>
                                                    </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <div class="text-center">
                                                    <p>Please select a category to view subcategories.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($result_subcategory_table && mysqli_num_rows($result_subcategory_table) > 0): ?>
                                            <div class="mt-auto pt-2 border-top">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0"><strong>Total</strong></p>
                                                    <h5 class="mb-0 font-size-14">
                                                        <strong><?php echo htmlspecialchars($total_subcategory_table_count); ?></strong>
                                                    </h5>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                  
                           

                       
<!-- ========================================  MIDDLE ROW - 4 WIDGETS / CARDS  =================================================================== -->

                        <div class = "row mt-4">


<!--  LEVEL OF CARE DETAIL TABLE -  COLUMN 1 ------------------ -->

                            <?php
                            
                                $sql_loc_table = "SELECT patient_loc, COUNT(*) as loc_table_count 
                                                  FROM occur
                                                  WHERE 1=1";

                                if ($selected_location != 'All') {
                                    $sql_loc_table .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql_loc_table .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                    $sql_loc_table .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql_loc_table .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                    $sql_loc_table .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql_loc_table .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                    $sql_loc_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                    $sql_loc_table .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                    $sql_loc_table .= " AND reporter_category = '$selected_category'";
                                }

                                // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                                $sql_loc_table .= " GROUP BY patient_loc
                                                    ORDER BY loc_table_count DESC";

                                // Rest of your existing code remains the same
                                $result_loc_table = mysqli_query($conn, $sql_loc_table);
                                if (!$result_loc_table) {
                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_loc_table);
                                }

                                // Initialize total count variable
                                $total_loc_table_count = 0;
                                // Predefined set of colors
                                $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                                // Initialize the dynamic styles array
                                $loc_table_styles = [];
                                // Create a dynamic styles array based on query results
                                $color_index = 0;
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


                            <div class="col-md-3">
                                <?php if (mysqli_num_rows($result_loc_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">LEVEL OF CARE</h5>
                                            </div>

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
                                                                <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_loc_table; ?>"></i>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <p class="mb-0"><?php echo $patient_loc_table; ?></p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0 font-size-14">
                                                                    <a href="drill_loc.php?loc=<?php echo urlencode($patient_loc_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                        <?php echo $count_loc_table; ?>
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
                                                                <strong><?php echo htmlspecialchars($total_loc_table_count); ?></strong>
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
                                                <h5 class="card-title me-2">LEVEL OF CARE</h5>
                                                <!-- <a href="detail_month.php?month=<?php echo htmlspecialchars($selected_month); ?>#myTable" class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard 
                                                </a>
                                                -->
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
                            </div> <!-- end col -->



<!--  LOCATION DETAIL TABLE -  COLUMN 2 ---------------------- -->


                        <?php
                            
                               $sql_location_table =  "SELECT occur_location, COUNT(*) as location_table_count 
                                                       FROM occur 
                                                       WHERE 1=1";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                    $sql_location_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                    $sql_location_table .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql_location_table .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                    $sql_location_table .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql_location_table .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                    $sql_location_table .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql_location_table .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                    $sql_location_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                $sql_location_table .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                $sql_location_table .= " AND reporter_category = '$selected_category'";
                                }

                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                                $sql_location_table .= " GROUP BY occur_location 
                                                        ORDER BY location_table_count DESC";

                                      
                                $result_location_table = mysqli_query($conn, $sql_location_table);
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

                        ?>

                            <div class="col-md-3">
                                <?php if (mysqli_num_rows($result_location_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">LOCATION</h5>
                                            </div>

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
                                                                <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_location_table; ?>"></i>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <p class="mb-0"><?php echo $occur_location_table; ?></p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0 font-size-14">
                                                                    <a href="drill_location.php?location=<?php echo urlencode($occur_location_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                        <?php echo $count_location_table; ?>
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
                                                                <strong><?php echo htmlspecialchars($total_location_table_count); ?></strong>
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
                                                <h5 class="card-title me-2">LOCATION DETAIL</h5>
                                                <!--
                                                <a href="detail_month.php?month=<?php //echo htmlspecialchars($selected_month); ?>#myTable" class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                                </a>
                                                -->
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
                            </div> <!-- end col -->



<!--  PROGRAM DETAIL TABLE -  COLUMN 3 ----------------------- -->

                        <?php
                           
                            $sql_program_table = "SELECT patient_program, COUNT(*) as program_table_count 
                                                  FROM occur 
                                                  WHERE 1=1";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                    $sql_program_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                    $sql_program_table .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql_program_table .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                    $sql_program_table .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql_program_table .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                    $sql_program_table .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql_program_table .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                    $sql_program_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                $sql_program_table .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                    $sql_program_table .= " AND reporter_category = '$selected_category'";
                                }

                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                                $sql_program_table .= " GROUP BY patient_program 
                                                       ORDER BY program_table_count DESC";

                            // Rest of your existing code remains the same
                                $result_program_table = mysqli_query($conn, $sql_program_table);
                                if (!$result_program_table) {
                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_program_table);
                                }

                            // Initialize total count variable
                                $total_program_table_count = 0;
                            
                            // Predefined set of colors
                                $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                            
                            // Initialize the dynamic styles array
                                $program_table_styles = [];
                            
                            // Create a dynamic styles array based on query results
                                $color_index = 0;
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

                            <div class="col-md-3">
                                <?php if (mysqli_num_rows($result_program_table) > 0): ?>
                                    <div class="card h-100">
                                        <div class="card-body pb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title me-2">PROGRAM</h5>
                                            </div>

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
                                                                <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_program_table; ?>"></i>
                                                                <div class="flex-grow-1 ms-2">
                                                                    <p class="mb-0"><?php echo $patient_program_table; ?></p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0 font-size-14">
                                                                    <a href="drill_program.php?program=<?php echo urlencode($patient_program_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                        <?php echo $count_program_table; ?>
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
                                                                <strong><?php echo htmlspecialchars($total_program_table_count); ?></strong>
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
                                                <h5 class="card-title me-2">PROGRAM DETAIL</h5>
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



<!-- AERA DETAIL TABLE -  COLUMN 4 --------------------------- -->

                    <?php
                            $sql_area_table = "SELECT occur_area, COUNT(*) as area_table_count 
                                               FROM occur 
                                               WHERE 1=1";

                            if ($selected_location != 'All') {
                               $sql_area_table .= " AND occur_location = '$selected_location'";
                            }

                            if ($selected_loc != 'All') {
                               $sql_area_table .= " AND patient_loc = '$selected_loc'";
                            }

                            if ($selected_program != 'All') {
                               $sql_area_table .= " AND patient_program = '$selected_program'";
                            }

                            if ($selected_unit != 'All') {
                               $sql_area_table .= " AND patient_unit = '$selected_unit'";
                            }

                            if ($selected_area != 'All') {
                               $sql_area_table .= " AND occur_area = '$selected_area'";
                            }

                            if ($selected_severity != 'All') {
                               $sql_area_table .= " AND rm_severity = '$selected_severity'";
                            }

                            if ($selected_flag != 'All') {
                               $sql_area_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                            }

                            if ($selected_md != 'All') {
                                $sql_area_table .= " AND md_attending = '$selected_md'";
                            }

                            if ($selected_category != 'All') {
                                $sql_area_table .= " AND reporter_category = '$selected_category'";
                            }

                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                            $sql_area_table .= " GROUP BY occur_area 
                                                ORDER BY area_table_count DESC";

                            $result_area_table = mysqli_query($conn, $sql_area_table);
                            if (!$result_area_table) {
                               die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_area_table);
                            }

                            // Initialize total count variable
                            $total_area_table_count = 0;
                            // Predefined set of colors
                            $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                            // Initialize the dynamic styles array
                            $area_table_styles = [];
                            // Create a dynamic styles array based on query results
                            $color_index = 0;
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

                        <div class="col-md-3">
                            <div class="card h-100" style="height: 400px;">
                                <div class="card-body pb-2 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title me-2">AREA</h5>
                                        <?php /* Commented dashboard link
                                        <a href="dashboard_area.php" class="btn btn-sm btn-primary">
                                            <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                        </a>
                                        */ ?>
                                    </div>
                                    
                                    <div class="mt-2 flex-grow-1 overflow-auto">
                                        <?php if (mysqli_num_rows($result_area_table) > 0): ?>
                                            <?php while ($row_area_table = mysqli_fetch_assoc($result_area_table)):
                                                $occur_area_table = htmlspecialchars($row_area_table['occur_area']);
                                                $count_area_table = htmlspecialchars($row_area_table['area_table_count']);
                                                $total_area_table_count += $row_area_table['area_table_count'];
                                                $color_area_table = $area_table_styles[$occur_area_table]['color'];
                                            ?>
                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="mdi mdi-circle font-size-10 me-2 <?php echo $color_area_table; ?>"></i>
                                                        <p class="mb-0" style="font-size: 0.9rem;"><?php echo $occur_area_table; ?></p>
                                                    </div>
                                                    <h6 class="mb-0 font-size-14">
                                                        <a href="drill_area.php?area=<?php echo urlencode($occur_area_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                            <?php echo $count_area_table; ?>
                                                        </a>
                                                    </h6>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <div class="text-center">
                                                <p>No data available.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-auto pt-2 border-top">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0"><strong>Total</strong></p>
                                            <h5 class="mb-0 font-size-14">
                                                <strong><?php echo htmlspecialchars($total_area_table_count); ?></strong>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div> <!-- end of row -->



<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


    <!-- SQL QUERY FOR TABLE ---------------------------------->

                        <?php 
                            $sql = "SELECT * 
                                    FROM occur 
                                    WHERE 1=1
                                    ";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                   $sql .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                   $sql .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                   $sql .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                   $sql .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                   $sql .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                   $sql .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                   $sql .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                   $sql .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                    $sql .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                    $sql .= " AND reporter_category = '$selected_category'";
                                }

                                $result = mysqli_query($conn, $sql);
                                if (!$result) {
                                   die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); // Updated to mysqli_error
                                }

                                $numrows = mysqli_num_rows($result);
                        ?>

    <!-- RENDER TABLE ----------------------------------------->

                           
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                         <!-- Flex Container for Title and Buttons -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                             <h4 class="card-title">Reports Submitted:&nbsp&nbsp<?php echo $selected_category ?></h4>
                                            <div id="buttonsContainer3"></div> <!-- Container for DataTables Buttons -->
                                        </div>

                                     
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

                                        <table id="myTable3" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
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
                                                    <th>Location</th> 
                                                    <th>Program</th> 
                                                    <th>Area</th> 
                                                    <th>Category</th>
                                                    <th>Subcategory</th>
                                                    <th>Severity</th>
                                                    <th>Injury?</th>
                                                    <!-- <th>Attending</th> -->
                                                   
                                                 


                                                    <th>Description</th>
                                                    <th>Intervention</th>
                                                    <th>Time</th>

                                                    <th>Restraint</th>
                                                    <th>Seclusion</th>  
                                                    <th>Status</th>    
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
                                                            echo "<td style='white-space:nowrap'>{$row['occur_location']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_program']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_area']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['reporter_category']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_subcategory']}</td>";
                                                            echo "<td>{$row['rm_severity']}</td>";
                                                            echo "<td>{$row['occur_patient_injury']}</td>";
                                                          
                                                            //echo "<td>{$row['md_attending']}</td>";
                                                           

                                                            /*
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
                                                            */
                                                            
                                                            echo "<td style='white-space:nowrap'>{$row['occur_description']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_intervention']}</td>";
                                                            echo "<td>{$row['occur_time']}</td>";

                                                            echo "<td>{$row['occur_patient_restraint']}</td>";
                                                            echo "<td>{$row['occur_patient_seclusion']}</td>";

                                                            echo "<td>{$row['occur_status']}</td>";

                                                        echo "</tr>";
                                                    }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>  <!-- end card-body -->
                                </div>  <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->



<!-- ========================================  BOOTSTRAP ROW:  HOURLY CHART   ========================================================= -->

        
        <!-- QUERY FOR CHART: REPORTS BY HOUR OF THE DAY -->

                        <?php
                           
                            // Set values for reports by HOUR OF THE DAY
                                $sql_report_hourly = "
                                    SELECT 
                                        HOUR(occur_time) AS hour,
                                        COUNT(*) AS item_report_hourly
                                    FROM 
                                        occur
                                     WHERE 1=1";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                    $sql_report_hourly .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                    $sql_report_hourly .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                    $sql_report_hourly .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                    $sql_report_hourly .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                    $sql_report_hourly .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                    $sql_report_hourly .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                    $sql_report_hourly .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                    $sql_report_hourly .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                    $sql_report_hourly .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                    $sql_report_hourly .= " AND reporter_category = '$selected_category'";
                                }

                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                                $sql_report_hourly .= " GROUP BY HOUR(occur_time)
                                                        ORDER BY HOUR(occur_time)";

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


        <!-- RENDER CHART: REPORTS BY HOUR OF THE DAY -->

                        <div class="row">
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
                        </div>

                        <script>
                            var chartData_report_hourly = <?php echo $chart_data_js_report_hourly; ?>;
                            var hourLabels = <?php echo $hour_labels; ?>;
                        </script>


<!-- ========================================  MIDDLE ROW - 3 WIDGETS / CARDS  =================================================================== -->

                        <div class = "row mt-4">


                    <!--  ATTENDING MD TABLE -  COLUMN 1 --------------------- -->

                            <?php
                                                
                                    $sql_md_table =  "SELECT md_attending, COUNT(*) as md_table_count 
                                                      FROM occur
                                                      WHERE 1=1";

                                // Only add date filter if month was explicitly selected
                                    if (isset($_GET['month'])) {
                                        $sql_md_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                    }

                                    if ($selected_location != 'All') {
                                        $sql_md_table .= " AND occur_location = '$selected_location'";
                                    }

                                    if ($selected_loc != 'All') {
                                        $sql_md_table .= " AND patient_loc = '$selected_loc'";
                                    }

                                    if ($selected_program != 'All') {
                                        $sql_md_table .= " AND patient_program = '$selected_program'";
                                    }

                                    if ($selected_unit != 'All') {
                                        $sql_md_table .= " AND patient_unit = '$selected_unit'";
                                    }

                                    if ($selected_area != 'All') {
                                        $sql_md_table .= " AND occur_area = '$selected_area'";
                                    }

                                    if ($selected_severity != 'All') {
                                        $sql_md_table .= " AND rm_severity = '$selected_severity'";
                                    }

                                    if ($selected_flag != 'All') {
                                        $sql_md_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                    }

                                    if ($selected_md != 'All') {
                                        $sql_md_table .= " AND md_attending = '$selected_md'";
                                    }

                                    if ($selected_category != 'All') {
                                        $sql_md_table .= " AND reporter_category = '$selected_category'";
                                    }

                                // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                                    $sql_md_table .= " GROUP BY md_attending
                                                       ORDER BY md_table_count DESC";

                                // Rest of your existing code remains the same
                                    $result_md_table = mysqli_query($conn, $sql_md_table);
                                    if (!$result_md_table) {
                                        die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_md_table);
                                    }

                                // Initialize total count variable
                                    $total_md_table_count = 0;
                                
                                // Predefined set of colors
                                    $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                                
                                // Initialize the dynamic styles array
                                    $md_table_styles = [];
                                
                                // Create a dynamic styles array based on query results
                                    $color_index = 0;
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


                                <div class="col-md-3">
                                    <?php if (mysqli_num_rows($result_md_table) > 0): ?>
                                        <div class="card h-100">
                                            <div class="card-body pb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h5 class="card-title me-2">ATTENDING MD</h5>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <div>
                                                        <?php while ($row_md_table = mysqli_fetch_assoc($result_md_table)):
                                                            $attending_md = htmlspecialchars($row_md_table['md_attending']);
                                                            $count_md_table = htmlspecialchars($row_md_table['md_table_count']);
                                                            $total_md_table_count += $row_md_table['md_table_count'];
                                                            $color_md_table = $md_table_styles[$attending_md]['color'];
                                                        ?>
                                                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                                                <div class="d-flex">
                                                                    <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_md_table; ?>"></i>
                                                                    <div class="flex-grow-1 ms-2">
                                                                        <p class="mb-0"><?php echo $attending_md; ?></p>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <h5 class="mb-0 font-size-14">
                                                                        <a href="drill_md.php?md=<?php echo urlencode($attending_md); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                            <?php echo $count_md_table; ?>
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
                                                                    <strong><?php echo htmlspecialchars($total_md_table_count); ?></strong>
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
                                                    <h5 class="card-title me-2">ATTENDING MD</h5>
                                                    <!--
                                                    <a href="detail_month.php?month=<?php echo htmlspecialchars($selected_month); ?>#myTable" class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                                    </a>
                                                    -->
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
                                </div> <!-- end col -->


                    <!--  UNIT DETAIL TABLE -  COLUMN 2 --------------------- -->

                        <?php
                                   
                           $sql_unit_table = "SELECT patient_unit, COUNT(*) as unit_table_count 
                                              FROM occur 
                                              WHERE 1=1";

                            // Only add date filter if month was explicitly selected
                               if (isset($_GET['month'])) {
                                   $sql_unit_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                               }

                               if ($selected_location != 'All') {
                                   $sql_unit_table .= " AND occur_location = '$selected_location'";
                               }

                               if ($selected_loc != 'All') {
                                   $sql_unit_table .= " AND patient_loc = '$selected_loc'";
                               }

                               if ($selected_program != 'All') {
                                   $sql_unit_table .= " AND patient_program = '$selected_program'";
                               }

                               if ($selected_unit != 'All') {
                                   $sql_unit_table .= " AND patient_unit = '$selected_unit'";
                               }

                               if ($selected_area != 'All') {
                                   $sql_unit_table .= " AND occur_area = '$selected_area'";
                               }

                               if ($selected_severity != 'All') {
                                   $sql_unit_table .= " AND rm_severity = '$selected_severity'";
                               }

                               if ($selected_flag != 'All') {
                                   $sql_unit_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                               }

                               if ($selected_md != 'All') {
                                   $sql_unit_table .= " AND md_attending = '$selected_md'";
                               }

                               if ($selected_category != 'All') {
                                   $sql_unit_table .= " AND reporter_category = '$selected_category'";
                               }

                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                               $sql_unit_table .= " GROUP BY patient_unit 
                                                   ORDER BY unit_table_count DESC";

                            // Rest of your existing code remains the same                         
                               $result_unit_table = mysqli_query($conn, $sql_unit_table);
                               if (!$result_unit_table) {
                                   die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_unit_table);
                               }

                            // Initialize total count variable
                               $total_unit_table_count = 0;

                            // Predefined set of colors
                               $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];

                            // Initialize the dynamic styles array
                               $unit_table_styles = [];

                            // Create a dynamic styles array based on query results
                                $color_index = 0;
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
                                                               <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_unit_table; ?>"></i>
                                                               <div class="flex-grow-1 ms-2">
                                                                   <p class="mb-0"><?php echo $patient_unit_table; ?></p>
                                                               </div>
                                                           </div>
                                                           <div>
                                                               <h5 class="mb-0 font-size-14">
                                                                   <a href="drill_unit.php?unit=<?php echo urlencode($patient_unit_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                       <?php echo $count_unit_table; ?>
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
                                                               <strong><?php echo htmlspecialchars($total_unit_table_count); ?></strong>
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
                                               <h5 class="card-title me-2">UNIT DETAIL</h5>
                                               <!--
                                               <a href="detail_month.php?month=<?php echo htmlspecialchars($selected_month); ?>#myTable" class="btn btn-sm btn-primary">
                                                   <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                               </a>
                                                -->
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
                            </div> <!-- end col -->


                    <!--  SEVERITY DETAIL TABLE -  COLUMN 3 --------------------- -->

                       <?php
                          
                           $sql_severity_table = "SELECT rm_severity, COUNT(*) as severity_table_count 
                                                 FROM occur 
                                                   WHERE 1=1";

                            // Only add date filter if month was explicitly selected
                                if (isset($_GET['month'])) {
                                   $sql_severity_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                                }

                                if ($selected_location != 'All') {
                                   $sql_severity_table .= " AND occur_location = '$selected_location'";
                                }

                                if ($selected_loc != 'All') {
                                   $sql_severity_table .= " AND patient_loc = '$selected_loc'";
                                }

                                if ($selected_program != 'All') {
                                   $sql_severity_table .= " AND patient_program = '$selected_program'";
                                }

                                if ($selected_unit != 'All') {
                                   $sql_severity_table .= " AND patient_unit = '$selected_unit'";
                                }

                                if ($selected_area != 'All') {
                                   $sql_severity_table .= " AND occur_area = '$selected_area'";
                                }

                                if ($selected_severity != 'All') {
                                   $sql_severity_table .= " AND rm_severity = '$selected_severity'";
                                }

                                if ($selected_flag != 'All') {
                                   $sql_severity_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                                }

                                if ($selected_md != 'All') {
                                $sql_severity_table .= " AND md_attending = '$selected_md'";
                                }

                                if ($selected_category != 'All') {
                                  $sql_severity_table .= " AND reporter_category = '$selected_category'";
                                }

                             

                           // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                               $sql_severity_table .= " GROUP BY rm_severity 
                                                      ORDER BY severity_table_count DESC";

                           // Rest of your existing code remains the same
                               $result_severity_table = mysqli_query($conn, $sql_severity_table);
                               if (!$result_severity_table) {
                                   die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_severity_table);
                               }

                           // Initialize total count variable
                               $total_severity_table_count = 0;
                           
                           // Predefined set of colors
                               $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                           
                           // Initialize the dynamic styles array
                               $severity_table_styles = [];
                           
                           // Create a dynamic styles array based on query results
                               $color_index = 0;
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

                           <div class="col-md-3">
                               <?php if (mysqli_num_rows($result_severity_table) > 0): ?>
                                   <div class="card h-100">
                                       <div class="card-body pb-2">
                                           <div class="d-flex justify-content-between align-items-center mb-2">
                                               <h5 class="card-title me-2">SEVERITY DETAIL</h5>
                                           </div>

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
                                                               <i class="mdi mdi-circle font-size-12 mt-1 <?php echo $color_severity_table; ?>"></i>
                                                               <div class="flex-grow-1 ms-2">
                                                                   <p class="mb-0"><?php echo $rm_severity_table; ?></p>
                                                               </div>
                                                           </div>
                                                           <div>
                                                               <h5 class="mb-0 font-size-14">
                                                                   <a href="drill_severity.php?severity=<?php echo urlencode($rm_severity_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                                       <?php echo $count_severity_table; ?>
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
                                                               <strong><?php echo htmlspecialchars($total_severity_table_count); ?></strong>
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
                                               <h5 class="card-title me-2">SEVERITY DETAIL</h5>
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


                    <!-- PATIENT INJURY - COLUMN 4 ============================ -->

                        <?php
                            $sql_injury_table = "SELECT occur_patient_injury, COUNT(*) as injury_table_count 
                                                 FROM occur 
                                                 WHERE 1=1
                                                  ";

                            // Only add date filter if month was explicitly selected
                               if (isset($_GET['month'])) {
                                  $sql_injury_table .= " AND occur_date >= '$start_date' AND occur_date < '$end_date'";
                               }

                               if ($selected_location != 'All') {
                                  $sql_injury_table .= " AND occur_location = '$selected_location'";
                               }

                               if ($selected_loc != 'All') {
                                  $sql_injury_table .= " AND patient_loc = '$selected_loc'";
                               }

                               if ($selected_program != 'All') {
                                  $sql_injury_table .= " AND patient_program = '$selected_program'";
                               }

                               if ($selected_unit != 'All') {
                                  $sql_injury_table .= " AND patient_unit = '$selected_unit'";
                               }

                               if ($selected_area != 'All') {
                                  $sql_injury_table .= " AND occur_area = '$selected_area'";
                               }

                               if ($selected_severity != 'All') {
                                  $sql_injury_table .= " AND rm_severity = '$selected_severity'";
                               }

                               if ($selected_flag != 'All') {
                                  $sql_injury_table .= " AND FIND_IN_SET('$selected_flag', occur_flag)";
                               }

                               if ($selected_md != 'All') {
                                   $sql_injury_table .= " AND md_attending = '$selected_md'";
                               }

                               if ($selected_category != 'All') {
                                   $sql_injury_table .= " AND reporter_category = '$selected_category'";
                               }

                              
                            // Add the GROUP BY and ORDER BY clauses after all WHERE conditions
                               $sql_injury_table .= " GROUP BY occur_patient_injury 
                                                   ORDER BY injury_table_count DESC";

                            // Rest of your existing code remains the same
                               $result_injury_table = mysqli_query($conn, $sql_injury_table);
                               if (!$result_injury_table) {
                                  die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_injury_table);
                               }

                            // Initialize total count variable
                               $total_injury_table_count = 0;

                            // Predefined set of colors
                               $colors = ['text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-dark'];
                               
                            // Initialize the dynamic styles array
                               $injury_table_styles = [];
                               
                            // Create a dynamic styles array based on query results
                               $color_index = 0;
                               while ($row = mysqli_fetch_assoc($result_injury_table)) {
                                  $injury = $row['occur_patient_injury'];
                                  if (!isset($injury_table_styles[$injury])) {
                                      $injury_table_styles[$injury] = [
                                          'color' => $colors[$color_index % count($colors)]
                                      ];
                                      $color_index++;
                                  }
                               }
                               // Reset the result pointer
                               mysqli_data_seek($result_injury_table, 0);
                        ?>

                           <div class="col-md-3">
                               <div class="card h-100" style="height: 400px;">
                                   <div class="card-body pb-2 d-flex flex-column">
                                       <div class="d-flex justify-content-between align-items-center mb-2">
                                           <h5 class="card-title me-2">PATIENT INJURY</h5>
                                           <?php /* Commented dashboard link
                                           <a href="dashboard_injury.php" class="btn btn-sm btn-primary">
                                               <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                                           </a>
                                           */ ?>
                                       </div>
                                       
                                       <div class="mt-2 flex-grow-1 overflow-auto">
                                           <?php if (mysqli_num_rows($result_injury_table) > 0): ?>
                                               <?php while ($row_injury_table = mysqli_fetch_assoc($result_injury_table)):
                                                   $occur_patient_injury_table = htmlspecialchars($row_injury_table['occur_patient_injury']);
                                                   $count_injury_table = htmlspecialchars($row_injury_table['injury_table_count']);
                                                   $total_injury_table_count += $row_injury_table['injury_table_count'];
                                                   $color_injury_table = $injury_table_styles[$occur_patient_injury_table]['color'];
                                               ?>
                                                   <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                       <div class="d-flex align-items-center">
                                                           <i class="mdi mdi-circle font-size-10 me-2 <?php echo $color_injury_table; ?>"></i>
                                                           <p class="mb-0" style="font-size: 0.9rem;"><?php echo $occur_patient_injury_table; ?></p>
                                                       </div>
                                                       <h6 class="mb-0 font-size-14">
                                                           <a href="drill_injury.php?pt_injury=<?php echo urlencode($occur_patient_injury_table); ?>&category=<?php echo urlencode($selected_category); ?>">
                                                               <?php echo $count_injury_table; ?>
                                                           </a>
                                                       </h6>
                                                   </div>
                                               <?php endwhile; ?>
                                           <?php else: ?>
                                               <div class="text-center">
                                                   <p>No data available.</p>
                                               </div>
                                           <?php endif; ?>
                                       </div>

                                       <div class="mt-auto pt-2 border-top">
                                           <div class="d-flex justify-content-between align-items-center">
                                               <p class="mb-0"><strong>Total</strong></p>
                                               <h5 class="mb-0 font-size-14">
                                                   <strong><?php echo htmlspecialchars($total_injury_table_count); ?></strong>
                                               </h5>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </div> <!-- end of row -->


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE - ALL OPEN REPORTS  ========================================================= -->


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
                                if ($selected_md != 'All') {
                                    $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                                }
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
                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <th>Description</th>
                                                        <th>Mgr Status</th>
                                                        <th>Target Date</th>
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
                                        
                                    </div>  <!-- end card-body -->
                                </div>  <!-- end card -->
                            </div>  <!-- end col -->
                        </div>  <!-- end row -->

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
                                if ($selected_md != 'All') {
                                    $sql .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
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

                             // Process Results
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
                                                    <th>Date</th>
                                                    <th>Category</th>
                                                    <th>Action Plan</th>
                                                    <th>Notes</th>
                                                    <th>Target Date</th>
                                                 </tr>
                                            </thead>        

                                            <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            echo "<td><a href='rm_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
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
                                                            echo "<td>";
                                                                if (!empty($row['target_date'])) {
                                                                    echo date("m/d/y", strtotime($row['target_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
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





<!-- ========================================  DATA DOWNLOAD SECTION:  =================================================================== -->


    <!-- SET START AND END DATE VARIABLES -------------------- -->
            <?php
                //ERROR REPORTING
                    //error_reporting(E_ALL);
                    //ini_set('display_errors', 1);

                // Set the start date to the first day of the month 12 months ago
                    $start_date = date('Y-m-01', strtotime('-11 months')); // -11 because we want to include the current month

                    // Set the end date to the first day of the next month (equivalent to the last day of the current month for comparison purposes)
                    $end_date = date('Y-m-01', strtotime('+1 month'));

                // Debug output
                    //echo "Debug: start_date = $start_date<br>";
                    //echo "Debug: end_date = $end_date<br>";
            ?>


    <!-- PAGE HEADLINE / TOGGLE TO SHOW/HIDE  -------------------- -->

            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-sm-0 font-size-16 fw-bold">CATEGORY DATA FOR DOWNLOAD: &nbsp;&nbsp; <?php echo strtoupper($selected_category); ?></h4>
                            <p id="toggle-btn" style="color: blue; cursor: pointer;">Click to Show / Hide</p>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->


    <!-- TTM DATA DOWNLOAD TO CSV BUTTON -------------------- -->


                <div class="row" id="toggle-content" style="display:none;"> <!-- This div will be toggled -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">



                <?php
                // CSV Export button aligned to the right, placed above the table.  
                // Add hidden variables to pass variables to the export file
                echo '
                <div class="d-flex justify-content-end mb-3">
                    <form action="export_to_csv.php" method="POST">
                        <input type="hidden" name="start_date" value="' . htmlspecialchars($start_date) . '">
                        <input type="hidden" name="end_date" value="' . htmlspecialchars($end_date) . '">
                        <input type="hidden" name="reporter_category" value="' . htmlspecialchars($selected_category) . '">
                        <button type="submit" class="btn btn-primary">Download CSV</button>
                    </form>
                </div>';


    // ----  GENERATE TTM DATA  -------------------- -->
              
                // Generate a list of the last 12 months in 'Y-m' format
                $months = [];
                for ($i = 0; $i < 12; $i++) {
                    $months[] = date('Y-m', strtotime("-$i months"));
                }
                $months = array_reverse($months); // Reverse to display in ascending order

                // Debug output for $months
                // echo "Debug: Months array:<br>";
                // var_dump($months);

                // Function to display data block with customized headers
                function display_data_block($label, $data, $months, $header_class) {
                    //echo "Debug: Label=$label, Data count=" . count($data) . ", Months count=" . count($months) . "<br>";
                    echo "<table class='table table-striped table-bordered table-sm'>";
                    echo "<thead class='bg-$header_class text-light'><tr><th colspan='" . (count($months) + 1) . "'>$label</th></tr>";
                    echo "<tr class='bg-secondary text-light'><th>Category</th>";
                    foreach ($months as $month) {
                        echo "<th>$month</th>";
                    }
                    echo "</tr></thead><tbody>";

                    foreach ($data as $group_name => $counts_by_month) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($group_name) . "</td>";
                        foreach ($months as $month) {
                            $count = isset($counts_by_month[$month]) ? $counts_by_month[$month] : 0;
                            echo "<td>" . htmlspecialchars($count) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody></table><br>";
                }

    
    // ----  QUERIES FOR EACH SET OF DATA / CRITERIA  -------------------- -->

                /** Location Data **/
                       $sql_location_trend = "SELECT occur_location, DATE_FORMAT(occur_date, '%Y-%m') as report_month, 
                              COUNT(*) as location_count 
                       FROM occur
                       WHERE 1=1 
                       AND occur_date >= '$start_date'";

                        if ($selected_category != 'All') {
                            $sql_location_trend .= " AND reporter_category = '$selected_category'";
                        }

                        $sql_location_trend .= " GROUP BY occur_location, report_month
                                                ORDER BY report_month ASC";

                        $result_location_trend = mysqli_query($conn, $sql_location_trend);
                        if (!$result_location_trend) {
                            die("Query failed: " . mysqli_error($conn));
                        }

                        $location_data = [];
                        if (mysqli_num_rows($result_location_trend) > 0) {
                            while ($row_location = mysqli_fetch_assoc($result_location_trend)) {
                                $location = $row_location['occur_location'];
                                $month = $row_location['report_month'];
                                $count = $row_location['location_count'];
                                $location_data[$location][$month] = $count;
                            }
                        }
                        mysqli_free_result($result_location_trend);

                        display_data_block("Location: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                          $location_data, 
                                          $months, 
                                          "primary");


                /** Program Data **/

                        $sql_patient_program = "SELECT patient_program, DATE_FORMAT(occur_date, '%Y-%m') as report_month, 
                                COUNT(*) as program_count 
                                FROM occur
                                WHERE 1=1 
                                AND occur_date >= '$start_date'";

                        if ($selected_category != 'All') {
                            $sql_patient_program .= " AND reporter_category = '$selected_category'";
                        }

                        $sql_patient_program .= " GROUP BY patient_program, report_month
                                                 ORDER BY report_month ASC";

                        $result_patient_program = mysqli_query($conn, $sql_patient_program);
                        if (!$result_patient_program) {
                            die("Query failed: " . mysqli_error($conn));
                        }

                        $program_data = [];
                        if (mysqli_num_rows($result_patient_program) > 0) {
                            while ($row_program = mysqli_fetch_assoc($result_patient_program)) {
                                $program = $row_program['patient_program'];
                                $month = $row_program['report_month'];
                                $count = $row_program['program_count'];
                                $program_data[$program][$month] = $count;
                            }
                        }
                        mysqli_free_result($result_patient_program);

                        // Update the display title to use $selected_category
                        display_data_block("Program: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                          $program_data, 
                                          $months, 
                                          "warning");


                /** Area Data **/

                       $sql_occur_area = "SELECT occur_area, DATE_FORMAT(occur_date, '%Y-%m') as report_month,
                                  COUNT(*) as area_count 
                                  FROM occur
                                  WHERE 1=1 
                                  AND occur_date >= '$start_date'";

                        if ($selected_category != 'All') {
                           $sql_occur_area .= " AND reporter_category = '$selected_category'";
                        }

                        $sql_occur_area .= " GROUP BY occur_area, report_month
                                            ORDER BY report_month ASC";

                        $result_occur_area = mysqli_query($conn, $sql_occur_area);
                        if (!$result_occur_area) {
                           die("Query failed: " . mysqli_error($conn));
                        }

                        $area_data = [];
                        if (mysqli_num_rows($result_occur_area) > 0) {
                           while ($row_area = mysqli_fetch_assoc($result_occur_area)) {
                               $area = $row_area['occur_area'];
                               $month = $row_area['report_month'];
                               $count = $row_area['area_count'];
                               $area_data[$area][$month] = $count;
                           }
                        }
                        mysqli_free_result($result_occur_area);

                        display_data_block("Area: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                         $area_data, 
                                         $months, 
                                         "success");


                /** Attending MD Data **/

                            $sql_md_attending = "SELECT MD_Attending, DATE_FORMAT(occur_date, '%Y-%m') as report_month,
                                                 COUNT(*) as md_count 
                                                 FROM occur
                                                 WHERE 1=1 
                                                 AND occur_date >= '$start_date'";

                            if ($selected_category != 'All') {
                               $sql_md_attending .= " AND reporter_category = '$selected_category'";
                            }

                            $sql_md_attending .= " GROUP BY MD_Attending, report_month
                                                  ORDER BY report_month ASC";

                            $result_md_attending = mysqli_query($conn, $sql_md_attending);
                            if (!$result_md_attending) {
                               die("Query failed: " . mysqli_error($conn));
                            }

                            $md_data = [];
                            if (mysqli_num_rows($result_md_attending) > 0) {
                               while ($row_md = mysqli_fetch_assoc($result_md_attending)) {
                                   $md = $row_md['MD_Attending'];
                                   $month = $row_md['report_month'];
                                   $count = $row_md['md_count'];
                                   $md_data[$md][$month] = $count;
                               }
                            }
                            mysqli_free_result($result_md_attending);

                            display_data_block("Attending MD: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                             $md_data, 
                                             $months, 
                                             "danger");


                /** Patient Unit Data **/
                        
                        $sql_patient_unit = "SELECT patient_unit, DATE_FORMAT(occur_date, '%Y-%m') as report_month,
                                             COUNT(*) as unit_count 
                                             FROM occur  
                                             WHERE 1=1
                                             AND occur_date >= '$start_date'";

                            if ($selected_category != 'All') {
                               $sql_patient_unit .= " AND reporter_category = '$selected_category'";
                            }

                            $sql_patient_unit .= " GROUP BY patient_unit, report_month
                                                  ORDER BY report_month ASC";

                            $result_patient_unit = mysqli_query($conn, $sql_patient_unit);
                            if (!$result_patient_unit) {
                               die("Query failed: " . mysqli_error($conn));
                            }

                            $unit_data = [];
                            if (mysqli_num_rows($result_patient_unit) > 0) {
                               while ($row_unit = mysqli_fetch_assoc($result_patient_unit)) {
                                   $unit = $row_unit['patient_unit'];
                                   $month = $row_unit['report_month'];
                                   $count = $row_unit['unit_count'];
                                   $unit_data[$unit][$month] = $count;
                               }
                            }
                            mysqli_free_result($result_patient_unit);

                            display_data_block("Unit: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                             $unit_data, 
                                             $months, 
                                             "info");


                /** Severity Data **/

                    $sql_severity = "SELECT rm_severity, DATE_FORMAT(occur_date, '%Y-%m') as report_month,
                                     COUNT(*) as severity_count 
                                     FROM occur
                                     WHERE 1=1
                                     AND occur_date >= '$start_date'";

                            if ($selected_category != 'All') {
                               $sql_severity .= " AND reporter_category = '$selected_category'";
                            }

                            $sql_severity .= " GROUP BY rm_severity, report_month
                                              ORDER BY report_month ASC";

                            $result_severity = mysqli_query($conn, $sql_severity);
                            if (!$result_severity) {
                               die("Query failed: " . mysqli_error($conn));
                            }

                            $severity_data = [];
                            if (mysqli_num_rows($result_severity) > 0) {
                               while ($row_severity = mysqli_fetch_assoc($result_severity)) {
                                   $severity = $row_severity['rm_severity'];
                                   $month = $row_severity['report_month'];
                                   $count = $row_severity['severity_count'];
                                   $severity_data[$severity][$month] = $count;
                               }
                            }
                            mysqli_free_result($result_severity);

                            display_data_block("Severity: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                             $severity_data, 
                                             $months, 
                                             "secondary");


                                $sql_subcategory = "SELECT occur_subcategory, DATE_FORMAT(occur_date, '%Y-%m') as report_month, 
                                                   COUNT(*) as subcategory_count 
                                            FROM occur
                                            WHERE 1=1
                                            AND occur_date >= '$start_date'";
                                if ($selected_category != 'All') {
                                    $sql_subcategory .= " AND reporter_category = '$selected_category'";
                                    }
                        
                                $sql_subcategory .= " GROUP BY occur_subcategory, report_month
                                                      ORDER BY report_month ASC";
                                $result_subcategory = mysqli_query($conn, $sql_subcategory);
                                if (!$result_subcategory) {
                                    die("Query failed: " . mysqli_error($conn));
                                }
                                $subcategory_data = [];
                                if (mysqli_num_rows($result_subcategory) > 0) {
                                    while ($row_subcategory = mysqli_fetch_assoc($result_subcategory)) {
                                        $subcategory = $row_subcategory['occur_subcategory'];
                                        $month = $row_subcategory['report_month'];
                                        $count = $row_subcategory['subcategory_count'];
                                        $subcategory_data[$subcategory][$month] = $count;
                                    }
                                }
                                mysqli_free_result($result_subcategory);
                                display_data_block("Subcategory: " . ($selected_category != 'All' ? $selected_category : 'All Categories'), 
                                                  $subcategory_data, 
                                                  $months, 
                                                  "dark");
                        ?>

                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div> <!-- end column -->
                        </div> <!-- end row -->


    <!-- ----  JS / JQUERY TO TOGGLE VISIBILITY  -------------------- -->


        <script>
            // jQuery to toggle the visibility of the data content
            $(document).ready(function() {
                $('#toggle-btn').click(function() {
                    $('#toggle-content').toggle();
                });
            });
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



<!-- ========================================  PAGE SPECIFIC ASSETS  ========================================================== -->

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


      
<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->


    <!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
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


    <!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
        <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance in a variable
            var table = $('#myTable_open_reports').DataTable({
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


    <!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
        <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance in a variable
            var table = $('#myTable_inprogress').DataTable({
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


    <!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
        <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance in a variable
            var table = $('#myTable_complete').DataTable({
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


    <!-- CONFIGURE CHART OF REPORTS BY HOUR (TIME OF OCCURRENCE, NOT SUBMISSION TIME) -->


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



<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




