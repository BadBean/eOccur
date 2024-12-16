<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->


  


<!-- CSS:  MANUAL STYLING FOR DATATABLE   ======================================================================================== -->

        <style>
            table.dataTable thead th {
              background-color: #f2f2f2;
            }
            table.dataTable tbody td {
              color: #555;
            }
        </style>



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

                                    <div class="page-title-right">
                                        <!--
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Samply</a></li>
                                            <li class="breadcrumb-item active">Edit / Update Occurrence Report</li>
                                        </ol>
                                    -->
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<!-- ========================================  QUERY FOR DATATABLE   ========================================================= -->

<!-- SQL QUERY ===================== -->

            <?php 
            $sql =  "SELECT *
                     FROM occur
                     WHERE occur_status = 'Submitted'
                     ORDER BY occur_id desc
                     ";
            $result = mysqli_query($conn, $sql);
                     if (!$result) 
                     { die("<p>Error in tables: " . mysql_error() . "</p>"); }
            $numrows = mysqli_num_rows($result);

// TITLE AND SUBTITLES FOR DATATABLE  ================ -->

            $datatable_name = "Submitted Reports Pending Review"; 
            $datatable_name_sub = $format_selected_month_name;

            ?> 


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

    <?php
    include ("datatable_date_filter.php");
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

       
<!-- ========================================  PAGE SPECIFIC ASSETS  =========================================== -->
 
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

    
<!-- ========================================  PAGE SPECIFIC SCRIPTS  =========================================== -->
 
<!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
<script>
    $(document).ready(function() {
        // Initialize DataTable and store the instance in a variable
        var table = $('#myTable').DataTable({
            "order": [[ 0, "desc" ]] // Order by the first column (DATE) in descending order
        });
        
        // Custom filtering function which will search data in the DATE column
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                // Get the start and end dates from the input fields
                var min = $('#startDate').val();
                var max = $('#endDate').val();
                var dateStr = data[0]; // Assuming the DATE column is the first column (index 0)
                
                //If you set to true then rows with blank dates can be part of results        
                if (!dateStr) {
                    return false; // If there's no date, do not include
                }

                // Parse the date from "m/d/y" to a JavaScript Date object
                var dateParts = dateStr.split('/');
                var month = parseInt(dateParts[0], 10);
                var day = parseInt(dateParts[1], 10);
                var year = parseInt(dateParts[2], 10);
                year += (year < 100) ? 2000 : 0; // Adjust for 2-digit year if necessary
                var rowDate = new Date(year, month - 1, day);
                
                // Parse the min and max dates from the input fields
                var minDate = min ? new Date(min) : null;
                var maxDate = max ? new Date(max) : null;
                
                // Compare the row's date with the min and max dates
                if (
                    (!minDate || rowDate >= minDate) &&
                    (!maxDate || rowDate <= maxDate)
                ) {
                    return true;
                }
                return false;
            }
        );
        
        // Event listener for the start and end date inputs to redraw the table on change
        $('#startDate, #endDate').on('change', function() {
            table.draw();
        });
    });
</script>


   
<!-- ========================================  FINAL CLOSING TAGS  =========================================== -->
</body>
</html>











