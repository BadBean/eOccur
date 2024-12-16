<?php
// Start the session and include necessary files
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php");
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

// To get the variable passed from bar chart click
if (isset($_GET['month'])) {
    // Retrieve the month passed in the URL (e.g., '2023-07')
    $selected_month = $_GET['month'];

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
                    </div>
                </div>
            </div>
            <!-- end page title -->

<!-- BOOTSTRAP ROW: TOTAL REPORTS BY DAY IN SELECTED MONTH -->
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
</div>

<!-- COLUMN CHART: TOTAL REPORTS BY DAY -->
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
