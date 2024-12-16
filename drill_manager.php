<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

// SECTION 1: GET THE DRILL-DOWN PARAMETER AND SESSION FILTERS
        // Get the specific manager
        $drill_manager = $_GET['manager_followup_name'] ?? '';

        // Check if we're using session filters (coming from filtered dashboard)
        if (isset($_SESSION['filters'])) {
            $filters = $_SESSION['filters'];
            $selected_month = $filters['month'];
            $selected_location = $filters['location'];
            $selected_loc = $filters['loc'];
            $selected_program = $filters['program'];
            $selected_unit = $filters['unit'];
            $selected_md = $filters['md'];
            $selected_area = $filters['area'];
            $selected_mgr = $filters['mgr'];
            
            // Calculate the start and end dates for the month filter
            //$start_date = $selected_month . '-01';
            //$end_date = date('Y-m-d', strtotime("$start_date +1 month"));
        }
?>            

<!-- ============================================  PAGE FORMATTING  ================================================================= -->

    <!-- Start right Content here -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- =============================================   PAGE TITLE  ======================================================================== -->

    <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                        <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

<!-- SECTION 2: FILTER DISPLAY -->

            <?php if (isset($_SESSION['filters'])): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5>Current Filters:</h5>
                           
                            Month: <strong><?php echo date('F Y', strtotime($selected_month . '-01')); ?></strong><br>
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
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5>Showing all records for:</h5>
                            Manager: <strong><?php echo htmlspecialchars($drill_manager); ?></strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>




<!-- SECTION 3: BUILD AND EXECUTE QUERY -->

<?php
if (isset($_SESSION['filters'])) {
   // Build the SQL query with all filters if coming from filtered dashboard
   $sql = "SELECT *  
             FROM occur
             WHERE manager_status NOT IN ('Complete', 'No Action Needed') 
             AND manager_status IS NOT NULL
             AND manager_status != ''
             AND manager_followup_name = '" . mysqli_real_escape_string($conn, $drill_manager) . "'
             
             ";

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
   
   $sql .= " ORDER BY occur_id DESC";
   
} else {
   // Simple query if coming from unfiltered dashboard
   $sql = "SELECT *  
             FROM occur
             WHERE manager_status NOT IN ('Complete', 'No Action Needed') 
             AND manager_status IS NOT NULL
             AND manager_status != ''
             AND manager_followup_name = '" . mysqli_real_escape_string($conn, $drill_manager) . "'
             
             ";
}

$result = mysqli_query($conn, $sql);
if (!$result) { 
   die("<p>Error in query: " . mysqli_error($conn) . "</p>"); 
}

$numrows = mysqli_num_rows($result);

// Set names for title/subtitle on Datatables
if (isset($_SESSION['filters'])) {
   $datatable_name = "Manager Detail: " . htmlspecialchars($drill_manager);
   //$datatable_name_sub = date('F Y', strtotime($selected_month . '-01'));
} else {
   $datatable_name = "Manager Detail: " . htmlspecialchars($drill_manager);
   //$datatable_name_sub = "All Records";
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