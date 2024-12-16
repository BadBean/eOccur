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
                                    <h2 class="mb-sm-0 font-size-16 fw-bold">REPORT AN OCCURRENCE</h2>

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

<!-- ========================================  BOOTSTRAP ROW:   ==================================================================== -->
<!-- NOTE ******  FOR EACH FIELD ADDED, ADD TO DATABASE, VALUES FILE, AND INSERT STATEMENT.  NOT DONE: MNG, EDIT AND DELETE FILES -->
<!-- TO DO: RANDOMIZED DATA FOR TESTING,   -->

                       

<!-- FORM: OPENING TAG AND FILE DESTINATION -------------------------------------- -->

                        <form action="occur_values.php" class="form-floating mb-3" role="form" method="post" enctype=
                            "multipart/form-data">

<!-- FORM:  REPORTER SECTION -- COMPLETE -------------------------------------- -->
                    
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Reporter</h4>
                                            <p class="card-title-desc">Person completing the occurrence report</p>
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Last Name" name="reporter_last_name">
                                                        <label for="floatingInput">Last Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="First Name" name="reporter_first_name">
                                                        <label for="floatingInput">First Name</label>
                                                    </div>
                                                </div>
                                            </div>
                        
                                            <div class="row">
                                               <div class="col-lg-6">
                                                   <div class="form-floating mb-3">
                                                       <input type="tel" 
                                                              class="form-control" 
                                                              id="reporterPhone"
                                                              placeholder="(123) 456-7890" 
                                                              name="reporter_phone"
                                                              pattern="\(\d{3}\)\s\d{3}-\d{4}">
                                                       <label for="reporterPhone">Phone #/ Ext</label>
                                                       <div class="invalid-feedback">
                                                           Please enter a valid phone number
                                                       </div>
                                                   </div>
                                               </div>
                                               <div class="col-lg-6">
                                                   <div class="form-floating mb-3">
                                                       <input type="email" 
                                                              class="form-control" 
                                                              id="reporterEmail"
                                                              placeholder="Email" 
                                                              name="reporter_email"
                                                              pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                                       <label for="reporterEmail">Email</label>
                                                       <div class="invalid-feedback">
                                                           Please enter a valid email address (example@domain.com)
                                                       </div>
                                                   </div>
                                               </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=reporter_dept>
                                                            <option disabled selected>Select Your Department</option>
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
                                                        <label for="floatingSelect">Your Department</label>
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
                                           
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelectCategory"
                                                            aria-label="Floating label select example" name="reporter_category">
                                                            <option disabled selected>Category</option>
                                                            <?php
                                                            // SQL query to populate category dropdown based on active status
                                                            $sql = "SELECT * 
                                                                    FROM occur_setup_category
                                                                    WHERE category_status = 'Active'
                                                                    AND map_type = 'Safety'";
                                                            $result = mysqli_query($conn, $sql);
                                                            if (!$result) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }
                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result) > 0) {
                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    echo '<option value="'.$row["setup_category"].'">'.$row["setup_category"].'</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelectSubcategory"
                                                            aria-label="Floating label select example" name="occur_subcategory">
                                                            <option disabled selected>Sub Category</option>
                                                            <!-- Subcategories will be populated here via AJAX -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <script>
                                                document.getElementById('floatingSelectCategory').addEventListener('change', function() {
                                                var category = this.value;

                                                if (category) {
                                                    var xhr = new XMLHttpRequest();
                                                    xhr.open('POST', 'get_subcategories.php', true);
                                                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                                                    xhr.onload = function() {
                                                        if (xhr.status === 200) {
                                                            try {
                                                                var response = JSON.parse(xhr.responseText);

                                                                // Debugging: Log the response
                                                                console.log('Response from server:', response);

                                                                // Check for errors in the response
                                                                if (response.error) {
                                                                    console.error('Error from server:', response.error);
                                                                    return;
                                                                }

                                                                // Ensure the response is an array
                                                                if (Array.isArray(response)) {
                                                                    var subcategorySelect = document.getElementById('floatingSelectSubcategory');
                                                                    
                                                                    // Clear existing options
                                                                    subcategorySelect.innerHTML = '<option disabled selected>Sub Category</option>';
                                                                    
                                                                    // Populate new options
                                                                    response.forEach(function(subcategory) {
                                                                        var option = document.createElement('option');
                                                                        option.value = subcategory.setup_subcategory; // Use the correct column name
                                                                        option.textContent = subcategory.setup_subcategory; // Use the correct column name
                                                                        subcategorySelect.appendChild(option);
                                                                    });
                                                                } else {
                                                                    console.error('Unexpected response format:', response);
                                                                }
                                                            } catch (e) {
                                                                console.error('Error parsing JSON response:', e);
                                                            }
                                                        } else {
                                                            console.error('AJAX request failed with status:', xhr.status);
                                                        }
                                                    };

                                                    xhr.onerror = function() {
                                                        console.error('AJAX request failed.');
                                                    };

                                                    xhr.send('category=' + encodeURIComponent(category));
                                                } else {
                                                    // Clear subcategories if no category is selected
                                                    document.getElementById('floatingSelectSubcategory').innerHTML = '<option disabled selected>Sub Category</option>';
                                                }
                                            });

                                            </script>
     


                                                

                                            
                                             <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="reporter_severity">
                                                            <option disabled selected>Severity Level</option>

                                                            <?php
                                                            //SEVERITY SQL query to populate SEVERITY dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_severity";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["setup_severity"].'">'.$row["setup_severity"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>

                                            </div> <!-- end row -->


                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end card row -->

<!-- FORM: INCIDENT LOCATION / LOC -------------------------------------- -->
                    
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            
                                            
                                            
                                           

<!-- FORM:  PROGRAM / UNIT / LOC -- COMPLETE    -------------------------------------- -->

                                            <div class="row">

                                                <h4 class="card-title">Incident Detail - Location / Program</h4>
                                                <p class="card-title-desc">Select the Category that most closely matches the occurrence</p>

                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_location>
                                                            <option disabled selected>Select Facility Location</option>
                                                            <?php
                                                            //LOC SQL query to populate LEVEL OF CARE dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_location
                                                                        WHERE setup_location_status = 'Active'";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["setup_location_name"].'">'.$row["setup_location_name"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">FacilityLocation</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_unit>
                                                            <option disabled selected>Select Unit</option>

                                                            <?php
                                                            //UNIT SQL query to populate UNIT dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_units
                                                                        WHERE unit_name_status = 'Active'";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["occur_unit_name"].'">'.$row["occur_unit_name"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Unit</label>
                                                    </div>
                                                </div>

                                            </div> <!-- close row -->

                                            <br>

                                            <div class="row">
                                            
                                                

                                            
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_loc>
                                                            <option disabled selected>Select Level of Care</option>
                                                           <?php
                                                            //LOC SQL query to populate LEVEL OF CARE dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_LOC
                                                                        WHERE loc_status = 'Active'";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["setup_loc_name"].'">'.$row["setup_loc_name"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Level of Care</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_program>
                                                             <option disabled selected>Select Program</option>
                                                            <?php
                                                            //LOC SQL query to populate LEVEL OF CARE dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_program
                                                                        WHERE program_status = 'Active'";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["setup_program_name"].'">'.$row["setup_program_name"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Program</label>
                                                    </div>
                                                </div>
                                            </div>

                                             <br>

                                            <div class="row">
                                            
                                                

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

                                            <h4 class="card-title">Incident Detail - Non Clinical</h4>
                                            <p class="card-title-desc"></p>
                        

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Date of Occurrence</label>
                                                        <input class="form-control" type="date" value="" id="example-date-input" name="occur_date">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="example-time-input" class="form-label">Time of Occurrence</label>
                                                    <input class="form-control" type="time" value="" id="example-time-input" name="occur_time">
                                                </div>  
                                            </div> <!-- close row -->

                                            <div class="row">
                                            
                                                

                                                
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_area>
                                                             <option disabled selected>Area of Occurrence</option>
                                                            <?php
                                                            //AREA SQL query to populate CATEGORY dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_areas
                                                                        WHERE occur_area_status = 'Active'";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["occur_area_name"].'">'.$row["occur_area_name"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            
                                                            ?>
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
                                                            id="floatingTextarea" name="occur_description"></textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0">
                                                        <br>
                                                        <label for="floatingTextarea">Describe Interventions</label>
                                                        <textarea class="form-control" rows="10" cols="30" placeholder="Intervention"
                                                            id="floatingTextarea" name="occur_intervention"></textarea>
                                                       
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
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
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
                                                             <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        <label for="floatingSelect">Patient Injury</label>
                                                    </div>
                                                </div> 
                                            </div> <!-- close row -->

                                            <br>

                                      
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Note Employee Injury Details"
                                                            id="floatingTextarea" name="employee_injury_description"></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Note Patient Injury Details"
                                                            id="floatingTextarea" name="patient_injury_description"></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>

                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <br>

                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=other_injury>
                                                            <option disabled selected>Any injury to visitors / public? </option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        <label for="floatingSelect">Injury to Others</label>
                                                    </div>
                                                    
                                                </div>

                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_code>
                                                             <option disabled selected>Code Called?</option>
                                                              <option value="No">No</option>
                                                            <?php
                                                            //CATEGIRT SQL query to populate CATEGORY dropdowns based on user specific options
                                                                $sql = "SELECT * 
                                                                        FROM occur_setup_codes
                                                                        WHERE setup_code_status = 'Active'";
                                                                $result = mysqli_query($conn, $sql);
                                                                    if (!$result) 
                                                                    { die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); }
                                                            //Generate drop down menu options from query results
                                                                if (mysqli_num_rows($result) > 0) {
                                                                    // output data of each row
                                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                                        echo '<option value="'.$row["setup_code_long_name"].'">'.$row["setup_code_long_name"].'</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option value="">No options available</option>';
                                                                }
                                                            
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Code Called?</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                </div> 
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Notes on Injuries"
                                                            id="floatingTextarea" name="other_injury_notes"></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Note on Code"
                                                            id="floatingTextarea" name="code_notes"></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                
                                            </div> <!-- close row -->

                                        </div>
                                    </div>
                                </div> <!-- close card -->

   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Follow Up / Notifications</h4>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3 mb-lg-0"> <br>
                            <textarea class="form-control" rows="5" cols="28" placeholder="Notification Detail:  Name / Time "
                                id="floatingTextarea" name="occur_notification_notes"></textarea>
                            <label for="floatingTextarea"></label>
                        </div>
                    </div>
                    <div class="col-lg-6"> <br>
                        <div class="mb-3 mb-lg-0">
                            <textarea class="form-control" rows="5" cols="28" placeholder="Additional Notes / Followup"
                                id="floatingTextarea" name="occur_additional_notes"></textarea>
                            <label for="floatingTextarea"></label>
                        </div>
                    </div>
                </div> <!-- close row -->

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3 mb-lg-0"> <br>
                            <textarea class="form-control" rows="5" cols="28" placeholder="Note Staff/MD's present"
                                id="floatingTextarea" name="occur_staff"></textarea>
                            <label for="floatingTextarea"></label>
                        </div>
                    </div>
                </div> <!-- close row -->
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->  

<!-- Hidden Fields -->
<input type="hidden" name="occur_type" value="Safety">

<!-- Submit Button Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div style="margin: 0 auto; text-align: center;"> 
                    <input type="submit" name="submit" value="Submit" class="btn btn-warning">
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<!-- Closing Tags / Footer / Right Sidebar -->
</div> <!-- container-fluid -->
</div> <!-- End Page-content -->
<?php include ("includes/occur_footer.php"); ?>
</div> <!-- end main content-->
</div> <!-- END layout-wrapper -->
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
    // Phone number formatting
    document.getElementById('reporterPhone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    // Email validation on blur/tab out
    document.getElementById('reporterEmail').addEventListener('blur', function(e) {
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