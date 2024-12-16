<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->

  



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
                                    <h4 class="mb-sm-0 font-size-16 fw-bold">eOccur</h4>

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

<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" id="buttons">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Edit Users / Access</h4>

                                        <div>
                                            <h5 class="font-size-14"></h5>
                                            <p class="card-title-desc">
                                            </p>

<!-- ================================================  SQL QUERY  ============================================= -->

<?PHP
foreach ($_POST as $key=>$value)
{
    ${$key} = $value;
}

$id = $_POST['id'];


//Filter data prior to insertion for escape string / strip tags  *************** UNCOMMENT LATER ************************
//include("includes/user_filter_data.php");

//Convert multiple checkbox selections array into a string ******** ERROR HAPPENS IF FILTER IS LOADED AFTER ARRAY IS IMPLODED ******
$user_access_A = implode(',', $_POST['user_access_A']);
$notify_type = implode(',', $_POST['notify_type']);
$notify_category = implode(',', $_POST['notify_category']);
$notify_subcategory = implode(',', $_POST['notify_subcategory']);
$notify_severity = implode(',', $_POST['notify_severity']);
$notify_flag = implode(',', $_POST['notify_flag']);
$notify_factor = implode(',', $_POST['notify_factor']);
$notify_location = implode(',', $_POST['notify_location']);
$notify_tag = implode(',', $_POST['notify_tag']);
$notify_group = implode(',', $_POST['notify_group']);

$sql = 
    "UPDATE users  
     SET    user_last_name ='$user_last_name', 
            user_first_name ='$user_first_name',
            user_title ='$user_title',
            user_supervisor = '$user_supervisor',
            user_department = '$user_department',
            user_username = '$user_username',
            user_role = '$user_role',
            management_user = '$management_user',
            user_handle = '$user_handle',
            user_email = '$user_email',
            user_phone = '$user_phone',
            user_cell = '$user_cell',
            user_password = '$user_password',
            user_category = '$user_category',
            user_status = '$user_status',
            super_user = '$super_user',
            user_access_A = '$user_access_A',
            notify_type = '$notify_type',
            notify_severity = '$notify_severity',
            notify_category = '$notify_category',
            notify_subcategory = '$notify_subcategory',
            notify_severity = '$notify_severity',
            notify_flag = '$notify_flag',
            notify_factor = '$notify_factor',
            notify_location = '$notify_location',
            notify_tag = '$notify_tag',
            notify_group = '$notify_group'
            
     WHERE user_id = '$id'";  

    if ($conn->query($sql) === TRUE) 
        {   echo "Record for User #" . $id . " successfully updated: ";    } 
    else {  echo "Error: " . $sql . "<br>" . $conn->error;  }


    $conn->close();
?> 

                                        
                                        </div>
                                    </div><!-- End card-body -->
                                </div><!-- End card -->
                            </div><!-- End col -->
                        </div><!-- End row -->



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


<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->
<!--
            
            notify_type = '$notify_type',
            notify_category = '$notify_category',
            notify_subcategory = '$notify_subcategory',
            notify_severity = '$notify_severity',
            notify_flag = '$notify_flag',
            notify_factor = '$notify_factor',
            notify_location = '$notify_location',
            notify_tag = '$notify_tag',
            notify_group = '$notify_group'

-->





<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>