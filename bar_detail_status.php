<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

?>         

<!-- PAGE SPECIFIC    ===================================================================================================== -->

<?php
// SECTION 1: INITIALIZE ALL VARIABLES / SESSION FILTERS

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

  // Get variables passed from bar chart click - check each independently
        $status = $_GET['status'] ?? '';
        $mgr_status = $_GET['mgr'] ?? '';


?>

<!-- ============================================  PAGE FORMATTING  ================================================================= -->
<!-- Start right Content here -->

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- ========================================   PAGE TITLE  ======================================================================== -->

<!-- start page title ----------------------------------->
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
                      

<!-- SECTION 2: FILTER DISPLAY ----------------------------->

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <?php if (isset($_SESSION['filters'])): ?>
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
  
                                        <?php else: ?>
                                            <h5>Showing all records for status: <?php echo htmlspecialchars($status); ?></h5>
                                        <?php endif; ?>
                                </div>
                            </div>
                        </div> <!-- end row -->


<!-- ========================================  BOOTSTRAP ROW:  CHART  ========================================================= -->

<!-- PHP / SQL QUERY TO PULL STATUS DETAIL  ------------------>

                        <?php 
                        // Generate a list of the last 12 months
                            $months = [];
                            for ($i = 11; $i >= 0; $i--) {
                                $months[] = date('Y-m', strtotime("-$i months"));
                        }

                        // Build the base query
                       // Build the base query
                            $sql_count = "
                                SELECT 
                                    DATE_FORMAT(occur_date, '%Y-%m') AS month_year, 
                                    COUNT(*) AS item_count
                                FROM occur
                                WHERE occur_status = '" . mysqli_real_escape_string($conn, $status) . "'
                                AND occur_date >= DATE_FORMAT(CURDATE() - INTERVAL 12 MONTH, '%Y-%m-01')";

                            // Add filter conditions
                            if ($selected_location != 'All') {
                                $sql_count .= " AND occur_location = '" . mysqli_real_escape_string($conn, $selected_location) . "'";
                            }
                            if ($selected_area != 'All') {
                                $sql_count .= " AND occur_area = '" . mysqli_real_escape_string($conn, $selected_area) . "'";
                            }
                            if ($selected_loc != 'All') {
                                $sql_count .= " AND patient_loc = '" . mysqli_real_escape_string($conn, $selected_loc) . "'";
                            }
                            if ($selected_program != 'All') {
                                $sql_count .= " AND patient_program = '" . mysqli_real_escape_string($conn, $selected_program) . "'";
                            }
                            if ($selected_unit != 'All') {
                                $sql_count .= " AND patient_unit = '" . mysqli_real_escape_string($conn, $selected_unit) . "'";
                            }
                            if ($selected_md != 'All') {
                                $sql_count .= " AND md_attending = '" . mysqli_real_escape_string($conn, $selected_md) . "'";
                            }
                            if ($selected_mgr != 'All') {
                                $sql_count .= " AND manager_followup_name = '" . mysqli_real_escape_string($conn, $selected_mgr) . "'";
                            }
                            if ($selected_category != 'All') {
                                $sql_count .= " AND reporter_category = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
                            }
                            if ($selected_severity != 'All') {
                                $sql_count .= " AND rm_severity = '" . mysqli_real_escape_string($conn, $selected_severity) . "'";
                            }
                            if ($selected_flag != 'All') {
                                // Special handling for flag since it might be comma-separated
                                $sql_count .= " AND FIND_IN_SET('" . mysqli_real_escape_string($conn, $selected_flag) . "', occur_flag)";
                            }

                            // Add ending group by / order by SQL 
                            $sql_count .= " GROUP BY DATE_FORMAT(occur_date, '%Y-%m')
                                ORDER BY DATE_FORMAT(occur_date, '%Y-%m')";
    
                         // Process results
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


<!-- Chart Display -------------------------------------->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo htmlspecialchars($status); ?></h4>
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
                                    </div>
                                </div>
                            </div>
                        </div>


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

<!-- SECTION 3: DATATABLE QUERY ------------------->
                        <?php 
                        // Build the base query
                        $sql = "SELECT * FROM occur WHERE occur_status = '" . mysqli_real_escape_string($conn, $status) . "'";

                        // Add filter conditions
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

                        $result = mysqli_query($conn, $sql);
                        if (!$result) { 
                            die("<p>Error in query: " . mysqli_error($conn) . "</p>"); 
                        }
                        $numrows = mysqli_num_rows($result);
                        ?>

<!-- SECTION 3: DATATABLE DISPLAY ------------------->

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Status: <?php echo $status; ?></h4>
                                        <p class="card-title-desc">
                                        </p>
                                        <!-- Date filter inputs -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="startDate">Start Date:</label>
                                                <input type="date" id="startDate" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="endDate">End Date:</label>
                                                <input type="date" id="endDate" class="form-control">
                                            </div>
                                        </div>
                                        
                                          <table id="myTable" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>ID</th>
                                                    <!--<th>Time</th> -->
                                                    <!-- <th>Type</th> -->
                                                    <th>Category</th>
                                                    <th>Pt LName</th>
                                                    <!-- <th>MRN</th> -->
                                                    <th>Age</th>
                                                    <th>M/F</th>
                                                    <th>Unit</th>
                                                    <th>Location</th> 
                                                    <!-- <th>Program</th> -->
                                                    <th>Area</th> 
                                                  
                                                    <!-- <th>Subcategory</th> -->
                                                    <th>Severity</th>
                                                    <th>Injury?</th>
                                                    <th>Status</th>    
                                                    <!-- <th>Attending</th> -->
                                                   
                                                    <th>Actions</th>


                                                    <th>Description</th>
                                                    <th>Intervention</th>
                                                    <!-- <th>Time</th> -->

                                                    <th>Restraint</th>
                                                    <th>Seclusion</th>  
                                                    
                                                </tr>
                                            </thead>             

<!--  PHP FOR TABLE OUTPUT  ====================== -->
                                               
                                            <tbody>
                                                <?php for ($i = 0; $i < $numrows; $i++): 
                                                    $row = mysqli_fetch_array($result);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                        if (!empty($row['occur_date'])) {
                                                            echo date("m/d/y", strtotime($row['occur_date']));
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><a href="pdf_report.php?id=<?php echo $row['occur_id']; ?>"><?php echo $row['occur_id']; ?></a></td>
                                                    <td style="white-space:nowrap"><?php echo $row['reporter_category']; ?></td>
                                                    <td style="white-space:nowrap"><?php echo $row['patient_last_name']; ?></td>
                                                    <td><?php echo $row['patient_age']; ?></td>
                                                    <td><?php echo $row['patient_gender']; ?></td>
                                                    <td><?php echo $row['patient_unit']; ?></td>
                                                    <td style="white-space:nowrap"><?php echo $row['occur_location']; ?></td>
                                                    <td style="white-space:nowrap"><?php echo $row['occur_area']; ?></td>
                                                    <td><?php echo $row['rm_severity']; ?></td>
                                                    <td><?php echo $row['occur_patient_injury']; ?></td>
                                                    <td><?php echo $row['occur_status']; ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="text-muted dropdown-toggle font-size-16" role="button"
                                                                data-bs-toggle="dropdown" aria-haspopup="true">
                                                                <i class="mdi mdi-dots-vertical"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="occur_pdf.php?id=<?php echo $row[0]; ?>">View/Print</a>
                                                                <a class="dropdown-item" href="edit_occur.php?id=<?php echo $row[0]; ?>">Edit Report</a>
                                                                <a class="dropdown-item" href="rm_review.php?id=<?php echo $row[0]; ?>">Mgmt Review</a>
                                                                <a class="dropdown-item" href="delete_occur.php?id=<?php echo $row[0]; ?>">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="white-space:nowrap"><?php echo $row['occur_description']; ?></td>
                                                    <td style="white-space:nowrap"><?php echo $row['occur_intervention']; ?></td>
                                                    <td><?php echo $row['occur_patient_restraint']; ?></td>
                                                    <td><?php echo $row['occur_patient_seclusion']; ?></td>
                                                </tr>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
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




<!-- ========================================  PAGE SPECIFIC ASSETS  ========================================================== -->

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
     
     <!-- Apex charts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<!-- ========================================  PAGE SPECIFIC SCRIPTS  ========================================================== -->


    <!-- Datatables JS / Add Order to change default by ID field -->
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "order": [[ 1, "desc" ]] // Order by the first column (ID) in descending order
                });
            });
        </script>


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




