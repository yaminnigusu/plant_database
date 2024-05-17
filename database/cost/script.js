$(document).ready(function() {
    $.ajax({
        url: 'get_analytics_data.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                console.error('Error fetching analytics data:', data.error);
                return;
            }
            var plantNames = [];
            var profitMargins = [];

            data.forEach(function(item) {
                plantNames.push(item.plantName);
                profitMargins.push(item.profitMargin);
            });

            var ctx = document.getElementById('analyticsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: plantNames,
                    datasets: [{
                        label: 'Profit Margins for Each Plant',
                        data: profitMargins,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching analytics data:', error);
            console.error('Response:', xhr.responseText);
        }
    });
});

// get_plant_type_dat
