                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <div card bg-primary border-primary>
                                        <h4 class="card-title" id="in-progress">Action Plans in Progress</h4>
                                        <p class="card-title-desc">Assigned to: <?php echo " " . $user_full_name_title; ?></p>
                                        </div> <!-- close card color -->
<!--      Note:  to add buttons back add id=datatable-buttons to the table tag below                                   
                                        <p>Use the buttons below to copy entries to clipboard, export to Excel or PDF, or change column visibility</p>
-->                                            <table id="myTablePlan" class="table table-bordered table-condensed dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <!-- <th>PT NAME</th>  -->
                                                        <!-- <th>MRN</th> -->

                                                        <th>Date</th>
                                                        <th>Category</th>
                                                        <th>Action Plan</th>
                                                        <th>Notes</th>
                                                        <!-- <th>Severity</th> -->
                                                        
                                                       
                                                        
                                                        <!-- <th>Unit</th> -->
                                                        <!-- <th>Program</th> -->
                                                        <!-- <th>Status</th> -->
                                                        <!--<th>Description</th> -->
                                                        <!-- <th>Intervention</th>-->
                                                        <!-- <th>Mgr Status</th> -->

                                                        <!--<th>Manager Followup</th> -->
                                                        <th>Target Date</th>
                                                        <th>Description</th>
                                                        <th>Intervention</th>
                                                        
                                                        <!-- <th>Print</th> -->
                                                        <!-- <th>Review</th> -->
                                                        <!-- <th>Edit</th> -->
                                                        <!-- <th>Delete</th> -->
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
                                                            echo "<td>{$row['manager_followup_plan']}</td>";
                                                            echo "<td>{$row['manager_followup_notes']}</td>";                                                            
                                                            //echo "<td>{$row['rm_severity']}</td>";
                                                            
                                                            //echo "<td>{$row['occur_description']}</td>";
                                                            
                                                            // echo "<td>{$row['patient_unit']}</td>";
                                                            // echo "<td>{$row['patient_program']}</td>";
                                                            //echo "<td>{$row['occur_status']}</td>";
                                                            
                                                            //echo "<td>{$row['manager_status']}</td>";
                                                            

                                                            //echo "<td>{$row['manager_followup_name']}</td>";
                                                        echo "<td>";
    if (!empty($row['target_date'])) {
        $target_date = strtotime($row['target_date']);
        $current_date = strtotime(date('Y-m-d'));
        
        $class = ($current_date > $target_date) ? 'past-due' : '';
        echo '<span class="' . $class . '">' . date("m/d/y", $target_date) . '</span>';
    } else {
        echo "";
    }
echo "</td>";
                                                            echo "<td>{$row['occur_description']}</td>";
                                                            echo "<td>{$row['occur_intervention']}</td>";
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
