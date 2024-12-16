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



<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" id="buttons">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Blank Template / Card</h4>

                                        <div>
                                            <h5 class="font-size-14"></h5>
                                            <p class="card-title-desc">
                                            </p>

                                            <div class="d-flex flex-wrap gap-4">
                                                
                                            </div>
                                        </div>
                                     
                                    </div><!-- End card-body -->
                                </div><!-- End card -->
                            </div><!-- End col -->
                        </div><!-- End row -->

<!-- ========================================  BOOTSTRAP ROW: TABLE   =================================================================== -->


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Active Users</h4>
                                        <p class="card-title-desc">
                                        </p>
                                            <div class="panel-title"><span class="pull-right small">
                                            <a href="users_active.php">Active Users Only</a> &nbsp&nbsp&nbsp&nbsp 
                                            <a href = "users.php">All Users</a></span>
                                            </div> <!-- End panel-title -->

                                    </div> <!-- End card-body -->  
                                    &nbsp
        
                                        <div class="panel-body">

<!-- ========================================  PHP / TABLE  =================================================================== -->

                                            <?php
                                              echo '<table class="table table-striped table-condensed">';
                                                
                                                echo "<thead>";
                                                echo "<tr>";

                                                  echo "<th>ID</th>";
                                                  echo "<th>LAST NAME</th>";
                                                  
                                                  echo "<th>EMAIL</th>";
                                                  echo "<th>SUPER?</th>";
                                                  echo "<th>ACCESS</th>";
                                                  echo "<th>STATUS</th>";
                                                  echo "<th>EDIT</th>";
                                                  echo "<th>DELETE</th>";
                                                  echo "<th></th>";
                                                  echo "<th></th>";
                                                  
                                                echo "</tr>";
                                                echo "</thead>";
                                                  
                                                  
                                              $numrows = mysqli_num_rows($result);
                                              echo"&nbsp&nbsp $numrows Users: ";

                                              for ($i = 0; $i < $numrows; $i++)
                                              {
                                                $row = mysqli_fetch_array($result);

                                                 echo "<tr>";
                                                   //echo "<td><a href=\"edit_user.php?id={$row[0]}\">Edit</a></td>"; 
                                                   echo "<td>{$row[0]}</td>";
                                                   echo "<td>{$row[1]}</td>";
                                                   echo "<td>{$row[2]}</td>";
                                                   echo "<td>{$row[3]}</td>";
                                                   echo "<td>{$row[4]}</td>";
                                                   echo "<td>{$row[5]}</td>";
                                                   echo "<td><a href=\"edit_user.php?id={$row[0]}\"><span class=\"fa fa-pencil-alt\"></span></a><td>";
                                                   echo "<td><a href=\"delete_user.php?id={$row[0]}\"><span class=\"fa fa-trash\"></span></a><td>";
                                                  
                                                 echo "</tr>";
                                              }
                                                 echo "</table>";
                                            ?>

                            </div> <!-- end coloumn -->    
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







