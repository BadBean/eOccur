<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

?>         

<!-- PAGE SPECIFIC    ===================================================================================================== -->

<?php

// SECTION 1: GET THE DRILL-DOWN PARAMETER AND SESSION FILTERS
    // Get the specific manager status
        $drill_mgr_status = $_GET['manager_status'] ?? '';

    // Initialize filter variables with defaults
        $selected_month = 'All';
        $selected_location = 'All';
        $selected_loc = 'All';
        $selected_program = 'All';
        $selected_unit = 'All';
        $selected_area = 'All';
        $selected_md = 'All';
        $selected_mgr = 'All';
        $selected_category = 'All';
        $selected_severity = 'All';
        $selected_flag = 'All';

    // If session filters exist, override defaults
    if (isset($_SESSION['filters'])) {
        $filters = $_SESSION['filters'];
        $selected_month = isset($filters['month']) ? $filters['month'] : 'All';
        $selected_location = isset($filters['location']) ? $filters['location'] : 'All';
        $selected_loc = isset($filters['loc']) ? $filters['loc'] : 'All';
        $selected_program = isset($filters['program']) ? $filters['program'] : 'All';
        $selected_unit = isset($filters['unit']) ? $filters['unit'] : 'All';
        $selected_area = isset($filters['area']) ? $filters['area'] : 'All';
        $selected_md = isset($filters['md']) ? $filters['md'] : 'All';
        $selected_mgr = isset($filters['mgr']) ? $filters['mgr'] : 'All';
        $selected_category = isset($filters['category']) ? $filters['category'] : 'All';
        $selected_severity = isset($filters['severity']) ? $filters['severity'] : 'All';
        $selected_flag = isset($filters['flag']) ? $filters['flag'] : 'All';


    }

    // Debugging: Print the filter values to verify consistency
    //echo "<pre>";
    //echo "Debug: Session Filters for Manager Status\n";
    //print_r($filters);
    //echo "Drill Manager Status: " . htmlspecialchars($drill_mgr_status) . "\n";
    //echo "</pre>";
    
?>

<!-- ============================================  PAGE FORMATTING  ================================================================= -->

    <!-- Start right Content here -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

<!-- =============================================   PAGE TITLE  ======================================================================== -->

<!-- start page title -------------------------------->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR</h4>
                                </div>
                            </div>
                        </div>
                      

<!-- SECTION 2: FILTER DISPLAY ----------------------->

                        <?php if (isset($_SESSION['filters'])): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5>Current Filters:</h5>

                                    <?php if ($selected_location != 'All'): ?>
                                        Location: <strong><?php echo htmlspecialchars($selected_location); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_area != 'All'): ?>
                                        Area: <strong><?php echo htmlspecialchars($selected_area); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_loc != 'All'): ?>
                                        Level of Care: <strong><?php echo htmlspecialchars($selected_loc); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_program != 'All'): ?>
                                        Program: <strong><?php echo htmlspecialchars($selected_program); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_unit != 'All'): ?>
                                        Unit: <strong><?php echo htmlspecialchars($selected_unit); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_md != 'All'): ?>
                                        Attending: <strong><?php echo htmlspecialchars($selected_md); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_mgr != 'All'): ?>
                                        Manager: <strong><?php echo htmlspecialchars($selected_mgr); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_category != 'All'): ?>
                                        Category: <strong><?php echo htmlspecialchars($selected_category); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_severity != 'All'): ?>
                                        Severity: <strong><?php echo htmlspecialchars($selected_severity); ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($selected_flag != 'All'): ?>
                                        Flag: <strong><?php echo htmlspecialchars($selected_flag); ?></strong><br>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5>Showing all records</h5>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

<!-- SECTION 3: BUILD AND EXECUTE QUERY ------------------------>

                        <?php
                        if (isset($_SESSION['filters'])) {
                            // Build the SQL query with all filters if coming from filtered dashboard
                            if ($drill_mgr_status === 'No Manager Status') {
                                // If "No Manager Status" is selected, filter for NULL or empty strings
                                $sql = "SELECT *  
                                        FROM occur
                                        WHERE (manager_status IS NULL OR TRIM(manager_status) = '')
                                        AND occur_status != 'Closed'
                                        ";
                            } else {
                                // If a specific manager status is provided
                                $sql = "SELECT *  
                                        FROM occur
                                        WHERE occur_status != 'Closed'
                                        AND manager_status = '" . mysqli_real_escape_string($conn, $drill_mgr_status) . "'";
                            }

                            // Add conditions for each dropdown if a specific option is selected
                            if ($selected_location != 'All') {
                                $sql .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                            }
                            if ($selected_area != 'All') {
                                $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
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
                            if ($selected_mgr != 'All') {
                                $sql .= " AND manager_followup_name = '" . mysqli_real_escape_string($conn, $selected_mgr) . "'";
                            }
                            if ($selected_category != 'All') {
                                $sql .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                            }
                            if ($selected_severity != 'All') {
                                $sql .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
                            }
                            if ($selected_flag != 'All') {
                                // Special handling for flag since it might be comma-separated
                                $sql .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
                            }

                            $sql .= " ORDER BY occur_id DESC";

                        } else {
                            // Simple query if coming from unfiltered dashboard
                            if ($drill_mgr_status === 'No Manager Status') {
                                // If "No Manager Status" is selected, filter for NULL or empty strings
                                $sql = "SELECT *  
                                        FROM occur
                                        WHERE (manager_status IS NULL OR TRIM(manager_status) = '')
                                        AND occur_status != 'Closed'
                                        ";
                            } else {
                                // If a specific manager status is provided
                                $sql = "SELECT *  
                                        FROM occur
                                        WHERE occur_status != 'Closed'
                                        AND manager_status = '" . mysqli_real_escape_string($conn, $drill_mgr_status) . "'";
                            }
                        }

                        // Debugging: Print the SQL query to verify correctness
                        //echo "<pre>";
                        //echo "Debug: SQL Query for Manager Status\n";
                        //echo htmlspecialchars($sql);
                        //echo "</pre>";

                        $result = mysqli_query($conn, $sql);
                        if (!$result) { 
                            die("<p>Error in query: " . mysqli_error($conn) . "</p>"); 
                        }

                        $numrows = mysqli_num_rows($result);




// Set names for title/subtitle on Datatables --------------->

                        if (isset($_SESSION['filters'])) {
                            $datatable_name = "Pending Manager Status Review: " . htmlspecialchars($drill_mgr_status);
                        } else {
                            $datatable_name = "Manager Detail: " . htmlspecialchars($drill_mgr_status);
                        }
                        ?>

<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ==================================================== -->
                            
<!-- Include the datatable -->
<?php include ("datatable_manager.php"); ?>

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

<!-- Datatables initialization script -->
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

</body>
</html>
