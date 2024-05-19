    $(document).ready(function() {
        $('.has-submenu > a').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            // Toggle the submenu visibility
            $(this).siblings('.submenu').slideToggle();
        });

        // Toggle the form visibility
        $('#toggleFormBtn').click(function() {
            $('#plantForm').toggle();
        });

        // Form submission handling
        $('#plantForm').submit(function(e) {
            e.preventDefault(); // Prevent actual form submission

            // Get form data
            const title = $('#title').val();
            const subject = $('#subject').val();
            const description = $('#description').val();
            const completionDate = $('#completionDate').val();

            // Use AJAX to submit the form data
            $.post('process.php', {
                title: title,
                subject: subject,
                description: description,
                completionDate: completionDate,
                completed: false // Newly added plan is always ongoing
            }, function(data) {
                // Append the new plan to the ongoing plans list
                $('#ongoingPlansList').append(data);

                // Clear the form fields
                $('#plantForm')[0].reset();

                // Hide the form after submission
                $('#plantForm').hide();
            });
        });

        // Move plan to completed plans list when checkbox is checked
        $(document).on('change', '.complete-checkbox', function() {
            const isChecked = $(this).is(':checked');
            const card = $(this).closest('.card');
            const title = card.find('.card-title').text();
            const subject = card.find('.card-text').eq(0).text().split(': ')[1];
            const description = card.find('.card-text').eq(1).text().split(': ')[1];
            const completionDate = card.find('.card-text').eq(2).text().split(': ')[1];

            // Use AJAX to update the completion status in the database
            $.post('update_status.php', {
                title: title,
                completed: isChecked
            }, function(data) {
                // Update the UI based on the completion status
                if (isChecked) {
                    // Append the plan to completed plans list
                    $('#completedPlansList').append(card);
                } else {
                    // Append the plan to ongoing plans list
                    $('#ongoingPlansList').append(card);
                }
            });
        });

        // Function to move completed plans back to ongoing
        $('#moveToOngoingBtn').click(function() {
            // Iterate over each completed plan
            $('#completedPlansList .card').each(function() {
                const card = $(this);
                const title = card.find('.card-title').text();

                // Use AJAX to update the completion status in the database
                $.post('update_status.php', {
                    title: title,
                    completed: false // Marking the plan as ongoing
                }, function(data) {
                    // Move the plan back to ongoing plans list
                    $('#ongoingPlansList').append(card);
                });
            });

            // Remove all completed plans from the completed plans list
            $('#completedPlansList').empty();
        });
    });

