<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
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
                        <br><br>
                        <h2 class="mb-sm-0 font-size-16 fw-bold">REVIEW / UPDATE RECORD</h2>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


<!-- ========================================  PHP / PROCESS FORM ======================================================================== -->


 <!-- Processing Results Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card card gainsboro-card">
                        <div class="card-body">
                          
            <?php
                // Process POST data
                    foreach ($_POST as $key => $value) {
                        ${$key} = $value;
                    }

                    $id = $_POST['id'];

                // Filter data prior to insertion
                    include("includes/occur_filter_data.php");

                //Convert multiple checkbox selections array into a string
                    $rm_contributing_factors = implode(',', $_POST['rm_contributing_factors']);
                    $occur_flag = implode(',', $_POST['occur_flag']);

                // QUERY TO ADD USERS TO OCCUR_NOTIFICATION COLUMN / VARIABLE
                // First, get the current occur_notify value
                    $current_notify_query = "SELECT occur_notify FROM occur WHERE occur_id = '$id'";
                    $current_notify_result = mysqli_query($conn, $current_notify_query);
                    $current_notify_row = mysqli_fetch_assoc($current_notify_result);
                    $current_notify = $current_notify_row['occur_notify'];

                // Convert the current string to an array
                    $current_notify_array = $current_notify ? explode(', ', $current_notify) : [];

                // Initialize the base query for new notifications
                    $sql = "SELECT * FROM users WHERE (";

                // Create an array to hold conditions
                    $conditions = [];
                    // Add conditions based on non-empty variables
                    if (!empty($occur_type)) {
                        $conditions[] = "notify_type LIKE '%" . mysqli_real_escape_string($conn, $occur_type) . "%'";
                    }
                    if (!empty($reporter_category)) {
                        $conditions[] = "notify_category LIKE '%" . mysqli_real_escape_string($conn, $reporter_category) . "%'";
                    }
                    if (!empty($reporter_severity)) {
                        $conditions[] = "notify_severity LIKE '%" . mysqli_real_escape_string($conn, $reporter_severity) . "%'";
                    }
                    if (!empty($occur_flag)) {
                        $conditions[] = "notify_flag LIKE '%" . mysqli_real_escape_string($conn, $occur_flag) . "%'";
                    }
                    if (!empty($occur_factor)) {
                        $conditions[] = "notify_factor LIKE '%" . mysqli_real_escape_string($conn, $occur_factor) . "%'";
                    }
                    if (!empty($occur_location)) {
                        $conditions[] = "notify_location LIKE '%" . mysqli_real_escape_string($conn, $occur_location) . "%'";
                    }
                    // Combine conditions with OR if any are present
                    if (count($conditions) > 0) {
                        $sql .= implode(' OR ', $conditions);
                    } else {
                        // If no conditions are met, you may want to handle this case
                        $sql .= " 0"; // This will result in no matches
                    }
                    $sql .= ")"; // Close the WHERE clause


                    $result = mysqli_query($conn, $sql);
                    
                    // Initialize an array to store new names
                        $new_notify = []; 

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $user_full_name = $row['user_first_name'] . " " . $row['user_last_name']; 
                                if (!in_array($user_full_name, $new_notify) && !in_array($user_full_name, $current_notify_array)) {
                                    $new_notify[] = $user_full_name; 
                                }
                            }
                        // Combine current and new notifications
                            $occur_notify = array_merge($current_notify_array, $new_notify);
                            $occur_notify = array_unique($occur_notify); // Remove any duplicates
                            $occur_notify = implode(', ', $occur_notify); 
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }

                // List of date fields
                    $dateFields = [
                        'occur_date',
                        'manager_review_date',
                        'rm_followup_date',
                        'rm_review_date',
                        'occur_close_date',
                        'patient_dob',
                        'admit_date',
                        'target_date',
                        'complete_date'
                    ];

                // Process form data
                    foreach ($dateFields as $field) {
                        if (isset($_POST[$field]) && !empty($_POST[$field]) && $_POST[$field] !== '0000-00-00' && $_POST[$field] !== '1970-01-01') {
                            ${$field} = "'" . date('Y-m-d', strtotime($_POST[$field])) . "'";
                        } else {
                            ${$field} = "NULL";
                        }
                    }

                // Correct for error on null values in restraint/seclusion minutes for int data type
                    $seclusion_minutes = is_numeric($seclusion_minutes) ? (int)$seclusion_minutes : 0;
                    $restraint_minutes = is_numeric($restraint_minutes) ? (int)$restraint_minutes : 0;

                // Prepare the SQL statement
                    $sql = "UPDATE occur SET 
                        manager_followup_job='$manager_followup_job',
                        manager_followup_name='$manager_followup_name',
                        manager_review_date=$manager_review_date,
                        manager_communication='$manager_communication',
                        manager_followup_notes='$manager_followup_notes',
                        manager_followup_plan='$manager_followup_plan',
                        rm_followup_name='$rm_followup_name',
                        rm_severity='$rm_severity',
                        rm_followup_notes='$rm_followup_notes',
                        rm_followup_plan='$rm_followup_plan',
                        rm_followup_date=$rm_followup_date,
                        rm_review_date=$rm_review_date,
                        occur_close_date=$occur_close_date,
                        occur_status='$occur_status',
                        occur_tags='$occur_tags',
                        rm_additional_notes='$rm_additional_notes',
                        rm_contributing_factors='$rm_contributing_factors',
                        occur_flag='$occur_flag',
                        rm_verify='$rm_verify',
                        target_date=$target_date,
                        complete_date=$complete_date,
                        manager_status='$manager_status',
                        manager_action='$manager_action',
                        occur_notify='$occur_notify'
                    WHERE occur_id = '$id'";

                   if (mysqli_query($conn, $sql)) {
                                ?>
                                <div class="no-print">
                                    <h6>Record for occurrence report #<?php echo $id; ?> successfully updated</h6>
                                    <strong>Routing:&nbsp;&nbsp;</strong><?php echo $occur_notify; ?><br>
                                    <br>
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <a href="manage_occur.php" class="btn btn-warning btn-sm waves-effect waves-light">Back</a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            <?php

                // Check if the ID is passed via POST
                        if (isset($_POST['id']) && is_numeric($_POST['id']) && ($_POST['id'] > 0)) {
                            $id = $_POST['id']; // Retrieve ID from POST

                            $query = "SELECT * FROM occur WHERE occur_id = '$id'";
                            $result = mysqli_query($conn, $query);

                        if (!$result) {
                            print '<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
                            print '<p> The query being run was: ' . $query . '</p>';
                        } else {
                            $row = mysqli_fetch_array($result);

                        if ($row) {
                            // Assign row values to variables dynamically
                            foreach ($row as $column => $value) {
                                $$column = $value;
                            }
                        } else {
                            echo '<p>No record found for the given ID.</p>';
                        }
                        }
                    } else {
                        echo '<p>Invalid ID provided.</p>';
                    }
            ?>

<!-- ========================================  BUTTONS    ======================================================================== -->
 
     <!-- Action Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-end mb-3 no-print">
                        <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="openPDF();">PDF</button>
                        <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="window.print();">Print</button>
                        <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="downloadPDF();">Download</button>
                        <button type="button" class="btn btn-warning btn-sm no-print" onclick="emailPDF();">Email</button>
                    </div>
                </div>
            </div>


<!-- ========================================   REPORT CONTENT / PDF================================================================== -->


           
            <!-- Report Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body" id="report-content">
                            <div class="row">
                                <div class="col-12">
                                    <div style="padding: 15px;">
                                        <h2>OCCURRENCE REPORT SUMMARY</h2>
                                        <?php include("pdf_report_detail.php"); ?>
                                        
                                        <!-- Updated page break div with padding to prevent content overlap -->
                                        <div style="break-after: page; padding-bottom: 50px;"></div>
                                        
                                        <!-- Add top margin for management section -->
                                        <div style="padding-top: 72px;">  <!-- 1 inch = 72px -->
                                            <?php include("pdf_mgr.php"); ?>
                                            <?php //include("pdf_rm.php"); ?>
                                        </div>
                                    </div>                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


<!-- ========================================  CLOSING TAGS / FOOTER / RIGHT SIDEBAR  =========================================== -->
 
        </div> <!-- end container -->
    </div> <!-- end page content -->

<?php include("includes/occur_footer.php"); ?>
</div> <!-- end main content -->

<?php include("includes/right_sidebar.php"); ?>
<?php include("includes/footer_scripts.php"); ?>


<!-- ========================================  PAGE SPECIFIC ASSETS  =============================================================== -->

    <!-- Include required libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


<!-- ========================================  PAGE SPECIFIC SCRIPTS  =============================================================== -->


    <!-- Functionality for Buttons -->

        <script>
            // Shared options object
                const createPdfOptions = (filename) => ({
                margin: 0.5,
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 0.9,
                    dpi: 192,
                    letterRendering: true,
                    useCORS: true,
                    scrollY: 0,
                    scrollX: 0
                },
                jsPDF: { 
                    unit: 'in', 
                    format: 'letter', 
                    orientation: 'portrait',
                    compress: true,
                    precision: 16
                },
                pagebreak: {
                    mode: ['css', 'legacy'],
                    after: ['.page-break'],
                    avoid: ['tr', 'td'],
                    beforeAvoid: true  // Add this line to prevent content from appearing before breaks
                }
            });

            // Function to open PDF in new tab
                function openPDF() {
                    const element = document.getElementById('report-content');
                    
                    // Clone the element
                    const clone = element.cloneNode(true);
                    
                    // Create a temporary container
                    const container = document.createElement('div');
                    container.appendChild(clone);
                    document.body.appendChild(container);
                    
                    // Apply temporary scaling
                    container.style.transform = 'scale(0.9)';
                    container.style.transformOrigin = 'top left';
                    
                    const options = createPdfOptions('report.pdf');
                    
                    html2pdf()
                        .from(container)
                        .set(options)
                        .toPdf()
                        .output('dataurlnewwindow')
                        .then(() => {
                            // Cleanup
                            document.body.removeChild(container);
                        });
                }

            // Function to generate PDF and open email client
                async function emailPDF() {
                    try {
                        const element = document.getElementById('report-content');
                        
                        // Clone the element
                        const clone = element.cloneNode(true);
                        
                        // Create a temporary container
                        const container = document.createElement('div');
                        container.appendChild(clone);
                        document.body.appendChild(container);
                        
                        // Apply temporary scaling
                        container.style.transform = 'scale(0.9)';
                        container.style.transformOrigin = 'top left';
                        
                        const options = createPdfOptions('report-summary.pdf');
                        
                        // Generate PDF using the same method as openPDF
                        const pdf = await html2pdf()
                            .from(container)
                            .set(options)
                            .toPdf()
                            .output('datauristring');
                            
                        // Cleanup temporary container
                        document.body.removeChild(container);
                        
                        // Create mailto link
                        const subject = encodeURIComponent('Occurrence Report Summary');
                        const body = encodeURIComponent('Please find attached the occurrence report summary.');
                        const mailtoLink = `mailto:?subject=${subject}&body=${body}`;
                        
                        // First, open the email client
                        window.location.href = mailtoLink;
                        
                        // Then create a temporary link to download the PDF
                        const link = document.createElement('a');
                        link.href = pdf;
                        link.download = 'report-summary.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        alert('Your report has been dowloaded.  Please attach the downloaded PDF to your email.');
                        
                    } catch (error) {
                        console.error('Error generating PDF for email:', error);
                        alert('There was an error preparing the email. Please try again.');
                    }
                }

            // Function to download PDF
                function downloadPDF() {
                    const element = document.getElementById('report-content');
                    
                    // Clone the element
                    const clone = element.cloneNode(true);
                    
                    // Create a temporary container
                    const container = document.createElement('div');
                    container.appendChild(clone);
                    document.body.appendChild(container);
                    
                    // Apply temporary scaling
                    container.style.transform = 'scale(0.9)';
                    container.style.transformOrigin = 'top left';
                    
                    const options = createPdfOptions('report-summary.pdf');
                    
                    html2pdf()
                        .from(container)
                        .set(options)
                        .toPdf()
                        .output('datauristring')
                        .then(pdf => {
                            // Create a temporary link to download the PDF
                            const link = document.createElement('a');
                            link.href = pdf;
                            link.download = 'report-summary.pdf';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            
                            // Cleanup temporary container
                            document.body.removeChild(container);
                        })
                        .catch(error => {
                            console.error('Error generating PDF:', error);
                            alert('There was an error generating the PDF. Please try again.');
                        });
                }
        </script>

<!-- ========================================  FINAL CLOSING TAGS  ============================================================ -->
</body>
</html>