

<?php

//config file includes database connection statement

include ("includes/occur_config.php");

// Convert user input from form into variables and prevent SQL injection
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);

// Select record where email and password match
$sql = "SELECT * FROM users WHERE user_email = '$email' AND user_password = '$password'";
$result = mysqli_query($conn, $sql);

// Verify that record exists
if (mysqli_num_rows($result) > 0) {
    // Start session and store user data in session variables
    session_start();
    $_SESSION['logged_in'] = true;
    $_SESSION['email'] = $email; // Set the session variable

    // Redirect to the protected page
    header('Location: occur_landing.php');
    exit();
} 
else 
{
    //include header and navbar for CSS / formatting
    include ("includes/occur_header.php");
    include ("includes/occur_navbar.php");
    include ("includes/occur_sidebar.php");
    
    // login failed
}
?>


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
                                    <h2 class="mb-sm-0 font-size-16 fw-bold">e-Occur Login</h2>
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


<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                        
                                            <h4 class="card-title">ACCESS DENIED</h4>
                                            <p class="card-title-desc">Invalid username or password</p>     

                                            <div class="btn-list m-b-35">
                                                <a href="occur_login.php" class="btn btn-sm btn-success">Back to Login</a>
                                            </div> 
                                    
                                            <p>
                                                <?php 
                                                    echo"email" . ":" . "$email"; 
                                                    echo"<br>";
                                                    echo"password" . ":" . "$password";
                                                ?>


                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->        

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
