<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
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

          
<!-- ========================================  DATATABLE DATE FILTERS  ======================================================================== -->
     

            <!-- DataTables -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <h4 class="card-title">Select Date Range</h4> -->
                         
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

                        </div> <!-- close card body -->
                    </div> <!-- close card -->
                </div> <!-- close col -->
            </div> <!-- close row -->

<!-- ========================================  DATATABLE   ======================================================================== -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">  

                <!-- Flex Container for Title and Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Search all Reports</h4>
                    <div id="buttonsContainer"></div> <!-- Container for DataTables Buttons -->
                </div>

                <br>

                <!-- Optional: Remove the <br> as spacing is handled by Bootstrap classes -->
                <!-- <br> -->

                <table id="myTable" class="table table-borderless dt-responsive table-condensed table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th> <!-- $formatted_date -->
                            <!-- <th>JS Date</th> --> 
                            <th>Time</th>
                            <th>ID</th>

                            <th>Type</th>
                            <th>Category</th>
                            <th>Subcategory</th>

                            <th>Severity</th>

                            <th>Pt Name</th>
                            <!-- <th>Pt FName</th> -->
                            <th>MRN</th>
                            <th>Age</th>
                            <th>M/F</th>
                            <th>Admit Date</th>
                            <th>Unit</th>

                            <th>Location</th>
                            <th>Area</th>
                            <th>Program</th>
                            <th>LOC</th>

                            <th>MD Attending</th>

                            <th>Staff Injury</th>
                            <th>Staff Injury Note</th>
                            <th>Pt Injury</th>
                            <th>Pt. Injury Note</th>
                            <th>Injury Other</th>
                            <th>Injury Other Note</th>

                            <th>Code</th>
                            <th>Code Notes</th>

                            <th>PRN Meds</th>

                            <th>Staff Present</th>

                            <th>Patient Transfer</th>
                            <th>Patient Transfer Notes</th>


                            <th>Restraint</th>
                            <th>Restraint Minutes</th>
                            <th>Restraint Notes</th>
                            <th>Seclusion</th>
                            <th>Seclusion Minutes</th>
                            <th>Seclusion Notes</th>

                            <th>R/S Documentation</th>  
                            <th>R/S Notification</th>  
                            <th>R/S Notification Notes</th>  
                            <th>R/S Additional Notes</th>  

                            <th>Description</th>
                            <th>Intervention</th>  

                            <th>Reporter</th>  
                            <th>Reporter Phone</th>  
                            <th>Reporter Email</th>  
                            <th>Reporter Department</th>  
                            <th>Reporter Job</th>  
                            <th>Reporter Severity</th>  

                            <th>Notification Notes</th>  
                            <th>Additional Notes</th>  

                            <th>Mgr Assigned</th>  
                            <th>Job</th>  

                            <th>Mgr Review Date</th>

                            <th>Mgr Action Plan</th>  
                            <th>Mgr Follow Up Notes</th>  
                            <th>Mgr Action</th>  

                            <th>Target Date</th>  
                            <th>Complete Date</th>  
                            <th>Manager Status</th>  

                            <th>RM Name</th>  
                            <th>RM Follow Up Notes</th>  
                            <th>RM Action Plan</th>  
                            <th>RM Follow Up Date</th>  
                            <th>RM Additional Notes</th>  

                            <th>RM Status</th> 
                            <th>Close Date</th>

                            <th>RM Verify</th>
                            <th>RM Contributing Factors</th>
                            <th>Flags</th>
                            <th>Tags</th>

                            <th>Reporting Timestamp</th>
                        </tr>
                    </thead>        
                    <tbody>
                        <?php
                        $sql =  "SELECT * FROM occur ORDER BY occur_id asc";
                        $result = mysqli_query($conn, $sql);
                        if (!$result) { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                        $numrows = mysqli_num_rows($result);


                        for ($i = 0; $i < $numrows; $i++) {
                            $row = mysqli_fetch_array($result);


                            //$formatted_date = date("m-d-Y", strtotime($row['occur_date']));
                            //$js_date = date("Y-m-d", strtotime($row['occur_date']));

                            echo "<tr>";
                           
                            echo "<td style='white-space:nowrap' data-date='" . date("Y-m-d", strtotime($row['occur_date'])) . "'>";
                                if (!empty($row['occur_date'])) {
                                    echo date("m/d/y", strtotime($row['occur_date']));
                                } else {
                                    echo ""; // Output blank if the value is null or empty
                                }
                            echo "</td>";
                            
                            //echo "<td style='white-space:nowrap'>{$js_date}</td>";
                            
                            echo "<td style='white-space:nowrap'>{$row['occur_time']}</td>";
                            echo "<td><a href='pdf_report.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                           
                            echo "<td style='white-space:nowrap'>{$row['occur_type']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['reporter_category']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_subcategory']}</td>";
                            
                            echo "<td>{$row['rm_severity']}</td>";

                            //echo "<td>{$row['patient_last_name']}</td>";
                            //echo "<td>{$row['patient_first_name']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['patient_first_name']} {$row['patient_last_name']}</td>";


                            echo "<td>{$row['patient_MRN']}</td>";
                            echo "<td>{$row['patient_age']}</td>";
                            echo "<td>{$row['patient_gender']}</td>";
                            echo "<td>{$row['admit_date']}</td>";
                            echo "<td>{$row['patient_unit']}</td>";

                            echo "<td>{$row['occur_location']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_area']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['patient_program']}</td>";
                            echo "<td>{$row['patient_loc']}</td>";
                            
                            echo "<td>{$row['md_attending']}</td>";

                            echo "<td>{$row['occur_employee_injury']}</td>";
                            echo "<td>{$row['employee_injury_description']}</td>";
                            echo "<td>{$row['occur_patient_injury']}</td>";
                            echo "<td>{$row['patient_injury_description']}</td>";
                            echo "<td>{$row['injury_other']}</td>";
                            echo "<td>{$row['injury_other_notes']}</td>";
                           
                            echo "<td>{$row['occur_code']}</td>";
                            echo "<td>{$row['code_notes']}</td>";

                            echo "<td>{$row['occur_PRN']}</td>";

                            echo "<td>{$row['occur_staff']}</td>";

                            echo "<td>{$row['patient_transfer']}</td>";
                            echo "<td>{$row['patient_transfer_notes']}</td>";

                            echo "<td>{$row['occur_patient_restraint']}</td>";
                            echo "<td>{$row['restraint_minutes']}</td>";
                            echo "<td>{$row['patient_restraint_notes']}</td>";
                            echo "<td>{$row['occur_patient_seclusion']}</td>";
                            echo "<td>{$row['seclusion_minutes']}</td>";
                            echo "<td>{$row['patient_seclusion_notes']}</td>";

                            echo "<td>{$row['rs_documentation']}</td>";
                            echo "<td>{$row['rs_notification']}</td>";
                            echo "<td>{$row['rs_notification_notes']}</td>";
                            echo "<td>{$row['rs_additional_notes']}</td>";

                            echo "<td style='white-space:nowrap'>{$row['occur_description']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_intervention']}</td>";

                            echo "<td style='white-space:nowrap'>{$row['reporter_first_name']} {$row['reporter_last_name']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['reporter_phone']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['reporter_email']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['reporter_dept']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['reporter_job']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['reporter_severity']}</td>";

                            echo "<td style='white-space:nowrap'>{$row['occur_notification_notes']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_additional_notes']}</td>";


                            // MANAGEMENT FOLLOW UP AND ANALYSIS
                            echo "<td style='white-space:nowrap'>{$row['manager_followup_name']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['manager_followup_job']}</td>";
                            
                            echo "<td style='white-space:nowrap'>{$row['manager_review_date']}</td>";
                            
                            echo "<td style='white-space:nowrap'>{$row['manager_followup_plan']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['manager_followup_notes']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['manager_action']}</td>";
                            
                            echo "<td style='white-space:nowrap'>{$row['target_date']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['complete_date']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['manager_status']}</td>";
                            
                            echo "<td style='white-space:nowrap'>{$row['rm_followup_name']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['rm_followup_notes']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['rm_followup_plan']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['rm_followup_date']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['rm_additional_notes']}</td>";

                            echo "<td style='white-space:nowrap'>{$row['occur_status']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_close_date']}</td>";

                            echo "<td style='white-space:nowrap'>{$row['rm_verify']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['rm_contributing_factors']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_flag']}</td>";
                            echo "<td style='white-space:nowrap'>{$row['occur_tags']}</td>";
                            
                            echo "<td style='white-space:nowrap'>{$row['reporting_timestamp']}</td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include ("includes/occur_footer.php"); ?>
<?php include ("includes/right_sidebar.php"); ?>
<?php include ("includes/footer_scripts.php"); ?>



 <!-- DataTables: Required DataTables JS -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables: Buttons Extensions -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>

<!-- DataTables: KeyTable and Select Extensions -->
<script src="assets/libs/datatables.net-keyTable/js/dataTables.keyTable.min.js"></script>
<script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>

<!-- DataTables: Responsive Extensions -->
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!-- DataTables: Buttons HTML5, Print, and Column Visibility -->
<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- DataTables: Initialization Scripts -->
<script src="assets/js/pages/datatables.init.js"></script>
<script src="assets/js/app.js"></script>


<!-- Custom filtering function -->
<!-- Custom Date Filter and Buttons Script -->
<script>
    $(document).ready(function() {
        // Initialize DataTable with Buttons and custom configurations
        var table = $('#myTable').DataTable({
            "order": [[ 0, "desc" ]], // Order by the first column (DATE) in descending order
            "dom": 'frtip', // Exclude 'B' since we will place buttons manually
            "buttons": [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-info me-2', // Added 'me-2' for right margin
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'btn btn-success me-2',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success me-2',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                /*{
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-warning me-2',
                    orientation: 'landscape', // Set PDF to landscape orientation
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function (doc) {
                        // Optional: Customize PDF further if needed
                        doc.pageMargins = [40, 60, 40, 60];
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 12;
                        doc.styles.tableHeader.bold = true;
                        doc.content.unshift({
                            text: 'E-OCCUR Reports',
                            style: 'header',
                            alignment: 'center',
                            margin: [0, 0, 0, 20]
                        });
                        doc.styles.header = {
                            fontSize: 18,
                            bold: true
                        };
                        doc.footer = function(currentPage, pageCount) {
                            return {
                                text: currentPage.toString() + ' of ' + pageCount,
                                alignment: 'right',
                                margin: [0, 0, 20, 0]
                            };
                        };
                    }
                },
                /*
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-info me-2',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                */
               
            ],
            "responsive": true // Enable responsive design
        });
        
        // Move the buttons to the custom container
        table.buttons().container().appendTo('#buttonsContainer');
        
        // Custom filtering function which will search data in the DATE column
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                // Get the start and end dates from the input fields
                var min = $('#startDate').val();
                var max = $('#endDate').val();
                
                // Use the data-date attribute for reliable date parsing
                var dateStr = $(table.row(dataIndex).node()).find('td').eq(0).data('date');
                
                if (!dateStr) {
                    return false; // Exclude rows without a date
                }
                
                var rowDate = new Date(dateStr);
                
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

        // Clear Filters Button Functionality (Optional)
        $('#clearFilters').on('click', function() {
            $('#startDate').val('');
            $('#endDate').val('');
            table.draw();
        });

        // Optional: Validate End Date is not earlier than Start Date
        $('#endDate').on('change', function() {
            var startDate = $('#startDate').val();
            var endDate = $(this).val();
            if (startDate && endDate && endDate < startDate) {
                alert('End Date cannot be earlier than Start Date.');
                $(this).val(''); // Reset the end date
            }
        });
    });
</script>



<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>




