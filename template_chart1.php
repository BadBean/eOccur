<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            


<!-- ============  PAGE SPECIFIC FILES / CSS =============================================================================================== -->









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

<!-- ========================================  BOOTSTRAP ROW: TABLE   =================================================================== -->



<!-- PHP / SQL QUERY FOR CATEGORY CHART    ========================================================================================= -->

            <?php 
            //Query to count # of reports by category

               $sql = "SELECT reporter_category, COUNT(*) as count, DATE(occur_date) as occur_date
                       FROM occur
                       GROUP BY reporter_category, DATE(occur_date)";

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




<!-- BOOTSTRAP ROW:  COLUMN 1:  CATEGORY CHART    ========================================================================================= -->


            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h5 class="card-title me-2">REPORTS BY CATEGORY</h5>
                                <div class="ms-auto">
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="updateChart('all')">ALL</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="updateChart('12mo')">12 MO</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="updateChart('mtd')">MTD</button>
                                        <button type="button" class="btn btn-light btn-sm" onclick="updateChart('24hr')">24 HR</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Chart Container -->
                                            <div id="chart_category" class="apex-charts" dir="ltr"></div>
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










 <!-- SCRIPT FOR CATEGORY CHART -->
<script>

// Function to aggregate data by reporter category
function aggregateData(data) {
    // The 'reduce' method is used to iterate over the data array and build an object
    // where keys are reporter categories and values are the sum of counts for each category
    return data.reduce((acc, item) => {
        // If this category doesn't exist in the accumulator yet, initialize it to 0
        if (!acc[item.reporter_category]) {
            acc[item.reporter_category] = 0;
        }
        // Add the count (converted to an integer) to the accumulator for this category
        acc[item.reporter_category] += parseInt(item.count);
        return acc;
    }, {}); // The initial value of the accumulator is an empty object
}

// Initial data aggregation
// 'chartData' is assumed to be a global variable containing the raw data from PHP
let initialAggregatedData = aggregateData(chartData);
// Extract categories (keys) and counts (values) from the aggregated data
let initialCategories = Object.keys(initialAggregatedData);
let initialCounts = Object.values(initialAggregatedData);


// Configuration options for the ApexCharts 
var options = {
    series: [{
        name: 'Reports Count',
        data: initialCounts // Use the initial aggregated data
    }],
    chart: {
        type: 'bar',
        width: '100%',
        height: '400px',
        events: {
            // This function is called when a bar in the chart is clicked
            dataPointSelection: function(event, chartContext, config) {
                var selectedIndex = config.dataPointIndex;
                var selectedCategory = initialCategories[selectedIndex];
                // Redirect to a detail page for the selected category
                window.location.href = 'bar_detail_category.php?category=' + encodeURIComponent(selectedCategory);
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
    colors: ['#00b33c'], // Color of the bars
    xaxis: {
        categories: initialCategories // Use the initial categories
    },
    title: {
        text: '',
        align: 'top',
        margin: 0
    }
};

// Create and render the chart
var chart = new ApexCharts(document.querySelector("#chart_category"), options);
chart.render();




// Function to update the chart based on the selected time range
function updateChart(range) {
    let newData;
    const currentDate = new Date();
    
    // Filter data based on the selected range
    switch (range) {
        case 'all':
            newData = chartData; // Use all data
            break;
        case '12mo':
            newData = chartData.filter(item => {
                if (item.occur_date) {
                    let itemDate = new Date(item.occur_date);
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
                    let itemDate = new Date(item.occur_date);
                    return itemDate.getMonth() === currentDate.getMonth() && 
                           itemDate.getFullYear() === currentDate.getFullYear();
                }
                return false;
            });
            break;
        case '24hr':
            newData = chartData.filter(item => {
                if (item.occur_date) {
                    let itemDate = new Date(item.occur_date);
                    const last24Hours = new Date(currentDate.getTime() - 24 * 60 * 60 * 1000);
                    return itemDate >= last24Hours;
                }
                return false;
            });
            break;
        default:
            newData = [];
    }

    // Aggregate the filtered data
    let aggregatedData = aggregateData(newData);
    let categories = Object.keys(aggregatedData);
    let counts = Object.values(aggregatedData);

    // Update the chart with new data
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
        // If no data, show empty chart
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

    // Update button styles to show which range is active
    updateButtonStyles(range);
}

// Function to update button styles based on the active range
function updateButtonStyles(activeRange) {
    const buttons = document.querySelectorAll('.btn-primary, .btn-light');
    buttons.forEach(button => {
        button.classList.remove('btn-primary', 'btn-light');
        button.classList.add(button.getAttribute('data-range') === activeRange ? 'btn-primary' : 'btn-light');
    });
}

// Wait for the DOM to be fully loaded before attaching event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Select all buttons that have an onclick attribute starting with "updateChart"
    const buttons = document.querySelectorAll('button[onclick^="updateChart"]');
    buttons.forEach(button => {
        // Extract the range value from the onclick attribute
        const range = button.getAttribute('onclick').match(/'(.+)'/)[1];
        // Set a data-range attribute on the button for easy access
        button.setAttribute('data-range', range);
        // Add a click event listener that calls updateChart with the correct range
        button.addEventListener('click', () => updateChart(range));
    });

    // Set initial active button and chart data
    updateButtonStyles('all');
    updateChart('all');
});
</script>



   

    


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->

    </body>
</html>







