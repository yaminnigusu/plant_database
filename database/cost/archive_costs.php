<?php

// Constants for file paths
define('ARCHIVE_FOLDER', __DIR__ . '/archives/');
define('COST_FILE', __DIR__ . '/cost.php');

// Ensure the archive folder exists
if (!file_exists(ARCHIVE_FOLDER)) {
    mkdir(ARCHIVE_FOLDER, 0777, true);
}

// Function to generate archive on January 1st
function generateArchive() {
    $currentYear = date('Y');
    $previousYear = $currentYear - 1;
    $archiveFile = ARCHIVE_FOLDER . "costs_$previousYear.csv";

    // Fetch all cost data from the previous year
    $costData = fetchCostDataByYear($previousYear);

    if (empty($costData)) {
        echo "No cost data available for year $previousYear.";
        return;
    }

    // Prepare CSV content
    $csvContent = "Cost ID,Plant Name,Cost,Date\n";
    foreach ($costData as $row) {
        $csvContent .= implode(",", $row) . "\n";
    }

    // Add metadata at the end of the file
    $totalCost = array_sum(array_column($costData, 'cost'));
    $csvContent .= "\nTotal Costs,$totalCost\nNumber of Records," . count($costData) . "\nArchive Date," . date('Y-m-d H:i:s') . "\n";

    // Save to file
    file_put_contents($archiveFile, $csvContent);
    echo "Archive for $previousYear created successfully: $archiveFile\n";
}

// Fetch cost data by year
function fetchCostDataByYear($year) {
    // Simulate database fetch (replace with actual DB query)
    $mockData = [
        ['id' => 1, 'plant_name' => 'Rose', 'cost' => 100, 'date' => "$year-05-10"],
        ['id' => 2, 'plant_name' => 'Tulip', 'cost' => 150, 'date' => "$year-06-15"],
    ];

    return array_filter($mockData, function ($row) use ($year) {
        return strpos($row['date'], (string)$year) === 0;
    });
}

// Function to display archives
function displayArchives() {
    $files = glob(ARCHIVE_FOLDER . '*.csv');
    echo "<h1>Available Archives</h1>\n<ul>";
    foreach ($files as $file) {
        $year = basename($file, '.csv');
        echo "<li><a href='download.php?file=" . urlencode($file) . "'>$year</a></li>\n";
    }
    echo "</ul>";
}

// Function to calculate costs based on archived data
function calculateCosts() {
    $files = glob(ARCHIVE_FOLDER . '*.csv');
    $totalCosts = 0;

    foreach ($files as $file) {
        $handle = fopen($file, 'r');
        while (($data = fgetcsv($handle)) !== false) {
            if (is_numeric($data[2])) {
                $totalCosts += $data[2];
            }
        }
        fclose($handle);
    }

    return $totalCosts;
}

// Run archiving logic if it's January 1st
if (date('m-d') === '01-01') {
    generateArchive();
}

// Example usage
displayArchives();
$totalCosts = calculateCosts();
echo "<p>Total Costs from All Archives: $totalCosts</p>";

?>
