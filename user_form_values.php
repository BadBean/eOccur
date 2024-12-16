<!-- ======================================== FORM FIELDS  ======================================================================== -->


<!-- ========================================  BOOTSTRAP ROW:   ==================================================================== -->

                       
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">USER DETAIL</h4>
                                        <p class="card-title-desc"></p>
                    
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput"
                                                        placeholder="Last Name" name="user_last_name" value='<?php echo "{$row['user_last_name']}"; ?>'>
                                                    <label for="floatingInput">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput" value='<?php echo "{$row['user_first_name']}"; ?>'
                                                        placeholder="First Name" name="user_first_name">
                                                    <label for="floatingInput">First Name</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="userTitle" 
                                                           value='<?php echo "{$row['user_title']}"; ?>'
                                                           placeholder="Title" 
                                                           name="user_title">
                                                    <label for="userTitle">Title</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="email" 
                                                           class="form-control" 
                                                           id="userEmail" 
                                                           value='<?php echo "{$row['user_email']}"; ?>'
                                                           placeholder="Email" 
                                                           name="user_email"
                                                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                                           required>
                                                    <label for="userEmail">Email</label>
                                                    <div class="invalid-feedback">
                                                        Please enter a valid email address (example@domain.com)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="tel" 
                                                           class="form-control" 
                                                           id="userWorkPhone" 
                                                           value='<?php echo "{$row['user_phone']}"; ?>'
                                                           placeholder="(123) 456-7890" 
                                                           name="user_phone"
                                                           pattern="\(\d{3}\)\s\d{3}-\d{4}"
                                                           required>
                                                    <label for="userWorkPhone">Work Phone</label>
                                                    <div class="invalid-feedback">
                                                        Please enter a valid phone number
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-3">
                                                    <input type="tel" 
                                                           class="form-control" 
                                                           id="userCellPhone" 
                                                           value='<?php echo "{$row['user_cell']}"; ?>'
                                                           placeholder="(123) 456-7890" 
                                                           name="user_cell"
                                                           pattern="\(\d{3}\)\s\d{3}-\d{4}"
                                                           required>
                                                    <label for="userCellPhone">Cell Phone</label>
                                                    <div class="invalid-feedback">
                                                        Please enter a valid phone number
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                          <div class="col-lg-6">
                                              <div class="form-floating">
                                                  <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="user_department">
                                                      <option disabled>Select User Department</option>
                                                      <?php
                                                      // DEPARTMENT SQL query to populate DEPARTMENT dropdowns based on user-specific options
                                                      $sql = "SELECT * FROM occur_setup_department WHERE setup_department_status = 'Active'";
                                                      $result_department = mysqli_query($conn, $sql);

                                                      if (!$result_department) {
                                                          die("<p>Error in department query: " . mysqli_error($conn) . "</p>");
                                                      }

                                                      // Generate dropdown menu options from query results
                                                      if (mysqli_num_rows($result_department) > 0) {
                                                          // Output data of each row
                                                          while ($row_department = mysqli_fetch_assoc($result_department)) {
                                                              $selected = ($row_department["setup_department_description"] == $row['user_department']) ? 'selected' : '';
                                                              echo '<option value="'.$row_department["setup_department_description"].'" '.$selected.'>'.$row_department["setup_department_description"].'</option>';
                                                          }
                                                      } else {
                                                          echo '<option value="">No options available</option>';
                                                      }
                                                      ?>
                                                  </select>
                                                  <label for="floatingSelect">User Department</label>
                                              </div>
                                          </div> <!-- end column -->
                   



                                     
                                        <br>

                                        
                                            <div class="col-lg-6">
                                                <div class="form-floating">
                                                    <select class="form-select" id="floatingSelect"
                                                        aria-label="Floating label select example" name=user_supervisor>
                                                        <option disabled selected>User Supervisor</option>
                                                        <?php
                                                        /*
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
                                                        */    
                                                        ?>
                                                    </select>
                                                    <label for="floatingSelect">User Supervisor</label>
                                                </div>
                                            </div>  <!-- end column -->
                                        </div> <!-- end row -->
                                        <br>




                                        <div class="row">
                                           <div class="col-lg-6">
                                              <div class="form-floating">
                                                  <select class="form-select" id="floatingSelect" aria-label="Floating label select example" name="user_role">
                                                      <option disabled>Select User Role</option>
                                                      <?php
                                                      // USER ROLE SQL query to populate ROLE dropdowns based on user-specific options
                                                      $sql = "SELECT * FROM user_setup_roles WHERE role_status = 'Active'";
                                                      $result_role = mysqli_query($conn, $sql);

                                                      if (!$result_role) {
                                                          die("<p>Error in department query: " . mysqli_error($conn) . "</p>");
                                                      }

                                                      // Generate dropdown menu options from query results
                                                      if (mysqli_num_rows($result_role) > 0) {
                                                          // Output data of each row
                                                          while ($row_role = mysqli_fetch_assoc($result_role)) {
                                                              $selected = ($row_role["setup_user_role"] == $row_role['user_role']) ? 'selected' : '';
                                                              echo '<option value="'.$row_role["setup_user_role"].'" '.$selected.'>'.$row_role["setup_user_role"].'</option>';
                                                          }
                                                      } else {
                                                          echo '<option value="">No options available</option>';
                                                      }
                                                      ?>
                                                  </select>
                                                  <label for="floatingSelect">User Role</label>
                                              </div>
                                          </div> <!-- end column -->

                                          <div class="col-lg-6">
                                              <div class="form-floating">
                                                  <select class="form-select" id="floatingSelectStatus" aria-label="Floating label select example" name="user_status">
                                                      <option disabled>Select Status</option> 
                                                      <option value="Active" <?php echo ($row['user_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                      <option value="Inactive" <?php echo ($row['user_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                      <option value="Archive" <?php echo ($row['user_status'] == 'Archive') ? 'selected' : ''; ?>>Archive</option>
                                                  </select>
                                                  <label for="floatingSelectStatus">User Status</label>
                                              </div>
                                          </div> <!-- end column -->


                
                                        
                                      </div> <!-- end row -->


                                    </div> <!-- card body -->
                                </div> <!-- card -->
                            </div>
                        </div>


<!-- ========================================  BOOTSTRAP ROW:   ==================================================================== -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="card-title">ACCESS DETAIL</h4>
                                        <p class="card-title-desc"></p>



                                                                          <div class="mb-4">
                                        <h5 class="font-size-14 mb-2">SuperUser Access</h5>
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="super_user" value="Yes" <?php if ($row['super_user'] == 'Yes') echo 'checked'; ?>>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="super_user" value="No" <?php if ($row['super_user'] == 'No') echo 'checked'; ?>>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card body -->
                                </div>
                            </div>
                        </div>

<!-- Explode checkbox arrays to populate existing values -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">                        
                                            
                                    <div class="mb-3">
                                        <h5 class="font-size-14 mb-2">Access Levels</h5>
                                        <div class="form-check mb-2">
                                            <?php
                                                // SQL query to populate factor checkboxes based on user-specific options
                                                $sql = "SELECT * FROM user_setup_access";
                                                $result_access = mysqli_query($conn, $sql);
                                                if (!$result_access) { 
                                                    die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
                                                }

                                                 // Explode checkbox arrays to populate existing values
                                                $user_access_A = explode(',', $row['user_access_A']);
                                        
                                                // Generate checkbox options from query results
                                                if (mysqli_num_rows($result_access) > 0) {
                                                    // Output data of each row
                                                    while ($row_access = mysqli_fetch_assoc($result_access)) {
                                                        $isChecked = in_array($row_access['access_levels'], $user_access_A) ? 'checked' : '';
                                                        echo '<div class="form-check">';
                                                        echo '<input class="form-check-input" type="checkbox" name="user_access_A[]" value="'.$row_access["access_levels"].'" '.$isChecked.'>';
                                                        echo '<label class="form-check-label" for="user_access_A'.$row_access["access_levels"].'">'.$row_access["access_levels"].'</label>';
                                                        echo '</div>';
                                                    }
                                                } else {
                                                    echo '<p>No options available</p>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<!-- ========================================  BOOTSTRAP ROW: CONFIGURE NOTIFICATIONS  ==================================================================== -->

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">CONFIGURE NOTIFICATIONS / ROUTING</h4>
            <p class="card-title-desc"></p>

            <div class="row">
                <!-- First Column -->
                <div class="col-lg-4">
                    <div class="mb-3">
                        <h5 class="font-size-14 mb-2">BASED ON TYPE</h5>
                        <div class="form-check mb-2">
                            <?php
                            // SQL query to populate factor checkboxes based on user-specific options
                            $sql = "SELECT * FROM occur_setup_type";
                            $result_type = mysqli_query($conn, $sql);
                            if (!$result_type) { 
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>"); 
                            }

                            // Explode checkbox arrays to populate existing values
                            $notify_type = explode(',', $row['notify_type']);

                            // Generate checkbox options from query results
                            if (mysqli_num_rows($result_type) > 0) {
                                // Output data of each row
                                while ($row_type = mysqli_fetch_assoc($result_type)) {
                                    $isChecked = in_array($row_type['setup_type'], $notify_type) ? 'checked' : '';
                                    echo '<div class="form-check">';
                                    echo '<input class="form-check-input" type="checkbox" name="notify_type[]" value="'.$row_type["setup_type"].'" '.$isChecked.'>';
                                    echo '<label class="form-check-label" for="notify_type'.$row_type["setup_type"].'">'.$row_type["setup_type"].'</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end form-check -->
                    </div> <!-- end mb-3 -->
                    <br>

                    <div class="mb-3">
                        <h5 class="font-size-14 mb-2">BASED ON SEVERITY</h5>
                        <div class="form-check mb-2">
                            <?php
                            // SQL query to populate SEVERITY options based on user-specific options
                            $sql = "SELECT * FROM occur_setup_severity WHERE severity_status = 'Active'";
                            $result_severity = mysqli_query($conn, $sql);
                            if (!$result_severity) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            // Explode checkbox arrays to populate existing values
                            $notify_severity = isset($row['notify_severity']) ? explode(',', $row['notify_severity']) : [];

                            // Generate checklist from query results
                            if (mysqli_num_rows($result_severity) > 0) {
                                while ($row_severity = mysqli_fetch_assoc($result_severity)) {
                                    $isChecked = in_array($row_severity['setup_severity'], $notify_severity) ? 'checked' : '';

                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="severity_' . htmlspecialchars($row_severity["setup_severity"]) . '" name="notify_severity[]" value="' . htmlspecialchars($row_severity["setup_severity"]) . '" ' . $isChecked . '>';
                                    echo '<label class="form-check-label" for="severity_' . htmlspecialchars($row_severity["setup_severity"]) . '">' . htmlspecialchars($row_severity["setup_severity"]) . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end form-check -->
                    </div> <!-- end mb-3 -->
                    <br>
                    
                    <div class="mb-3">
                        <h5 class="font-size-14 mb-2">BASED ON FLAG</h5>
                        <div class="form-check mb-4">
                            <?php
                            // SQL query to populate FLAG options based on user-specific options
                            $sql = "SELECT * FROM occur_setup_flags WHERE setup_flag_status = 'Active'";
                            $result_flag = mysqli_query($conn, $sql);
                            if (!$result_flag) {
                                die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                            }

                            // Explode checkbox arrays to populate existing values
                            $notify_flag = isset($row['notify_flag']) ? explode(',', $row['notify_flag']) : [];

                            // Generate checklist from query results
                            if (mysqli_num_rows($result_flag) > 0) {
                                while ($row_flag = mysqli_fetch_assoc($result_flag)) {
                                    $isChecked = in_array($row_flag['setup_flag'], $notify_flag) ? 'checked' : '';

                                    echo '<div class="form-check mb-2">';
                                    echo '<input class="form-check-input" type="checkbox" id="flag_' . htmlspecialchars($row_flag["setup_flag"]) . '" name="notify_flag[]" value="' . htmlspecialchars($row_flag["setup_flag"]) . '" ' . $isChecked . '>';
                                    echo '<label class="form-check-label" for="flag_' . htmlspecialchars($row_flag["setup_flag"]) . '">' . htmlspecialchars($row_flag["setup_flag"]) . '</label>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No options available</p>';
                            }
                            ?>
                        </div> <!-- end form-check -->
                    </div> <!-- end mb-3 -->
                </div> <!-- end col-lg-3 -->

                <!-- Third Column -->
                <div class="col-lg-4">
                    <div class="mb-4">
                        <h5 class="font-size-14 mb-2">BASED ON CATEGORY</h5>
                        <?php
                        // SQL query to populate CATEGORY options based on user-specific options
                        $sql = "SELECT * FROM occur_setup_category WHERE category_status = 'Active'";
                        $result_category = mysqli_query($conn, $sql);
                        if (!$result_category) {
                            die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                        }

                        // Explode checkbox arrays to populate existing values
                        $notify_category = isset($row['notify_category']) ? explode(',', $row['notify_category']) : [];

                        // Generate checklist from query results
                        if (mysqli_num_rows($result_category) > 0) {
                            while ($row_category = mysqli_fetch_assoc($result_category)) {
                                $isChecked = in_array($row_category['setup_category'], $notify_category) ? 'checked' : '';

                                echo '<div class="form-check mb-2">';
                                echo '<input class="form-check-input" type="checkbox" id="category_' . htmlspecialchars($row_category["setup_category"]) . '" name="notify_category[]" value="' . htmlspecialchars($row_category["setup_category"]) . '" ' . $isChecked . '>';
                                echo '<label class="form-check-label" for="category_' . htmlspecialchars($row_category["setup_category"]) . '">' . htmlspecialchars($row_category["setup_category"]) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No options available</p>';
                        }
                        ?>
                    </div> <!-- end mb-4 -->
                </div> <!-- end col-lg-3 -->


                <!-- Third Column -->
                <div class="col-lg-3">
                    <div class="mb-4">
                        <h5 class="font-size-14 mb-2">BASED ON CONTRIBUTING FACTORS</h5>
                        <?php
                        // SQL query to populate CATEGORY options based on user-specific options
                        $sql = "SELECT * FROM occur_setup_factors WHERE setup_factor_status = 'Active'";
                        $result_factor = mysqli_query($conn, $sql);
                        if (!$result_factor) {
                            die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                        }

                        // Explode checkbox arrays to populate existing values
                        $notify_factor = isset($row['notify_factor']) ? explode(',', $row['notify_factor']) : [];

                        // Generate checklist from query results
                        if (mysqli_num_rows($result_factor) > 0) {
                            while ($row_factor = mysqli_fetch_assoc($result_factor)) {
                                $isChecked = in_array($row_factor['setup_factor'], $notify_factor) ? 'checked' : '';

                                echo '<div class="form-check mb-2">';
                                echo '<input class="form-check-input" type="checkbox" id="factor_' . htmlspecialchars($row_factor["setup_factor"]) . '" name="notify_factor[]" value="' . htmlspecialchars($row_factor["setup_factor"]) . '" ' . $isChecked . '>';
                                echo '<label class="form-check-label" for="factor_' . htmlspecialchars($row_factor["setup_factor"]) . '">' . htmlspecialchars($row_factor["setup_factor"]) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No options available</p>';
                        }
                        ?>
                    </div> <!-- end mb-4 -->
                    <div class="mb-4">
                        <h5 class="font-size-14 mb-2">BASED ON LOCATION</h5>
                        <?php
                        // SQL query to populate CATEGORY options based on user-specific options
                        $sql = "SELECT * FROM occur_setup_location WHERE setup_location_status = 'Active'";
                        $result_location = mysqli_query($conn, $sql);
                        if (!$result_location) {
                            die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
                        }

                        // Explode checkbox arrays to populate existing values
                        $notify_location = isset($row['notify_location']) ? explode(',', $row['notify_location']) : [];

                        // Generate checklist from query results
                        if (mysqli_num_rows($result_location) > 0) {
                            while ($row_location = mysqli_fetch_assoc($result_location)) {
                                $isChecked = in_array($row_location['setup_location_name'], $notify_location) ? 'checked' : '';

                                echo '<div class="form-check mb-2">';
                                echo '<input class="form-check-input" type="checkbox" id="location_' . htmlspecialchars($row_location["setup_location_name"]) . '" name="notify_location[]" value="' . htmlspecialchars($row_location["setup_location_name"]) . '" ' . $isChecked . '>';
                                echo '<label class="form-check-label" for="location_' . htmlspecialchars($row_location["setup_location_name"]) . '">' . htmlspecialchars($row_location["setup_location_name"]) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No options available</p>';
                        }
                        ?>
                    </div> <!-- end mb-4 -->

                </div> <!-- end col-lg-3 -->

            </div> <!-- end row -->
        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end col-12 -->

<!-- ========================================  BOOTSTRAP ROW: CONFIGURE NOTIFICATIONS  ==================================================================== -->

<script>
    // Phone number formatting for both work and cell phones
    document.getElementById('userWorkPhone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    document.getElementById('userCellPhone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    // Email validation on blur/tab out
    document.getElementById('userEmail').addEventListener('blur', function(e) {
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
