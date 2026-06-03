<?php
    $conge = $conges ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullCalendar - Exemple</title>

    <!-- CSS FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        #calendar {
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="app-wrap">

  <!-- SIDEBAR EMPLOYÉ -->
  <?= $this->include('partials/sidebar_employe') ?>

      <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mon Calendrier</div>
        <div class="topbar-breadcrumb">
          <a href="<?= base_url('employe/dashboard') ?>">Accueil</a>
          <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mon calendrier
        </div>
      </div>
    </div>

    <div id="calendar"></div>

    <!-- JS FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                events: [
                    <?php foreach ($conge as $c) { 
                        $dateFin = new DateTime($c['date_fin']);
                        $dateFin->modify('+1 day');
                        ?>
                    {
                        title: '<?= esc($c['type_conge']) ?>',
                        start: '<?= esc($c['date_debut']) ?>',
                        end: '<?= esc($dateFin->format('Y-m-d')) ?>'
                    },
                    <?php } ?>
                ]
            });

            calendar.render();
        });
    </script>
</body>
</html>