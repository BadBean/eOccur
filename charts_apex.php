<?php
session_start();
//require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->






<!-- ================================================  SQL QUERY           ======================================================== -->





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
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Samply</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

<!-- PHP / SQL QUERY FOR CATEGORY CHART    ========================================================================================= -->

<?php 
//Query to count # of reports by category

    $sql =  "SELECT reporter_category, COUNT(*) as count
             FROM occur
             GROUP BY reporter_category
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $data = [];

// Fetch data and store in an array
            if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
    }
}    

// Encode the data as JSON
//echo json_encode($data);
?>


<script>
    var chartData = <?php echo json_encode($data); ?>;
</script>


        <!-- ================================================  END PHP   ==================================== -->
        
                                    </div>
                                    <!-- end card-body -->

                                </div>
                                <!-- end card -->

                            </div> <!-- end col -->

                        </div> <!-- end row -->



chart.render();
</script>
         


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


<!-- ========================================  PAGE SPECIFIC FILES   ========================================================== -->


 

    

<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->


  



<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>










