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
                                            <p class="card-title-desc"></p>
                                            <div class="d-flex flex-wrap gap-4"></div>
                                        </div>
                                    </div><!-- End card-body -->
                                </div><!-- End card -->
                            </div><!-- End col -->
                        </div><!-- End row -->

<!-- ========================================  BOOTSTRAP ROW: TABLE   =================================================================== -->

<!-- PHP / SQL QUERY FOR CATEGORY CHART    ========================================================================================= -->

<?php 
// Query to count # of reports by category
$sql_category = "SELECT reporter_category, COUNT(*) as count, DATE(occur_date) as occur_date
                 FROM occur
                 GROUP BY reporter_category, DATE(occur_date)";

$result_category = mysqli_query($conn, $sql_category);
if (!$result_category) { 
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
}

$data_category = [];

// Fetch data and store in an array
if (mysqli_num_rows($result_category) > 0) {
    while ($row = mysqli_fetch_assoc($result_category)) {
        $data_category[] = $row;
    }
}
?>

<!-- Pass data to JavaScript so Apex can use it -->
<script> var chartData_category = <?php echo json_encode($data_category); ?>; </script>

<!-- BOOTSTRAP ROW:  COLUMN 1:  CATEGORY CHART    ========================================================================================= -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body pb-2">
                <div class="d-flex flex-wrap align-items-center mb-2">
                    <h5 class="card-title me-2">REPORTS BY CATEGORY</h5>
                    <div class="ms-auto">
                        <div>
                            <button type="button" class="btn btn-primary" data-range-category="all">ALL</button>
                            <button type="button" class="btn btn-light" data-range-category="12mo">12 MO</button>
                            <button type="button" class="btn btn-light" data-range-category="mtd">MTD</button>
                            <button type="button" class="btn btn-light" data-range-category="24hr">24 HR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    

<div class="row">   
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Chart Container -->
                <div id="chart_category" class="apex-charts" dir="ltr"></div>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->

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
   // Function to aggregate data
function aggregateData_category(data) {
    return data.reduce((acc_category, item) => {
        if (!acc_category[item.reporter_category]) {
            acc_category[item.reporter_category] = 0;
        }
        acc_category[item.reporter_category] += parseInt(item.count);
        return acc_category;
    }, {});
}

// Initial data aggregation
let initialAggregatedData_category = aggregateData_category(chartData_category);
let initialCategories_category = Object.keys(initialAggregatedData_category);
let initialCounts_category = Object.values(initialAggregatedData_category);

var options_category = {
    series: [{
        name: 'Reports Count',
        data: initialCounts_category
    }],
    chart: {
        type: 'bar',
        width: '100%',
        height: '400px',
        events: {
            dataPointSelection: function(event, chartContext, config) {
                var selectedIndex = config.dataPointIndex;
                var selectedCategory = initialCategories_category[selectedIndex];
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
    colors: ['#00b33c'],
    xaxis: {
        categories: initialCategories_category
    },
    title: {
        text: '',
        align: 'top',
        margin: 0
    }
};

var chart_category = new ApexCharts(document.querySelector("#chart_category"), options_category);
chart_category.render();

function updateChart_category(range) {
    let newData;
    const currentDate = new Date();
    
    switch (range) {
        case 'all':
            newData = chartData_category;
            break;
        case '12mo':
            newData = chartData_category.filter(item => {
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
            newData = chartData_category.filter(item => {
                if (item.occur_date) {
                    let itemDate = new Date(item.occur_date + 'T00:00:00');
                    return itemDate.getMonth() === currentDate.getMonth() && 
                           itemDate.getFullYear() === currentDate.getFullYear();
                }
                return false;
            });
            break;
        case '24hr':
            newData = chartData_category.filter(item => {
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
    let aggregatedData_category = aggregateData_category(newData);
    let categories_category = Object.keys(aggregatedData_category);
    let counts_category = Object.values(aggregatedData_category);

    if (counts_category.length > 0) {
        chart_category.updateSeries([{
            name: 'Reports Count',
            data: counts_category
        }]);
        chart_category.updateOptions({
            xaxis: {
                categories: categories_category
            }
        });
    } else {
        chart_category.updateSeries([{
            name: 'Reports Count',
            data: []
        }]);
        chart_category.updateOptions({
            xaxis: {
                categories: []
            }
        });
    }

    // Update button styles
    updateButtonStyles_category(range);
}

function updateButtonStyles_category(activeRange) {
    const buttons = document.querySelectorAll('button[data-range-category]');
    buttons.forEach(button => {
        button.classList.remove('btn-primary', 'btn-light');
        if (button.getAttribute('data-range-category') === activeRange) {
            button.classList.add('btn-primary');
        } else {
            button.classList.add('btn-light');
        }
    });
}

// Attach button click events after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('button[data-range-category]');
    buttons.forEach(button => {
        const range = button.getAttribute('data-range-category');
        button.addEventListener('click', () => updateChart_category(range));
    });

    // Set initial active button and chart data
    updateButtonStyles_category('all');
    updateChart_category('all');
});
</script>   

<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->

    </body>
</html>
