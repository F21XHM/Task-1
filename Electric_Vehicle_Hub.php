<?php
// Database connection settings
$host = 'localhost'; // The server where your database is hosted (usually 'localhost')
$db = 'db2341553'; // The name of your database
$user = '2341553'; // Your database username
$pass = 'Rebbekkah2015#'; // Your database password

// Create connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // If the connection fails, show an error
}

// Set the number of results you want to show per page
$results_per_page = 5; // You can adjust this number to show more or fewer results per page

// Query to get the total number of vehicles in the database (needed for pagination)
$sql = "SELECT COUNT(*) AS total FROM vehicles";
$result = $conn->query($sql); // Execute the query
$row = $result->fetch_assoc(); // Get the result as an associative array
$total_results = $row['total']; // Get the total number of vehicles in the database

// Calculate how many pages of results you will need
$total_pages = ceil($total_results / $results_per_page); // ceil() rounds up to the nearest whole number

// Determine the current page number, default is page 1
$page = isset($_GET['page']) ? $_GET['page'] : 1; // If no page number is provided, use page 1
$start_from = ($page - 1) * $results_per_page; // Calculate the starting point for the SQL query

// Query to get a limited number of vehicles based on the current page and the results per page
$sql = "SELECT * FROM vehicles LIMIT $start_from, $results_per_page";
$result = $conn->query($sql); // Execute the query to get the vehicles for the current page

// Check if there are any results
if ($result && $result->num_rows > 0) {
    // If there are results, start displaying them
    echo "<h1>Electric Vehicle Hub</h1>"; // Page title
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>"; // Start the table
    echo "<tr><th>Vehicle Name</th><th>Vehicle Price (£)</th><th>Buy / Rent</th></tr>"; // Table header

    // Loop through each row of data and display it in the table
    while ($row = $result->fetch_assoc()) { // Fetch each row as an associative array
        echo "<tr>"; // Start a new row
        echo "<td>" . htmlspecialchars($row['Vehicle_Name']) . "</td>"; // Vehicle Name
        echo "<td>£" . number_format($row['Vehicle_Price'], 2) . "</td>"; // Vehicle Price, formatted as currency
        echo "<td>" . htmlspecialchars($row['Buy_Rent']) . "</td>"; // Whether the vehicle is for sale or rent
        echo "</tr>"; // End the row
    }

    echo "</table>"; // End the table

    // Pagination Controls: Links to go to different pages
    echo "<div style='margin-top: 20px;'>"; // Start the pagination container
    for ($i = 1; $i <= $total_pages; $i++) { // Loop through all pages
        echo "<a href='?page=" . $i . "' style='margin: 0 5px; text-decoration: none;'>" . $i . "</a>"; // Create a link for each page
    }
    echo "</div>"; // End the pagination container

} else {
    echo "No data found."; // If no vehicles are found, display this message
}

// Close the connection to the database
$conn->close();
?>
