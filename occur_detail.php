<?php
session_start();
require_once('auth.php');
include ("includes/occur_header_datatables.php");
include ("includes/occur_navbar.php"); 
include ("includes/occur_sidebar.php");
include ("includes/occur_config.php");
?>        

 
<!-- ========================================   PAGE SPECIFIC SCRIPTS      ======================================================== -->

    <!-- DataTables -->
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Select datatable -->
        <link href="assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable -->
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />     


<html>
  <head>
    <style>
      ul {
        padding: 1px 0;
      }
    </style>
  </head>
</html>





<!-- ================================================  SQL QUERY  ============================================= -->

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


<!-- ========================================  BOOTSTRAP ROW:   ========================================================= -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Incident Report Detail</h4>
                                        <p class="card-title-desc">
                                        </p>


<!-- ================================================  FORM ACTION / SUBMIT   =========================================== -->
<!-- includes form_value file to populate existing values prior to being updated / overwritten -->


<!-- ======================================== FORM FIELDS  ======================================================================== -->

                    
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Summary:  </h4>
                                            
                                                <ul>  
                                                    <ul>Category: <?php echo "{$row['occur_type']}"; ?></ul>
                                                    <ul>Category Detail</ul>
                                                    <ul>Severity Level</ul>
                                                    <ul>Date / Time</ul>
                                                    <ul>Patient Name</ul>
                                                    <ul>Unit</ul>
                                                    <ul>Program</ul>
                                                    <ul>Area</ul>
                                                    <ul>Description</ul>
                                                    <ul>Intervention</ul>
                                                    <ul>Injuries</ul>
                                                </ul>

                                        <div> <!-- close card body -->
                                    </div> <!-- close card -->
                                </div>
                            </div> <!-- close row -->





                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Last Name" name="reporter_last_name" value='<?php echo "{$row['reporter_last_name']}"; ?>'>
                                                        <label for="floatingInput">Last Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="First Name" name="reporter_first_name" value='<?php echo "{$row['reporter_first_name']}"; ?>'>
                                                        <label for="floatingInput">First Name</label>
                                                    </div>
                                                </div>
                                            </div>
                        
                                            <div class = "row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Phone" name="reporter_phone" value='<?php echo "{$row['reporter_phone']}"; ?>'>
                                                        <label for="floatingInput">Phone #/ Ext</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="email" class="form-control" id="floatingInput"
                                                            placeholder="Email" name="reporter_email" value='<?php echo "{$row['reporter_email']}"; ?>'>
                                                        <label for="floatingInput">Email</label>
                                                    </div>
                                                </div>
                                                 </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=reporter_dept>
                                                            <option disabled selected>Select Your Department</option>
                                                            <option value="nursing" <?php echo $row['reporter_dept'] == 'nursing' ? ' selected="selected"' : ''; ?> >Nursing </options>
                                                            <option value="clinical_svc" <?php echo $row['reporter_dept'] == 'clinical_svc' ? ' selected="selected"' : ''; ?> >Clinical Services </options>
                                                            <option value="dietary" <?php echo $row['reporter_dept'] == 'dietary' ? ' selected="selected"' : ''; ?> >Dietary </options>
                                                            <option value="housekeeping" <?php echo $row['reporter_dept'] == 'housekeeping' ? ' selected="selected"' : ''; ?> >Housekeeping </options>
                                                            <option value="maintenance" <?php echo $row['reporter_dept'] == 'maintenance' ? ' selected="selected"' : ''; ?> >Plant Ops </options>
                                                            <option value="admissions" <?php echo $row['reporter_dept'] == 'admissions' ? ' selected="selected"' : ''; ?> >Admissions </options>
                                                            <option value="admin" <?php echo $row['reporter_dept'] == 'admin' ? ' selected="selected"' : ''; ?> >Admin / Support </options>
                                                            <option value="other_clinical" <?php echo $row['reporter_dept'] == 'other_clinical' ? ' selected="selected"' : ''; ?> >Other - Clinical </options>
                                                            <option value="other_nonclinical" <?php echo $row['reporter_dept'] == 'other_nonclinical' ? ' selected="selected"' : ''; ?> >Other - Non Clinical </options>
                                                        </select>
                                                        <label for="floatingSelect">Your Department</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="reporter_role">
                                                            <option didsabled selected>Select your Job</option>
                                                            <option value="1">One</option>
                                                            <option value="2">Two</option>
                                                            <option value="3">Three</option>
                                                        </select>
                                                        <label for="floatingSelect">Position / Job</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                        
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->

<!-- FORM:  TYPE / REPORTER SEVERITY -- COMPLETE -------------------------------------- -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                        
                                            <h4 class="card-title">Type of Occurrence / Severity</h4>
                                            <p class="card-title-desc">Select the Category that most closely matches the occurrence</p>
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="reporter_category"> 
                                                            <option disabled selected>Type of Occurrence</option>
                                                            <option value="1">Patient Specific: Clinical</option>
                                                            <option value="2">Patient Specific: Other</option>
                                                            <option value="5">Non Clinical: Safety</option>
                                                            <option value="6">Non Clinical: Other</option>
                                                        </select>
                                                    </div>
                                                </div>
     
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="reporter_severity">
                                                            <option disabled selected>Severity</option>
                                                            <option value="Grave">Grave</option>
                                                            <option value="Major">Major</option>
                                                            <option value="Minor">Minor</option>
                                                            <option value="Low">Low</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->
                     
<!-- FORM:  TIME / LOCATION -------------------------------------- -->


 




<!-- FORM:  PATIENT DETAIL / COMPLETE -------------------------------------- -->

                    
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Patient Detail</h4>
                                            <p class="card-title-desc">Complete separate report for each patient involved</p>
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="" name="patient_last_name" value='<?php echo "{$row['patient_last_name']}"; ?>'>
                                                        <label for="floatingInput">Last Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="" name="patient_first_name" value='<?php echo "{$row['patient_first_name']}"; ?>'>
                                                        <label for="floatingInput">First Name</label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                            
                                            
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_gender>
                                                            <option disabled selected>Select Gender</option>
                                                            <option value="M">Male</option>
                                                            <option value="F">Female</option>
                                                            <option value="O">Other</option>
                                                        </select>
                                                        <label for="floatingSelect">Gender</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Program" name="patient_age" value='<?php echo "{$row['patient_age']}"; ?>'>
                                                        <label for="floatingInput">Age</label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                            <br>

<!-- FORM:  PROGRAM / UNIT / LOC -- COMPLETE    -------------------------------------- -->

                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Program" name="patient_MRN" value='<?php echo "{$row['patient_MRN']}"; ?>'>
                                                        <label for="floatingInput">Account / MR # $</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_unit>
                                                            <option disabled selected>Select Unit</option>
                                                            <option value="unit_1">Unit 1</option>
                                                            <option value="unit_2">Unit 2</option>
                                                            <option value="unit_3">Unit 3</option>
                                                            <option value="unit_4">Unit 4</option>
                                                            <option value="unit_5">Unit 5</option>
                                                            <option value="other">Other</option>
    
                                                        </select>
                                                        <label for="floatingSelect">Unit</label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                            <div class="row">
                                            
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_program>
                                                             <option disabled selected>Select Program</option>
                                                            <option value="program_1">Program 1</option>
                                                            <option value="program_2">Program 2</option>
                                                            <option value="program_3">Program 3</option>
                                                            <option value="program_4">Program 4</option>
                                                            <option value="program_5">Program 5</option>
                                                            <option value="program_6">Other</option>
                                                        </select>
                                                        <label for="floatingSelect">Program</label>
                                                    </div>
                                                </div>

                                            
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_loc>
                                                            <option disabled selected>Select Level of Care</option>
                                                            <option value="inpatient_loc">Inpatient</option>
                                                            <option value="detox_loc">Detox</option>
                                                            <option value="residential_loc">Residential</option>
                                                            <option value="php_loc">PHP</option>
                                                            <option value="iop_loc">IOP</option>
                                                            <option value="iop_loc">Other</option>
                                                        </select>
                                                        <label for="floatingSelect">Level of Care</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                        
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->


<!-- FORM:  INCIDENT DETAIL  -------------------------------------- -->


                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Incident Detail - Clinical</h4>
                                            <p class="card-title-desc"></p>
                        

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Date</label>
                                                        <input class="form-control" type="date" value="" id="example-date-input" name="occur_date"> 
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="example-time-input" class="form-label">Time</label>
                                                    <input class="form-control" type="time" value="" id="example-time-input" name="occur_time">
                                                </div>  
                                            </div> <!-- close row -->

                                            <div class="row">
                                            
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=clinical_category>
                                                            <option disabled selected>Select Category</option>
                                                            <option value="med_error">Med Error</option>
                                                            <option value="contraband">Contraband</option>
                                                            <option value="treatment_issue">Treatment Issue</option>
                                                            <option value="physical_confrontation">Physical Confrontation</option>
                                                            <option value="SAO">SAO / Boundary</option>
                                                            <option value="patient_fall">Fall</option>
                                                        </select>
                                                        <label for="floatingSelect">Clinical Category</label>
                                                    </div>
                                                </div>

                                                
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_area>
                                                            <option disabled selected>Area of Occurance</option>
                                                            <option value="area_group_room">Group Room</option>
                                                            <option value="area_patient_room">Patient Room</option>
                                                            <option value="area_restroom">Restroom</option>
                                                            <option value="area_common">Common Area</option>
                                                              <option value="area_dining">Kitchen/Dining</option>
                                                            <option value="area_outdoors">Outdoors</option>
                                                            <option value="area_other">Other</option>
                                                        </select>
                                                        <label for="floatingSelect">Select Area</label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                            
                                            <div class="row">
                                           
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0">
                                                        <br>
                                                        <label for="floatingTextarea">Description of Incident</label>
                                                        <textarea class="form-control" rows="10" cols="30" placeholder="Brief Description"
                                                            id="floatingTextarea" name="occur_description"><?php echo $row['occur_description']; ?></textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0">
                                                        <br>
                                                        <label for="floatingTextarea">Describe Interventions</label>
                                                        <textarea class="form-control" rows="10" cols="30" placeholder="Intervention"
                                                            id="floatingTextarea" name="occur_intervention" <?php echo $row['occur_intervention']; ?>></textarea>
                                                       
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_employee_injury>
                                                            <option disabled selected>Did Employee Injury Result?</option>
                                                            <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                        <label for="floatingSelect">Employee Injury</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="occur_patient_injury">
                                                            <option disabled selected>Did Patient Injury Result</option>
                                                             <option value="1">Yes</option>
                                                            <option value="2">No</option>
                                                        </select>
                                                        <label for="floatingSelect">Patient Injury</label>
                                                    </div>
                                                </div> 
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Describe employee injury"
                                                            id="floatingTextarea" name="employee_injury_description"><?php echo $row['employee_injury_description']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Describe patient injury"
                                                            id="floatingTextarea" name="patient_injury_description"><?php echo $row['patient_injury_description']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>

                                            </div> <!-- close row -->

                                            


                                        </div>
                                    </div>
                                </div> <!-- close card -->


<!-- FORM:  TIMESTAMP FOR DATE SUBMITTED BY REPORTER   -------------------------------------- -->

                            <!-- Timestamp function is in file with the insert statement
                            <input type="hidden" name="reporting_timestamp" value="<//?php echo time(); ?>">
                            -->                            

<!-- FORM:  MANAGEMENT FOLLOW UP  -------------------------------------- -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Management Follow Up</h4>
                                            <p class="card-title-desc"></p>
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="manager_followup_job">
                                                            <option selected>Management Review By </option>
                                                            <option value="M">Chief Nursing Oficer</option>
                                                            <option value="F">Clinical Services Dir</option>
                                                            <option value="F">Intake Director</option>
                                                            <option value="F">Program Director: 1</option>
                                                            <option value="F">Program Director: 2</option>
                                                            
                                                        </select>
                                                        <label for="floatingSelect">Assign Manager for Review</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Last Name" name="manager_followup_name" value='<?php echo "{$row['manager_followup_name']}"; ?>'>
                                                        <label for="floatingInput">Manager Name</label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Manager Review Date</label>
                                                        <input class="form-control" type="date" value="" id="example-date-input" name="manager_review_date">
                                                    </div>
                                                </div>





                                            </div> <!-- close row -->



                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Note any additional findings"
                                                            id="floatingTextarea" name="manager_followup_notes" <?php echo $row['manager_followup_notes']; ?>></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Follow up / Action plan"
                                                            id="floatingTextarea" name="manager_followup_plan"><?php echo $row['manager_followup_plan']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->                                         
                                            
                                        </div>
                                    </div> <!-- close card -->
                                </div> 
                            </div> <!-- close row -->
                                            <br>

<!-- FORM:  RISK MANAGEMENT REVIEW  -------------------------------------- -->

                         <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Risk Management Review</h4>
                                            <p class="card-title-desc"></p>
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Reviewer Name" name="rm_followup_name" value='<?php echo "{$row['manager_followup_name']}"; ?>'>
                                                        <label for="floatingInput">Review by:</label>
                                                    </div>

                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="rm_severity">
                                                            <option selected>RM Severity</option>
                                                            <option value="1">One</option>
                                                            <option value="2">Two</option>
                                                            <option value="3">Three</option>
                                                            <option value="3">Three</option>
                                                        </select>
                                                    </div>
                                                </div>
                                               
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Note any additional findings"
                                                            id="floatingTextarea" name="rm_followup_notes"><?php echo $row['manager_followup_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Follow up / Action plan"
                                                            id="floatingTextarea" name="rm_followup_plan"><?php echo $row['manager_followup_plan']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->    

                                            <div class="row">
                                                 <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">RM Review Date</label>
                                                        <input class="form-control" type="date" value="" id="example-date-input" name="rm_review_date">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Date Closed</label>
                                                        <input class="form-control" type="date" value="" id="example-date-input" name="occur_close_date">
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                             <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="occur_status">
                                                            <option disabled selected>Update Status</option>

                                                            <option value="Pending Manager Review" <?php echo $row['occur_status'] == 'Pending Manager Review' ? ' selected="selected"' : ''; ?> >Pending Manager Review </options>
                                                            <option value="Action Plan Needed" <?php echo $row['occur_status'] == 'Action Plan Needed' ? ' selected="selected"' : ''; ?> >Action Plan Needed </options>
                                                            <option value="Pending RM Review" <?php echo $row['occur_status'] == 'Pending RM Review' ? ' selected="selected"' : ''; ?> >Pending RM Review </options>
                                                            <option value="Follow Up Required" <?php echo $row['occur_status'] == 'Follow Up Required' ? ' selected="selected"' : ''; ?> >Follow Up Required </options>
                                                            <option value="Closed" <?php echo $row['occur_status'] == 'closed' ? ' selected="selected"' : ''; ?> >Closed </options>
                                                

                                                
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->

                                        </div>
                                    </div> <!-- close card -->
                                </div> 
                            </div> <!-- close row -->
                                            <br>





                                       

                                       
                                      

                                    </div>
                                </div>
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


<!-- ========================================  PAGE SPECIFIC SCRIPTS   ========================================================== -->


    


<!-- ========================================  FINAL CLOSING TAGS FOR PAGE   ====================================================== -->
    </body>
</html>





