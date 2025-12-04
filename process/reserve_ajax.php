<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Methode non autorisée']);
    exit;
}

// récupérer et valider
$room_id = (int)($_POST['room_id'] ?? 0);
$start = $_POST['start'] ?? '';
$end = $_POST['end'] ?? '';

if ($room_id <= 0 || $start === '' || $end === '') {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Champs manquants']);
    exit;
}

try {
    $startDT = new DateTime($start);
    $endDT = new DateTime($end);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'Format date invalide']);
    exit;
}
if ($startDT >= $endDT) {
    echo json_encode(['status'=>'error','message'=>'La date de fin doit être après la date de début']);
    exit;
}

// durées / heures ouvrables (exemples)
$minMinutes = 15;
$maxMinutes = 8*60;
$interval = $startDT->diff($endDT);
$minutes = $interval->h*60 + $interval->i;
if ($minutes < $minMinutes) {
    echo json_encode(['status'=>'error','message'=>"Durée minimale: {$minMinutes} minutes"]);
    exit;
}
if ($minutes > $maxMinutes) {
    echo json_encode(['status'=>'error','message'=>"Durée maximale: {$maxMinutes} minutes"]);
    exit;
}
// exemple heures ouvrables 08:00 - 20:00
$openH = 8; $closeH = 20;
if ((int)$startDT->format('H') < $openH || (int)$endDT->format('H') >= $closeH) {
    echo json_encode(['status'=>'error','message'=>"Réservations entre {$openH}h et {$closeH}h seulement"]);
    exit;
}

// conflit: même salle, chevauchement
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE room_id = :room AND NOT (end_datetime <= :start OR start_datetime >= :end)");
$stmt->execute([
    'room' => $room_id,
    'start' => $startDT->format('Y-m-d H:i:s'),
    'end' => $endDT->format('Y-m-d H:i:s')
]);
$cnt = (int)$stmt->fetchColumn();
if ($cnt > 0) {
    echo json_encode(['status'=>'error','message'=>'Conflit : la salle est déjà réservée à ce créneau.']);
    exit;
}

// nom du professeur: si utilisateur connecté, utiliser son username; sinon demander 'professor' param
$professor = '';
if (isset($_SESSION['user'])) {
    $professor = $_SESSION['user']['username'];
    $user_id = (int)$_SESSION['user']['id'];
} else {
    $professor = trim($_POST['professor'] ?? '');
    $user_id = null;
    if ($professor === '') {
        echo json_encode(['status'=>'error','message'=>'Nom du professeur requis (ou connecte-toi).']);
        exit;
    }
}

// insertion
$stmt = $pdo->prepare("INSERT INTO reservations (room_id,user_id,professor,start_datetime,end_datetime) VALUES (:room,:user,:prof,:start,:end)");
$stmt->execute([
    'room'=>$room_id,
    'user'=> $user_id,
    'prof'=>$professor,
    'start'=>$startDT->format('Y-m-d H:i:s'),
    'end'=>$endDT->format('Y-m-d H:i:s')
]);

echo json_encode(['status'=>'ok','message'=>'Réservation créée.']);
