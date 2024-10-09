<?php
session_start(); // Start the session to use session variables
include("../database/config.php");

// Get plant information from the query parameters
$plantId = $_GET['plant_id'] ?? '';  // Fetch plant_id from query parameters
$plantName = $_GET['plant_name'] ?? ''; // Fetch plant_name from query parameters

// Initialize variables
$plants = [];
$availableQuantity = 0;
$totalAmount = 0;

// Generate a unique transaction number
$transactionNumber = strtoupper(uniqid('TXN-'));

// Check if plantId and plantName are provided
if ($plantId && $plantName) {
    // Fetch sum of quantity for all records of the specific plant
    $stmt = $conn->prepare("SELECT SUM(quantity) AS total_quantity, plant_name, photo_path, value, GROUP_CONCAT(plant_type) AS plant_types FROM plants WHERE plant_name = ?");
    $stmt->bind_param("s", $plantName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Populate $plants array with fetched plant data
        while ($row = $result->fetch_assoc()) {
            $plantTypes = explode(',', $row['plant_types']);
            $costPerPlant = $row['value'] / $row['total_quantity'];

            // Calculate total quantity and apply 65%, then round down
            $totalQuantity = (int)$row['total_quantity'];
            $reducedQuantity = floor($totalQuantity * 0.65); // 65% of total quantity, rounded down

            // Create plant array with required data
            $plants[] = [
                'plant_name' => htmlspecialchars($row['plant_name']),
                'photo_path' => htmlspecialchars($row['photo_path']),
                'quantity' => $reducedQuantity,
                'plant_type' => $plantTypes,
            ];

            // Set available quantity and total amount for the order form
            $availableQuantity = $reducedQuantity;
            // Calculate the selling price based on your previous logic in shop.php
            $additionalCostPerPlant = 0; // Set this according to your logic
            $profitMargin = 2.5; // Adjust as needed
            // Get selling price from the query parameters
            $sellingPrice = $_GET['selling_price'] ?? 0; // Default to 0 if not set
        }
    } else {
        echo "No records found for the specified plant.";
    }
    $stmt->close();
} else {
    echo "Error: Plant ID or Name is missing in the URL.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="icon" href="../images/logo.png" type="image/jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> <!-- Flatpickr CSS -->
    <style>
    body {
        background: linear-gradient(to right, #e2f0d9, #c2e1b8);
        font-family: 'Arial', sans-serif;
    }
    .container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        padding: 30px;
        margin-top: 50px;
        transition: all 0.3s ease;
    }
    .container:hover {
        transform: scale(1.02);
    }
    h1 {
        color: #28a745;
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 20px;
    }
    .order-summary {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        border-left: 5px solid #28a745;
        font-weight: bold;
    }
    .btn-success {
        background-color: #28a745;
        border: none;
        font-size: 1.25rem;
    }
    .btn-success:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
    .additional-link {
        margin-top: 20px;
        text-align: center;
        font-weight: bold;
    }
    .additional-link a {
        color: #007bff;
        text-decoration: none;
    }
    .additional-link a:hover {
        text-decoration: underline;
    }
    /* Input Field Styles */
    .form-control {
        border: 1px solid #28a745; /* Green border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Padding */
        transition: border-color 0.3s ease; /* Smooth transition */
    }
    .form-control:focus {
        border-color: #218838; /* Darker green on focus */
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); /* Focus shadow */
    }
    /* Payment Section Styling */
    .payment-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        border-left: 5px solid #28a745;
    }
    .payment-info p {
        font-size: 1.1rem;
        font-weight: bold;
        color: #333;
    }
    .bank-account {
        margin-bottom: 10px;
        font-size: 1.2rem;
    }
    .bank-account span {
        font-weight: bold;
        color: #28a745;
    }
    </style>

</head>

<body>
    
    <div class="container">
        <h1>Order Form</h1>
        <form action="process_order.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="transaction_number" value="<?= htmlspecialchars($transactionNumber); ?>">
            

            <div class="mb-3">
                <label for="plant_name" class="form-label">Plant Name</label>
                <input type="text" class="form-control" id="plant_name" name="plant_name" value="<?= htmlspecialchars($plantName); ?>" readonly>
                <input type="hidden" name="plant_id" value="<?php echo $plantId; ?>">
                <input type="hidden" name="plant_name" value="<?php echo $plantName; ?>">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity (Available: <?= htmlspecialchars($availableQuantity); ?>)</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="<?= htmlspecialchars($availableQuantity); ?>" oninput="updateOrderSummary()" required>
            </div>

            <div class="mb-3">
                <label for="delivery_location" class="form-label">Delivery Location (Addis Ababa only)</label>
                <select class="form-control" id="delivery_location" name="delivery_location" required>
                    <option value="" disabled selected>Select your delivery location</option>
                    <option value="Addis Ababa, Bole">Bole</option>
                    <option value="Addis Ababa, Arada">Arada</option>
                    <option value="Addis Ababa, Addis Ketema">Addis Ketema</option>
                    <option value="Addis Ababa, Gulele">Gulele</option>
                    <option value="Addis Ababa, Kirkos">Kirkos</option>
                    <option value="Addis Ababa, Kolfe Keranio">Kolfe Keranio</option>
                    <option value="Addis Ababa, Lideta">Lideta</option>
                    <option value="Addis Ababa, Yeka">Yeka</option>
                    <option value="Addis Ababa, Lemin kura">Lemi Kura</option>

                    <!-- Add more locations as needed -->
                </select>
            </div>

            <div class="mb-3">
                <label for="delivery_date" class="form-label">Preferred Delivery Date (Fridays only)</label>
                <input type="text" class="form-control" id="delivery_date" name="delivery_date" readonly required>
                <small class="text-muted">Please select a Friday as the delivery date.</small>
            </div>

            <div class="mb-3">
                <label for="customer_name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>

            <div class="mb-3">
                <label for="customer_email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
            </div>

            <div class="mb-3">
                <label for="customer_phone" class="form-label">Your Phone Number</label>
                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
            </div>

            <div class="mb-3">
                <label for="comments" class="form-label">Additional Comments</label>
                <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
            </div>

            <div class="order-summary" id="order_summary">
                Total Amount: <span id="total_amount">0</span> (ETB)
            </div>

            <div class="payment-info">
                <h4>Payment Details</h4>
                <p>Please make your payment to one of the following accounts:</p>
                <div class="bank-account">
                    CBE: <span>1000583842687</span>
                </div>
                <div class="bank-account">
                    BOA: <span>130659508</span>
                </div>
                <div class="bank-account">
                    Telebirr: <span>0940384999</span>
                </div>
                <div class="mb-3">
                    <label for="payment_confirmation" class="form-label">Upload Payment Confirmation (screenshot)</label>
                    <input type="file" class="form-control" id="payment_confirmation" name="payment_confirmation" accept="image/*" required>
                </div>
                <small class="text-muted">Your order will not proceed without the payment confirmation screenshot.</small>
            </div>


            <button type="submit" class="btn btn-success">Submit Order</button>
        </form>
        <div class="additional-link">
            <p class="mt-3"> <a href="home.php">Return to the homepage</a></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
$(document).ready(function () {
    // Initialize flatpickr for date input
    $('#delivery_date').flatpickr({
        disable: [
            function(date) {
                // Disable all days that are not Fridays
                return (date.getDay() !== 5);
            }
        ],
        dateFormat: "Y-m-d", // Set the desired date format
        allowInput: true, // Allow users to type in the date
   

   // Ensure only Fridays can be selected
   onReady: function(selectedDates, dateStr, instance) {
                    instance.config.onChange.push(function(selectedDates, dateStr) {
                        const selectedDate = new Date(dateStr);
                        const day = selectedDate.getDay();
                        if (day !== 5) { // 5 represents Friday
                            alert("Please select a Friday.");
                            instance.clear(); // Clear the date
                        }
                    });
                },
            });

            // Initial order summary update
            updateOrderSummary();
        });

        function updateOrderSummary() {
            const quantity = document.getElementById("quantity").value;
            const sellingPrice = <?= json_encode($sellingPrice); ?>; // Fetch selling price from PHP
            const totalAmount = quantity * sellingPrice;
            document.getElementById("total_amount").textContent = totalAmount.toFixed(2);
        }

        function validateForm() {
            const quantity = document.getElementById("quantity").value;
            if (quantity < 1 || quantity > <?= json_encode($availableQuantity); ?>) {
                alert("Please enter a valid quantity.");
                return false;
            }
            return true;
        }
</script>

</body>
</html>
