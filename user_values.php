<?php
//session_start();
//require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            



<!-- ============  PAGE SPECIFIC FILES / CSS ====================================================================================== -->
   






<!-- ============   PHP / SQL QUERY TO ADD NEW USER  ========================================================================================= -->


<?php

        foreach ($_POST as $key=>$value)
        {
            ${$key} = $value;
        }

        $user_access_A = implode(',', $_POST['user_access_A']);
        $notify_type = implode(',', $_POST['notify_type']);
        $notify_category = implode(',', $_POST['notify_category']);
        //$notify_subcategory = implode(',', $_POST['notify_subcategory']);
        $notify_severity = implode(',', $_POST['notify_severity']);
        $notify_flag = implode(',', $_POST['notify_flag']);
        $notify_factor = implode(',', $_POST['notify_factor']);
        $notify_location = implode(',', $_POST['notify_location']);
        //$notify_tag = implode(',', $_POST['notify_tag']);
        //$notify_group = implode(',', $_POST['notify_group']);
        



        $id = $_POST['user_id'];

        //include("includes/occur_filter_user_data.php");

            $sql = 
            "INSERT INTO users
            (user_first_name, user_last_name, user_title, user_supervisor, user_department, user_username, user_role, management_user, user_handle, user_email, user_phone, user_cell, user_password, user_status, super_user, user_access_A, notify_type, notify_category, notify_subcategory, notify_severity, notify_flag, notify_factor, notify_location, notify_tag, notify_group)  
            VALUES
            ('$user_first_name', '$user_last_name', '$user_title', '$user_supervisor', '$user_department', '$user_username', '$user_role', '$management_user', '$user_handle', '$user_email', '$user_phone', '$user_cell', '$user_password', '$user_status', '$super_user', '$user_access_A', '$notify_type', '$notify_category', '$notify_subcategory', '$notify_severity', '$notify_flag', '$notify_factor', '$notify_location', '$notify_tag', '$notify_group')  
            ";  

            if ($conn->query($sql) === TRUE) 
                { $error = "New record created successfully"; } 
            else 
                { $error = "Error: " . $sql . "<br>" . $conn->error;  }

            $conn->close();
    
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

<!-- ========================================  PAGE SPECIFIC SCRIPTS   ======================================================== -->

    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>

    <!--Quill js-->
    <script src="assets/libs/quill/quill.min.js"></script>

    <!--Flatpickr js-->
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

    <!-- Plugins js -->
    <script src="assets/libs/dropzone/min/dropzone.min.js"></script>


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ================================================ -->
    </body>
</html>



_