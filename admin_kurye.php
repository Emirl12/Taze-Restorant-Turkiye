<?php
// Veritabanı bağlantısı
include_once 'includes/connection.php';

// Durum güncelleme formu gönderildiyse çalışacak kısım
if (isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];

    $updateQuery = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
    $stmt = $conn->prepare($updateQuery);
    
    if ($stmt) {
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
    }
    
    // JS ile localStorage'ı temizleyerek dashboard'u senkronize et
    echo "<script>
            localStorage.removeItem('order_".$orderId."_start_time');
            window.location.href = 'admin_kurye.php';
          </script>";
    exit;
}

// Sistemdeki tüm siparişleri listelemek için çekiyoruz
$query = "SELECT * FROM `orders` ORDER BY order_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kurye Simülasyon Paneli (Admin)</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
        .admin-box { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #0a5e10; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #0d5215; color: white; }
        select { padding: 6px; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .status-badge { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>

<div class="admin-box">
    <h1>Taaza Restaurant - Kurye Simülatörü</h1>
    <p style="text-align: center; color: #666;">Buradan sipariş durumunu değiştirebilirsiniz. Değişiklik yaptığınızda kullanıcı dashboard'u otomatik olarak sıfırlanacaktır.</p>
    
    <table>
        <thead>
            <tr>
                <th>Sipariş No</th>
                <th>Müşteri</th>
                <th>Ürün</th>
                <th>Mevcut Durum</th>
                <th>Durumu Değiştir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $currentStatus = !empty($row['status']) ? $row['status'] : 'Hazırlanıyor';
                    
                    if ($currentStatus === 'Hazırlanıyor') {
                        $badgeStyle = "background: #fef3c7; color: #d97706;";
                    } elseif ($currentStatus === 'Yolda') {
                        $badgeStyle = "background: #e0f2fe; color: #0369a1;";
                    } elseif ($currentStatus === 'Teslim Edildi') {
                        $badgeStyle = "background: #dcfce7; color: #15803d;";
                    } else {
                        $badgeStyle = "background: #e2e8f0; color: #475569;";
                    }
                    ?>
                    <tr>
                        <td>#<?php echo $row['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['item']); ?> (x<?php echo $row['quantity']; ?>)</td>
                        <td>
                            <span class="status-badge" style="<?php echo $badgeStyle; ?>">
                                <?php echo $currentStatus; ?>
                            </span>
                        </td>
                        <td>
                            <form action="admin_kurye.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="update_status" value="1">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="Hazırlanıyor" <?php if($currentStatus == 'Hazırlanıyor') echo 'selected'; ?>>Hazırlanıyor</option>
                                    <option value="Yolda" <?php if($currentStatus == 'Yolda') echo 'selected'; ?>>Yolda</option>
                                    <option value="Teslim Edildi" <?php if($currentStatus == 'Teslim Edildi') echo 'selected'; ?>>Teslim Edildi</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Henüz verilmiş bir sipariş yok.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="dashboard.php" style="color: #0d5215; font-weight: bold; text-decoration: none;">← Profil Sayfama Dön</a>
    </div>
</div>

</body>
</html>