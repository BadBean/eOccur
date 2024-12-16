<?php
session_start();
require_once('auth.php');
include("includes/occur_header.php");
include("includes/occur_navbar.php"); 
include("includes/occur_sidebar.php");
include("includes/occur_config.php");
?>
<!-- Add CSS to hide elements when printing -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->

<!-- ================================================  SQL QUERY  ============================================= -->

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
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Buttons -->
            <div class="d-flex justify-content-end mb-3 no-print">
                <a href="edit_occur.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm me-2">Edit</a>
                <button type="button" class="btn btn-warning btn-sm me-2" onclick="window.print();">Print</button>
                <button type="button" class="btn btn-warning btn-sm me-2" onclick="downloadPDF();">Download</button>
                <button type="button" class="btn btn-warning btn-sm" onclick="emailPDF();">Email</button>
            </div>

            <!-- Report Content -->
            <div class="card">
                <!-- Wrap the report content with an ID for JavaScript targeting -->
                <div class="card-body" id="report-content">
                	<div style="background-color: gainsboro; padding: 15px;">
    					<h2>OCCURRENCE REPORT SUMMARY</h2>
                    <?php include("pdf_report.php"); ?>
                </div>
            </div>

            <!-- ========================================  CLOSING TAGS / FOOTER / RIGHT SIDEBAR  =========================================== -->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <?php include("includes/occur_footer.php"); ?>
</div>
<!-- end main content-->
<!-- END layout-wrapper -->
<?php include("includes/right_sidebar.php"); ?>
<?php include("includes/footer_scripts.php"); ?>

<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->

<!-- Required libraries and scripts -->
<!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Datatables JS -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables Buttons -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>

<!-- Additional DataTables Plugins -->
<script src="assets/libs/datatables.net-keyTable/js/dataTables.keyTable.min.js"></script>
<script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!-- Datatables Initialization -->
<script src="assets/js/pages/datatables.init.js"></script>

<!-- App JS -->
<script src="assets/js/app.js"></script>

<!-- jsPDF and html2canvas Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Function for PDF download -->
<script>
    function downloadPDF() {
        const { jsPDF } = window.jspdf;

        const content = document.getElementById('report-content');

        html2canvas(content, {
            scale: 2,
            useCORS: true
            // If needed, you can ignore elements here
            // ignoreElements: function(element) {
            //     return element.classList.contains('no-print');
            // }
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'pt', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            // Handle multi-page content
            let heightLeft = pdfHeight;
            let position = 0;

            pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
            heightLeft -= pdf.internal.pageSize.getHeight();

            while (heightLeft >= 0) {
                position = heightLeft - pdfHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                heightLeft -= pdf.internal.pageSize.getHeight();
            }

            pdf.save('report-summary.pdf');
        });
    }

    // Placeholder function for emailPDF (you can implement this later)
    function emailPDF() {
        alert('Email functionality is not implemented yet.');
    }
</script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>

<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
</body>
</html>
