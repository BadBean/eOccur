
<?php
include ("includes/occur_config.php");
session_start();

include ("includes/occur_header.php");

?>            

<!-- ================================================ PHP for Login/Check Errors  ============================================= -->
<?php

//Check for errors / display
if (isset($errors) && !empty($errors))
{
    echo '<h1>Error!</h1>
    <p>The following error(s) have occurred:<br>';

    foreach ($errors as $msg)
    {
        echo " - $msg<br>\n";
    }
    echo '</p><p> Please try again.</p>';
}
?>


    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->
        <div class="account-pages">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="text-center mb-5">
                            <a href="occur_home.php" class="auth-logo">
                                <img src="assets/images/logo_eoccur_clear.png" alt="Logo Dark" height="60" class="auth-logo-dark">
                                <img src="assets/images/logo_eoccur_clear.png" alt="Logo Light" height="60" class="auth-logo-light">
                            </a>
                            <p class="font-size-15 text-muted mt-3">Signal Healthcare Solutions</p>
                        </div>
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">

                                        <div>
                                            <h5>Welcome!</h5>
                                            <p class="text-muted">Sign in to continue.</p>
                                        </div>
                                    
                                        <div class="mt-4 pt-3">


                                               <form action="occur_login_script.php" method="post" role="form">
                                                    <div class="form-group">
                                                        <label for="InputEmail1">Email address</label>
                                                        <input type="email" id="InputEmail1" placeholder="Email" class="form-control" name="email" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="InputPassword1">Password</label>
                                                        <input type="password" id="InputPassword1" placeholder="Password" class="form-control" name="password" required>
                                                    </div>
                                                    
                                                    <div style="margin: 0 auto; text-align: center;" class="btn-list m-b-35">
                                                        <input type="submit" name="submit" value="Submit">
                                                    </div>
                                                </form>

                                                                                                <!--
                                                <div class="mt-4">
                                                    <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                                </div>
                                                -->
                                            </form>

                                        </div>
                    
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 bg-auth h-100 d-none d-lg-block">
                                        <div class="bg-overlay"></div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- end card -->
                        
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end account page -->
   

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

    





<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ================================================ -->
    </body>
</html>
