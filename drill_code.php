<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
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



<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->




<!-- PHP / SQL QUERY FOR TABLE  ================ -->


			<?php
				// Check if the 'rm_severity' parameter is set
				if (isset($_GET['occur_code'])) {
				    // Get the value of 'occur_code'
				    $occur_code = $_GET['occur_code'];

				    // Sanitize the value (optional but recommended)
				    $occur_code = htmlspecialchars($occur_code);

				    // You can now use $occur_location as needed
				    //echo "You selected the location: " . $occur_location;

				    // Continue with your logic, e.g., querying the database with this location
				} else {
				    echo "None selected.";
				}
			?>

        <?php 
        $sql =  "SELECT *
                 FROM occur
                 WHERE occur_code = '$occur_code'
                 ORDER BY occur_id asc 
                 ";
        $result = mysqli_query($conn, $sql);
                 if (!$result) 
                 { die("<p>Error in tables: " . mysql_error() . "</p>"); }

        $numrows = mysqli_num_rows($result);

        ?> 



<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo "Report Detail for:" . "  "  . $occur_code . "</h4>"; ?>
                                        <p class="card-title-desc">
                                        </p>
                                          <table id="myTable1" class="table table-bordered table-condensed dt-responsive table-hover table-condensed table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>ID</th>
                                                    <!--<th>Time</th> -->
                                                    <th>Type</th>
                                                    <th>Pt LName</th>
                                                    <th>MRN</th>
                                                    <th>Age</th>
                                                    <th>M/F</th>
                                                    <th>Unit</th>
                                                    <th>Location</th>
                                                    <th>Program</th>
                                                    <th>Category</th>
                                                    <th>Severity</th>
                                                    <th>Code</th>
                                                    <th>Attending</th> 
                                                    <th>Restraint</th>
                                                    <th>Seclusion</th>


                                                    <th>Notes</th>
                                                    <th>Intervention</th>       
                                                </tr>
                                            </thead>        

<!--  PHP FOR TABLE OUTPUT  ====================== -->
                                               
                                            <tbody>
                                            
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);
                                                        //Have table display date only without time
                                                        $formatted_date = date("m-d-Y", strtotime($row['occur_date']));

                                                        echo "<tr>";
                                                            echo "<td style='white-space:nowrap'>{$formatted_date}</td>";
                                                            echo "<td><a href='pdf_report.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            //echo "<td><a href='rm_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            //echo "<td>{$row['occur_time']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_type']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_last_name']}</td>";
                                                            echo "<td>{$row['patient_MRN']}</td>";
                                                            echo "<td>{$row['patient_age']}</td>";
                                                            echo "<td>{$row['patient_gender']}</td>";
                                                            echo "<td>{$row['patient_unit']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_location']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_program']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['rm_severity']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_code']}</td>";
                                                            echo "<td>{$row['md_attending']}</td>";
                                                            echo "<td>{$row['occur_patient_restraint']}</td>";
                                                            echo "<td>{$row['occur_patient_seclusion']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_description']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_intervention']}</td>";

                                                        echo "</tr>";
                                                    }
                                                ?>
<!--  END PHP  ====================== -->
											</tbody>
										</table>


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





