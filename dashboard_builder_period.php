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
        // Dynamically populate drop down menus for filters

            // DATES:  DEFAULT TO YTD or initialize dates from GET parameters
                $start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date('Y-01-01'); // January 1st of current year
                $end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'); // Today

                // Format dates for display
                $formatted_start_date = date('m/d/y', strtotime($start_date));
                $formatted_end_date = date('m/d/y', strtotime($end_date));


            // LOCATION:  Query to get unique locations from the occur_location field ----------------------------------
     
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


            // LEVEL OF CARE:  Query to get unique patient level of care from the patient_loc field ----------------------------------

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

                    $selected_loc = isset($_GET['loc']) && in_array($_GET['loc'], $locs) ? $_GET['loc'] : 'All';


            // PROGRAMS:  Query to get unique patient programs from the patient_program field --------------------------------------
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


            // UNIT:  Query to get unique patient units from the patient_unit field ------------------------------------------------
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


            // ATTENDING MD:  Query to get unique attending physicians from the md_attending field
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


            // AREAS:  Query to get unique areas from the occur_area field
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


            // CATEGORIES: Query to get unique categories from the reporter_category field
                $sql_categories = "SELECT DISTINCT reporter_category 
                                   FROM occur 
                                   WHERE reporter_category IS NOT NULL AND reporter_category != ''
                                   ORDER BY reporter_category ASC";
                $result_categories = mysqli_query($conn, $sql_categories);
                if (!$result_categories) {
                    die("Query failed: " . mysqli_error($conn));
                }
                // Fetch the categories into an array
                $categories = ['All']; // Start with 'All' as the first option
                while ($row = mysqli_fetch_assoc($result_categories)) {
                    $categories[] = $row['reporter_category'];
                }
                // Set default to 'All' if no category is selected or if the selected category is invalid
                $selected_category = isset($_GET['reporter_category']) && in_array($_GET['reporter_category'], $categories) ? $_GET['reporter_category'] : 'All';


        // Set Session Filters
       
            // Clear any existing filters first
                unset($_SESSION['filters']);
           
            //  Set Session filters for use on drill down pages
                if (isset($_GET['month']) || isset($_GET['location']) || isset($_GET['loc']) || 
                    isset($_GET['patient_program']) || isset($_GET['patient_unit']) || isset($_GET['md_attending']) ||
                    isset($_GET['occur_area']) || isset($_GET['reporter_category'])) {    
                    $_SESSION['filters'] = [
                        'month' => $selected_month,
                        'location' => $selected_location,
                        'loc' => $selected_loc,
                        'program' => $selected_program,
                        'unit' => $selected_unit,
                        'md' => $selected_md,
                        'area' => $selected_area,
                        'category' => $selected_category
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

    <!-- DISPLAY START AND END DATE FIELDS ------------------------ -->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card" style="background-color: #DCDCDC;">
                                    <div class="card-body text-center text-black">
                                        <h4 class="fw-bold">Custom Date Range</h4>
                                        <form action="" method="GET" class="d-flex justify-content-center align-items-center gap-4 mt-3">
                                            <!-- Start Date input -->
                                            <div class="d-flex align-items-center">
                                                <label for="start_date" class="form-label mb-0 me-2">Start Date:</label>
                                                <input type="date" id="start_date" name="start_date" 
                                                       class="form-control form-control-sm border-0" 
                                                       style="background-color: #E3F2FD; border: 1px solid #CED4DA; width: 150px;"
                                                       value="<?php echo $start_date; ?>">
                                            </div>
                                            <!-- End Date input -->
                                            <div class="d-flex align-items-center">
                                                <label for="end_date" class="form-label mb-0 me-2">End Date:</label>
                                                <input type="date" id="end_date" name="end_date" 
                                                       class="form-control form-control-sm border-0" 
                                                       style="background-color: #E3F2FD; border: 1px solid #CED4DA; width: 150px;"
                                                       value="<?php echo $end_date; ?>">
                                            </div>
                                            <!-- Submit button -->
                                            <button type="submit" class="btn btn-secondary btn-sm">Apply</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

    

    <!-- DISPLAY DROP DOWN FILTERS AT TOP OF PAGE ------------------------ -->

                        <div class = "row">
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
                            
                            <!-- Reporter Category Dropdown -->
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
                                               href="?<?php echo http_build_query(array_merge($_GET, ['reporter_category' => $category])); ?>">
                                                <?php echo htmlspecialchars($category); ?>
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
                        </div> <!-- end row -->

                        <br>
          
          

<!-- ========================================  TOP ROW - 4 WIDGETS / CARDS  =================================================================== -->


    <!-- CARD: TOTAL REPORTS MONTH - COLUMN 1 ============================ -->

        <!-- QUERY FOR "TOTAL REPORTS: MONTH" - COLUMN 1 ============================ -->
   
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
                            if ($selected_category != 'All') {
                                $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }

                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];

                        ?>

        <!-- RENDER "TOTAL REPORTS: MONTH" - COLUMN 1 ============================ -->

                        <div class="row">
                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                          <a href="drill_table.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-folder-star text-primary"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">TOTAL REPORTS</p>
                                            <a href="drill_table.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
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


    <!-- CARD: HIGH SEVERITY -  COLUMN 2 ============================ -->

        <!-- QUERY AND HTML FOR HIGH SEVERITY -  COLUMN 2 ============================ -->

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
                            if ($selected_category != 'All') {
                                $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }

                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];

                        ?>

        <!-- QUERY AND HTML FOR HIGH SEVERITY -  COLUMN 2 ============================ -->

                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                           <a href="drill_high_severity.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-alert-circle text-danger"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">HIGH SEVERITY</p>
                                             <a href="drill_high_severity.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
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


    <!--  CARD: FLAGS  -  COLUMN 3 ============================ -->
        
        <!--  QUERY  FOR FLAGS  -  COLUMN 3 ============================ -->

                        <?php
                            // Query to count # of reports submitted but not reviewed by RM
                                $sql = "SELECT COUNT(*) AS item_count
                                        FROM occur
                                        WHERE occur_date >= '$start_date' 
                                          AND occur_date < '$end_date'
                                          AND occur_flag IS NOT NULL 
                                          AND occur_flag != ''";

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
                            if ($selected_category != 'All') {
                                $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }

                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];


                            ?>

        <!--  RENDER FLAGS  -  COLUMN 3 ============================ -->                    
        
                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="drill_flag.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-flag text-warning"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">FLAGS</p>
                                             <a href="drill_flag.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
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


    <!-- CARD: OPEN REPORTS  -  COLUMN 4 ============================ -->

        <!-- QUERY FOR OPEN REPORTS  -  COLUMN 4 ============================ -->

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
                            if ($selected_category != 'All') {
                                $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                            }
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
                            }

                            $row = mysqli_fetch_assoc($result);
                            $numrows_submitted = $row['item_count'];

            ?>

        <!-- RENDER OPEN REPORTS  -  COLUMN 4 ============================ -->


                            <div class="col-md-6 col-xl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="filter_open_detail.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">OPEN REPORTS</p>
                                             <a href="filter_open_detail.php?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>">
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


     <!-- BAR CHART: CATEGORY  -  COLUMN 2  ---------------------------- -->

        <!-- QUERY FOR CATEGORY CHART  -  COLUMN 1  ============================ -->

                        <?php

                            // Build the SQL query with explicit filter checks
                                $sql = "SELECT reporter_category, COUNT(*) AS count, DATE(occur_date) AS occur_date
                                        FROM occur 
                                        WHERE 1=1";
                                // Add date range
                                if ($start_date && $end_date) {
                                    $sql .= " AND occur_date >= '$start_date' AND occur_date <= '$end_date'";
                                }
                                // Explicitly add each filter condition
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
                                if ($selected_category != 'All') {
                                    $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                                }

                            // Add Group By
                                $sql .= " GROUP BY reporter_category, DATE(occur_date)";
                            
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


        <!-- RENDER CATEGORY CHART  ======================================================== -->

                      
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
                                                    <!--
                                                    <button type="button" class="btn btn-primary" data-range="all">ALL</button>
                                                    <button type="button" class="btn btn-light" data-range="12mo">12 MO</button>
                                                    <button type="button" class="btn btn-light" data-range="mtd">MTD</button>
                                                    <button type="button" class="btn btn-light" data-range="24hr">24 HR</button>
                                                    -->
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div id="chart_category"  dir="ltr"></div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->



    <!-- PIE CHART: INCIDENT TYPE  -  COLUMN 2  ---------------------------- -->


        <!-- QUERY FOR PIE CHART  -  COLUMN 2  ============================ -->

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
                                // 7. Category
                                if ($selected_category != 'All') {
                                    $sql_type_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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
                           echo '                            <h5 class="mb-0 font-size-14"><a href="drill_type.php?type=' . urlencode($occur_type) . '&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date) . '">' . $count . '</a></h5>';

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


<!-- ========================================  BOOTSTRAP ROW: COLUMN CHART: TOTAL REPORTS BY MONTH  ===================================================== -->


    <!-- QUERY TO PULL DETAIL BY MONTH ----- -->

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


    <!-- RENDER DETAIL BY MONTH ----- -->
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
                    


<!-- ========================================  MIDDLE ROW - 3 WIDGETS / CARDS  =================================================================== -->

                     
    <!-- CARD:  LEVEL OF CARE DETAIL - COLUMN 1 ------------------------ -->

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
                                    if ($selected_category != 'All') {
                                        $sql_loc_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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
                                                                <a href="drill_loc.php?loc=<?= urlencode($patient_loc_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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


    <!-- CARD: LOCATION DETAIL - COLUMN 2 ------------------------------ -->

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
                                if ($selected_category != 'All') {
                                   $sql_location_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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
                                                                <a href="drill_location.php?location=<?= urlencode($occur_location_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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


    <!-- CARD: PROGRAM DETAIL - COLUMN 3 ------------------------------- -->

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
                                    if ($selected_category != 'All') {
                                       $sql_program_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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
                                                                <a href="drill_program.php?program=<?= urlencode($patient_program_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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
                                                <a href="drill_program.php?program=<?= urlencode($patient_program_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
                                                                    <?= $count_program_table ?>
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

                          
<!-- ========================================  BOOTSTRAP ROW:  ??????    ========================================================= -->

                        <?php 

                            // SQL query to get data for the selected month

                                $sql_month_detail = "SELECT * FROM occur
                                                     WHERE occur_date >= '$start_date' AND occur_date < '$end_date'";

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
                                if ($selected_category != 'All') {
                                   $sql_month_detail .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                                }
                                // Execute the query
                                $result_month_detail = mysqli_query($conn, $sql_month_detail);
                                if (!$result_month_detail) {
                                   die("Query failed: " . mysqli_error($conn));
                                }
                                $numrows_month_detail = mysqli_num_rows($result_month_detail);

                        ?>

              
                                
<!-- ========================================  BOOTSTRAP ROW:  HOURLY CHART   ========================================================= -->

    <!-- QUERY: REPORTS BY HOUR OF THE DAY -->
                    <?php
                        // Query to set values for reports by HOUR OF THE DAY
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
                                // 7. Category
                                if ($selected_category != 'All') {
                                   $sql_report_hourly .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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
                                                        <div id="chart_report_hourly"  dir="ltr"></div>
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


    <!-- DATATABLE: SET NAME  -------------------------->

            <?php

            // Set names for title/subtitle on Datatables
                    $datatable_name = "Report Detail";
            ?>   


    <!-- DATATABLE  ---------------------------------- -->

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                         <!-- Title and Container for Buttons -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="card-title"><?php echo $datatable_name; ?></h4>
                                            <div id="buttonsContainer"></div>
                                        </div>
                                        <p class="card-title-desc"><?php echo $datatable_name_sub; ?></p>


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

                                <table id="myTableStandardButtons" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
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
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['rm_severity']}</td>";
                                            echo "<td style='white-space:nowrap'>{$row_month_detail['occur_status']}</td>";
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


                                
<!-- ========================================  BOOTSTRAP ROW: 4 TABLES: AREA, MD, UNIT, SEVERITY    ========================================================= -->


                    
    <!-- QUERY FOR "AREA" - COLUMN 1 ------------------- -->

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
                                if ($selected_category != 'All') {
                                   $sql_area_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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

    <!-- RENDER "AREA" - COLUMN 1 ------------------- -->

                        <div class="row mt-4">
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
                                                                    <a href="drill_area.php?area=<?= urlencode($occur_area_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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
                                                                     

    
    <!-- QUERY FOR "MD ATTENDING DETAIL" - COLUMN 2 ------------------- -->

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
                                    if ($selected_category != 'All') {
                                       $sql_md_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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

    <!-- RENDER "MD ATTENDING DETAIL" - COLUMN 2 ------------------- -->

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
                                                                <a href="drill_md.php?md=<?= urlencode($md_attending_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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



    <!-- QUERY FOR "UNIT DETAIL" - COLUMN 3 --------------------- -->

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
                                if ($selected_category != 'All') {
                                   $sql_unit_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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


    <!-- DISPLAY "UNIT DETAIL" - COLUMN 3 --------------------- -->

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
                                                                <a href="drill_unit.php?unit=<?= urlencode($patient_unit_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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



<!-- PHP / SQL QUERY AND HTML FOR "SEVERITY" - COLUMN 4 ============================ -->

                             

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
if ($selected_category != 'All') {
   $sql_severity_table .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
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
                                                                <a href="drill_severity.php?severity=<?= urlencode($rm_severity_table) ?>&start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>">
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
                                          

<!-- ========================================  BOOTSTRAP ROW: HEADLINE FOR FOCUS AREAS================================================================== -->

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

    <!-- Datatable Buttons -->
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>


    <!-- Apex Charts -->
        <script src="assets/js/app.js"></script>
       

    
<!-- ========================================  PAGE SPECIFIC SCRIPTS: CHARTS AND TABLES   ========================================================== -->


    <!-- DATATABLES / SET DEFAULT SORT COLUMN/ORDER ======================= -->

        <script>
            $(document).ready(function() {
                var table = $('#myTableStandardButtons').DataTable({
                "order": [[0, "desc"]],
                "responsive": true,
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"B>f>rtip', // Add 'f' for search box
                        "buttons": [
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> Copy',
                            className: 'btn btn-info btn-sm me-2',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fas fa-file-csv"></i> CSV',
                            className: 'btn btn-success btn-sm me-2',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel"></i> Excel',
                            className: 'btn btn-success btn-sm me-2',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fas fa-file-pdf"></i> PDF',
                            className: 'btn btn-warning btn-sm me-2',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ]
                });

                // Move the buttons to the custom container
                table.buttons().container().appendTo('#buttonsContainer');
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
                    text: 'Reports by Category:  <?php echo $formatted_start_date;?>  Through <?php echo $formatted_end_date;?>',
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
                   dataLabels: {
            enabled: true,
            textAnchor: 'middle',
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                colors: ['#ffffff']  // White color
            },
            background: {
                enabled: false
            },
            offsetX: 0,
            offsetY: 0,
        },
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
                    colors: ['#f5bd58']  // Single color for all bars
                };

                var chart_report_hourly = new ApexCharts(document.querySelector("#chart_report_hourly"), options_report_hourly);
                chart_report_hourly.render();
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



<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->

    </body>
</html>




