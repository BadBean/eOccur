<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

// SECTION 1: GET THE DRILL-DOWN PARAMETER AND INITIALIZE DATE VARIABLES

    $start_date = null;
    $end_date = null;

    // Initialize filter variables with defaults
    $selected_month = '';
    $selected_location = 'All';
    $selected_loc = 'All';
    $selected_program = 'All';
    $selected_unit = 'All';
    $selected_md = 'All';
    $selected_area = 'All';

// SECTION 2: HANDLE DATE INITIALIZATION FOR BOTH FILTERED AND UNFILTERED CASES

    if (isset($_SESSION['filters'])) {
        // Get values from session filters
        $filters = $_SESSION['filters'];
        $selected_month = $filters['month'];
        $selected_location = $filters['location'];
        $selected_loc = $filters['loc'];
        $selected_program = $filters['program'];
        $selected_unit = $filters['unit'];
        $selected_md = $filters['md'];
        $selected_area = $filters['area'];
    } else {
        // Handle unfiltered case - use current month if no month specified
        $selected_month = $_GET['month'] ?? date('Y-m');
    }


// Check if start_date and end_date are set in the browser (GET parameters)
    if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
        // Use the start_date and end_date from GET parameters
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
    } else {
        // Calculate start and end dates based on selected month
        $start_date = $selected_month . '-01';
        $end_date = date('Y-m-d', strtotime("$start_date +1 month"));
    }

// Format $start_date and $end_date as mm/dd/yy
    $formatted_start_date = date('m/d/y', strtotime($start_date));
    $formatted_end_date = date('m/d/y', strtotime($end_date));

?>            
  

<!-- ============================================  PAGE FORMATTING  ================================================================= -->

    <!-- Start right Content here -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- =============================================   PAGE TITLE  ======================================================================== -->


<!-- start page title --------------------------->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                        <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


<!-- SECTION 3: FILTER DISPLAY ------------------->

            <?php if (isset($_SESSION['filters'])): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5>Current Filters:</h5>
                           
                            <?php if (!empty($selected_month) && $selected_month != 'All'): ?>
                                Month: <strong><?php echo date('F Y', strtotime($selected_month . '-01')); ?></strong><br>
                            <?php endif; ?>

                            <?php if (!empty($selected_location) && $selected_location != 'All'): ?>
                                Location: <strong><?php echo htmlspecialchars($selected_location); ?></strong><br>
                            <?php endif; ?>

                            <?php if (!empty($selected_area) && $selected_area != 'All'): ?>
                                Area: <strong><?php echo htmlspecialchars($selected_area); ?></strong><br>
                            <?php endif; ?>

                            <?php if (!empty($selected_loc) && $selected_loc != 'All'): ?>
                                Level of Care: <strong><?php echo htmlspecialchars($selected_loc); ?></strong><br>
                            <?php endif; ?>

                            <?php if (!empty($selected_program) && $selected_program != 'All'): ?>
                                Program: <strong><?php echo htmlspecialchars($selected_program); ?></strong><br>
                            <?php endif; ?>

                            <?php if (!empty($selected_unit) && $selected_unit != 'All'): ?>
                                Unit: <strong><?php echo htmlspecialchars($selected_unit); ?></strong><br>
                            <?php endif; ?>

                            <?php if (!empty($selected_md) && $selected_md != 'All'): ?>
                                Attending: <strong><?php echo htmlspecialchars($selected_md); ?></strong><br>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5>Showing all records for:</h5>
                            Severity Level: <strong><?php echo htmlspecialchars($drill_severity); ?></strong>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


<!-- SECTION 4: BUILD AND EXECUTE QUERY -->
<?php
    if (isset($_SESSION['filters'])) {
        // Build the SQL query with all filters if coming from filtered dashboard
        $sql = "SELECT *
                FROM occur
                WHERE occur_date >= '$start_date' AND occur_date < '$end_date'
                AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";

        // Add conditions for each dropdown if a specific option is selected
        if ($selected_location != 'All') {
            $sql .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
        }
        if ($selected_area != 'All') {
            $sql .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
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
    } else {
        // Simple query if coming from unfiltered dashboard
        $sql = "SELECT *
                FROM occur
                WHERE occur_date >= '$start_date' AND occur_date < '$end_date'
                AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
    }

    // Common ORDER BY clause
    $sql .= " ORDER BY occur_id DESC";

    $result = mysqli_query($conn, $sql);
    if (!$result) { 
        die("<p>Error in query: " . mysqli_error($conn) . "</p>"); 
    }

    $numrows = mysqli_num_rows($result);

    // Set names for title/subtitle on Datatables
    if (isset($_SESSION['filters'])) {
        $datatable_name = "Level of Care Detail: " . htmlspecialchars($selected_loc);
        $datatable_name_sub = $formatted_start_date . "  " . "Through" . $formatted_end_date;
    } else {
        $datatable_name = "Level of Care Detail: " . htmlspecialchars($selected_loc);
        $datatable_name_sub = $formatted_start_date . "  " . "Through" . $formatted_end_date;
    }
?>

<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ==================================================== -->
                            
<!-- Include the datatable -->

            <?php include ("datatable_standard_buttons.php"); ?>


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