<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>     

<!-- ============  PAGE SPECIFIC FILES / CSS =============================================================================================== -->









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

 <!-- ========================================  BOOTSTRAP ROW     ============================================================== -->


                         <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Registered Users:</h4>
                                        <br>
                                        <br>

<!-- ============   PHP / SQL QUERY FOR USER TABLE  ========================================================================================= -->

<?php
    $error = "";

    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0))
    {
        $query = "DELETE 
                  FROM users
                  WHERE user_id = {$_GET['id']}
                  LIMIT 1
                  ";

        $result = mysqli_query($conn, $query);

        if (!$result) 
        {
            print'<p> Could not retrieve the data because: <br>' . mysqli_error($conn) . '</p>';
            print'<p> The query being run was:  ' . $query . '</p>';
        }
        else
        {
            $row = mysqli_fetch_array($result);
            print'<p> Record Deleted</p>';
        }
    }
?>




 <a href="users.php" class="btn btn-warning btn-sm waves-effect waves-light">Back</a>




