<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>            

<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->

<style>
.card {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none; /* To remove underline from links */
}

.card-body {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.card-body .mt-auto {
    margin-top: auto;
}
</style>




<!-- ================================================  SQL QUERY           ======================================================== -->





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

<!-- PHP / SQL QUERY FOR "NEW REPORTS"    ========================================================================================= -->









<!-- ========================================  BOOTSTRAP ROW     =================================================================== -->


                                    <br>
                        <!-- mb-2 in bootstrap refers to "margin bottom" meaning vertical justification is top -->
						<div class="row mb-2">
                            <div class="col-lg-4">
                                <a href="add_occur.php" class="card bg-primary border-primary text-white-50 h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="mb-4 text-white card-title text-truncate">
                                            <i class="mdi mdi-bullseye-arrow me-2"></i> Patient Care
                                        </h5>
                                        <p class="card-text">Incidents directly related to the delivery of healthcare services and medical treatment to patients.</p>
                                        <p>Note: If an incident includes multiple patients, a separate report needs to be completed for each patient.</p>
                                        <div class="mt-auto">
                                            <button type="button" class="btn btn-light waves-effect waves-light btn-sm">ADD REPORT</button>
                                        </div>
                                    </div>
                                </a>
                            </div><!-- End col -->

                            <div class="col-lg-4">
                                <a href="add_other.php" class="card bg-success border-success text-white-50 h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="mb-4 text-white card-title text-truncate">
                                            <i class="mdi mdi-check-all me-2"></i> Other
                                        </h5>
                                        <p class="card-text">Incidents that do not fall under "Patient Care" or "Safety" but still require reporting for accountability, compliance, or quality improvement purposes.</p>
                                        <p>Examples include: HIPAA breach, Missing patient belongings, Disruptive staff behavior, Critical IT system issues.</p>
                                        <div class="mt-auto">
                                            <button type="button" class="btn btn-light waves-effect waves-light btn-sm">ADD REPORT</button>
                                        </div>
                                    </div>
                                </a>
                            </div><!-- End col -->

                            <div class="col-lg-4">
                                <a href="add_safety.php" class="card bg-warning border-warning text-white-50 h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="mb-4 text-white card-title text-truncate">
                                            <i class="mdi mdi-alert-circle-outline me-2"></i> Safety
                                        </h5>
                                        <p class="card-text">Incidents that pose a risk to the physical or psychological safety of patients, staff, or visitors, but are not directly related to medical care.</p>
                                        <p>Examples include: Equipment failures, Fire, Hazardous Material spills, Facility system failures (HVAC, Plumbing, Water, Power).</p>
                                        <div class="mt-auto">
                                            <button type="button" class="btn btn-light waves-effect waves-light btn-sm">ADD REPORT</button>
                                        </div>
                                    </div>
                                </a>
                            </div><!-- End col -->
                        </div><!-- End row -->

                        <br>
                        <br>
                        <br>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">When is an occurrence report required?</h4>
                                    <!--<p class="card-title-desc">How does our organization define an incident that requires an occurrence report?</p> -->
                                   
                                    <p>A reportable occurrence, or incident, is defined as any event that could have, or did, lead to unintended or unexpected harm to a patient, staff, or visitor.</p>  

                                    <p>Incident reports form the basis for quantifying many critical quality and safety goals and accurate reporting is important blah blah blah</p>

                                    <p>Not all issues the come up require an incident report.  For example, all patient complaints are tracked by administration but minor complaints that do not impact quality of care do not require a report.  An example would be complaints about food or room temperature.  Talk to your supervisor or quality director if you have any questions about what should be reported </p>
                                </div>
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->




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



                           






