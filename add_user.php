<?php

include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            
?>            

<!-- =======================================  PAGE SPECIFIC FILES / CSS ========================================================== -->

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







<!-- =============================================================================================================================== -->
<!-- Start right Content here -->
<!-- =============================================================================================================================== -->

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

<!-- ========================================   PAGE TITLE  ======================================================================== -->

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                                    <br><br>
                                    <h2 class="mb-sm-0 font-size-16 fw-bold">ADD NEW USER</h2>

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



<!-- ========================================   FORM: OPENING TAG AND FILE DESTINATION  ======================================================================== -->

                    <form action="user_values.php" class="form-floating mb-3" role="form" method="post" enctype=
                                                "multipart/form-data">



<!-- ========================================  BOOTSTRAP ROW:   ==================================================================== -->

                       
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">USER DETAIL</h4>
                                        <p class="card-title-desc"></p>
                    
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput"
                                                        placeholder="Last Name" name="user_last_name">
                                                    <label for="floatingInput">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput"
                                                        placeholder="First Name" name="user_first_name">
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                           <div class="col-lg-6">
                                               <div class="form-floating mb-3">
                                                   <input type="text" class="form-control" id="userTitle"
                                                       placeholder="Title" name="user_title">
                                                   <label for="userTitle">Title</label>
                                               </div>
                                           </div>
                                           <div class="col-lg-6">
                                               <div class="form-floating mb-3">
                                                   <input type="email" 
                                                          class="form-control" 
                                                          id="userEmail"
                                                          placeholder="Email" 
                                                          name="user_email"
                                                          pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                                   <label for="userEmail">Email</label>
                                                   <div class="invalid-feedback">
                                                       Please enter a valid email address (example@domain.com)
                                                   </div>
                                               </div>
                                           </div>
                                        </div>
                                        <div class="row">
                                           <div class="col-lg-6">
                                               <div class="form-floating mb-3">
                                                   <input type="tel" 
                                                          class="form-control" 
                                                          id="userWorkPhone"
                                                          placeholder="(123) 456-7890" 
                                                          name="user_phone"
                                                          pattern="\(\d{3}\)\s\d{3}-\d{4}">
                                                   <label for="userWorkPhone">Work Phone</label>
                                                   <div class="invalid-feedback">
                                                       Please enter a valid phone number
                                                   </div>
                                               </div>
                                           </div>
                                           <div class="col-lg-6">
                                               <div class="form-floating mb-3">
                                                   <input type="tel" 
                                                          class="form-control" 
                                                          id="userCellPhone"
                                                          placeholder="(123) 456-7890" 
                                                          name="user_cell"
                                                          pattern="\(\d{3}\)\s\d{3}-\d{4}">
                                                   <label for="userCellPhone">Cell Phone</label>
                                                   <div class="invalid-feedback">
                                                       Please enter a valid phone number
                                                   </div>
                                               </div>
                                           </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelect"
                                                        aria-label="Floating label select example" name=user_department>
                                                        <option disabled selected>User Department</option>
                                                        <?php
                                                        //DEPARTMENT SQL query to populate DEPARTMENT dropdowns based on user specific options
                                                        //Query filters for active status
                                                            $sql = "SELECT * 
                                                                    FROM occur_setup_department
                                                                    WHERE setup_department_status = 'Active'";
                                                            $result = mysqli_query($conn, $sql);
                                                                if (!$result) 
                                                                { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                        //Generate drop down menu options from query results
                                                            if (mysqli_num_rows($result) > 0) {
                                                                // output data of each row
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    echo '<option value="'.$row["setup_department_description"].'">'.$row["setup_department_description"].'</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                    <label for="floatingSelect">User Department</label>
                                                </div>
                                            </div>  <!-- end column -->

                                     
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelect"
                                                        aria-label="Floating label select example" name=user_status>
                                                        <option disabled selected>User Status</option>
                                                        <option disabled selected>Select Status</option> 
                                                        <option value="Active">Active</option>
                                                        <option value="Inactive">Inactive</option>
                                                        <!-- <option value="Archive">Archive</option> -->
                                                    </select>
                                                    <label for="floatingSelect">User Status</label>
                                                </div>
                                            </div>  <!-- end column -->
                                        </div><!-- end row -->
                                            <br>
                                       

                                        <div class="row">   
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelect"
                                                        aria-label="Floating label select example" name=user_role>
                                                        <option disabled selected>User Role</option>
                                                        <?php
                                                        //DEPARTMENT SQL query to populate DEPARTMENT dropdowns based on user specific options
                                                        //Query filters for active status
                                                            $sql = "SELECT * 
                                                                    FROM user_setup_roles
                                                                    WHERE role_status = 'Active'";
                                                            $result_role = mysqli_query($conn, $sql);
                                                                if (!$result_role) 
                                                                { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                        //Generate drop down menu options from query results
                                                            if (mysqli_num_rows($result_role) > 0) {
                                                                // output data of each row
                                                                while ($row_role = mysqli_fetch_assoc($result_role)) {
                                                                    echo '<option value="'.$row_role["user_role"].'">'.$row_role["user_role"].'</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                    <label for="floatingSelect">User Role</label>
                                                </div>
                                            </div>  <!-- end column -->
                                       

                                        </div> <!-- row -->



                                    </div> <!-- card body -->
                                </div> <!-- card -->
                            </div>
                        </div>


<!-- ========================================  BOOTSTRAP ROW: ACCESS DETAIL  ==================================================================== -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">ACCESS DETAIL</h4>
                                        <p class="card-title-desc"></p>



                                        <div class="mb-4">
                                        <h5 class="font-size-14 mb-2">SuperUser Access</h5>
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="super_user" value="Yes"> Yes
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="super_user" value="No" checked> No
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card body -->
                                 </div> <!-- end card -->
                            </div> <!-- end column -->
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="mb-4">
                                        <h5 class="font-size-14 mb-2">Access Levels</h5>
                                            <?php
                                            // DEPARTMENT SQL query to populate DEPARTMENT options based on user-specific options
                                            // Query filters for active status
                                            $sql = "SELECT * 
                                                    FROM user_setup_access
                                                    WHERE access_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            if (!$result) {
                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                            }

                                            // Generate checklist from query results
                                            if (mysqli_num_rows($result) > 0) {
                                                // Output data of each row
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<div class="form-check mb-2">'; // Add the 'mb-2' class here
                                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row["access_levels"] . '" name="user_access_A[]" value="' . $row["access_levels"] . '">';
                                                    echo '<label class="form-check-label" for="access_' . $row["access_levels"] . '">' . $row["access_levels"] . '</label>';
                                                    echo '</div>';
                                                }
                                            } else {
                                                echo '<p>No options available</p>';
                                            }
                                            ?>
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div> <!-- end column -->
                        </div> <!-- end row -->

                      <div class="row">


<!-- ========================================  BOOTSTRAP ROW: CONFIGURE NOTIFICATIONS  ==================================================================== -->


<div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">CONFIGURE NOTIFICATIONS / ROUTING</h4>
                <p class="card-title-desc"></p>

                <div class="row">
                    <!-- First Column -->
                    <div class="col-lg-3">
                        <div class="mb-4">
                            <h5 class="font-size-14 mb-2">BASED ON TYPE</h5>
                            <?php
                            // DEPARTMENT SQL query to populate DEPARTMENT options based on user-specific options
                            $sql = "SELECT * FROM occur_setup_type WHERE setup_type_status = 'Active'";
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            // Generate checklist from query results
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row["setup_type"] . '" name="notify_type[]" value="' . $row["setup_type"] . '">';
                                    echo '<label class="form-check-label" for="access_' . $row["setup_type"] . '">' . $row["setup_type"] . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                            <br>
                        </div> <!-- end mb-4 -->
                        <br>
                 
                        <div class="mb-4">
                            <h5 class="font-size-14 mb-2">BASED ON SEVERITY</h5>
                            <?php
                            // DEPARTMENT SQL query to populate DEPARTMENT options based on user-specific options
                            $sql = "SELECT * FROM occur_setup_severity WHERE severity_status = 'Active'";
                            $result_severity = mysqli_query($conn, $sql);
                            if (!$result_severity) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            // Generate checklist from query results
                            if (mysqli_num_rows($result_severity) > 0) {
                                while ($row_severity = mysqli_fetch_assoc($result_severity)) {
                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row_severity["setup_severity"] . '" name="notify_severity[]" value="' . $row_severity["setup_severity"] . '">';
                                    echo '<label class="form-check-label" for="access_' . $row_severity["setup_severity"] . '">' . $row_severity["setup_severity"] . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                            <br>
                        </div> <!-- end mb-4 -->
                        <div class="mb-4">
                            <h5 class="font-size-14 mb-2">BASED ON FLAG</h5>
                            <?php
                            // Repeat the same PHP code for the third column
                            $sql = "SELECT * FROM occur_setup_flags WHERE setup_flag_status = 'Active'";
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row["setup_flag"] . '" name="notify_flag[]" value="' . $row["setup_flag"] . '">';
                                    echo '<label class="form-check-label" for="access_' . $row["setup_flag"] . '">' . $row["setup_flag"] . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end mb-4 -->
                    </div> <!-- end col-lg-4 -->

                 <!-- Second Column -->               
                    <div class="col-lg-3">
                        <div class="mb-4">
                            <h5 class="font-size-14 mb-2">BASED ON CATEGORY</h5>
                            <?php
                            // Repeat the same PHP code for the second column
                            $sql = "SELECT * FROM occur_setup_category WHERE category_status = 'Active'";
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row["setup_category"] . '" name="notify_category[]" value="' . $row["setup_category"] . '">';
                                    echo '<label class="form-check-label" for="access_' . $row["setup_category"] . '">' . $row["setup_category"] . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end mb-4 -->
                    </div> <!-- end col-lg-3 -->

                <!-- Third Column -->
                    <div class="col-lg-3">
                        <div class="mb-4">
                            <h5 class="font-size-14 mb-2">BASED ON CONTRIBUTING FACTOR</h5>
                            <?php
                            // Repeat the same PHP code for the third column
                            $sql = "SELECT * FROM occur_setup_factors WHERE setup_factor_status = 'Active'";
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row["setup_factor"] . '" name="notify_factor[]" value="' . $row["setup_factor"] . '">';
                                    echo '<label class="form-check-label" for="access_' . $row["setup_factor"] . '">' . $row["setup_factor"] . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end mb-4 -->

                         <div class="mb-4">
                            <h5 class="font-size-14 mb-2">BASED ON LOCATION</h5>
                            <?php
                            // Repeat the same PHP code for the third column
                            $sql = "SELECT * FROM occur_setup_location WHERE setup_location_status = 'Active'";
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="access_' . $row["setup_location_name"] . '" name="notify_location[]" value="' . $row["setup_location_name"] . '">';
                                    echo '<label class="form-check-label" for="access_' . $row["setup_location_name"] . '">' . $row["setup_location_name"] . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end mb-4 -->
                    </div> <!-- end col-lg-3 -->


                </div> <!-- end row -->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div> <!-- end col-12 -->
</div> <!-- end row -->

                                   

                                   
 <!-- ========================================  HIDDEN VARIABLE: DEFAULT PASSWORD  =========================================== -->
                                       
                                          
                                            <input  type="hidden" name="user_password" value="new_password">
                                          


<!-- ========================================  FORM SUBMIT BUTTON AND CLOSE FORM  =========================================== -->

                                          <p>
                                            <div class="col-sm-9 " style="text-align:center;"><br><br>
                                                <input type="submit" name="submit" class="btn btn-warning" value="Create User">  <br>
                                                <br>       
                                            </div>
                                          </p>
                    </form>

<!-- ========================================  CLOSING CARD AND ROW TAGS  =========================================== -->


                                        </div> <!-- end card body -->
                                    </div> <!-- end card -->
                                </div> <!-- close coloumn -->    
                            </div> <!-- close row -->

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
    // Phone number formatting for both work and cell phones
    document.getElementById('userWorkPhone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    document.getElementById('userCellPhone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    // Email validation on blur/tab out
    document.getElementById('userEmail').addEventListener('blur', function(e) {
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


