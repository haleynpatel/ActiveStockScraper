<?php
#use MongoDB\Driver\Exception\Exception;
require 'vendor/autoload.php';

$MongoClient = new MongoDB\Client("mongodb://localhost:27017");
$MongoHost = 'localhost';
$Port = 27017;
$Database = 'stocks';
$Collection = 'most_active';

// Connect to MongoDB
$MongoClient = new MongoDB\Client("mongodb://$MongoHost:$Port");
$customDatabase = $MongoClient->$Database;
$customCollection = $customDatabase->$Collection;
/*try {

} catch (Exception $customException) {
    echo "MongoDB Connection Error: " . $customException->getMessage();
    exit;
}*/

// Fetch data from MongoDB collection
$result = $customCollection->find([]);

// Close MongoDB connection
$MongoClient = null;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Most Active Stocks</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 12px;
            }

            th {
                cursor: pointer;
            }

            th:hover {
                background-color: #f5f5f5;
            }

            tbody tr:hover {
                background-color: #f5f5f5;
            }
        </style>
    </head>
        <body>

        <h1>Most Active Stocks</h1>

                <table>
                    <thead>
                    <tr>
                        <th onclick="sortTable(0)">Symbol</th>
                        <th onclick="sortTable(1)">Name</th>
                        <th onclick="sortTable(2)">Price</th>
                        <th onclick="sortTable(3)">Change</th>
                        <th onclick="sortTable(4)">Volume</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result as $stock) : ?>
                        <tr>
                            <td><?php echo $stock['Symbol']; ?></td>
                            <td><?php echo $stock['Name']; ?></td>
                            <td><?php echo $stock['Price']; ?></td>
                            <td><?php echo $stock['Change']; ?></td>
                            <td><?php echo $stock['Volume']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <script>
                    function sortTable(columnIndex) {
                        var table, rows, switching, i, x, y, switchFlag;
                        table = document.querySelector("table");
                        switching = true;
                        
                        while (switching) {
                            switching = false;
                            rows = table.rows;

                            for (i = 1; i < rows.length - 1; i++) {
                                switchFlag = false;
                                x = rows[i].getElementsByTagName("td")[columnIndex].textContent;
                                y = rows[i + 1].getElementsByTagName("td")[columnIndex].textContent;
                                    // at least one is not numeric, compare as strings
                                if (isNaN(x) || isNaN(y)) {
                                    switchFlag = x.toString().toLowerCase() > y.toString().toLowerCase();
                                } else {
                                    // Both values are valid numbers, compare them as numbers
                                    switchFlag = parseFloat(x) > parseFloat(y);
                                }

                                if (switchFlag) {
                                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                                    switching = true;
                                }
                            }
                        }
                    }
            </script>
        </body>
</html>
