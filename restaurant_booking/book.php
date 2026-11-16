<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }

$name = trim($_POST['guest_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$date = $_POST['booking_date'] ?? '';
$time = $_POST['booking_time'] ?? '';
$guests = filter_var($_POST['guests'] ?? null, FILTER_VALIDATE_INT);
$hallId = !empty($_POST['hall_id']) ? (int)$_POST['hall_id'] : null;

if ($name === '' || $phone === '' || !$guests || $guests < 1 || $guests > 20 ||
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !preg_match('/^\d{2}:\d{2}$/', $time)) {
    flash('Лутфан ҳамаи маълумотро дуруст пур кунед.', 'error');
    header('Location: index.php#booking'); exit;
}
if ($date < date('Y-m-d') || $time < '10:00' || $time > '22:00') {
    flash('Сана ё вақт берун аз вақти кории тарабхона аст.', 'error');
    header('Location: index.php#booking'); exit;
}
try {
    $sql = "SELECT t.id, t.table_number, t.seats, h.name AS hall_name
            FROM tables t JOIN halls h ON h.id=t.hall_id
            WHERE t.seats >= ? AND t.is_active=1";
    $params = [$guests];
    if ($hallId) { $sql .= " AND h.id=?"; $params[] = $hallId; }
    $sql .= " ORDER BY t.seats ASC";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $available = null;
    foreach ($stmt->fetchAll() as $table) {
        if (!booking_conflict((int)$table['id'], $date, $time)) { $available = $table; break; }
    }
    if (!$available) {
        flash('Мизи мувофиқ барои ин вақт ёфт нашуд. Лутфан вақти дигарро интихоб кунед.', 'error');
        header('Location: index.php#booking'); exit;
    }
    $insert = db()->prepare("INSERT INTO bookings (user_id, table_id, guest_name, phone, guests, booking_date, booking_time, status) VALUES (NULL,?,?,?,?,?,?, 'pending')");
    $insert->execute([$available['id'], $name, $phone, $guests, $date, $time]);
    $bookingId = (int)db()->lastInsertId();
    header('Location: booking_success.php?id=' . $bookingId); exit;
} catch (Throwable $e) {
    flash('Хатогӣ ҳангоми сабти банд. Танзимоти пойгоҳи додаҳоро санҷед.', 'error');
    header('Location: index.php#booking'); exit;
}
