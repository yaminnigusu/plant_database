<?php
include("../config.php");

// Define archive file name based on the current year
$year = date('Y');
$archiveDir = "../archives";
$archiveFileName = "$archiveDir/cuttings_archive_$year.xml";

// Ensure archive directory exists
if (!is_dir($archiveDir)) {
    if (!mkdir($archiveDir, 0777, true)) {
        die("Failed to create directory: $archiveDir");
    }
}

// Fetch all records
$sql = "SELECT * FROM optional_plants";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create a new XML document
    $xml = new DOMDocument("1.0", "UTF-8");
    $xml->formatOutput = true;

    // Add root element
    $root = $xml->createElement("CuttingsArchive");
    $root = $xml->appendChild($root);

    // Add each record as an XML node
    while ($row = $result->fetch_assoc()) {
        $plantNode = $xml->createElement("Plant");
        foreach ($row as $key => $value) {
            $childNode = $xml->createElement($key, htmlspecialchars($value));
            $plantNode->appendChild($childNode);
        }
        $root->appendChild($plantNode);
    }

    // Save XML to file
    if ($xml->save($archiveFileName)) {
        echo "Archive created successfully: $archiveFileName\n";

        // Clear the table
        $deleteSQL = "TRUNCATE TABLE optional_plants";
        if ($conn->query($deleteSQL)) {
            echo "Table cleared successfully for the new year.\n";
        } else {
            echo "Error clearing table: " . $conn->error . "\n";
        }
    } else {
        echo "Error saving archive file.\n";
    }
} else {
    echo "No records found to archive.\n";
}

// Close the database connection
$conn->close();
?>
