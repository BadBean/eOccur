<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- PAGE SPECIFIC SCRIPTS   ===================================================================================================== -->


<?php

// to get the variable passed from bar chart click
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    // Use $status in your SQL query to fetch the relevant details
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


<!-- PHP / SQL QUERY TO PULL CATEGORY DETAIL    ========================================================================================= -->

<?php 

// Generate a list of the last 12 months
$months = [];
for ($i = 11; $i >= 0; $i--) {
    $months[] = date('Y-m', strtotime("-$i months"));
}

// Query to count # of reports submitted but not reviewed by RM
$sql_count = "
    SELECT 
        DATE_FORMAT(occur_date, '%Y-%m') AS month_year, 
        COUNT(*) AS item_count
    FROM 
        occur
    WHERE 
        reporter_category = '$category' AND
        occur_date >= DATE_FORMAT(CURDATE() - INTERVAL 12 MONTH, '%Y-%m-01')
    GROUP BY 
        DATE_FORMAT(occur_date, '%Y-%m')
    ORDER BY 
        DATE_FORMAT(occur_date, '%Y-%m')
";

$result_count = mysqli_query($conn, $sql_count);

if (!$result_count) {
    die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
}

// Initialize data array with zeros for all months
$data = array_fill_keys($months, 0);

// Fill in the data array with actual counts from the query
while ($row_count = mysqli_fetch_assoc($result_count)) {
    $data[$row_count['month_year']] = $row_count['item_count'];
}

// Prepare data for ApexCharts
$months_js = json_encode(array_keys($data));
$counts_js = json_encode(array_values($data));

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $category; ?></h4>
                <p class="card-title-desc"></p>

                <div id="chart"></div>

                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                <script>
                    var options = {
                        chart: {
                            type: 'bar',
                            width: '100%',  
                            height: '400px' 
                        },
                        series: [{
                            name: 'Reports',
                            data: <?php echo $counts_js; ?>
                        }],
                        xaxis: {
                            categories: <?php echo $months_js; ?>,
                            title: {
                                text: 'Month'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Count'
                            }
                        },
                        title: {
                            text: 'Reports Submitted by Month',
                            align: 'center'
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                </script>
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end column -->
</div> <!-- end row -->


<!-- ========================================  BOOTSTRAP ROW:  DATATABLE   ========================================================= -->


<!-- SQL QUERY FOR TABLE =================================================================================================================== -->

<?php 
   $sql =  "SELECT *
             FROM occur
             WHERE reporter_category = '$category'
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
                                        <h4 class="card-title"><?php echo $category; ?></h4>
                                        <p class="card-title-desc">
                                        </p>
        
                                          <table id="myTable" class="table table-bordered dt-responsive table-hover table-compact w-100">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
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
                                                            echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$formatted_date}</td>";
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
                                                            echo "<td>{$row['reporter_severity']}</td>";
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
        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->





