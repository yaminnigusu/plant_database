<?php
// Establish database connection
include("../config.php");

// Get form data
$plantName = $_POST['plantName'];
$plantingTime = $_POST['plantingTime'];
$quantity = $_POST['quantity'];
$additionalInfo = $_POST['additionalInfo'];

// Prepare SQL statement
$sql = "INSERT INTO plant_planning (plant_name, planting_time, quantity, additional_info) 
        VALUES ('$plantName', '$plantingTime', $quantity, '$additionalInfo')";

// Execute SQL statement
if ($conn->query($sql) === TRUE) {
    echo "Plant planning saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close database connection
$conn->close();
?>
<?php
// Process form submission and add new plan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $plantName = $_POST['plantName'];
    $plantingTime = $_POST['plantingTime'];
    $quantity = $_POST['quantity'];
    $additionalInfo = $_POST['additionalInfo'];

    // Build HTML for displaying the added plan
    $planHtml = "<div class='plan'>
                    <h3>$plantName</h3>
                    <p><strong>Planting Time:</strong> $plantingTime</p>
                    <p><strong>Quantity:</strong> $quantity</p>
                    <p><strong>Additional Information:</strong> $additionalInfo</p>
                </div>";

    // Return the HTML to display the added plan
    echo $planHtml;
}
?>

