<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");


?>            


<!-- ========================================  PAGE SPECIFIC SCRIPTS   ======================================================== -->

 <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
        <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />

 <!-- quill css -->
        <link href="assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/@chenfengyuan/datepicker/datepicker.min.css">

        <!-- flatpickr css -->
        <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

        <!-- File Upload Plugins css -->
        <link href="assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />



 
<!-- ================================================  PHP / SQL QUERY  ============================================= -->

    <?php
        if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0))
          {
            $query = "SELECT *
                      FROM occur
                      WHERE occur_id = {$_GET['id']}
                     ";
            $result = mysqli_query($conn, $query);

            //$occur_id = $_GET['id'];

            if (!$result) 
            {
              print'<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
              print'<p> The query being run was:  ' . $query . '</p>';
            }
            else
            {
              $row = mysqli_fetch_array($result);
            }
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
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">E-OCCUR </h4>

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


<!-- ========================================  BOOTSTRAP ROW:  ========================================================= -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"> Edit / Update</h4>
                                        <p class="card-title-desc">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>

<!-- ================================================  FORM ACTION / SUBMIT   =========================================== -->
<!-- includes form_value file to populate existing values prior to being updated / overwritten -->

                                        <form action="update_occur.php" class="form-horizontal" method="post">

                                              <?php include("occur_form_values.php"); ?>      
                                       
                                              <input name="id" type="hidden" value="<?php echo $row['occur_id']; ?>">
                                            
                                              <div style="margin: 0 auto; text-align: center;">
                                              <input type="submit" name="submit" class="btn btn-sm btn-warning" value="Update Record">  <br>          
                                              </div>

                                        </form>

                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        <br>
                        <br>


                   

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


<!-- ========================================  PAGE SPECIFIC ASSETS   ======================================================== -->

    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>

    <!--Quill js-->
    <script src="assets/libs/quill/quill.min.js"></script>

    <!--Flatpickr js-->
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

    <!-- Plugins js -->
    <script src="assets/libs/dropzone/min/dropzone.min.js"></script>

<!-- ========================================  PAGE SPECIFIC SCRIPTS   ======================================================== -->


    <script>
    // Phone number formatting
    document.getElementById('reporterPhone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    // Email validation on blur/tab out
    document.getElementById('reporterEmail').addEventListener('blur', function(e) {
        if (this.value && !this.value.includes('@')) {
            this.classList.add('is-invalid');
            this.parentElement.classList.add('was-validated');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Form validation
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ================================================ -->
    </body>
</html>



