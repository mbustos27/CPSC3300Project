<?php
$servername = "css1.seattleu.edu";
$username = "ll_mbustos";
$password = "Cpsc3300Mbustos";
$dbname = "ll_mbustos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute a query and return results as HTML
function executeQuery($sql, $conn) {
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return "Error preparing the statement: " . htmlspecialchars($conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $output = "<table class='table'>";
        $output .= "<thead><tr>";
        while ($fieldinfo = $result->fetch_field()) {
            $output .= "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
        }
        $output .= "</tr></thead>";
        $output .= "<tbody>";
        while($row = $result->fetch_assoc()) {
            $output .= "<tr>";
            foreach($row as $value) {
                $output .= "<td>" . htmlspecialchars($value) . "</td>";
            }
            $output .= "</tr>";
        }
        $output .= "</tbody></table>";
    } else {
        $output = "0 results";
    }
    $stmt->close();
    return $output;
}

if (isset($_GET['relation'])) {
    $relation = $conn->real_escape_string($_GET['relation']);
    $sql = "SELECT * FROM $relation";
    echo executeQuery($sql, $conn);
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
            $sql = "SELECT team_name, AVG(height) as avg_height FROM players GROUP BY team_name HAVING AVG(height) < 77";
            break;
        case 'query5':
            $sql = "SELECT p.first_name, p.last_name, t.team_name FROM players p LEFT JOIN teams t ON p.team_id = t.team_id";
            break;
    }
    echo executeQuery($sql, $conn);
}

if (isset($_POST['submit'])) {
    $adhoc_query = $_POST['adhoc_query'];
    if (!empty($adhoc_query)) {
        echo executeQuery($adhoc_query, $conn);
    } else {
        echo "Please enter a query.";
    }
}

$conn->close();
?>
