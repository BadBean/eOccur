<?php
session_start();
require_once('auth.php');
include ("includes/occur_header.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- jQuery -->
<script src="assets/libs/jquery/jquery.min.js"></script>

<!-- Sweet Alert-->
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between card flex-sm-row border-0">
                        <h4 class="mb-sm-0 font-size-16 fw-bold">eOccur</h4>
                        <div class="page-title-right">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="buttons">
                        <div class="card-body">
                            <?php
                            if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0))
                            {
                                $query = "DELETE FROM occur WHERE occur_id = {$_GET['id']} LIMIT 1";
                                $occur_id = $_GET['id'];         
                                $result = mysqli_query($conn, $query);
                                
                                echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    if(" . ($result ? "true" : "false") . ") {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'Occurrence report #" . $occur_id . " has been deleted.',
                                            icon: 'success',
                                            confirmButtonColor: '#34c38f'
                                        }).then(function() {
                                            window.location = 'manage_occur.php';
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Could not delete the record.',
                                            icon: 'error',
                                            confirmButtonColor: '#f46a6a'
                                        }).then(function() {
                                            window.location = 'manage_occur.php';
                                        });
                                    }
                                });
                                </script>";
                            }
                            ?>
                            <br><br>
                            <a href="manage_occur.php" class="btn btn-warning">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>