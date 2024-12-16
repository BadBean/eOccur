<?php
include("includes/occur_config.php");


// Retrieve the reporter_category from POST data
if (isset($_POST['reporter_category'])) {
    $reporter_category = mysqli_real_escape_string($conn, $_POST['reporter_category']);
} else {
    // Handle the case where reporter_category is not set
    die("Error: reporter_category not specified.");
}

// Set headers to indicate the file will be a CSV and trigger download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="trend_data_export.csv"');

// Open output stream for writing CSV data
$output = fopen('php://output', 'w');

// Generate a list of the last 12 months in 'Y-m' format
$months = [];
for ($i = 0; $i < 12; $i++) {
    $months[] = date('Y-m', strtotime("-$i months"));
}
$months = array_reverse($months); // Reverse to display in ascending order

// Function to export data block to CSV
function export_data_block_csv($label, $data, $months, $output) {
    // Write the block header
    fputcsv($output, [$label]);

    // Write the month headers
    $headers = array_merge(['Category'], $months);
    fputcsv($output, $headers);

    // Write the data rows
    foreach ($data as $group_name => $counts_by_month) {
        $row = [$group_name];
        foreach ($months as $month) {
            $row[] = isset($counts_by_month[$month]) ? $counts_by_month[$month] : 0;
        }
        fputcsv($output, $row);
    }

    // Add a blank line for spacing between different data blocks
    fputcsv($output, []);
}

/** Fetch Location Data **/
$sql_location_trend = "SELECT occur_location, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as location_count 
                       FROM occur
                       WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                       AND reporter_category = '$reporter_category'
                       GROUP BY occur_location, report_month
                       ORDER BY report_month ASC";

$result_location_trend = mysqli_query($conn, $sql_location_trend);

$location_data = [];
if (mysqli_num_rows($result_location_trend) > 0) {
    while ($row_location = mysqli_fetch_assoc($result_location_trend)) {
        $location = $row_location['occur_location'];
        $month = $row_location['report_month'];
        $count = $row_location['location_count'];
        $location_data[$location][$month] = $count;
    }
}
mysqli_free_result($result_location_trend);

// Export Location data to CSV
export_data_block_csv("Location", $location_data, $months, $output);


/** Fetch Patient Program Data **/
$sql_patient_program = "SELECT patient_program, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as program_count 
                        FROM occur
                        WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                        AND reporter_category = '$reporter_category'
                        GROUP BY patient_program, report_month
                        ORDER BY report_month ASC";

$result_patient_program = mysqli_query($conn, $sql_patient_program);

$program_data = [];
if (mysqli_num_rows($result_patient_program) > 0) {
    while ($row_program = mysqli_fetch_assoc($result_patient_program)) {
        $program = $row_program['patient_program'];
        $month = $row_program['report_month'];
        $count = $row_program['program_count'];
        $program_data[$program][$month] = $count;
    }
}
mysqli_free_result($result_patient_program);

// Export Program data to CSV
export_data_block_csv("Program", $program_data, $months, $output);


/** Fetch Occur Area Data **/
$sql_occur_area = "SELECT occur_area, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as area_count 
                  FROM occur
                  WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  AND reporter_category = '$reporter_category'
                  GROUP BY occur_area, report_month
                  ORDER BY report_month ASC";

$result_occur_area = mysqli_query($conn, $sql_occur_area);

$area_data = [];
if (mysqli_num_rows($result_occur_area) > 0) {
    while ($row_area = mysqli_fetch_assoc($result_occur_area)) {
        $area = $row_area['occur_area'];
        $month = $row_area['report_month'];
        $count = $row_area['area_count'];
        $area_data[$area][$month] = $count;
    }
}
mysqli_free_result($result_occur_area);

// Export Area data to CSV
export_data_block_csv("Area", $area_data, $months, $output);


/** Fetch Attending MD Data **/
$sql_md_attending = "SELECT MD_Attending, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as md_count 
                     FROM occur
                     WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                     AND reporter_category = '$reporter_category'
                     GROUP BY MD_Attending, report_month
                     ORDER BY report_month ASC";

$result_md_attending = mysqli_query($conn, $sql_md_attending);

$md_data = [];
if (mysqli_num_rows($result_md_attending) > 0) {
    while ($row_md = mysqli_fetch_assoc($result_md_attending)) {
        $md = $row_md['MD_Attending'];
        $month = $row_md['report_month'];
        $count = $row_md['md_count'];
        $md_data[$md][$month] = $count;
    }
}
mysqli_free_result($result_md_attending);

// Export Attending MD data to CSV
export_data_block_csv("Attending MD", $md_data, $months, $output);


/** Fetch Patient Unit Data **/
$sql_patient_unit = "SELECT patient_unit, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as unit_count 
                     FROM occur
                     WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                     AND reporter_category = '$reporter_category'
                     GROUP BY patient_unit, report_month
                     ORDER BY report_month ASC";

$result_patient_unit = mysqli_query($conn, $sql_patient_unit);

$unit_data = [];
if (mysqli_num_rows($result_patient_unit) > 0) {
    while ($row_unit = mysqli_fetch_assoc($result_patient_unit)) {
        $unit = $row_unit['patient_unit'];
        $month = $row_unit['report_month'];
        $count = $row_unit['unit_count'];
        $unit_data[$unit][$month] = $count;
    }
}
mysqli_free_result($result_patient_unit);

// Export Patient Unit data to CSV
export_data_block_csv("Patient Unit", $unit_data, $months, $output);


/** Fetch Severity Data **/
$sql_severity = "SELECT rm_severity, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as severity_count 
                 FROM occur
                 WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                 AND reporter_category = '$reporter_category'
                 GROUP BY rm_severity, report_month
                 ORDER BY report_month ASC";

$result_severity = mysqli_query($conn, $sql_severity);

$severity_data = [];
if (mysqli_num_rows($result_severity) > 0) {
    while ($row_severity = mysqli_fetch_assoc($result_severity)) {
        $severity = $row_severity['rm_severity'];
        $month = $row_severity['report_month'];
        $count = $row_severity['severity_count'];
        $severity_data[$severity][$month] = $count;
    }
}
mysqli_free_result($result_severity);

// Export Severity data to CSV
export_data_block_csv("Severity", $severity_data, $months, $output);


/** Fetch Subcategory Data **/
$sql_subcategory = "SELECT occur_subcategory, DATE_FORMAT(occur_date, '%Y-%m') as report_month, COUNT(*) as subcategory_count 
                    FROM occur
                    WHERE occur_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                    AND reporter_category = '$reporter_category'
                    GROUP BY occur_subcategory, report_month
                    ORDER BY report_month ASC";

$result_subcategory = mysqli_query($conn, $sql_subcategory);

$subcategory_data = [];
if (mysqli_num_rows($result_subcategory) > 0) {
    while ($row_subcategory = mysqli_fetch_assoc($result_subcategory)) {
        $subcategory = $row_subcategory['occur_subcategory'];
        $month = $row_subcategory['report_month'];
        $count = $row_subcategory['subcategory_count'];
        $subcategory_data[$subcategory][$month] = $count;
    }
}
mysqli_free_result($result_subcategory);

// Export Subcategory data to CSV
export_data_block_csv("Subcategory", $subcategory_data, $months, $output);


// Close output stream
fclose($output);
exit;
?>
