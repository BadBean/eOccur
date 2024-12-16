

<!-- ================================================  PHP / SQL QUERY  ============================================= -->

<?php
    // List of date fields
    $dateFields = [
        'occur_date',
        'manager_review_date',
        'rm_followup_date',
        'rm_review_date',
        'occur_close_date',
        'patient_dob',
        'admit_date',
        'target_date',
        'complete_date'
    ];

    // Initialize variables
    foreach ($dateFields as $field) {
        if (!empty($row[$field]) && $row[$field] !== '0000-00-00') {
            $$field = date('Y-m-d', strtotime($row[$field]));
        } else {
            $$field = ''; // Set to empty string if NULL or invalid
        }
    }
?>





<!-- ======================================== FORM FIELDS  ======================================================================== -->

                    
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
                        
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="tel" 
                                                               class="form-control" 
                                                               id="reporterPhone"
                                                               placeholder="(123) 456-7890" 
                                                               name="reporter_phone"
                                                               pattern="\(\d{3}\)\s\d{3}-\d{4}"
                                                               required
                                                               value='<?php echo "{$row['reporter_phone']}"; ?>'>
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
                                                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                                               required
                                                               value='<?php echo "{$row['reporter_email']}"; ?>'>
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
                                                        

                                                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="reporter_dept">
                                                            <option disabled>Select Your Department</option>
                                                            <?php
                                                            // $row['reporter_dept'] contains the current department value from query in edit file
                                                            $reporter_dept = $row['reporter_dept'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_department WHERE setup_department_status = 'Active'";
                                                            $result_select = mysqli_query($conn, $sql);

                                                            if (!$result_select) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_select) > 0) {
                                                                // Output data of each row
                                                                while ($row_select = mysqli_fetch_assoc($result_select)) {
                                                                    $selected = $row_select['setup_department_description'] == $reporter_dept ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_select["setup_department_description"]) . '"' . $selected . '>' . htmlspecialchars($row_select["setup_department_description"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>

                                                        <label for="floatingSelect">Your Department</label>
                                                    </div>
                                                </div>
                                            
                                                <!--
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
                                                -->
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
                                                        <select class="form-select" id="floatingSelectCategory"
                                                                aria-label="Floating label select example" name="reporter_category">
                                                            <option value="">Select Category</option>
                                                            <?php
                                                            // $row['reporter_category'] contains the current category value from query in the edit file
                                                            $reporter_category = $row['reporter_category'];
                                                            // SQL query to populate category dropdown based on active status
                                                            $sql = "SELECT * 
                                                                    FROM occur_setup_category
                                                                    WHERE category_status = 'Active'";
                                                            $result_cat = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($result_cat) > 0) {
                                                                while ($row_cat = mysqli_fetch_assoc($result_cat)) {
                                                                    // Pre-select the current category
                                                                    $selected = ($row_cat['setup_category'] == $reporter_category) ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_cat["setup_category"]) . '"' . $selected . '>' . htmlspecialchars($row_cat["setup_category"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelectCategory">Reporter Category</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelectSubcategory"
                                                            aria-label="Floating label select example" name="occur_subcategory">
                                                            <option disabled>Select Subcategory</option>
                                                            <?php
                                                            // $row['occur_subcategory'] contains the current subcategory value from query in the edit file
                                                            $occur_subcategory = $row['occur_subcategory'];

                                                            // SQL query to populate subcategory dropdown based on active category
                                                            // This will initially load the subcategories for the selected category
                                                            $sql = "SELECT * 
                                                                    FROM occur_setup_subcategory
                                                                    WHERE subcategory_status = 'Active' 
                                                                    ";
                                                            $result_sub = mysqli_query($conn, $sql);
                                                            if (mysqli_num_rows($result_sub) > 0) {
                                                                while ($row_sub = mysqli_fetch_assoc($result_sub)) {
                                                                    // Pre-select the current subcategory
                                                                    $selected = $row_sub['setup_subcategory'] == $occur_subcategory ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_sub["setup_subcategory"]) . '"' . $selected . '>' . htmlspecialchars($row_sub["setup_subcategory"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="occur_type"> 
                                                            <option disabled selected>Type of Occurrence</option>
                                                            <?php
                                                            // $row['occur_type'] contains the current value from query in edit file
                                                            $occur_type = $row['occur_type'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_type";
                                                            $result_type = mysqli_query($conn, $sql);

                                                            if (!$result_type) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_type) > 0) {
                                                                // Output data of each row
                                                                while ($row_type = mysqli_fetch_assoc($result_type)) {
                                                                    $selected = $row_type['setup_type'] == $occur_type ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_type["setup_type"]) . '"' . $selected . '>' . htmlspecialchars($row_type["setup_type"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
   
                                                        </select>
                                                          <label for="floatingSelect">Type of Occurrence</label>
                                                    </div>
                                                </div>
     
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="reporter_severity">
                                                            <option disabled selected>Severity</option>
                                                           <?php
                                                            // $row['reporter_severity'] contains the current department value from query in edit file
                                                            $reporter_severity = $row['reporter_severity'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_severity";
                                                            $result_severity = mysqli_query($conn, $sql);

                                                            if (!$result_severity) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_severity) > 0) {
                                                                // Output data of each row
                                                                while ($row_severity = mysqli_fetch_assoc($result_severity)) {
                                                                    $selected = $row_severity['setup_severity'] == $reporter_severity ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_severity["setup_severity"]) . '"' . $selected . '>' . htmlspecialchars($row_severity["setup_severity"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
   
                                                        </select>
                                                        <label for="floatingSelect">Severity Level</label>
                                                    </div>
                                                </div>

                                            </div> 

                                            <br>
                                            



<!-- SKIP CATEGORY AND SUBCATEGORY SHOULD BE HERE  -->




















<!-- SKIP CATEGORY AND SUBCATEGORY SHOULD BE HERE  -->

                                    </div> <!-- close card-body -->
                                </div> <!-- close card -->
                            </div> <!-- close col-12 -->
                        </div> <!-- row -->



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
                                                            <option value="M" <?php echo $row['patient_gender'] == 'M' ? ' selected="selected"' : ''; ?> >Male </options>
                                                            <option value="F" <?php echo $row['patient_gender'] == 'F' ? ' selected="selected"' : ''; ?> >Female </options>
                                                            <option value="O" <?php echo $row['patient_gender'] == 'O' ? ' selected="selected"' : ''; ?> >Other </options>
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


                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="example-date-input" class="form-label">Date of Birth</label>
                                                        <input class="form-control" type="date" value="<?php echo htmlspecialchars($patient_dob); ?>" id="example-date-input" name="patient_dob">
                                                    </div> <!-- close mb-3 -->
                                                </div> <!-- close column -->

                                                <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="example-date-input" class="form-label">Admit date</label>
                                                        <input class="form-control" type="date" value="<?php echo htmlspecialchars($admit_date); ?>" id="example-date-input" name="admit_date">
                                                    </div> <!-- close mb-3 -->
                                                </div> <!-- close column -->
                                            </div> <!-- close row -->
                                            <br>

 
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
                                                            aria-label="Floating label select example" name=md_attending>
                                                            <option disabled selected>Attending MD</option>
                                                            <?php
                                                            // $row['setup_md_short_name'] contains the current department value from query in edit file
                                                            $md_attending = $row['md_attending'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_md WHERE setup_md_status = 'Active'";
                                                            $result_md = mysqli_query($conn, $sql);

                                                            if (!$result_md) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_md) > 0) {
                                                                // Output data of each row
                                                                while ($row_md = mysqli_fetch_assoc($result_md)) {
                                                                    $selected = $row_md['setup_md_short_name'] == $md_attending ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_md["setup_md_short_name"]) . '"' . $selected . '>' . htmlspecialchars($row_md["setup_md_short_name"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Attending MD</label>
                                                    </div>
                                                </div>

                                            </div> <!-- close row -->

                                           
                                    </div> <!-- close card-body -->
                                </div> <!-- close card -->
                            </div> <!-- close col-12 -->
                        </div> <!-- row -->



<!-- FORM:  PROGRAM / UNIT / LOC -- COMPLETE    -------------------------------------- -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Program / Location</h4>
                                            <p class="card-title-desc"></p>                    


                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=patient_loc>
                                                             <option disabled selected>Select Level of Care</option>
                                                            <?php
                                                            // $row['patient_loc'] contains the current department value from query in edit file
                                                            $patient_loc = $row['patient_loc'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_LOC WHERE loc_status = 'Active'";
                                                            $result_loc = mysqli_query($conn, $sql);

                                                            if (!$result_loc) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_loc) > 0) {
                                                                // Output data of each row
                                                                while ($row_loc = mysqli_fetch_assoc($result_loc)) {
                                                                    $selected = $row_loc['setup_loc_name'] == $patient_loc ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_loc["setup_loc_name"]) . '"' . $selected . '>' . htmlspecialchars($row_loc["setup_loc_name"]) . '</option>';
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
                                                            // $row['patient_program'] contains the current department value from query in edit file
                                                            $patient_program = $row['patient_program'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_program WHERE program_status = 'Active'";
                                                            $result_program = mysqli_query($conn, $sql);

                                                            if (!$result_program) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_program) > 0) {
                                                                // Output data of each row
                                                                while ($row_program = mysqli_fetch_assoc($result_program)) {
                                                                    $selected = $row_program['setup_program_name'] == $patient_program ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_program["setup_program_name"]) . '"' . $selected . '>' . htmlspecialchars($row_program["setup_program_name"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Program</label>
                                                    </div>
                                                </div>

                                            
                                                
                                            </div> <!-- close row -->

                                            <br>
                                                   

                                           

                                            <div class="row">
                                            
                                                

                                            
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_location>
                                                            <option disabled selected>Select Location</option>
                                                            <?php
                                                            // $row['patient_location'] contains the current department value from query in edit file
                                                            $occur_location = $row['occur_location'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_location WHERE setup_location_status = 'Active'";
                                                            $result_location = mysqli_query($conn, $sql);

                                                            if (!$result_location) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_location) > 0) {
                                                                // Output data of each row
                                                                while ($row_location = mysqli_fetch_assoc($result_location)) {
                                                                    $selected = $row_location['setup_location_name'] == $occur_location? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_location["setup_location_name"]) . '"' . $selected . '>' . htmlspecialchars($row_location["setup_location_name"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Location</label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="patient_unit">
                                                            <option disabled selected>Select Unit</option>
                                                            <?php
                                                            // $row['patient_unit'] contains the current department value from query in edit file
                                                            $patient_unit = $row['patient_unit'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_units WHERE unit_name_status = 'Active'";
                                                            $result_units = mysqli_query($conn, $sql);

                                                            if (!$result_units) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_units) > 0) {
                                                                // Output data of each row
                                                                while ($row_units = mysqli_fetch_assoc($result_units)) {
                                                                    $selected = $row_units['occur_unit_name'] == $patient_unit ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_units["occur_unit_name"]) . '"' . $selected . '>' . htmlspecialchars($row_units["occur_unit_name"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>

                                                        <label for="floatingSelect">Unit</label>
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
                                                        <input class="form-control" type="date" value="<?php echo htmlspecialchars($occur_date); ?>" id="example-date-input" name="occur_date">

                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="example-time-input" class="form-label">Time</label>
                                                    <?php $occur_time = date('H:i', strtotime($row['occur_time'])); ?>
                                                    <input class="form-control" type="time" value="<?php echo htmlspecialchars($occur_time); ?>" id="example-time-input" name="occur_time"> 
                                                </div>  
                                            </div> <!-- close row -->

                                            <div class="row">
                                            
                                                

                                                
                                                <div class="col-lg-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_area>
                                                            <option disabled selected>Area of Occurance</option>
                                                            <?php
                                                            // $row['occur_area'] contains the current department value from query in edit file
                                                            $occur_area = $row['occur_area'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_areas WHERE occur_area_status = 'Active'";
                                                            $result_area = mysqli_query($conn, $sql);

                                                            if (!$result_area) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_area) > 0) {
                                                                // Output data of each row
                                                                while ($row_area = mysqli_fetch_assoc($result_area)) {
                                                                    $selected = $row_area['occur_area_name'] == $occur_area ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_area["occur_area_name"]) . '"' . $selected . '>' . htmlspecialchars($row_area["occur_area_name"]) . '</option>';
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
                                                            id="floatingTextarea" name="occur_description"><?php echo $row['occur_description']; ?></textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0">
                                                        <br>
                                                        <label for="floatingTextarea">Describe Interventions</label>
                                                        <textarea class="form-control" rows="10" cols="30" placeholder="Intervention"
                                                            id="floatingTextarea" name="occur_intervention"> <?php echo $row['occur_intervention']; ?></textarea>
                                                       
                                                    </div>
                                                </div>
                                            </div> <!-- close row -->
 
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                       <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="occur_employee_injury">
                                                            <option disabled>Did Employee Injury Result?</option>
                                                            <option value="Yes" <?php if ($row['occur_employee_injury'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['occur_employee_injury'] == 'No') echo 'selected'; ?>>No</option>
                                                            <option value="N/A" <?php if ($row['occur_employee_injury'] == 'N/A') echo 'selected'; ?>>N/A</option>
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
                                                            <option value="Yes" <?php if ($row['occur_patient_injury'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['occur_patient_injury'] == 'No') echo 'selected'; ?>>No</option>
                                                            <option value="N/A" <?php if ($row['occur_patient_injury'] == 'N/A') echo 'selected'; ?>>N/A</option>
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

                                            <div class="row">
                                                 <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_PRN>
                                                            <option disabled selected>PRN Meds Given?</option>
                                                            <option value="Yes" <?php if ($row['occur_PRN'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['occur_PRN'] == 'No') echo 'selected'; ?>>No</option>
                                                        </select>
                                                        <label for="floatingSelect">PRN Meds Given?</label>
                                                    </div>
                                                </div>
                                             
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_code>
                                                            <option disabled selected>Code Called?</option>
                                                            <option value="No" <?php if ($row['occur_code'] == 'No') echo 'selected'; ?>>No</option>
                                                            <?php
                                                            // $row['occur_code'] contains the current department value from query in edit file
                                                            $occur_code = $row['occur_code'];

                                                            // Query to fetch active departments
                                                            $sql = "SELECT * FROM occur_setup_codes WHERE setup_code_status = 'Active'";
                                                            $result_codes = mysqli_query($conn, $sql);

                                                            if (!$result_area) {
                                                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                                                            }

                                                            // Generate dropdown menu options from query results
                                                            if (mysqli_num_rows($result_area) > 0) {
                                                                // Output data of each row
                                                                while ($row_codes = mysqli_fetch_assoc($result_codes)) {
                                                                    $selected = $row_codes['occur_code'] == $occur_code ? ' selected="selected"' : '';
                                                                    echo '<option value="' . htmlspecialchars($row_codes["setup_code_long_name"]) . '"' . $selected . '>' . htmlspecialchars($row_codes["setup_code_long_name"]) . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="">No options available</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="floatingSelect">Code Called?</label>
                                                    </div>
                                                </div>
                                               
                                                </div> 
                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <label for="floatingTextarea">Staff/MD Present</label>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Note Staff/MD's present"
                                                            id="floatingTextarea" name="occur_staff"><?php echo $row['occur_staff']; ?></textarea>
                                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <label for="floatingTextarea">Notes on Code</label>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Additional detail on code"
                                                            id="floatingTextarea" name="code_notes"></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                
                                            </div> <!-- close row -->



                                        </div>
                                    </div>
                                </div> <!-- close card -->



<!-- FORM: SECLUSION / RESTRAINT  -------------------------------------- -->

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <h4 class="card-title">Seclusion / Restraint </h4>
                                            <p class="card-title-desc"></p>
                        

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=occur_patient_restraint>
                                                            <option disabled selected>Was the patient restrained?</option>
                                                            <option value="Yes" <?php if ($row['occur_patient_restraint'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['occur_patient_restraint'] == 'No') echo 'selected'; ?>>No</option>
                                                            <option value="N/A" <?php if ($row['occur_patient_restraint'] == 'N/A') echo 'selected'; ?>>N/A</option>
                                                        </select>
                                                        <label for="floatingSelect">Restraint</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="occur_patient_seclusion">
                                                            <option disabled selected>Was the patient Secluded?</option>
                                                            <option value="Yes" <?php if ($row['occur_patient_seclusion'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['occur_patient_seclusion'] == 'No') echo 'selected'; ?>>No</option>
                                                            <option value="N/A" <?php if ($row['occur_patient_seclusion'] == 'N/A') echo 'selected'; ?>>N/A</option>
                                                        </select>
                                                        <label for="floatingSelect">Seclusion</label>
                                                    </div>
                                                </div> 
                                            </div> <!-- close row -->

                                            <br>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Restraint Minutes" name="restraint_minutes"
                                                            value='<?php echo "{$row['restraint_minutes']}"; ?>'>
                                                        <label for="floatingInput">Restraint Time (in Minutes)</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="floatingInput"
                                                            placeholder="Seclusion Minutes" name="seclusion_minutes"
                                                            value='<?php echo "{$row['seclusion_minutes']}"; ?>'>
                                                        <label for="floatingInput">Seclusion Time (in Minutes)</label>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <!-- close row -->



                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Restraint Notes"
                                                            id="floatingTextarea" name="patient_restraint_notes"><?php echo $row['patient_restraint_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Seclusion Notes"
                                                            id="floatingTextarea" name="patient_seclusion_notes"><?php echo $row['patient_seclusion_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>

                                            </div> <!-- close row -->

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name=rs_notification>
                                                            <option disabled selected>Were required notifications completed?</option>
                                                            <option value="Yes" <?php if ($row['rs_notification'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['rs_notification'] == 'No') echo 'selected'; ?>>No</option>
                                                            <option value="N/A" <?php if ($row['rs_notification'] == 'N/A') echo 'selected'; ?>>N/A</option>
                                                        </select>
                                                        <label for="floatingSelect">Notification </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                     <br>
                                                    <div class="form-floating">
                                                        <select class="form-select" id="floatingSelect"
                                                            aria-label="Floating label select example" name="rs_documentation">
                                                            <option disabled selected>Seclusion/Restraint specific paperwork completed?</option>
                                                            <option value="Yes" <?php if ($row['rs_documentation'] == 'Yes') echo 'selected'; ?>>Yes</option>
                                                            <option value="No" <?php if ($row['rs_documentation'] == 'No') echo 'selected'; ?>>No</option>
                                                            <option value="N/A" <?php if ($row['rs_documentation'] == 'N/A') echo 'selected'; ?>>N/A</option>
                                                        </select>
                                                        <label for="floatingSelect">Documentation</label>
                                                    </div>
                                                </div> 
                                            </div> <!-- close row -->


                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Notification Detail:  Name / Time "
                                                            id="floatingTextarea" name="rs_notification_notes"><?php echo $row['rs_notification_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Additional Notes"
                                                            id="floatingTextarea" name="rs_additional_notes"><?php echo $row['rs_additional_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>

                                            </div> 
                                          <!-- close row -->



                                        </div>
                                    </div>
                                </div> <!-- close card -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                        
                                            <h4 class="card-title">Additional Notes / Notifications</h4>

                                            <div class="row">
                                                <div class="col-lg-6"> <br>
                                                    <div class="mb-3 mb-lg-0">
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Additional Notes / Followup"
                                                            id="floatingTextarea" name="occur_additional_notes"><?php echo $row['occur_additional_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3 mb-lg-0"> <br>
                                                        <textarea class="form-control" rows="5" cols="28" placeholder="Notification Detail:  Name / Time "
                                                            id="floatingTextarea" name="occur_notification_notes"><?php echo $row['occur_notification_notes']; ?></textarea>
                                                        <label for="floatingTextarea"></label>
                                                    </div>
                                                </div>
                                                

                                            </div> <!-- close row -->
                                        </div> <!-- close card body -->
                                    </div> <!-- end card -->
                                </div> <!-- end col -->
                            </div> <!-- end row -->



<!-- FORM:  TIMESTAMP FOR DATE SUBMITTED BY REPORTER   -------------------------------------- -->

                            <!-- Timestamp function is in file with the insert statement
                            <input type="hidden" name="reporting_timestamp" value="<//?php echo time(); ?>">
                            -->                            


                        </div>
                                            <br>

<!-- ========================================  PAGE SPECIFIC SCRIPTS   ======================================================== -->

                                                      