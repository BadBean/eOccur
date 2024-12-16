<?php
session_start();
require_once('auth.php');
include("includes/occur_header.php");
include("includes/occur_navbar.php"); 
include("includes/occur_sidebar.php");
include("includes/occur_config.php");
?>

<!-- ============================================  PAGE SPECIFIC FILES  ================================================================= -->

    <!-- Add CSS to hide elements when printing -->
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

<!-- =============================================  PAGE TITLE / BUTTONS  ======================================================================== -->

<!-- Set ID and query for all values ------------------->
                        <?php
                            if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
                                $query = "SELECT * FROM occur WHERE occur_id = {$_GET['id']}";
                                $result = mysqli_query($conn, $query);

                                $id = $_GET['id'];

                                if (!$result) {
                                    print '<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
                                    print '<p> The query being run was:  ' . $query . '</p>';
                                } else {
                                    $row = mysqli_fetch_array($result);
                                    // Assign row values to variables dynamically
                                    foreach ($row as $column => $value) {
                                        $$column = $value;
                                    }
                                }
                            } else {
                                echo '<p>No record found for the given ID.</p>';
                            }
                        ?>


                    <!-- Page Title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR</h4>
                                </div>
                            </div>
                        </div>

                    <!-- Buttons -->
                            <div class="d-flex justify-content-end mb-3 no-print">
                                <a href="edit_occur.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm me-2 no-print">Edit Report</a>
                                <a href="rm_review.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm me-2 no-print">Mgr Review</a>
                                <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="openPDF();">PDF</button>
                                <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="window.print();">Print</button>
                                <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="downloadPDF();">Download</button>
                                <button type="button" class="btn btn-warning btn-sm no-print" onclick="emailPDF();">Email</button>
                            </div>

<!-- =============================================   REPORT CONTENT  ======================================================================== -->




<!-- Report Content ----------------------------------->

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
                                                        <?php include("pdf_rm.php"); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->


                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                    

   
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