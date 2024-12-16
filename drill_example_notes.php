<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

    
/*  
    PHP arrays are written using square bracets []
    An associative array array uses key => value pairs:

    This sets up an array named $filter_params and populates it with key value pairs defining the mapping of db columns, variables, and url parameters.
    This is a "nested array" meaning an array inside an array.  The "large container" is the $filter_param array.  Inside, each key has an array of mapping attributes.

    To access values in this structure, you would use for example:
        $filter_params['program']['db_column']  // gives you 'patient_program'

    When implementing into code it requires that all URL parameters being passed use the same standardized one word name noted below.  This is defined in the link on the referring page.  Also when referencing, all variables use the $selected_* convention
*/

      // Define URL parameter to database column mapping and variable names
        $filter_params = [
             'location' => [
                'db_column' => 'occur_location',
                'variable' => 'selected_location',
                'url_param' => 'location'
            ],
            'loc' => [
                'db_column' => 'patient_loc',
                'variable' => 'selected_loc',
                'url_param' => 'loc'
            ],
            'program' => [
                'db_column' => 'patient_program',
                'variable' => 'selected_program',
                'url_param' => 'program'
            ],
            'unit' => [
                'db_column' => 'patient_unit',
                'variable' => 'selected_unit',
                'url_param' => 'unit'
            ],
            'area' => [
                'db_column' => 'occur_area',
                'variable' => 'selected_area',
                'url_param' => 'area'
            ],
            'category' => [
                'db_column' => 'reporter_category',
                'variable' => 'selected_category',
                'url_param' => 'category'
            ],
            'md' => [
                'db_column' => 'md_attending',
                'variable' => 'selected_md',
                'url_param' => 'md'
            ],
             'severity' => [
                'db_column' => 'rm_severity',
                'variable' => 'selected_severity',
                'url_param' => 'severity'
            ]
            
        ];


/* 
    In this next section, it is using a foreach loop to go through the filter parameters above

    foreach is going through each item in $filter_params
    It's putting each item into a temporary variable called $param
    The weird-looking ${$param['variable']} is using "variable variables" - it's a way to create variable names dynamically

    It is setting the "variable" reference in the arrays above to All.  The variable name is being dynamially generated so for example:
         $selected_md = 'All';
         $selected_severity = 'All';
         ....

*/

    // Initialize all variables with defaults (set $selected_variable to "All")

        foreach ($filter_params as $param) {
            ${$param['variable']} = 'All';
        }

    // Handle dates first (account for month, start and end dates, or no date range)

        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
        } else {
            $selected_month = $_GET['month'] ?? $_SESSION['filters']['month'] ?? null;
            if ($selected_month) {
                $start_date = $selected_month . '-01';
                $end_date = date('Y-m-t', strtotime($start_date));
            } else {
                $start_date = null;
                $end_date = null;
            }
        }

    // Format dates if we have them
        if ($start_date && $end_date) {
            $formatted_start_date = date('m/d/y', strtotime($start_date));
            $formatted_end_date = date('m/d/y', strtotime($end_date));
        } else {
            $formatted_start_date = 'All Time';
            $formatted_end_date = '';
        }

    // Check URL parameters first, then session filters (default values are overwritten where they are set by either filter or url)
       
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
                                
            $datatable_name = "Program Detail: " . htmlspecialchars($selected_program);
            $datatable_name_sub = $formatted_start_date;
            if ($formatted_end_date) {
                $datatable_name_sub .= " Through " . $formatted_end_date;
            }
            
            include ("datatable_standard.php"); 
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


    
<!-- ========================================  PAGE SPECIFIC SCRIPTS  =============================================================== -->
 
<!-- Datatables initialization script / Add Order to change default by ID field / Apply date filters -->

    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                "order": [[ 0, "desc" ]]
            });
            
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#startDate').val();
                    var max = $('#endDate').val();
                    var dateStr = data[0];
                    
                    if (!dateStr) {
                        return false;
                    }

                    var dateParts = dateStr.split('/');
                    var month = parseInt(dateParts[0], 10);
                    var day = parseInt(dateParts[1], 10);
                    var year = parseInt(dateParts[2], 10);
                    year += (year < 100) ? 2000 : 0;
                    var rowDate = new Date(year, month - 1, day);
                    
                    var minDate = min ? new Date(min) : null;
                    var maxDate = max ? new Date(max) : null;
                    
                    if (
                        (!minDate || rowDate >= minDate) &&
                        (!maxDate || rowDate <= maxDate)
                    ) {
                        return true;
                    }
                    return false;
                }
            );
            
            $('#startDate, #endDate').on('change', function() {
                table.draw();
            });
        });
    </script>
      

<!-- ========================================  FINAL CLOSING TAGS  ============================================================ -->
</body>
</html>

