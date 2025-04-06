<?php
// Database Connection
$host = "localhost";
$username = "root";
$password = "";
$database = "college"; // Change this if needed

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $cutoff = intval($_POST['cutoff']); // Ensure integer
    $location = $conn->real_escape_string($_POST['location']);
    $caste = $conn->real_escape_string($_POST['caste']);
    $selected_department = $conn->real_escape_string($_POST['department']); // Keep original case

    // **Map department to corresponding table name**
    $department_tables = [
        "COMPUTER SCIENCE AND ENGINEERING" => "cse",
        "IT" => "it",
        "ELECTRONICS AND COMMUNICATION ENGINEERING" => "ece",
        "ELECTRICAL AND ELECTRONICS ENGINEERING" => "eee",
        "CIVIL ENGINEERING" => "civil",
        "MECHANICAL ENGINEERING" => "mechanical",
        "ARTIFICIAL INTELLIGENCE AND DATA SCIENCE" => "aids",
        // Add more mappings here
    ];

    // **Find the correct table for the selected department**
    if (!isset($department_tables[$selected_department])) {
        die("<p>Invalid department selection.</p>");
    }
    
    $table_name = $department_tables[$selected_department]; // Get table name

    // **Construct SQL Query**
    $sql = "SELECT collegeid, collegename, location, branchname, COALESCE(`$caste`, 0) AS caste_cutoff
            FROM $table_name
            WHERE TRIM(branchname) = '$selected_department'  -- Match department
            AND TRIM(location) = '$location'  -- Match location
            AND COALESCE(`$caste`, 0) > 0  -- Ensures cutoff is greater than 0
            AND COALESCE(`$caste`, 0) <= $cutoff  -- Filters based on user cutoff
            ORDER BY `$caste` DESC";   

    // **Execute Query**
    $result = $conn->query($sql);

    // **Display Results with Styled Table**
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Recommended Colleges</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                background-color: #f5e6ff;
                margin: 0;
                padding-bottom: 80px; /* Ensure space for floating button */
            }
            h2 {
                color: #6a0dad;
            }
            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
                background-color: #ffffff;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
            }
            th, td {
                padding: 12px;
                border: 1px solid #ddd;
                text-align: center;
            }
            th {
                background-color: #6a0dad;
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f2e6ff;
            }
            tr:hover {
                background-color: #d9b3ff;
                transition: 0.3s;
            }
            .back-button {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                padding: 12px 25px;
                font-size: 16px;
                background-color: #6a0dad;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                transition: background 0.3s ease;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            }
            .back-button:hover {
                background-color: #4b0082;
            }
        </style>
    </head>
    <body>";

    if ($result->num_rows > 0) {
        echo "<h2>Recommended Colleges</h2>";
        echo "<table border='1'>";
        echo "<tr><th>College Code</th><th>College Name</th><th>Location</th><th>Branch</th><th>Cutoff</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['collegeid']}</td>
                    <td>{$row['collegename']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['branchname']}</td>
                    <td>{$row['caste_cutoff']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No matching colleges found.</p>";
    }

    echo "<a href='suggestion.html' class='back-button'>Back</a>";
    echo "</body></html>";
}

// Close Database Connection
$conn->close();
?>
