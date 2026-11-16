<?php
require_once __DIR__ . '/includes/functions.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT b.*, t.table_number, h.name AS hall_name FROM bookings b JOIN tables t ON t.id=b.table_id JOIN halls h ON h.id=t.hall_id WHERE b.id=?");
$stmt->execute([$id]); $booking = $stmt->fetch();
$pageTitle = 'Дархост қабул шуд';
require __DIR__ . '/includes/header.php';
?>
<section class="success-card">
 <div class="success-icon">✓</div><span class="eyebrow">ДАРХОСТ ҚАБУЛ ШУД</span>
 <h1>Ташаккур барои интихоби мо!</h1>
 <?php if($booking): ?>
 <p>Дархости шумо сабт гардид. Маъмури тарабхона онро тасдиқ мекунад.</p>
 <div class="booking-summary">
  <div><small>Рақами дархост</small><b>#<?= (int)$booking['id'] ?></b></div>
  <div><small>Меҳмон</small><b><?= e($booking['guest_name']) ?></b></div>
  <div><small>Толор / миз</small><b><?= e($booking['hall_name']) ?> / №<?= e($booking['table_number']) ?></b></div>
  <div><small>Сана ва вақт</small><b><?= e($booking['booking_date']) ?> · <?= e(substr($booking['booking_time'],0,5)) ?></b></div>
  <div><small>Шумораи меҳмонон</small><b><?= (int)$booking['guests'] ?> нафар</b></div>
  <div><small>Ҳолат</small><b class="status pending">Дар интизори тасдиқ</b></div>
 </div>
 <?php else: ?><p>Дархост ёфт нашуд.</p><?php endif; ?>
 <a class="button button-dark" href="index.php">Ба саҳифаи асосӣ</a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
