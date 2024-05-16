console.log('Document ready');
$.ajax({
    url: 'get_analytics_data.php',
    method: 'GET',
    success: function(data) {
        console.log('Data received:', data);

        var plantNames = [];
        var profitMargins = [];

        if (Array.isArray(data)) {
            data.forEach(function(item) {
                console.log('Plant Name:', item.plantName);
                console.log('Profit Margin:', item.profitMargin);

                plantNames.push(item.plantName);
                profitMargins.push(item.profitMargin);
            });

            console.log('Plant Names:', plantNames);
            console.log('Profit Margins:', profitMargins);

            // Create Chart.js chart with parsed data
            var ctx = document.getElementById('analyticsChart').getContext('2d');
            var chart = new Chart(ctx, {
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
        } else {
            console.error('Invalid data format:', data);
        }
    },
    error: function(xhr, status, error) {
        console.error('Error fetching analytics data:', error);
    }
});
