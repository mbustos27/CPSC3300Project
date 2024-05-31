<?php

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function executeQuery($sql, $conn) {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>";
        // Output header row
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>{$fieldinfo->name}</th>";
        }
        echo "</tr>";
        // Output data rows
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach($row as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}

if (isset($_GET['relation'])) {
    $relation = $_GET['relation'];
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
    executeQuery($adhoc_query, $conn);
}

$conn->close();
?>
