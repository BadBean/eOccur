<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->


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

                        <br>

<!-- ========================================  BOOTSTRAP ROW: PAGE HEADLINE  ========================================================== -->

                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card" style="background-color: #DCDCDC;">
                                    <div class="card-body text-center">
                                      <h4 class="mb-sm-0 font-size-16 fw-bold text-black">ACTION PLAN / FOLLOW UP:&nbsp;&nbsp; <?php echo strtoupper($reporter_category); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->


<!-- ========================================  TOP ROW - 4 WIDGETS / CARDS  =================================================================== -->







<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->




<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<?php 
//Query for reports assigned to user and not closed
  $sql = "SELECT *
          FROM occur
          WHERE occur_status <> 'Closed'
          AND manager_status <> 'Complete'
          
    ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysql_error() . "</p>"); }
    $numrows = mysqli_num_rows($result);
?>


        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Open Reports Requiring Followup</h4>
                                        <p class="card-title-desc"></p>
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
                                        
                                        <hr>
                                        <br>

<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTable_open_reports" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
                                                
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <!-- <th>Description</th> -->
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <th>Description</th>
                                                        <!-- <th>Intervention</th>-->
                                                         <th>Mgr Status</th>

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Target Date</th>
                                                        
                                                        <!-- <th>Print</th> -->
                                                        <!-- <th>Review</th> -->
                                                        <!-- <th>Edit</th> -->
                                                        <!-- <th>Delete</th> -->
                                                    </tr>
                                                </thead>        

                                            <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            //echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            // echo "<td>{$row['patient_last_name']}</td>";
                                                            // echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            echo "<td>{$row['occur_description']}</td>";
                                                            //echo "<td>{$row['occur_intervention']}</td>";
                                                           echo "<td style='white-space: nowrap;'>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                           echo "<td>";
                                                                if (!empty($row['target_date'])) {
                                                                    echo date("m/d/y", strtotime($row['target_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            
                                                            //echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            //echo "<td><a href=\"mgr_review.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            //echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            //echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
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

                        <br>
                        <br>


<!-- ================================================ ACTION PLANS IN PROGRESS  =================== -->

<?php 
//Query to count # of reports assigned to user and not closed
  $sql =   "SELECT *
            FROM occur
            WHERE complete_date IS NULL
            AND manager_followup_plan IS NOT NULL
            AND TRIM(manager_followup_plan) <> ''
              
        ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysql_error() . "</p>"); }
    $numrows = mysqli_num_rows($result);
?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <div card bg-primary border-primary>
                                        <h4 class="card-title">Action Plans in Progress</h4>
                                        <p class="card-title-desc"></p>
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
                                        
                                        <hr>
                                        <br>
                                        </div> <!-- close card color -->
<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTable_inprogress" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">  
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <th>Action Plan</th>
                                                        <th>Notes</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <!-- <th>Description</th> -->
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <!--<th>Description</th> -->
                                                        <!-- <th>Intervention</th>-->
                                                        <!-- <th>Mgr Status</th> -->

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Target Date</th>
                                                        
                                                        <!-- <th>Print</th> -->
                                                        <!-- <th>Review</th> -->
                                                        <!-- <th>Edit</th> -->
                                                        <!-- <th>Delete</th> -->
                                                    </tr>
                                                </thead>        

                                           <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            //echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            // echo "<td>{$row['patient_last_name']}</td>";
                                                            // echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['manager_followup_plan']}</td>";
                                                            echo "<td>{$row['manager_followup_notes']}</td>";                                                            
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            //echo "<td>{$row['occur_intervention']}</td>";
                                                            //echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['target_date'])) {
                                                                    echo date("m/d/y", strtotime($row['target_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            
                                                            //echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            //echo "<td><a href=\"mgr_review.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            //echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            //echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
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




<!-- ================================================ ACTION PLANS COMPLETED =================== -->

<?php 
//Query to count # of reports assigned to user and not closed
  $sql = "SELECT *
          FROM occur
          WHERE complete_date IS NOT NULL
        ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysql_error() . "</p>"); }
    $numrows = mysqli_num_rows($result);
?>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <div card bg-primary border-primary>
                                        <h4 class="card-title">Action Plans Completed</h4>
                                        <p class="card-title-desc"></p>
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
                                        
                                        <hr>
                                        <br>
                                        </div> <!-- close card color -->
<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTable_complete" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">   
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <th>Action Plan</th>
                                                        <th>Implementation Notes</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <th>Description</th>
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <!--<th>Description</th> -->
                                                        <!-- <th>Intervention</th>-->
                                                        <!-- <th>Mgr Status</th> -->

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Complete Date</th>
                                                        
                                                        <!-- <th>Print</th> -->
                                                        <!-- <th>Review</th> -->
                                                        <!-- <th>Edit</th> -->
                                                        <!-- <th>Delete</th> -->
                                                    </tr>
                                                </thead>        

                                           <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            //echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            // echo "<td>{$row['patient_last_name']}</td>";
                                                            // echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            echo "<td>{$row['manager_followup_plan']}</td>";
                                                            echo "<td>{$row['manager_followup_notes']}</td>";                                                            
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            echo "<td>{$row['occur_description']}</td>";
                                                            //echo "<td>{$row['occur_intervention']}</td>";
                                                            //echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['complete_date'])) {
                                                                    echo date("m/d/y", strtotime($row['target_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            
                                                            //echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            //echo "<td><a href=\"mgr_review.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            //echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            //echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
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



<!-- ========================================  BOOTSTRAP ROW: TWO COLUMNS  ========================================================== -->


<!-- PHP / SQL QUERY AND HTML FOR AMA'S -  COLUMN 1 ============================ -->

            <?php 
            //Query to count # of reports submitted but not reviewed by RM
                $sql =  "SELECT COUNT(*) AS item_count
                         FROM occur
                         WHERE rm_severity IN ('Severe', 'Sentinel')
                         
                        ";

                $result = mysqli_query($conn, $sql);
                         if (!$result) 
                         { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

                $row = mysqli_fetch_assoc($result);
                $numrows_submitted = $row['item_count'];
            ?>
                        <div class="row">
                            <div class="col-md-6 col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                           <a href="drill_high_severity.php?month=<?= $selected_month;?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-alert-circle text-danger"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">AMA </p>
                                             <a href="drill_severity.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->

<!-- PHP / SQL QUERY AND HTML FOR TRANSFERS -  COLUMN 2 ============================ -->

            <?php 
            //Query to count # of reports submitted but not reviewed by RM
              $sql = "SELECT COUNT(*) AS item_count
                    FROM occur
                    WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
                      AND occur_date <= LAST_DAY(CURDATE())";

            $result = mysqli_query($conn, $sql);
            if (!$result) {
                die("<p>Error in query: " . mysqli_error($conn) . "</p>");
            }

            $row = mysqli_fetch_assoc($result);
            $numrows_submitted = $row['item_count'];
            ?>

                            <div class="col-md-6 col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="mtd_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">TRANSFERS</p>
                                             <a href="mtd_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- close row -->








<!-- ========================================  BOOTSTRAP ROW: CARDS  ========================================================== -->


<!--   COLUMN 1:         --------------------------------->
            <div class="row mt-4">
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Code </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_code_table = "SELECT occur_code, COUNT(*) as code_table_count FROM occur GROUP BY occur_code";

                                                // Execute the query 
                                                $result_code_table = mysqli_query($conn, $sql_code_table);

                                                // Check for SQL errors
                                                if (!$result_code_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_code_table);
                                                }

                                                // Initialize total count variable
                                                $total_code_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_code_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_code_table = mysqli_fetch_assoc($result_code_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_code_table['occur_code']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_code.php?occur_code=" . urlencode($row_code_table['occur_code']) . "'>" . htmlspecialchars($row_code_table['code_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_code_count += $row_code_table['code_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_code_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            

<!--   COLUMN 2:         --------------------------------->

                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Code </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_code_table = "SELECT occur_code, COUNT(*) as code_table_count FROM occur GROUP BY occur_code";

                                                // Execute the query 
                                                $result_code_table = mysqli_query($conn, $sql_code_table);

                                                // Check for SQL errors
                                                if (!$result_code_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_code_table);
                                                }

                                                // Initialize total count variable
                                                $total_code_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_code_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_code_table = mysqli_fetch_assoc($result_code_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_code_table['occur_code']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_code.php?occur_code=" . urlencode($row_code_table['occur_code']) . "'>" . htmlspecialchars($row_code_table['code_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_code_count += $row_code_table['code_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_code_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            



<!--   COLUMN 1:         --------------------------------->

                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Code </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_code_table = "SELECT occur_code, COUNT(*) as code_table_count FROM occur GROUP BY occur_code";

                                                // Execute the query 
                                                $result_code_table = mysqli_query($conn, $sql_code_table);

                                                // Check for SQL errors
                                                if (!$result_code_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_code_table);
                                                }

                                                // Initialize total count variable
                                                $total_code_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_code_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_code_table = mysqli_fetch_assoc($result_code_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_code_table['occur_code']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_code.php?occur_code=" . urlencode($row_code_table['occur_code']) . "'>" . htmlspecialchars($row_code_table['code_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_code_count += $row_code_table['code_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_code_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->



<!--   COLUMN 1:         --------------------------------->

                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports by Code </h4>
                                        <p class="card-title-desc">
                                        </p>

                                            <?php
                                                // Query to count the number of reports by severity
                                                $sql_code_table = "SELECT occur_code, COUNT(*) as code_table_count FROM occur GROUP BY occur_code";

                                                // Execute the query 
                                                $result_code_table = mysqli_query($conn, $sql_code_table);

                                                // Check for SQL errors
                                                if (!$result_code_table) {
                                                    die("Query failed: " . mysqli_error($conn) . " - SQL: " . $sql_code_table);
                                                }

                                                // Initialize total count variable
                                                $total_code_count = 0;


                                                // Check if there are results
                                                if (mysqli_num_rows($result_code_table) > 0) {
                                                    // Add Bootstrap table classes
                                                    echo "<table class='table table-striped table-bordered table-sm'>";
                                                    echo "<thead class='bg-danger text-light'><tr><th>Location</th><th>Total Reports</th></tr></thead>";
                                                    echo "<tbody>";

                                                    // Fetch each row and display in table format
                                                    while ($row_code_table = mysqli_fetch_assoc($result_code_table)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row_code_table['occur_code']) . "</td>";
                                                        // Make the count clickable and pass occur_location
                                                        echo "<td><a href='drill_code.php?occur_code=" . urlencode($row_code_table['occur_code']) . "'>" . htmlspecialchars($row_code_table['code_table_count']) . "</a></td>";
                                                        echo "</tr>";

                                                        // Add to total count
                                                        $total_code_count += $row_code_table['code_table_count'];
                                                    }

                                         // Add total row
                                                    echo "<tr class='table-secondary'><td><strong>Total</strong></td><td><strong>" . htmlspecialchars($total_code_count) . "</strong></td></tr>";

                                        echo "</tbody></table>";
                                    } else {
                                        // No results found
                                        echo "No data available.";
                                    }

                                    // Free result set and close connection
                                    //mysqli_free_result($result_location_table);
                                    //mysqli_close($conn);
                                ?>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->






<!-- PHP / SQL QUERY FOR "REPORTS BY PROGRAM"    ========================================================================================= -->

<br>
<?php 
//Query to count # of reports submitted but not reviewed by RM
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE reporter_category = '$reporter_category'
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

<!-- ========================================  BOOTSTRAP ROW: PROGRAM DETAIL   =================================================================== -->

                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="drill_category.php?reporter_category=<?= $reporter_category;?>">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-folder-star text-primary"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">CATEGORY TOTAL</p>
                                            <a href="drill_category.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>2.65%</span> Pending RM review
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->

<!-- PHP / SQL QUERY FOR "HIGH SEVERITY"    ========================================================================================= -->


<?php 
//Query to count # of reports submitted but not reviewed by RM
    $sql =  "SELECT COUNT(*) AS item_count
             FROM occur
             WHERE rm_severity IN ('Severe', 'Sentinel');
            ";

    $result = mysqli_query($conn, $sql);
             if (!$result) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row = mysqli_fetch_assoc($result);
    $numrows_submitted = $row['item_count'];
?>

                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="severity_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-alert-circle text-danger"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">HIGH SEVERITY</p>
                                             <a href="severity_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->

<!-- PHP / SQL QUERY FOR "MTD TOTALS"    ========================================================================================= -->


<?php 
//Query to count # of reports submitted but not reviewed by RM
  $sql = "SELECT COUNT(*) AS item_count
        FROM occur
        WHERE occur_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
          AND occur_date <= LAST_DAY(CURDATE())";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("<p>Error in query: " . mysqli_error($conn) . "</p>");
}

$row = mysqli_fetch_assoc($result);
$numrows_submitted = $row['item_count'];
?>

                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="float-end">
                                            <a href="mtd_detail.php">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-light font-size-24">
                                                    <i class="mdi mdi-animation"></i>
                                                </span>
                                            </div>
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold font-size-13">INJURIES</p>
                                             <a href="mtd_detail.php">
                                                <h4 class="mb-1 mt-1">
                                                    <span class="counter-value" data-target="<?php echo $numrows_submitted; ?>"></span>
                                                </h4>
                                            </a>
                                        </div>
                                        <p class="text-muted mt-3 mb-0"><span class="badge badge-soft-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>4.58%</span> since last week
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end col-->




<!-- ========================================  DATA DOWNLOAD SECTION:  =================================================================== -->




         
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

      
<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->


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



<!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable and store the instance in a variable
        var table = $('#myTable_open_reports').DataTable({
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



<!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable and store the instance in a variable
        var table = $('#myTable_inprogress').DataTable({
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



<!-- Datatables JS / Add Order to change default by ID field / Apply date filters -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable and store the instance in a variable
        var table = $('#myTable_complete').DataTable({
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





<!-- CONFIGURE CHART OF REPORTS BY HOUR (TIME OF OCCURRENCE, NOT SUBMISSION TIME) ========================================================== -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    var options_report_hourly = {
        chart: {
            type: 'bar',
            height: 400
        },
        series: [{
            name: 'Reports',
            data: chartData_report_hourly
        }],
        xaxis: {
            categories: hourLabels,
            title: {
                text: 'Hour of Day'
            },
            labels: {
                formatter: function(value) {
                    return value + ':00';
                }
            }
        },
        yaxis: {
            title: {
                text: 'Number of Reports'
            }
        },
        title: {
            text: 'Reports by Hour of the Day',
            align: 'center'
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return value + ' reports';
                }
            }
        },
        colors: ['#008FFB']  // Single color for all bars
    };

    var chart_report_hourly = new ApexCharts(document.querySelector("#chart_report_hourly"), options_report_hourly);
    chart_report_hourly.render();
});
</script>




<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




