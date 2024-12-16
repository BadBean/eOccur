<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            



<!-- ============  PAGE SPECIFIC FILES / CSS =============================================================================================== -->





<!-- ============   PHP / SQL QUERY   ========================================================================================= -->


<?php
    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0))
      {
        $query = "SELECT *
                  FROM users
                  WHERE user_id = {$_GET['id']}
                 ";
        $result = mysqli_query($conn, $query);

        $user_id = $_GET['id'];

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

//  Explode checkbox arrays to populate existing values
//$user_access_A = explode(',', $row['user_access_A']);

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


<!-- ========================================  BOOTSTRAP ROW:  CARD/TITLE   ========================================================= -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Edit User Detail / Access </h4>
                                        <p class="card-title-desc">
                                        </p>
                               
                                    </div><!-- End card-body -->
                                </div><!-- End card -->
                            </div><!-- End col -->
                        </div><!-- End row -->

<!-- ================================================  FORM ACTION / SUBMIT   =========================================== -->
<!-- includes form_value file to populate existing values prior to being updated / overwritten -->

                                        <form action="update_users.php" class="form-horizontal" method="post">

                                              <?php include("user_form_values.php"); ?>      
                                       
                                              <input name="id" type="hidden" value="<?php echo $row['user_id']; ?>">
                                            
                                              <div style="margin: 0 auto; text-align: center;">
                                              <input type="submit" name="submit" class="btn btn-sm btn-success" value="Update Record">  <br> 
                                              <br>
                                              <br>         
                                              </div>

                                        </form>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->



<!-- ========================================  BOOTSTRAP ROW:  =================================================================== -->


                      


















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







