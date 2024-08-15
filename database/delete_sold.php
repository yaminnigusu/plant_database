<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the plant ID from the form submission
    $id = $_POST['id'];

    // Retrieve the data from the sold table before deleting it
    $stmt = $conn->prepare("SELECT plant_name, quantity_sold, sale_date, selling_price FROM sold WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($plant_name, $quantity_sold, $sale_date, $selling_price);
    $stmt->fetch();
    $stmt->close();

    // Check if the plant already exists in the plants table
    $check_stmt = $conn->prepare("SELECT id, quantity FROM plants WHERE plant_name = ?");
    $check_stmt->bind_param("s", $plant_name);
    $check_stmt->execute();
    $check_stmt->bind_result($plant_id, $existing_quantity);
    $plant_exists = $check_stmt->fetch();
    $check_stmt->close();

    if ($plant_exists) {
        // Update the existing plant's quantity
        $new_quantity = $existing_quantity + $quantity_sold;
        $update_stmt = $conn->prepare("UPDATE plants SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_quantity, $plant_id);

        if ($update_stmt->execute()) {
            // Delete the record from the sold table
            $delete_stmt = $conn->prepare("DELETE FROM sold WHERE id = ?");
            $delete_stmt->bind_param("i", $id);

            if ($delete_stmt->execute()) {
                // Redirect back to the sold.php page after successful deletion
                header("Location: sold.php");
                exit();
            } else {
                echo "Error deleting record: " . $conn->error;
            }

            $delete_stmt->close();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $update_stmt->close();
    } else {
        // Insert the data back into the plants table as a new row
        $insert_stmt = $conn->prepare("INSERT INTO plants (plant_name, quantity, plantation_date, value, cost_per_plant, selling_price) VALUES (?, ?, NOW(), ?, ?, ?)");
        $insert_stmt->bind_param("sidsd", $plant_name, $quantity_sold, $sale_date, $cost_per_plant, $selling_price);

        if ($insert_stmt->execute()) {
            // Delete the record from the sold table
            $delete_stmt = $conn->prepare("DELETE FROM sold WHERE id = ?");
            $delete_stmt->bind_param("i", $id);

            if ($delete_stmt->execute()) {
                // Redirect back to the sold.php page after successful deletion
                header("Location: sold.php");
                exit();
            } else {
                echo "Error deleting record: " . $conn->error;
            }

            $delete_stmt->close();
        } else {
            echo "Error inserting record: " . $conn->error;
        }

        $insert_stmt->close();
    }
}

$conn->close();
?>
