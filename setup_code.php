<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>     

<!-- ============  PAGE SPECIFIC FILES / CSS =============================================================================================== -->






<!-- ============   PHP / SQL QUERY FOR USER TABLE  ========================================================================================= -->


<?php
    $error = "";
    
    $sql =  "SELECT *
               FROM occur_setup_codes";

    $result = mysqli_query($conn, $sql);
          if (!$result) 
          { die("<p>Error in tables: " . mysql_error() . "</p>"); }

 

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









    <!-- ========================================  BOOTSTRAP ROW     ============================================================== -->


                         <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Codes:</h4>
                                        

                                       
                                        <div class="table-responsive">
                                        <table id="userTable" class="table table-hover table-borderless align-middle table-condensed table-nowrap mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>NAME</th>
                                                    <th>DESCRIPTION</th>
                                                    <th>LONG NAME</th>
                                                    <th>NOTES</th>
                                                    <th>STATUS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>


        <?php
        $numrows = mysqli_num_rows($result);
        //echo "&nbsp&nbsp $numrows Users: ";
        echo"<br>";

        for ($i = 0; $i < $numrows; $i++) {
            $row = mysqli_fetch_array($result);


             // Format the phone number
            $formatted_phone = preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $row['user_cell']);

            //Concatenate to display full name
            $user_full_name = $row['user_first_name'] . " " . $row['user_last_name']; // Use regular space, not &nbsp  

            // Determine the status display with the appropriate styling
            if ($row['user_status'] == 'Active') {
                $status_display = '<span class="badge bg-success bg-soft text-success">Active</span>';
            } elseif ($row['user_status'] == 'Inactive') {
                $status_display = '<span class="badge bg-danger bg-soft text-danger">Inactive</span>';
            } else {
                $status_display = $row['user_status']; // For any other status
            }

            echo "<tr>";
            echo "<td>{$row['setup_code_id']}</td>";
            echo "<td>{$row['setup_code_name']}</td>";
            echo "<td>{$row['setup_code_description']}</td>";
            echo "<td>{$row['setup_code_long_name']}</td>";
            echo "<td>{$row['setup_code_notes']}</td>";
            echo "<td>{$row['setup_code_status']}</td>";
           
            echo "  <td>
                        <div class=\"dropdown\">
                            <a class=\"text-muted dropdown-toggle font-size-16\" role=\"button\"
                                data-bs-toggle=\"dropdown\" aria-haspopup=\"true\">
                                <i class=\"mdi mdi-dots-vertical\"></i>
                            </a>
                            <div class=\"dropdown-menu dropdown-menu-end\">
                                <a class=\"dropdown-item\" href=\"edit_code.php?id={$row[0]}\">Edit</a>
                                <a class=\"dropdown-item\" href=\"delete_code.php?id={$row[0]}\">Delete</a>
                            </div>
                        </div>
                    </td>";

            echo "</tr>";
        }
        ?>
    </tbody>
</table>



<!--
    <span class="badge bg-success bg-soft text-success">Active</span>

     <span class="glyphicon glyphicon-pencil"></span>Edit</a>
     <span class="glyphicon glyphicon-trash"></span>Delete</a>
     <span class="glyphicon glyphicon-flag"></span>Flag</a>


      <td><a href="edit_user.php?id=<?php echo $row[0]; ?>"><span class="fa fa-pencil-alt"></span></a></td>
            
-->




        </div> <!-- close coloumn -->    
    </div> <!-- close row -->



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

 

 <script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "paging": true,        // Enable pagination
            "searching": true,     // Enable searching
            "ordering": true,      // Enable sorting
            "info": true,          // Show information about entries
            "autoWidth": false,    // Disable auto width
            "columnDefs": [
                { "orderable": false, "targets": [8, 9] } // Disable ordering on EDIT and DELETE columns
            ]
        });
    });
</script>

    


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>







