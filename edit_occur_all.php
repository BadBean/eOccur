<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");

?>        

 
<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->

    <!-- DataTables -->
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Select datatable -->
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />     

<!-- ================================================  SQL QUERY  ============================================= -->

    <?php
        if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0))
          {
            $query = "SELECT *
                      FROM occur
                      WHERE occur_id = {$_GET['id']}
                     ";
            $result = mysqli_query($conn, $query);

            //$occur_id = $_GET['id'];

            if (!$result) 
            {
              print'<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
              print'<p> The query being run was:  ' . $query . '</p>';
            }
            else
            {
              $row = mysqli_fetch_array($result);
            }
          }


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
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR </h4>

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


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"> Edit / Update</h4>
                                        <p class="card-title-desc">
                                        </p>


<!-- ================================================  FORM ACTION / SUBMIT   =========================================== -->
<!-- includes form_value file to populate existing values prior to being updated / overwritten -->

                                        <form action="update_occur.php" class="form-horizontal" method="post">

                                              <?php include("occur_form_values.php"); ?>      
                                       
                                              <input name="id" type="hidden" value="<?php echo $row['occur_id']; ?>">
                                            
                                              <div style="margin: 0 auto; text-align: center;">
                                              <input type="submit" name="submit" class="btn btn-sm btn-success" value="Update Record">  <br>          
                                              </div>

                                        </form>

                                    </div>
                                </div>
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


    


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>





