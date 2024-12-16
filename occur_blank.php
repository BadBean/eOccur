<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");


?>            


<!-- ========================================   PAGE SPECIFIC FILES  ======================================================================== -->






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

<!-- ========================================   BOOTSTRAP ROW  ======================================================================== -->

<!-- ========================================  BOOTSTRAP ROW    =================================================================== -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5> Card </h5> 


<!-- PHP / SQL QUERY TO PULL CATEGORY DETAIL    ========================================================================================= -->

<?php 

// Generate a list of the last 12 months
$months = [];
for ($i = 11; $i >= 0; $i--) {
    $months[] = date('Y-m', strtotime("-$i months"));
}

// Query to count # of reports submitted but not reviewed by RM
$sql_count = "
    SELECT 
        DATE_FORMAT(occur_date, '%Y-%m') AS month_year, 
        COUNT(*) AS item_count
    FROM 
        occur
    WHERE 
        reporter_category = '$category' AND
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

<!-- =============================================== BOOTSTRAP ROW: CHART RENDER / SCRIPT ========================= -->

                        <div class="row">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $category; ?></h4>
                                        <p class="card-title-desc"></p>

                                        <div id="chart"></div>

                                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                                        <script>
                                            var options = {
                                                chart: {
                                                    type: 'bar',
                                                    width: '100%',  
                                                    height: '400px' 
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
                                                    text: 'Reports Submitted by Month',
                                                    align: 'center'
                                                }
                                            };

                                            var chart = new ApexCharts(document.querySelector("#chart"), options);
                                            chart.render();
                                        </script>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end column -->
                        </div> <!-- end row -->

                                 


<!-- ========================================  BOOTSTRAP ROW    =================================================================== -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5> Card </h5> 
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->




         
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


<!-- ========================================  PAGE SPECIFIC SCRIPTS / GRAPHS   ========================================================== -->





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

    <!-- Datatables JS / Original script 
        <script> $(document).ready( function () {
                 $('#myTable').DataTable();
                 } );
        </script>
    -->

    <!-- Datatables JS / Add Order to change default by ID field -->
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [[ 0, "desc" ]] // Order by the first column (ID) in descending order
            });
        });
    </script>





<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>





<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>






