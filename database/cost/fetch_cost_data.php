<?php
include("../config.php");

// Retrieve search and category parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build the query
$sql = "SELECT id, description, cost_amount, created_at, category FROM costs WHERE 1=1";

if ($search) {
    // Sanitize search input to prevent SQL injection
    $sql .= " AND description LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}

if ($category) {
    // Sanitize category input to prevent SQL injection
    $sql .= " AND category = '" . mysqli_real_escape_string($conn, $category) . "'";
}

// Execute the query
$result = $conn->query($sql);

// Prepare output
$output = '';
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '<tr>';
            $output .= '<td>' . htmlspecialchars($row['description']) . '</td>';
            $output .= '<td>' . number_format($row['cost_amount'], 2) . ' Birr</td>';
            $output .= '<td>' . htmlspecialchars($row['created_at']) . '</td>';
            $output .= '<td>' . htmlspecialchars($row['category']) . '</td>';
            $output .= '<td>';
            $output .= '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">Edit</a>';
            $output .= '<a href="cost.php?action=delete&id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this cost?\')">Delete</a>';
            $output .= '</td>';
            $output .= '</tr>';
        }
    } else {
        $output .= '<tr><td colspan="5">No costs found.</td></tr>';
    }
} else {
    // Log SQL error if query fails
    echo "Error executing query: " . $conn->error;
}

// Echo the output
echo $output;

// Close the database connection
$conn->close();
?>
