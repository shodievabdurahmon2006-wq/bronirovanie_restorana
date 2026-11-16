<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Банд кардани миз | Dastarkhon';
$halls = db()->query("SELECT * FROM halls ORDER BY name")->fetchAll();
$today = date('Y-m-d');
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
 <div class="hero-copy">
  <span class="eyebrow">ТАҶРИБАИ НАВИ ФАРМОИШ</span>
  <h1>Шоми хуб<br><em>аз мизи шумо</em> оғоз мешавад.</h1>
  <p>Сана, вақт ва шумораи меҳмононро интихоб кунед. Мо барои шумо мизи мувофиқро пайдо мекунем.</p>
  <a class="button button-light" href="#booking">Миз банд кунед <span>↗</span></a>
 </div>
 <div class="hero-art"><div class="art-circle"></div><div class="art-card"><span>DASTARKHON</span><strong>Reserve<br>your table</strong><small>GOOD FOOD · GOOD MOMENTS</small></div></div>
</section>

<section class="section" id="booking">
 <div class="section-heading"><div><span class="eyebrow">БАНДКУНИИ ОНЛАЙН</span><h2>Мизи худро интихоб кунед</h2></div><p>Пур кардани шакл ҳамагӣ якчанд дақиқа вақт мегирад.</p></div>
 <div class="booking-layout">
  <form class="panel booking-form" method="post" action="book.php">
   <div class="form-grid">
    <label>Ному насаб<input name="guest_name" required maxlength="120" placeholder="Масалан, Абдураҳмон Шодиев"></label>
    <label>Рақами телефон<input name="phone" required maxlength="30" placeholder="+992 900 00 00 00"></label>
    <label>Санаи ташриф<input type="date" name="booking_date" min="<?= e($today) ?>" value="<?= e($today) ?>" required></label>
    <label>Вақти ташриф<input type="time" name="booking_time" min="10:00" max="22:00" required></label>
    <label>Шумораи меҳмонон<select name="guests" required><?php for($i=1;$i<=20;$i++): ?><option value="<?= $i ?>"><?= $i ?> нафар</option><?php endfor; ?></select></label>
    <label>Толор<select name="hall_id"><option value="">Ҳамаи толорҳо</option><?php foreach($halls as $hall): ?><option value="<?= (int)$hall['id'] ?>"><?= e($hall['name']) ?></option><?php endforeach; ?></select></label>
   </div>
   <button class="button button-dark full" type="submit">Ҷустуҷӯ ва банд кардани миз <span>→</span></button>
   <p class="form-note">Миз ба таври худкор аз рӯи шумораи ҷойҳо ва вақти холӣ интихоб мешавад. Банд пас аз тасдиқи маъмур ниҳоӣ мегардад.</p>
  </form>
  <aside class="info-panel">
   <span class="eyebrow">МАЪЛУМОТИ МУҲИМ</span>
   <h3>Мо интизори шумоем.</h3>
   <p>Вақти корӣ: ҳар рӯз аз 10:00 то 23:00.</p>
   <div class="info-line"><span>01</span><div><b>Интихоби осон</b><small>Сана ва вақти мувофиқро интихоб кунед.</small></div></div>
   <div class="info-line"><span>02</span><div><b>Мизи муносиб</b><small>Система мизи холиро пайдо мекунад.</small></div></div>
   <div class="info-line"><span>03</span><div><b>Тасдиқи маъмур</b><small>Пас аз тасдиқ банд фаъол мешавад.</small></div></div>
  </aside>
 </div>
</section>

<section class="section halls-section">
 <div class="section-heading"><div><span class="eyebrow">ФАЗОИ МО</span><h2>Толорҳои тарабхона</h2></div><p>Барои ҳар гуна вохӯрӣ фазои мувофиқ пайдо мешавад.</p></div>
 <div class="hall-grid">
 <?php foreach($halls as $i=>$hall): ?>
  <article class="hall-card"><div class="hall-number">0<?= $i+1 ?></div><h3><?= e($hall['name']) ?></h3><p><?= e($hall['description'] ?? 'Фазои бароҳат барои меҳмонон.') ?></p><span><?= (int)$hall['capacity'] ?> ҷой</span></article>
 <?php endforeach; ?>
 <?php if (!$halls): ?><p>Ҳоло толорҳо илова нашудаанд.</p><?php endif; ?>
 </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
