<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// récupérer salles pour le select
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY name");
$rooms = $stmt->fetchAll();

// info utilisateur
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Réservations — Calendrier</title>
  <link rel="stylesheet" href="styles.css">
  <!-- FullCalendar CSS/JS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>
<body>
<div class="topbar">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="assets/logo.png" alt="Logo école" style="height:70px; width: auto ; margin-right:50px;">
    <span></span>
  </a>
  <div class="container">
    <h1>Réservation de salles</h1>
    <div class="userbox">
      <?php if($user): ?>
        <span>Connecté: <?=htmlspecialchars($user['username'])?> (<?=htmlspecialchars($user['role'])?>)</span> |
        <a href="logout.php">Se déconnecter</a> |
        <a href="add_room.php">Gérer salles</a>
      <?php else: ?>
        <a href="login.php">Se connecter</a> | <a href="register.php">S'inscrire</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="container">
  <div style="display:flex; gap:16px; align-items:center; margin-bottom:12px;">
    <label>Filtrer par salle:</label>
    <select id="filterRoom">
      <option value="">— Toutes —</option>
      <?php foreach($rooms as $r): ?>
        <option value="<?=$r['id']?>"><?=htmlspecialchars($r['name'])?></option>
      <?php endforeach; ?>
    </select>
    <button id="newReservationBtn">+ Nouvelle réservation</button>
  </div>

  <div id="calendar"></div>
</div>

<!-- modal formulaire -->
<div id="modal" class="modal hidden">
  <div class="modal-content">
    <h3>Nouvelle réservation</h3>
    <form id="reserveForm">
      <label>Salle</label>
      <select name="room_id" id="roomSelect" required>
        <option value="">-- choisir --</option>
        <?php foreach($rooms as $r): ?>
          <option value="<?=$r['id']?>"><?=htmlspecialchars($r['name'])?></option>
        <?php endforeach; ?>
      </select>
      <?php if(!$user): ?>
        <label>Nom du professeur</label>
        <input name="professor" required>
      <?php endif; ?>
      <label>Début</label>
      <input type="datetime-local" name="start" id="startInput" required>
      <label>Fin</label>
      <input type="datetime-local" name="end" id="endInput" required>
      <div style="margin-top:10px;">
        <button type="submit">Réserver</button>
        <button type="button" id="closeModal">Annuler</button>
      </div>
    </form>
    <div id="formMsg"></div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let calendarEl = document.getElementById('calendar');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
    events: fetchEvents,
    eventClick: function(info) {
      const e = info.event;
      alert(e.title + "\\n" + "Début: " + e.start.toLocaleString() + "\\nFin: " + e.end?.toLocaleString());
    },
    selectable: true,
    select: function(selectionInfo) {
      // pré-remplir modal
      showModal();
      const startISO = selectionInfo.startStr;
      const endISO = selectionInfo.endStr;
      // convert to local datetime-local value
      document.getElementById('startInput').value = toLocalDatetime(selectionInfo.start);
      document.getElementById('endInput').value = toLocalDatetime(selectionInfo.end);
    }
  });

  calendar.render();

  // fetch events with optional room filter
  function fetchEvents(info, successCallback, failureCallback) {
    let url = 'reservations_json.php?start=' + encodeURIComponent(info.startStr) + '&end=' + encodeURIComponent(info.endStr);
    const filter = document.getElementById('filterRoom').value;
    if (filter) url += '&room_id=' + encodeURIComponent(filter);
    fetch(url).then(r=>r.json()).then(events => successCallback(events)).catch(err=> failureCallback(err));
  }

  // helpers for modal
  const modal = document.getElementById('modal');
  const newBtn = document.getElementById('newReservationBtn');
  const closeModalBtn = document.getElementById('closeModal');
  newBtn.addEventListener('click', showModal);
  closeModalBtn.addEventListener('click', hideModal);

  function showModal(){ modal.classList.remove('hidden'); document.getElementById('formMsg').innerText=''; }
  function hideModal(){ modal.classList.add('hidden'); }

  // submit reservation via AJAX
  const form = document.getElementById('reserveForm');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = new FormData(form);
    const res = await fetch('reserve_ajax.php', { method: 'POST', body: data });
    const json = await res.json();
    if (json.status === 'ok') {
      document.getElementById('formMsg').innerText = json.message;
      calendar.refetchEvents();
      setTimeout(()=>{ hideModal(); }, 700);
    } else {
      document.getElementById('formMsg').innerText = json.message || 'Erreur';
    }
  });

  // filter change
  document.getElementById('filterRoom').addEventListener('change', ()=> calendar.refetchEvents());

  // helper: convert Date to datetime-local string (local tz)
  function toLocalDatetime(d) {
    if (!d) return '';
    const pad = n => String(n).padStart(2,'0');
    const yyyy = d.getFullYear();
    const mm = pad(d.getMonth()+1);
    const dd = pad(d.getDate());
    const hh = pad(d.getHours());
    const min = pad(d.getMinutes());
    return `${yyyy}-${mm}-${dd}T${hh}:${min}`;
  }
});
</script>
</body>
</html>
