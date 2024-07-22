<?php
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = mysqli_real_escape_string($conn, $_POST['costDescription']);
    $amount = mysqli_real_escape_string($conn, $_POST['costAmount']);
    $category = mysqli_real_escape_string($conn, $_POST['costCategory']);

    $sql_insert = "INSERT INTO costs (description, cost_amount, category) VALUES ('$description', '$amount', '$category')";

    if ($conn->query($sql_insert) === TRUE) {
        header("Location: cost.php");
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
