<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

 // Define URL parameter to database column mapping and variable names
        $filter_params = [
            'program' => [
                'db_column' => 'patient_program',
                'variable' => 'selected_program',
                'url_param' => 'program'
            ],
            'location' => [
                'db_column' => 'occur_location',
                'variable' => 'selected_location',
                'url_param' => 'location'
            ],
            'area' => [
                'db_column' => 'occur_area',
                'variable' => 'selected_area',
                'url_param' => 'area'
            ],
            'unit' => [
                'db_column' => 'patient_unit',
                'variable' => 'selected_unit',
                'url_param' => 'unit'
            ],
            'md' => [
                'db_column' => 'md_attending',
                'variable' => 'selected_md',
                'url_param' => 'md'
            ],
            'category' => [
                'db_column' => 'reporter_category',
                'variable' => 'selected_category',
                'url_param' => 'category'
            ],
            'loc' => [
                'db_column' => 'patient_loc',
                'variable' => 'selected_loc',
                'url_param' => 'loc'
            ]
        ];

    // Initialize all variables with defaults
        foreach ($filter_params as $param) {
            ${$param['variable']} = 'All';
        }

        // Handle dates first
        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
        } else if (isset($_GET['month'])) {  // Only set month range if explicitly provided in URL
            $selected_month = $_GET['month'];
            $start_date = $selected_month . '-01';
            $end_date = date('Y-m-t', strtotime($start_date));
        } else {
            $start_date = null;
            $end_date = null;
        }

    // Format dates if we have them
        if ($start_date && $end_date) {
            $formatted_start_date = date('m/d/y', strtotime($start_date));
            $formatted_end_date = date('m/d/y', strtotime($end_date));
        } else {
            $formatted_start_date = 'All Time';
            $formatted_end_date = '';
        }

    // Check URL parameters first, then session filters
        foreach ($filter_params as $param) {
            $variable = $param['variable'];
            $url_param = $param['url_param'];
            
            if (isset($_GET[$url_param])) {
                $$variable = $_GET[$url_param];
            } elseif (isset($_SESSION['filters'][$url_param])) {
                $$variable = $_SESSION['filters'][$url_param];
            }
        }

?>            

<!-- ============================================  PAGE FORMATTING  ================================================================= -->

    <!-- Start right Content here -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">


<!-- =============================================   PAGE TITLE  ======================================================================== -->


<!-- start page title ----------------------------->

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<!-- Filter Display -------------------------------->

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><?php echo isset($_SESSION['filters']) ? 'Current Filters:' : 'Showing all records for:'; ?></h5>
                                    
                                    <?php
                                    // Display active filters
                                    foreach ($filter_params as $param) {
                                        $variable = $param['variable'];
                                        if ($$variable != 'All') {
                                            $display_name = ucwords(str_replace('_', ' ', $param['db_column']));
                                            echo "$display_name: <strong>" . htmlspecialchars($$variable) . "</strong><br>";
                                        }
                                    }
                                    
                                    // Display date range if set
                                    if ($start_date && $end_date) {
                                        echo "Date Range: <strong>$formatted_start_date Through $formatted_end_date</strong><br>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Query and Results -->
                        <?php
                            // Build the SQL query
                                $sql = "SELECT * FROM occur WHERE 1=1"; // Start with always-true condition

                                // Add filters that are set
                                foreach ($filter_params as $param) {
                                    $variable = $param['variable'];
                                    if ($$variable != 'All') {
                                        $sql .= " AND " . $param['db_column'] . " = '" . mysqli_real_escape_string($conn, $$variable) . "'";
                                    }
                                }

                            // Add date conditions if we have dates
                                if ($start_date && $end_date) {
                                    $sql .= " AND occur_date >= '$start_date' AND occur_date <= '$end_date'";
                                }

                                $sql .= " ORDER BY occur_id DESC";

                                $result = mysqli_query($conn, $sql);
                                if (!$result) { 
                                    die("<p>Error in query: " . mysqli_error($conn) . "</p>"); 
                                }
                                $numrows = mysqli_num_rows($result);



//<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ==================================================== -->


// Set datatable titles ----------------------------->
                                
            $datatable_name = "Area Detail: " . htmlspecialchars($selected_area);
            $datatable_name_sub = $formatted_start_date;
            if ($formatted_end_date) {
                $datatable_name_sub .= " Through " . $formatted_end_date;
            }
            
            include ("datatable_standard_buttons.php"); 
            ?>

    
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


<!-- ========================================  PAGE SPECIFIC ASSETS  ============================================================== -->


<!-- Datatables: Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-keyTable/js/dataTables.keyTable.min.js"></script>
    <script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/pages/datatables.init.js"></script>
    <script src="assets/js/app.js"></script>

<!-- Add these script files for buttons-->
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#myTableStandardButtons').DataTable({
                "order": [[0, "desc"]],
                "responsive": true,
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"B>>rtip',
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

</body>
</html>