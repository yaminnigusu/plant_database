
    $(document).ready(function() {
        // Toggle form visibility
        $('#toggleFormBtn').click(function() {
            $('#plantForm').slideToggle();
        });

        // Handle form submission
        $('#plantForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            var formData = $(this).serialize();

            // AJAX POST request to process.php to add new plan
            $.post('process.php', formData, function(response) {
                // Handle response (e.g., display added plan)
                $('#plansList').append(response);

                // Show checkout button if plans are added
                $('#checkoutBtn').show();
            });

            // Reset form fields
            $(this).trigger('reset');
        });

        // Handle checkout button click
        $('#checkoutBtn').click(function() {
            // Perform checkout action (e.g., remove completed plans)
            $('#plansList').empty(); // Clear the plans list

            // Hide checkout button
            $(this).hide();
        });
    });

    