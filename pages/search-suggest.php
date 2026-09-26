<?php

include "../includes/db.php";

header("Content-Type: application/json");

$q = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($q == "") {
    echo json_encode(array());
    exit;
}

$words = preg_split('/\s+/', $q);
$conditions = array();

foreach ($words as $word) {
    $word = trim($word);
    if ($word == "") {
        continue;
    }
    $safeWord = mysqli_real_escape_string($conn, $word);
    $conditions[] = "(name LIKE '%$safeWord%' OR category LIKE '%$safeWord%' OR edition LIKE '%$safeWord%')";
}

if (count($conditions) == 0) {
    echo json_encode(array());
    exit;
}

$whereClause = implode(" AND ", $conditions);

$query = "SELECT id, name, category, image FROM products WHERE $whereClause ORDER BY featured DESC LIMIT 8";

$result = mysqli_query($conn, $query);

$suggestions = array();

while ($row = mysqli_fetch_assoc($result)) {
    $suggestions[] = array(
        "id" => $row['id'],
        "name" => $row['name'],
        "category" => $row['category'],
        "image" => $row['image']
    );
}

echo json_encode($suggestions);

?>