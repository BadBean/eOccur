<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
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

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  ========================================================== -->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card" style="background-color: #DCDCDC;">
                                    <div class="card-body text-center">
                                      <h4 class="mb-sm-0 font-size-16 fw-bold text-black">CATEGORY DETAIL:&nbsp;&nbsp; <?php echo strtoupper($reporter_category); ?></h4>
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
             WHERE reporter_category = '$reporter_category'
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

<!-- ========================================  BOOTSTRAP ROW: PROGRAM DETAIL   =================================================================== -->

                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="drill_category.php?reporter_category=<?= $reporter_category;?>">
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

                            <div class="col-md-6 col-xl-4">
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






<!-- =============================================== BOOTSTRAP ROW: CATEGORY TREND CHART RENDER / SCRIPT ========================= -->


<!-- PHP / SQL QUERY TO PULL CATEGORY DETAIL   ================ -->

<?php 
// Generate a list of the last 12 months
$months = [];
for ($i = 11; $i >= 0; $i--) {
    $months[] = date('Y-m', strtotime("-$i months"));
}

// Query to count # of reports 
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

<!-- RENDER CHART / CHART OPTIONS   ====================================== -->

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
                            </div>

<?php
// Query to count the number of reports by subcategory within the desired date range
$sql_subcategory_table = "SELECT occur_subcategory, COUNT(*) as subcategory_table_count 
                          FROM occur 
                          WHERE reporter_category = '$reporter_category'
                          GROUP BY occur_subcategory
                          ORDER BY subcategory_table_count DESC";

// Execute the query 
$result_subcategory_table = mysqli_query($conn, $sql_subcategory_table);

// Check for SQL errors
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

?>

<div class="col-md-3">
    <div class="card" style="height: 400px;">
        <div class="card-body pb-2 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title me-2">SUBCATEGORY </h5>
                <!--<a href="dashboard_subcategory.php" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                </a>
                -->
            </div>
            <div class="mt-2 flex-grow-1 overflow-auto">
                <?php
                if (mysqli_num_rows($result_subcategory_table) > 0) {
                    while ($row_subcategory_table = mysqli_fetch_assoc($result_subcategory_table)) {
                        $occur_subcategory_table = htmlspecialchars($row_subcategory_table['occur_subcategory']);
                        $count_subcategory_table = htmlspecialchars($row_subcategory_table['subcategory_table_count']);
                        $total_subcategory_table_count += $row_subcategory_table['subcategory_table_count'];
                        $color_subcategory_table = $subcategory_table_styles[$occur_subcategory_table]['color'];

                        echo '<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">';
                        echo '    <div class="d-flex align-items-center">';
                        echo '        <i class="mdi mdi-circle font-size-10 me-2 ' . $color_subcategory_table . '"></i>';
                        echo '        <p class="mb-0" style="font-size: 0.9rem;">' . $occur_subcategory_table . '</p>';
                        echo '    </div>';
                        echo '    <h6 class="mb-0 font-size-14"><a href="drill_subcategory.php?occur_subcategory=' . urlencode($occur_subcategory_table) . '">' . $count_subcategory_table . '</a></h6>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="text-center"><p>No data available.</p></div>';
                }
                ?>
            </div>
            <div class="mt-auto pt-2 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0"><strong>Total</strong></p>
                    <h5 class="mb-0 font-size-14"><strong><?php echo htmlspecialchars($total_subcategory_table_count); ?></strong></h5>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ========================================  MIDDLE ROW - 3 WIDGETS / CARDS  =================================================================== -->


    <!-- PHP / SQL QUERY AND HTML FOR "LEVEL OF CARE DETAIL" - COLUMN 1 ============================ -->
    <?php
    // Query to count the number of reports by level of care within the desired date range
    $sql_loc_table =  "SELECT patient_loc, COUNT(*) as loc_table_count 
                        FROM occur
                        WHERE reporter_category = '$reporter_category' 
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

    // Start of the column
    echo '<div class="col-md-3">';
    if (mysqli_num_rows($result_loc_table) > 0) {
        echo '    <div class="card h-100">';
        echo '        <div class="card-body pb-2">';

        // Top Row: LEVEL OF CARE DETAIL label and Dashboard button
        echo '            <div class="d-flex justify-content-between align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">LEVEL OF CARE</h5>';
        echo '                <a href="dashboard_loc.php" class="btn btn-sm btn-primary">';
        echo '                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard';
        echo '                </a>';
        echo '            </div>';

        echo '            <br>';
        echo '            <div class="mt-4 text-center">';
        echo '                <div>';

        // Iterate through each report location and display it
        while ($row_loc_table = mysqli_fetch_assoc($result_loc_table)) {
            $patient_loc_table = htmlspecialchars($row_loc_table['patient_loc']);
            $count_loc_table = htmlspecialchars($row_loc_table['loc_table_count']);
            $total_loc_table_count += $row_loc_table['loc_table_count'];

            // Get the color for this location
            $color_loc_table = $loc_table_styles[$patient_loc_table]['color'];

            // Output the report location block
            echo '                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">';
            echo '                        <div class="d-flex">';
            echo '                            <i class="mdi mdi-circle font-size-12 mt-1 ' . $color_loc_table . '"></i>';
            echo '                            <div class="flex-grow-1 ms-2">';
            echo '                                <p class="mb-0">' . $patient_loc_table . '</p>';
            echo '                            </div>';
            echo '                        </div>';
            echo '                        <div>';
            echo '                            <h5 class="mb-0 font-size-14"><a href="drill_loc.php?patient_loc=' . urlencode($patient_loc_table) . '">' . $count_loc_table . '</a></h5>';
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
        echo '                            <h5 class="mb-0 font-size-14"><strong>' . htmlspecialchars($total_loc_table_count) . '</strong></h5>';
        echo '                        </div>';
        echo '                    </div>';

        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
    } else {
        // No results found
        echo '    <div class="card h-100">';
        echo '        <div class="card-body pb-2">';

        // Top Row: LEVEL OF CARE label and Dashboard button (no data case)
        echo '            <div class="d-flex justify-content-between align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">LEVEL OF CARE</h5>';
        echo '                <a href="detail_month.php?month=' . htmlspecialchars($selected_month) . '#myTable" class="btn btn-sm btn-primary">';
        echo '                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard';
        echo '                </a>';
        echo '            </div>';

        echo '            <br>';
        echo '            <div class="mt-4 text-center">';
        echo '                <div>';
        echo '                    <p>No data available.</p>';
        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
    }
    echo '</div>'; // end col
    ?>

    <!-- PHP / SQL QUERY AND HTML FOR "LOCATION DETAIL" - COLUMN 2 ============================ -->
    <?php
    // Query to count the number of reports by location within the desired date range
    $sql_location_table =  "SELECT occur_location, COUNT(*) as location_table_count 
                            FROM occur 
                            WHERE reporter_category = '$reporter_category' 
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

    // Start of the column
    echo '<div class="col-md-3">';
    if (mysqli_num_rows($result_location_table) > 0) {
        echo '    <div class="card h-100">';
        echo '        <div class="card-body pb-2">';

        // Top Row: LOCATION DETAIL label and Dashboard button
        echo '            <div class="d-flex justify-content-between align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">LOCATION</h5>';
        echo '                <a href="dashboard_location.php" class="btn btn-sm btn-primary">';
        echo '                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard';
        echo '                </a>';
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
    } else {
        // No results found
        echo '    <div class="card h-100">';
        echo '        <div class="card-body pb-2">';

        // Top Row: LOCATION DETAIL label and Dashboard button (no data case)
        echo '            <div class="d-flex justify-content-between align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">LOCATION DETAIL</h5>';
        echo '                <a href="detail_month.php?month=' . htmlspecialchars($selected_month) . '#myTable" class="btn btn-sm btn-primary">';
        echo '                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard';
        echo '                </a>';
        echo '            </div>';

        echo '            <br>';
        echo '            <div class="mt-4 text-center">';
        echo '                <div>';
        echo '                    <p>No data available.</p>';
        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
    }
    echo '</div>'; // end col
    ?>


    <!-- PHP / SQL QUERY AND HTML FOR "PROGRAM DETAIL" - COLUMN 3 ============================ -->
    <?php
    // Query to count the number of reports by program within the desired date range
    $sql_program_table =  "SELECT patient_program, COUNT(*) as program_table_count 
                            FROM occur 
                            WHERE reporter_category = '$reporter_category' 
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




    // Start of the column
    echo '<div class="col-md-3">';
    if (mysqli_num_rows($result_program_table) > 0) {
        echo '    <div class="card h-100">';
        echo '        <div class="card-body pb-2">';

        // Top Row: PROGRAM DETAIL label and Dashboard button
        echo '            <div class="d-flex justify-content-between align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">PROGRAM</h5>';
        echo '                <a href="dashboard_program.php" class="btn btn-sm btn-primary">';
        echo '                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard';
        echo '                </a>';
        echo '            </div>';

        echo '            <br>';
        echo '            <div class="mt-4 text-center">';
        echo '                <div>';

        // Iterate through each report program and display it
        while ($row_program_table = mysqli_fetch_assoc($result_program_table)) {
            $patient_program_table = htmlspecialchars($row_program_table['patient_program']);
            $count_program_table = htmlspecialchars($row_program_table['program_table_count']);
            $total_program_table_count += $row_program_table['program_table_count'];

            // Get the color for this program
            $color_program_table = $program_table_styles[$patient_program_table]['color'];

            // Output the report program block
            echo '                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">';
            echo '                        <div class="d-flex">';
            echo '                            <i class="mdi mdi-circle font-size-12 mt-1 ' . $color_program_table . '"></i>';
            echo '                            <div class="flex-grow-1 ms-2">';
            echo '                                <p class="mb-0">' . $patient_program_table . '</p>';
            echo '                            </div>';
            echo '                        </div>';
            echo '                        <div>';
            echo '                            <h5 class="mb-0 font-size-14"><a href="drill_program.php?patient_program=' . urlencode($patient_program_table) . '">' . $count_program_table . '</a></h5>';
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
        echo '                            <h5 class="mb-0 font-size-14"><strong>' . htmlspecialchars($total_program_table_count) . '</strong></h5>';
        echo '                        </div>';
        echo '                    </div>';

        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
    } else {
        // No results found
        echo '    <div class="card h-100">';
        echo '        <div class="card-body pb-2">';

        // Top Row: PROGRAM DETAIL label and Dashboard button (no data case)
        echo '            <div class="d-flex justify-content-between align-items-center mb-2">';
        echo '                <h5 class="card-title me-2">PROGRAM DETAIL</h5>';
        echo '                <a href="detail_month.php?month=' . htmlspecialchars($selected_month) . '#myTable" class="btn btn-sm btn-primary">';
        echo '                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard';
        echo '                </a>';
        echo '            </div>';

        echo '            <br>';
        echo '            <div class="mt-4 text-center">';
        echo '                <div>';
        echo '                    <p>No data available.</p>';
        echo '                </div>';
        echo '            </div>';
        echo '        </div>'; // end card-body
        echo '    </div>'; // end card
    }
    echo '</div>'; // end col
    ?>

<!-- PHP / SQL QUERY AND HTML FOR "AREA" - COLUMN 2 ============================ -->

            <?php
            // Query to count the number of reports by area within the desired date range
            $sql_area_table = "SELECT occur_area, COUNT(*) as area_table_count 
                               FROM occur 
                               WHERE reporter_category = '$reporter_category'
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
                <h5 class="card-title me-2">AREA </h5>
                <a href="dashboard_area.php" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-chart-bar me-1"></i>Dashboard
                </a>
            </div>
            <div class="mt-2 flex-grow-1 overflow-auto">
                <?php
                if (mysqli_num_rows($result_area_table) > 0) {
                    while ($row_area_table = mysqli_fetch_assoc($result_area_table)) {
                        $occur_area_table = htmlspecialchars($row_area_table['occur_area']);
                        $count_area_table = htmlspecialchars($row_area_table['area_table_count']);
                        $total_area_table_count += $row_area_table['area_table_count'];
                        $color_area_table = $area_table_styles[$occur_area_table]['color'];

                        echo '<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">';
                        echo '    <div class="d-flex align-items-center">';
                        echo '        <i class="mdi mdi-circle font-size-10 me-2 ' . $color_area_table . '"></i>';
                        echo '        <p class="mb-0" style="font-size: 0.9rem;">' . $occur_area_table . '</p>';
                        echo '    </div>';
                        echo '    <h6 class="mb-0 font-size-14"><a href="drill_area.php?occur_area=' . urlencode($occur_area_table) . '">' . $count_area_table . '</a></h6>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="text-center"><p>No data available.</p></div>';
                }
                ?>
            </div>
            <div class="mt-auto pt-2 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0"><strong>Total</strong></p>
                    <h5 class="mb-0 font-size-14"><strong><?php echo htmlspecialchars($total_area_table_count); ?></strong></h5>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- end of row -->



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



                           
                        <div class="row mt-4">
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
                                                    <th>Location</th> 
                                                    <th>Program</th> 
                                                    <th>Area</th> 
                                                    <th>Category</th>
                                                    <th>Subcategory</th>
                                                    <th>Severity</th>
                                                    <th>Injury?</th>
                                                    <!-- <th>Attending</th> -->
                                                   
                                                    <th>Actions</th>


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
                                                            echo "<td>{$row['occur_time']}</td>";

                                                            echo "<td>{$row['occur_patient_restraint']}</td>";
                                                            echo "<td>{$row['occur_patient_seclusion']}</td>";

                                                            echo "<td>{$row['occur_status']}</td>";

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



<!-- ========================================  BOOTSTRAP ROW:  HOURLY CHART   ========================================================= -->

<?php
// PHP to set values for reports by HOUR OF THE DAY

$sql_report_hourly = "
    SELECT 
        HOUR(occur_time) AS hour,
        COUNT(*) AS item_report_hourly
    FROM 
        occur
    WHERE reporter_category = '$reporter_category'
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



<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<?php 
//Query for reports assigned to user and not closed
  $sql = "SELECT *
          FROM occur
          WHERE occur_status <> 'Closed'
          AND reporter_category = '$reporter_category'
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
                                        <p class="card-title-desc"></p>
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
-->                                            <table id="myTable_open_reports" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
                                                
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
          WHERE complete_date IS NULL
          AND reporter_category = '$reporter_category'
          
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
                                        <p class="card-title-desc"></p>
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
                                        </div> <!-- close card color -->
<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTable_inprogress" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">  
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




<!-- ================================================ ACTION PLANS COMPLETED =================== -->

<?php 
//Query to count # of reports assigned to user and not closed
  $sql = "SELECT *
          FROM occur
          WHERE complete_date IS NOT NULL
          AND reporter_category = '$reporter_category'
          
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
                                        <h4 class="card-title">Action Plans Completed</h4>
                                        <p class="card-title-desc"></p>
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
                                        </div> <!-- close card color -->
<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTable_complete" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">   
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
                                                            echo "<td>{$row['occur_description']}</td>";
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
                <h4 class="mb-sm-0 font-size-16 fw-bold">CATEGORY DATA FOR DOWNLOAD: &nbsp;&nbsp; <?php echo strtoupper($reporter_category); ?></h4>
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
                        <input type="hidden" name="reporter_category" value="' . htmlspecialchars($reporter_category) . '">
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
                $sql_location_trend = "SELECT occur_location, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as location_count 
                                       FROM occur
                                       WHERE reporter_category = '$reporter_category' 
                                       AND occur_date >= '$start_date'
                                       GROUP BY occur_location, report_month
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
                //echo "Debug: Location Data:<br>";
                //var_dump($location_data);
                display_data_block("Location:  $reporter_category", $location_data, $months, "primary");

                /** Program Data **/
                $sql_patient_program = "SELECT patient_program, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as program_count 
                                        FROM occur
                                        WHERE reporter_category = '$reporter_category' 
                                        AND occur_date >= '$start_date'
                                        GROUP BY patient_program, report_month
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
                //echo "Debug: Program Data:<br>";
                //var_dump($program_data);
                display_data_block("Program:  $reporter_category", $program_data, $months, "warning");

                /** Area Data **/
                $sql_occur_area = "SELECT occur_area, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as area_count 
                                   FROM occur
                                   WHERE reporter_category = '$reporter_category' 
                                   AND occur_date >= '$start_date'
                                   GROUP BY occur_area, report_month
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
                //echo "Debug: Area Data:<br>";
                //var_dump($area_data);
                display_data_block("Area:  $reporter_category", $area_data, $months, "success");

                /** Attending MD Data **/
                $sql_md_attending = "SELECT MD_Attending, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as md_count 
                                     FROM occur
                                     WHERE reporter_category = '$reporter_category' 
                                     AND occur_date >= '$start_date'
                                     GROUP BY MD_Attending, report_month
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
                //echo "Debug: MD Data:<br>";
                //var_dump($md_data);
                display_data_block("Attending MD:  $reporter_category", $md_data, $months, "danger");

                /** Patient Unit Data **/
                $sql_patient_unit = "SELECT patient_unit, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as unit_count 
                                     FROM occur
                                     WHERE reporter_category = '$reporter_category' 
                                     AND occur_date >= '$start_date'
                                     GROUP BY patient_unit, report_month
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
                //echo "Debug: Unit Data:<br>";
                //var_dump($unit_data);
                display_data_block("Unit:  $reporter_category", $unit_data, $months, "info");

                /** Severity Data **/
                $sql_severity = "SELECT rm_severity, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as severity_count 
                                 FROM occur
                                 WHERE reporter_category = '$reporter_category' 
                                 AND occur_date >= '$start_date'
                                 GROUP BY rm_severity, report_month
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
                //echo "Debug: Severity Data:<br>";
                //var_dump($severity_data);
                display_data_block("Severity:  $reporter_category", $severity_data, $months, "secondary");

                /** Subcategory Data **/
                $sql_subcategory = "SELECT occur_subcategory, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as subcategory_count 
                                    FROM occur
                                    WHERE reporter_category = '$reporter_category' 
                                    AND occur_date >= '$start_date'
                                    GROUP BY occur_subcategory, report_month
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
                //echo "Debug: Subcategory Data:<br>";
                //var_dump($subcategory_data);
                display_data_block("Subcategory:  $reporter_category", $subcategory_data, $months, "dark");
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
        
    <!-- Datatables:  Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatables:  Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

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





<!-- CONFIGURE CHART OF REPORTS BY HOUR (TIME OF OCCURRENCE, NOT SUBMISSION TIME) ========================================================== -->

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




