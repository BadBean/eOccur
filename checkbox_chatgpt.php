<div class="mb-3">
    <h5 class="font-size-14 mb-2">Custom Report Flags</h5>
    <div class="form-check mb-2">
        <?php
        // Assume $conn is your database connection

        // Retrieve the stored values for the checkboxes from the database
        $user_id = 1; // Replace with the appropriate user ID or other identifier
        $stored_values = array();

        // Query to get stored values (example query, replace with your actual query)
        $sql_stored_values = "SELECT stored_flags FROM user_flags WHERE user_id = $user_id";
        $result_stored_values = mysqli_query($conn, $sql_stored_values);

        if ($result_stored_values && mysqli_num_rows($result_stored_values) > 0) {
            $row_stored_values = mysqli_fetch_assoc($result_stored_values);
            $stored_values = explode(',', $row_stored_values['stored_flags']);
        }

        // FLAG SQL query to populate FLAG checkboxes based on user specific options
        $sql = "SELECT * FROM occur_setup_flags WHERE setup_flag_status = 'Active'";
        $result_flag = mysqli_query($conn, $sql);

        if (!$result_flag) {
            die("<p>Error in tables: " . mysqli_error($conn) . "</p>");
        }

        // Generate checkbox options from query results
        if (mysqli_num_rows($result_flag) > 0) {
            // output data of each row
            while ($row_flag = mysqli_fetch_assoc($result_flag)) {
                $isChecked = in_array($row_flag['setup_flag'], $stored_values) ? 'checked' : '';
                echo '<div class="form-check">';
                echo '<input class="form-check-input" type="checkbox" name="occur_flag[]" value="'.$row_flag['setup_flag'].'" '.$isChecked.'>';
                echo '<label class="form-check-label" for="occur_flag'.$row_flag['setup_flag'].'">'.$row_flag['setup_flag'].'</label>';
                echo '</div>';
            }
        } else {
            echo '<p>No options available</p>';
        }
        ?>
    </div>
</div>
