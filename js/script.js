document.addEventListener('DOMContentLoaded', function() {
    const productsSection = document.getElementById('products');
    const paginationElement = document.getElementById('pagination');
    const productsPerPage = 18;
    let currentPage = 1;

    function displayProducts(plants) {
        // Clear the existing product list
        productsSection.innerHTML = '';

        // Loop through the plant data and create product elements
        plants.forEach(plant => {
            const productDiv = document.createElement('div');
            productDiv.classList.add('product-box');

            // Calculate 85% of the quantity
            const eightyFivePercentQuantity = Math.round(plant.quantity * 0.85);

            // Create HTML content for each plant box including plant type(s)
            productDiv.innerHTML = `
                <img src="${plant.photo}" alt="${plant.name}" class="product-image">
                <div class="product-details">
                    <h2>${plant.name}</h2>
                    <p>Price: Birr <b>${plant.valuePerQuantity.toFixed(2)}</b></p>
                    <p>Available Quantity: ${eightyFivePercentQuantity}</p>
                    <p>Plant Type: ${plant.plantType.join(', ')}</p>
                </div>
            `;

            // Append the product box to the products section
            productsSection.appendChild(productDiv);
        });

        // Update pagination controls
        // (Assuming updatePagination function handles pagination)
        updatePagination(plants.length);
    }

    function fetchPlants() {
        const searchInput = document.getElementById('searchInput').value;
        const plantTypeFilter = document.getElementById('plantTypeFilter').value;

        // Construct the URL with search parameters
        const url = `server.php?search=${encodeURIComponent(searchInput)}&type=${encodeURIComponent(plantTypeFilter)}`;

        // Fetch plants data from server
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(plants => {
                // Display fetched plants
                displayProducts(plants);
            })
            .catch(error => {
                console.error('Error fetching plants:', error);
            });
    }

    // Attach fetchPlants() to the Search button click event
    const searchButton = document.getElementById('searchButton');
    searchButton.addEventListener('click', fetchPlants);

    // Initial fetch and display of plants on page load
    fetchPlants();
});
// Function to toggle main navigation visibility
