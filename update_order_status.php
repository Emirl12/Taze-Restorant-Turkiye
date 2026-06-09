<?php
// Veritabanı bağlantısı
include_once '../../includes/connection.php';

if (isset($_POST['orderId']) && isset($_POST['newStatus'])) {
    $orderId = (int)$_POST['orderId'];
    $newStatus = $_POST['newStatus']; // "Hazırlanıyor", "Yolda", "Teslim Edildi"

    // Siparişin durumunu güncelle
    $updateQuery = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $newStatus, $orderId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Durum başarıyla güncellendi: ' . $newStatus]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Güncelleme başarısız.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgi gönderildi.']);
}
$conn->close();
?>