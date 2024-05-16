$(document).ready(function () {
    // Fetch total costs data via AJAX
    $.ajax({
        url: 'get_total_costs.php',
        method: 'GET',
        success: function(data) {
            // Parse data and create a chart
            var ctx = document.getElementById('costsChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Costs'],
                    datasets: [{
                        label: 'Total Costs',
                        data: [data.totalCosts],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
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
        }
    });
});
