        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title" id="followup">Open Reports Requiring Followup</h4>
                                        <p class="card-title-desc">Assigned to: <?php echo " " . $user_full_name_title; ?></p>

<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTableOpen"class="myTable table table-bordered table-condensed dt-responsive w-100">
                                                
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                        <!-- <th>Description</th> -->
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                          <th>Description</th>
                                                         <th>Action</th>

                                                         <!-- <th>Status</th> -->
                                                        
                                                       
                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Target Date</th>
                                                        
                                                        
                                                        <!-- <th>Intervention</th>-->

                                                       
                                                    </tr>
                                                </thead>        

                                            <tbody>
                                                <?php
                                                    for ($i = 0; $i < $numrows; $i++)
                                                    {
                                                        $row = mysqli_fetch_array($result);

                                                        echo "<tr>";
                                                            //echo "<td>{$row['occur_id']}</td>";
                                                            echo "<td><a href='mgr_review.php?id={$row['occur_id']}'>{$row['occur_id']}</a></td>";
                                                            // echo "<td>{$row['patient_last_name']}</td>";
                                                            // echo "<td>{$row['patient_MRN']}</td>";

                                                            //echo "<td>{$row['occur_date']}</td>";
                                                            echo "<td>";
                                                                if (!empty($row['occur_date'])) {
                                                                    echo date("m/d/y", strtotime($row['occur_date']));
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";
                                                            echo "<td>{$row['reporter_category']}</td>";
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            
                                                              echo "<td>{$row['occur_description']}</td>";
                                                           

                                                            echo "<td>";
                                                            $status = trim($row['manager_status']); // Remove any extra spaces
                                                            if ($status == 'Action Plan In Progress' || $status == 'Action Plan in Progress') {
                                                                echo '<span class="badge bg-success bg-soft text-success">In Progress</span>';
                                                            } elseif ($status == 'Pending Manager Review') {
                                                                echo '<span class="badge bg-primary bg-soft text-primary">Review</span>';
                                                            } else {
                                                                echo '<span class="badge bg-danger bg-soft text-danger">Action Needed</span>';
                                                            }
                                                            echo "</td>";                               


                                                            //echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";

                                                          

                                                            echo "<td>";
                                                                if (!empty($row['target_date'])) {
                                                                    $target_date = strtotime($row['target_date']);
                                                                    $current_date = strtotime(date('Y-m-d')); // Get current date without time
                                                                    
                                                                    if ($current_date > $target_date) {
                                                                        echo '<span class="past-due">' . date("m/d/y", $target_date) . '</span>';
                                                                    } else {
                                                                        echo date("m/d/y", $target_date);
                                                                    }
                                                                } else {
                                                                    echo ""; // Output blank if the value is null or empty
                                                                }
                                                            echo "</td>";

                                                          
                                                             //echo "<td>{$row['occur_intervention']}</td>";



                                                            //echo "<td><a href=\"occur_pdf.php?id={$row[0]}\"><i class=\"fas fa-file-pdf\"></i></a></td>";
                                                            //echo "<td><a href=\"mgr_review.php?id={$row[0]}\"><i class=\"fas fa-eye\"></i></a></td>";
                                                            //echo "<td><a href=\"edit_occur.php?id={$row[0]}\"><i class=\"fas fa-pencil-alt\"></i></a></td>";
                                                            //echo "<td><a href=\"delete_occur.php?id={$row[0]}\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                            
                                                            //echo "<td>{$row['reporter_severity']}</td>";

                                                            //fas fa-pencil-alt
                                                            
                                                        echo "</tr>";
                                                    }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <!-- end card-body -->
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        <br>
                        