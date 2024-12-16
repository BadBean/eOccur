<?php
include("includes/auth.php"); // Include auth.php to ensure the session is started and $email is set
include("includes/occur_config.php");

// Fetch the email from session, prevent SQL injection
$email = mysqli_real_escape_string($conn, $_SESSION['email']);




//======================= QUERY TO GET DETAIL ON CURRENT USER LOGGED IN =============================================

$sql = "SELECT * FROM users WHERE user_email = '$email'";

// Execute the query
$result_acct = mysqli_query($conn, $sql);

// Check if the query was successful and if it returned any rows
if ($result_acct && mysqli_num_rows($result_acct) > 0) {
    // Fetch the user's data as an associative array
    $row_acct = mysqli_fetch_assoc($result_acct);

    // Loop through each key-value pair in the associative array
    foreach ($row_acct as $key => $value) {
        // Create a dynamic variable with the name of the column and set its value
        ${$key} = $value;
    }

} else {
    // Handle cases where the query fails or returns no results
    echo "No user found or query failed.";
}

// ======================= QUERY TO SET FULL NAME VARIABLE  =============================================


// Construct the full name with a non-breaking space
$user_full_name = $row_acct['user_first_name'] . " " . $row_acct['user_last_name']; // Use regular space, not &nbsp
$user_full_name_title = $row_acct['user_first_name'] . " " . $row_acct['user_last_name'] . ", " . $row_acct['user_title']; // Use regular space, not &nbsp



// Close the database connection if necessary
//mysqli_close($conn);





// ======================= QUERY TO GET DETAIL ON CURRENT USER LOGGED IN =============================================


//Query to count # of reports pending for manager logged in
    $sql =  "SELECT COUNT(*) AS alert_count
             FROM occur
             WHERE manager_status = 'Pending Manager Review'
             AND manager_followup_name = '$user_full_name'
            ";

    $result_alert = mysqli_query($conn, $sql);
             if (!$result_alert) 
             { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }

    $row_alert = mysqli_fetch_assoc($result_alert);
    $numrows_alert = $row_alert['alert_count'];

    echo $numrows_alert;
?>



        <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="occur_home.php" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo_eoccur_clear.png" alt="logo-sm" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo-eoccur_clear.png" alt="logo-dark" height="23">
                                </span>
                            </a>

                         
                        </div>
 
                        <button type="button" class="btn btn-sm px-3 font-size-16 vertinav-toggle header-item waves-effect" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <button type="button" class="btn btn-sm px-3 font-size-16 horinav-toggle header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <!-- App Search-->
                        <!--
                        <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="mdi mdi-magnify"></span>
                            </div>
                        </form>
                        -->
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-inline-block d-lg-none ms-2">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">
        
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                <i class="mdi mdi-fullscreen"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell"></i>
                                <span class="badge bg-danger rounded-pill"><?php echo $numrows_alert; ?></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifications </h6>
                                        </div>
                                       
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    <a href="" class="text-reset notification-item d-block active">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title bg-danger rounded-circle font-size-16">
                                                       <i class="mdi mdi-alert-circle text-light"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <a href="dashboard_management.php">
                                                <h6 class="mb-1" key="t-shipped">New Reports Assigned</h6>
                                                <div class="font-size-13 text-muted">
                                                    <p class="mb-1" key="t-grammer">Your review or follow up is required</p>
                                                </div>
                                                    <!-- <p class="mb-0 font-size-12"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 hours ago</span></p> -->
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </a>
                                    
                                    <!--
                                    <a href="" class="text-reset notification-item d-block ">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                                        <i class="bx bx-badge-check"></i>
                                                    </span>
                                                </div>
                                            </div>
                                
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" key="t-shipped">Your item is shipped</h6>
                                                <div class="font-size-13 text-muted">
                                                    <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                                    <p class="mb-0 font-size-12"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 hours ago</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                -->

                                   
                            </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!--
                                <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                                    alt="Header Avatar">
                                -->
                                <span class="d-none d-xl-inline-block ms-1"><?php echo $user_first_name . " " . $user_last_name ?></span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">Welcome! </h6>
                                
                                <a class="dropdown-item" href="occur_login.php"><i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-profile">Change User</span></a>
                                
                                <!--
                                <a class="dropdown-item" href="#"><i class="mdi mdi-message-text-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-messages">Messages</span></a>
                                
                                <a class="dropdown-item" href="#"><i class="mdi mdi-calendar-check-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-taskboard">Taskboard</span></a>
                                -->

                                <a class="dropdown-item" href="dashboard_management.php"><i class="mdi mdi-lifebuoy text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-help">Logout</span></a>

                                  <a class="dropdown-item" href="dashboard_management.php"><i class="mdi mdi-lifebuoy text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-help">My Dashboard</span></a>
                                
                                
                                <div class="dropdown-divider"></div>
                                
                                <!--
                                <a class="dropdown-item" href="#"><i class="mdi mdi-wallet text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-balance">Balance : <b>$1901.67</b></span></a>
                                <a class="dropdown-item" href="#"><span class="badge bg-success bg-soft text-success mt-1 float-end">New</span><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-settings">Settings</span></a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-lock text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-lock-screen">Lock screen</span></a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span class="align-middle" key="t-logout">Logout</span></a>
                                -->
                            </div>
                        </div>

                        <!--
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                                <i class="bx bx-cog bx-spin"></i>
                            </button>
                        </div>
                        -->

                    </div>
                </div>
            </header>