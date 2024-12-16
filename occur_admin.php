<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->

        




   
<!-- PAGE FORMATTING  ============================================================================================================ -->

<!-- START RIGHT CONTENT HERE -->

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- PAGE TITLE ================================================================================================================== -->

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR ADMIN SETTINGS</h4>

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
                        <br>

<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->
                        
                         <div class="row">
                            
                             <div class="col-lg-5 offset-1">
                                <div class="card border border-info mb-lg-0">
                                    <div class="card-header bg-transparent border-info">
                                        <h5 class="mb-0 text-info card-title text-truncate">USER MANAGEMENT</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                         <p><a href="users.php"> View / Edit Users </a></p>
                                  <p><a href="add_user.php"> Add New User </a></p>
                                    </div>
                                </div>
                            </div><!-- End col -->
                            
                            <div class="col-lg-5">
                                <div class="card border border-info mb-lg-0">
                                    <div class="card-header bg-transparent border-info">
                                        <h5 class="mb-0 text-info card-title text-truncate">DATABASE / BACKUP</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                        <p><a href="occur_admin.php"> Export </a></p>
                                        <p><a href="occur_admin">Create Backup </a></p>
                                    </div>
                                </div>
                            </div><!-- End col -->
                          </div> <!-- End Row -->
                          <br>
                       
                        <div class="row">
                            <div class="col-lg-5 offset-1">
                                <div class="card border border-info mb-lg-0">
                                    <div class="card-header bg-transparent border-info">
                                        <h5 class="mb-0 text-info card-title text-truncate">FACILITY SETUP</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                        <p><a href="setup_md.php"> Physicians </a></p>
                                        <p><a href="setup_department.php">Departments </a></p>
                                        <p><a href="setup_loc.php">Level of Care </a></p>
                                        <p><a href="setup_program.php">Programs </a></p>
                                        <p><a href="setup_location.php">Locations </a></p>
                                        <p><a href="setup_unit.php">Units</a></p>
                                        <p><a href="setup_area.php">Areas</a></p>
                                    </div>
                                </div>
                            </div><!-- End col -->
                          <br>

                            <div class="col-lg-5">
                                <div class="card border border-info mb-lg-0">
                                    <div class="card-header bg-transparent border-info">
                                        <h5 class="mb-0 text-info card-title text-truncate">RISK MANAGEMENT SETUP</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                        <p><a href="setup_category.php">Type / Categories </a></p>
                                        <p><a href="setup_code.php">Codes</a></p>
                                        <p><a href="setup_severity.php">Severity Levels </a></p>
                                        <p><a href="setup_status.php">QA/Risk Status</a></p>
                                        <p><a href="setup_mgr_status.php">Manager Status</a></p>
                                        <p><a href="setup_factor.php">Custom Reporting:  Contributing Factors </a></p>
                                        <p><a href="setup_flag.php">Custom Reporting: Flags</a></p>
                                      
                                    </div>
                                </div>
                            </div> <!-- End col -->
                          </div>  <!-- end row -->
                          <br>

                         

                          
                          </div> <!-- end row -->
                            <br>








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







