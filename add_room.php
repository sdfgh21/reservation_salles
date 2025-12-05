<?php
session_start();
require 'db.php';
require_once "csrf.php";

// Admin only
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'admin') {
    header("Location: login.php"); exit();
}
$message = "";

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $message = "Salle supprimée avec succès.";
}

// Ajout / Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!csrf_check($_POST['csrf_token'] ?? '')) {
        $message = "Erreur de sécurité CSRF.";
    } else {
        $name = trim($_POST['name']);
        $color = trim($_POST['color']);
        $id = $_POST['id'] ?? '';
        if ($name === "") {
            $message = "Le nom de la salle est requis.";
        } else {
            if ($id === "") {
                $stmt = $pdo->prepare("INSERT INTO rooms (name, color) VALUES (:n, :c)");
                $stmt->execute(['n' => $name, 'c' => $color]);
                $message = "Salle ajoutée avec succès.";
            } else {
                $stmt = $pdo->prepare("UPDATE rooms SET name = :n, color = :c WHERE id = :id");
                $stmt->execute(['n' => $name, 'c' => $color, 'id' => $id]);
                $message = "Salle modifiée avec succès.";
            }
        }
    }
}
$rooms = $pdo->query("SELECT * FROM rooms ORDER BY name")->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Gestion des salles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold"><i class="bi bi-door-open"></i> Gestion des salles</h2>
        <div>
            <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
            <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
        </div>
    </div>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-plus-circle"></i> Ajouter / Modifier une salle</h5>
            <form method="post" class="row g-3">
                <input type="hidden" id="room_id" name="id">
                <input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
                <div class="col-md-6">
                    <label class="form-label">Nom de la salle</label>
                    <input type="text" class="form-control" id="room_name" name="name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Couleur (optionnelle)</label>
                    <input type="color" class="form-control" name="color" value="#ffffff">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-list-ul"></i> Liste des salles</h5>
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['name']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    onclick="editRoom(<?= $r['id'] ?>, '<?= htmlspecialchars($r['name']) ?>', '<?= $r['color'] ?>')">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </button>
                                <a href="add_room.php?delete=<?= $r['id'] }"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Supprimer cette salle ?');">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function editRoom(id, name, color) {
    document.getElementById("room_id").value = id;
    document.getElementById("room_name").value = name;
    document.querySelector("input[name='color']").value = color;
}
</script>
</body>
</html>
