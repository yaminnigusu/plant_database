<?php
// Establish database connection
include("../config.php");

// Process form submission and add new plan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $completionDate = $_POST['completionDate'];
    $completed = isset($_POST['completed']) ? 1 : 0;

    // Prepare SQL statement to insert into appropriate table based on completion status
    if ($completed) {
        $sql = "INSERT INTO completed_plans (title, subject, description, completion_date) 
                VALUES ('$title', '$subject', '$description', '$completionDate')";
    } else {
        $sql = "INSERT INTO ongoing_plans (title, subject, description, completion_date) 
                VALUES ('$title', '$subject', '$description', '$completionDate')";
    }

    // Execute SQL statement and check for errors
    if ($conn->query($sql) === TRUE) {
        // Build HTML for displaying the added plan
        $planHtml = "<div class='card mb-3' data-id='{$conn->insert_id}'>
                        <div class='card-body'>
                            <h5 class='card-title'>$title</h5>
                            <p class='card-text'><strong>Subject:</strong> $subject</p>
                            <p class='card-text'><strong>Description:</strong> $description</p>
                            <p class='card-text'><strong>Completion Date:</strong> $completionDate</p>
                            <div class='form-check'>
                                <input type='checkbox' class='form-check-input complete-checkbox'>
                                <label class='form-check-label'>Completed</label>
                            </div>
                        </div>
                    </div>";

        // Return the HTML to display the added plan
        echo $planHtml;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>
