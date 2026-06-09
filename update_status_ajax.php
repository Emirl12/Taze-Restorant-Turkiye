<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'includes/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($order_id > 0 && !empty($status)) {
        $query = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("si", $status, $order_id);
            if ($stmt->execute()) {
                $stmt->close();
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }
}
echo json_encode(['success' => false]);