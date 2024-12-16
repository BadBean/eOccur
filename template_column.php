<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
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

<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" id="buttons">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Blank Template / Card</h4>

                                        <div>
                                            <h5 class="font-size-14"></h5>
                                            <p class="card-title-desc">
                                            </p>

                                            <div class="d-flex flex-wrap gap-4">
                                                
                                            </div>
                                        </div>
                                     
                                    </div><!-- End card-body -->
                                </div><!-- End card -->
                            </div><!-- End col -->
                        </div><!-- End row -->

<!-- ========================================  BOOTSTRAP ROW: COLUMN CHART   =================================================================== -->


<!-- PHP / SQL QUERY TO PULL DETAIL BY MONTH ----- -->

<?php 

// Generate a list of the last 12 months.
$months_report_count = [];
for ($i = 11; $i >= 0; $i--) {
    $months_report_count[] = date('Y-m', strtotime("-$i months"));
}

// Query to count # of reports by month and type
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

<!-- COLUMN CHART: TOTAL REPORTS BY MONTH -->
<script>
    // Parse the PHP data
    var months_report_count = <?php echo $months_js_report_count; ?>;
    var chartData_report_count = <?php echo $chart_data_js_report_count; ?>;

    // Chart options
    var options_report_count = {
        chart: {
            type: 'bar',
            height: 400
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
