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

<!-- SQL QUERY =================================================================================================================== -->

<?php 
    $sql =  "SELECT *
             FROM occur
             WHERE rm_severity = 'Severe'
             ORDER BY occur_id desc
             ";
    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysql_error() . "</p>"); }
    $numrows = mysqli_num_rows($result);
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

<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" id="buttons">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">High Severity Occurrences</h4>

                                        <div>
                                            <h5 class="font-size-14"></h5>
                                            <p class="card-title-desc"> Occurrence reports classified by Risk Manager as high Severity or Sentinel events
                                            </p>

                                            

                                            <div class="d-flex flex-wrap gap-4">
                                                <a href="add_occur.php">
                                                    <button type="button" class="btn btn-success waves-effect waves-light">Add New</button>
                                                </a>
                                            <!--
                                                <a href="edit_occur.php">
                                                    <button type="button" class="btn btn-success waves-effect waves-light">Edit</button>
                                                </a>
                                                <a href="delete_occur.php">
                                                    <button type="button" class="btn btn-success waves-effect waves-light">Delete</button>
                                                </a>
                                            -->
                                                <a href="search_occur.php">
                                                    <button type="button" class="btn btn-success waves-effect waves-light">Advanced Search</button>
                                                </a>
                                            </div>
                                        </div>
                                     
                                    </div><!-- End card-body -->
                                </div><!-- End card -->
                            </div><!-- End col -->
                        </div><!-- End row -->


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->

        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Data Table:</h4>
                                        <p class="card-title-desc"></p>

<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                                
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>PT NAME</th>
                                                        <th>MRN</th>

                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th>Severity</th>
                                                        
                                                        <!-- <th>Description</th> -->
                                                        
                                                        <th>Unit</th>
                                                        <th>Program</th>
                                                        <th>Status</th>
                                                        
                                                        <th>Print</th>
                                                        <th>Review</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>        

                                            <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td>{$row['patient_last_name']}</td>";
                                                            echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>" . date("m/d/y", strtotime($row['occur_date'])) . "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            echo "<td>{$row['patient_unit']}</td>";
                                                            echo "<td>{$row['patient_program']}</td>";
                                                            echo "<td>{$row['occur_status']}</td>";
                                                            
                                                            echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            echo "<td><a href=\"occur_detail.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
                                                            //echo "<td>{$row['reporter_severity']}</td>";

                                                            //fas fa-pencil-alt
                                                            
                                                        echo "</tr>";
                                                    }
                                                ?>
                                                
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



<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->

<?php include ("includes/footer_scripts_datatables.php"); ?>






<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>