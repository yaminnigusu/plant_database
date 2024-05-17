<?php
include("../config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $costDescription = mysqli_real_escape_string($conn, $_POST['costDescription']);
    $costAmount = mysqli_real_escape_string($conn, $_POST['costAmount']);

    $sql = "INSERT INTO costs (description, cost_amount) VALUES ('$costDescription', '$costAmount')";

    if ($conn->query($sql) === TRUE) {
        header("Location: cost.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
