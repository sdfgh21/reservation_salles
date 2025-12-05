<?php
require 'db.php';
header('Content-Type: application/json');

// optional filter by room
$params = [];
$sql = "SELECT res.*, rooms.name AS room_name, users.username AS username
        FROM reservations res
        JOIN rooms ON res.room_id = rooms.id
        LEFT JOIN users ON res.user_id = users.id
        WHERE 1=1";

// support optional query params start / end (FullCalendar)
if (!empty($_GET['start']) && !empty($_GET['end'])) {
    $sql .= " AND NOT (res.end_datetime <= :start OR res.start_datetime >= :end)";
    $params['start'] = $_GET['start'];
    $params['end'] = $_GET['end'];
}
if (!empty($_GET['room_id'])) {
    $sql .= " AND res.room_id = :room_id";
    $params['room_id'] = (int)$_GET['room_id'];
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

$events = [];
foreach($rows as $r){
    $events[] = [
        'id' => $r['id'],
        'title' => $r['professor'] . " — " . $r['room_name'],
        'start' => date('c', strtotime($r['start_datetime'])),
        'end' => date('c', strtotime($r['end_datetime'])),
        'extendedProps' => [
            'room_name' => $r['room_name'],
            'professor' => $r['professor'],
            'created_by' => $r['username']
        ]
    ];
}
echo json_encode($events);
