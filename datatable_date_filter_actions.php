

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo $datatable_name; ?></h4>
                                        <p class="card-title-desc"><?php echo $datatable_name_sub; ?>
                                        </p>
                                        <!-- Date filter inputs -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="startDate">Start Date:</label>
                                                <input type="date" id="startDate" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="endDate">End Date:</label>
                                                <input type="date" id="endDate" class="form-control">
                                            </div>
                                        </div>

                                        <br>
                                        <hr style="height: 3px; background-color: black; border: none;">
                                        
                                          <table id="myTable" class="table table-bordered dt-responsive table-hover table-condensed table-sm w-100">
                                            <thead>
                                                <tr>
                                                    <th>DATE</th>
                                                    <th>ID</th>
                                                    <!--<th>Time</th> -->
                                                    <!-- <th>Type</th> -->
                                                    <th>Category</th>
                                                    <th>Pt LName</th>
                                                    <!-- <th>MRN</th> -->
                                                    <th>Age</th>
                                                    <th>M/F</th>
                                                    <th>Unit</th>
                                                    <th>Location</th> 
                                                    <!-- <th>Program</th> -->
                                                    <th>Area</th> 
                                                  
                                                    <!-- <th>Subcategory</th> -->
                                                    <th>Severity</th>
                                                    <th>Injury?</th>
                                                    <th>Status</th>    
                                                    <!-- <th>Attending</th> -->
                                                   
                                                    <th>Actions</th>


                                                    <th>Description</th>
                                                    <th>Intervention</th>
                                                    <!-- <th>Time</th> -->

                                                    <th>Restraint</th>
                                                    <th>Seclusion</th>  
                                                    
                                                </tr>
                                            </thead>             

<!--  PHP FOR TABLE OUTPUT  ====================== -->
                                               
                                            <tbody>
                                            
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                            echo "<tr>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td><a href='pdf_report.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            //echo "<td>{$row['occur_time']}</td>";
                                                            //echo "<td style='white-space:nowrap'>{$row['occur_type']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['reporter_category']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['patient_last_name']}</td>";
                                                            //echo "<td>{$row['patient_MRN']}</td>";
                                                            echo "<td>{$row['patient_age']}</td>";
                                                            echo "<td>{$row['patient_gender']}</td>";
                                                            echo "<td>{$row['patient_unit']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_location']}</td>";
                                                            //echo "<td style='white-space:nowrap'>{$row['patient_program']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_area']}</td>";
                                                           
                                                            //echo "<td style='white-space:nowrap'>{$row['occur_subcategory']}</td>";
                                                            echo "<td>{$row['rm_severity']}</td>";
                                                            echo "<td>{$row['occur_patient_injury']}</td>";
                                                                
                                                            //echo "<td>{$row['md_attending']}</td>";
                                                            echo "<td>{$row['occur_status']}</td>";


                                                            echo "<td>
                                                                <div class=\"dropdown\">
                                                                    <a class=\"text-muted dropdown-toggle font-size-16\" role=\"button\"
                                                                        data-bs-toggle=\"dropdown\" aria-haspopup=\"true\">
                                                                        <i class=\"mdi mdi-dots-vertical\"></i>
                                                                    </a>
                                                                    <div class=\"dropdown-menu dropdown-menu-end\">
                                                                        <a class=\"dropdown-item\" href=\"occur_pdf.php?id={$row[0]}\">View/Print</a>
                                                                        <a class=\"dropdown-item\" href=\"edit_occur.php?id={$row[0]}\">Edit Report</a>
                                                                        <a class=\"dropdown-item\" href=\"rm_review.php?id={$row[0]}\">Mgmt Review</a>
                                                                        <a class=\"dropdown-item\" href=\"delete_occur.php?id={$row[0]}\">Delete</a>
                                                                    </div>
                                                                </div>
                                                            </td>";

                                                            echo "<td style='white-space:nowrap'>{$row['occur_description']}</td>";
                                                            echo "<td style='white-space:nowrap'>{$row['occur_intervention']}</td>";
                                                            //echo "<td>{$row['occur_time']}</td>";

                                                            echo "<td>{$row['occur_patient_restraint']}</td>";
                                                            echo "<td>{$row['occur_patient_seclusion']}</td>";

                                                           

                                                        echo "</tr>";
                                                    }
                                                ?>
<!--  END PHP  ====================== -->
                                            </tbody>
                                        </table>
        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <br>