<?php
header('Content-Type: application/json');
include ("includes/occur_config.php");

//To implement AJAX to limit subcategories to those associated with category selection prior to submission

/* This is already in the config file
if (!$conn) {
    echo json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]);
    exit();
}
*/

if (isset($_POST['category'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $query = "SELECT setup_subcategory 
              FROM occur_setup_subcategory 
              WHERE setup_map_category = '$category'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
        exit();
    }
    
    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = $row;
    }

    echo json_encode($subcategories);
} else {
    echo json_encode(['error' => 'No category selected']);
}

mysqli_close($conn);
?>
