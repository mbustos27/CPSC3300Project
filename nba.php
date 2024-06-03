<?php
$servername = "css1.seattleu.edu";
$username = "ll_mbustos";
$password = "Cpsc3300Mbustos";
$dbname = "ll_mbustos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute a query and display results
function executeQuery($sql, $conn) {
    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing the statement: " . htmlspecialchars($conn->error);
        return;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<table class='table'>";
        echo "<thead><tr>";
        // Output header row
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";
        // Output data rows
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "0 results";
    }
    $stmt->close();
}

if (isset($_GET['relation'])) {
    $relation = $conn->real_escape_string($_GET['relation']);
    $sql = "SELECT * FROM $relation";
    executeQuery($sql, $conn);
}

if (isset($_GET['query'])) {
    switch ($_GET['query']) {
        case 'query1':
            $sql = "SELECT first_name, last_name, points_per_game FROM players";
            break;
        case 'query2':
            $sql = "SELECT team_name, COUNT(*) as player_count FROM players GROUP BY team_name";
            break;
        case 'query3':
            $sql = "SELECT first_name, last_name, position FROM players WHERE conference = 'Western'";
            break;
        case 'query4':
            $sql = "SELECT team_name, AVG(height) as avg_height FROM players GROUP BY team_name HAVING AVG(height) < 77"; // 6'5 is 77 inches
            break;
        case 'query5':
            $sql = "SELECT p.first_name, p.last_name, t.team_name FROM players p LEFT JOIN teams t ON p.team_id = t.team_id";
            break;
    }
    executeQuery($sql, $conn);
}

if (isset($_POST['submit'])) {
    $adhoc_query = $_POST['adhoc_query'];
    // Basic validation (this can be expanded as needed)
    if (!empty($adhoc_query)) {
        executeQuery($adhoc_query, $conn);
    } else {
        echo "Please enter a query.";
    }
}

$conn->close();
?>
