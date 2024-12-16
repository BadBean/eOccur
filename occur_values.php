<?php
session_start();
require_once('auth.php');
include("includes/occur_header.php");
include("includes/occur_navbar.php");
include("includes/occur_sidebar.php");
include("includes/occur_config.php");
?>

<!-- ============================================  PAGE SPECIFIC FILES  ================================================================= -->

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>

<!-- ============================================  PAGE FORMATTING  ================================================================= -->

    <!-- Start right Content here -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- =============================================  PAGE TITLE   ======================================================================== -->

                    <!-- Page Title / Top Div with 'no-print' class-->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0 no-print">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">eOccur</h4>
                                </div>
                            </div>
                        </div> <!-- end row -->

                   <!-- Rest of the content -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" id="buttons">
                                    <div class="card-body">
                                        <!-- Wrap the PHP messages in a 'no-print' div -->
                                        <div class="no-print">


                                <?php 
                                foreach ($_POST as $key => $value) {
                                    ${$key} = $value;
                                }

                                // FILTER DATA PRIOR TO INSERTION
                                include("includes/occur_filter_data.php");


                                // QUERY TO ADD USERS TO OCCUR_NOTIFICATION COLUMN / VARIABLE
                                // Initialize the base query
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

                                // Initialize an array to store unique names
                                $occur_notify = []; 

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $user_full_name = $row['user_first_name'] . " " . $row['user_last_name']; 
                                        if (!in_array($user_full_name, $occur_notify)) {
                                            $occur_notify[] = $user_full_name; 
                                        }
                                    }
                                    $occur_notify = implode(', ', $occur_notify); 
                                } else {
                                    echo "Error: " . mysqli_error($conn);
                                }


                                // Function to format the date or return NULL if empty
                                function formatDate($date, $conn) {
                                    if (empty($date)) {
                                        return "NULL"; // Return actual NULL
                                    } else {
                                        return "'" . mysqli_real_escape_string($conn, date('Y-m-d', strtotime($date))) . "'";
                                    }
                                }

                                // Apply the function to all datepicker fields
                                $occur_date = formatDate($occur_date, $conn);
                                $manager_review_date = formatDate($manager_review_date, $conn);
                                $rm_followup_date = formatDate($rm_followup_date, $conn);
                                $rm_review_date = formatDate($rm_review_date, $conn);
                                $occur_close_date = formatDate($occur_close_date, $conn);
                                $patient_dob = formatDate($patient_dob, $conn);
                                $admit_date = formatDate($admit_date, $conn);
                                $target_date = formatDate($target_date, $conn);
                                $complete_date = formatDate($complete_date, $conn);

                                // Handle NULL for integer values
                                $seclusion_minutes = !empty($seclusion_minutes) ? $seclusion_minutes : 0;
                                $restraint_minutes = !empty($restraint_minutes) ? $restraint_minutes : 0;

                                $reporting_timestamp = date("Y-m-d H:i:s");
                                $occur_status = "Submitted";

                                // Insert query
                                $sql = "INSERT INTO occur (
                                    reporter_last_name, reporter_first_name, reporter_phone, reporter_email, reporter_dept, reporter_job, 
                                    occur_type, reporter_category, reporter_severity, patient_last_name, patient_first_name, patient_gender, patient_age, 
                                    patient_MRN, patient_dob, admit_date, patient_unit, patient_program, patient_loc, occur_date, occur_time, occur_location, 
                                    occur_description, occur_intervention, occur_employee_injury, occur_patient_injury, employee_injury_description, 
                                    patient_injury_description, clinical_category, occur_area, manager_followup_job, reporting_timestamp, 
                                    manager_followup_name, manager_review_date, manager_followup_notes, manager_followup_plan, rm_followup_name, 
                                    rm_severity, rm_followup_notes, rm_followup_plan, rm_followup_date, rm_review_date, occur_close_date, 
                                    occur_status, patient_restraint_notes, patient_seclusion_notes, occur_patient_seclusion, occur_patient_restraint, 
                                    rs_notification_notes, rs_additional_notes, restraint_minutes, seclusion_minutes, rs_notification, rs_documentation,
                                    md_attending, md_temp, occur_staff, occur_PRN, occur_code, code_notes, occur_tags, rm_additional_notes, rm_contributing_factors, 
                                    occur_flag, rm_verify, occur_subcategory, occur_notification_notes, occur_additional_notes, occur_notify, 
                                    patient_transfer, patient_transfer_notes
                                ) VALUES (
                                    '$reporter_last_name', '$reporter_first_name', '$reporter_phone', '$reporter_email', '$reporter_dept','$reporter_job', 
                                    '$occur_type','$reporter_category', '$reporter_severity','$patient_last_name', '$patient_first_name', '$patient_gender', '$patient_age', 
                                    '$patient_MRN', $patient_dob, $admit_date, '$patient_unit', '$patient_program', '$patient_loc', 
                                    $occur_date, '$occur_time', '$occur_location', '$occur_description', '$occur_intervention', 
                                    '$occur_employee_injury', '$occur_patient_injury', '$employee_injury_description', '$patient_injury_description', 
                                    '$clinical_category', '$occur_area', '$manager_followup_job', '$reporting_timestamp', '$manager_followup_name', 
                                    $manager_review_date, '$manager_followup_notes', '$manager_followup_plan', '$rm_followup_name', '$rm_severity', 
                                    '$rm_followup_notes', '$rm_followup_plan', $rm_followup_date, $rm_review_date, $occur_close_date, 
                                    '$occur_status', '$patient_restraint_notes', '$patient_seclusion_notes', '$occur_patient_seclusion', 
                                    '$occur_patient_restraint', '$rs_notification_notes', '$rs_additional_notes', $restraint_minutes, 
                                    $seclusion_minutes, '$rs_notification', '$rs_documentation', '$md_attending', '$md_temp', '$occur_staff', '$occur_PRN', 
                                    '$occur_code', '$code_notes', '$occur_tags', '$rm_additional_notes', '$rm_contributing_factors', '$occur_flag', '$rm_verify', 
                                    '$occur_subcategory', '$occur_notification_notes', '$occur_additional_notes', '$occur_notify', '$patient_transfer', 
                                    '$patient_transfer_notes'
                                )";


                                if ($conn->query($sql) === TRUE) {
                                // Get the ID of the newly inserted record
                                $occur_id = $conn->insert_id;
                                $id = $occur_id;

                                    echo "<strong>Record " . " " . $occur_id . " " . "created successfully</strong>" . " "  . "<br><br>";
                                    echo "Report will be available to the following managers: <br>" . $occur_notify;
                                } else {
                                    echo "Error: " . $conn->error;
                                }
                                ?>
                                        </div> <!-- Close 'no-print' div -->
                                    </div> <!-- Close card-body -->
                                </div> <!-- Close card -->
                            </div> <!-- Close column -->
                        </div> <!-- Close row -->


                           <!-- Buttons -->
                            <div class="d-flex justify-content-end mb-3 no-print">
                                <a href="edit_occur.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm me-2 no-print">Edit</a>
                                <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="openPDF();">PDF</button>
                                <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="window.print();">Print</button>
                                <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="downloadPDF();">Download</button>
                                <button type="button" class="btn btn-warning btn-sm no-print" onclick="emailPDF();">Email</button>
                            </div>


          


<!-- =============================================  REPORT CONTENT  ======================================================================== -->


<!-- Report Content ----------------------------------->


                                <div class="card">
                                    <div class="card-body" id="report-content">
                                        <div class="row">
                                            <div class="col-12">
                                                <div style="padding: 15px;">
                                                    <h2>OCCURRENCE REPORT SUMMARY</h2>
                                                    <?php include("pdf_report_detail.php"); ?>
                                                    <?php //include("pdf_mgr.php"); ?>
                                                    <?php //include("pdf_rm.php"); ?>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->

                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->



            <!-- Include footer and scripts -->
            <?php include("includes/occur_footer.php"); ?>
        </div> <!-- End Page-content -->
    </div> <!-- End container-fluid -->
</div> <!-- End main-content -->

<!-- Include right sidebar and footer scripts -->
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
                        width: 816, // 8.5 inches * 96 DPI
                        height: 1056, // 11 inches * 96 DPI
                        windowWidth: 816,
                        windowHeight: 1056,
                        scrollX: 0,
                        scrollY: 0,
                        x: 0,
                        y: 0
                    },
                    jsPDF: { 
                        unit: 'in', 
                        format: 'letter', 
                        orientation: 'portrait',
                        compress: true,
                        precision: 16
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