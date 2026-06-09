<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once 'includes/connection.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header("Location: login.php"); exit(); }

$session_email = mysqli_real_escape_string($conn, $_SESSION['username']);
$result = mysqli_query($conn, "SELECT * FROM `orders` WHERE `email` = '$session_email' ORDER BY `order_id` DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Paneli & Siparişlerim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-color: #0a5e10; --bg-body: #f8fafc; --text-main: #1e293b; --border-color: #e2e8f0; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); padding: 40px 20px; }
        .dashboard-container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: #fff; padding: 20px 30px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .order-card { background: #fff; border-radius: 16px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid var(--border-color); display: flex; flex-wrap: wrap; gap: 30px; }
        .order-details { flex: 1; min-width: 280px; }
        .track-btn { display: inline-block; margin-top: 15px; padding: 10px 18px; background: var(--primary-color); color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s; }
        .track-btn:hover { background: #07470c; transform: translateY(-2px); }
        .stepper-container { width: 280px; position: relative; padding-left: 35px; }
        .stepper-line-back { position: absolute; left: 12px; top: 10px; bottom: 10px; width: 4px; background: #e2e8f0; z-index: 1; }
        .stepper-line-progress { position: absolute; left: 12px; top: 10px; width: 4px; height: 0%; background: var(--primary-color); z-index: 2; transition: height 0.5s ease; }
        .step-item { position: relative; margin-bottom: 35px; z-index: 3; color: #94a3b8; }
        .step-icon { position: absolute; left: -35px; top: -2px; width: 28px; height: 28px; border-radius: 50%; background: #fff; border: 3px solid #cbd5e1; display: flex; justify-content: center; align-items: center; font-size: 12px; color: #94a3b8; }
        .step-item.active { color: var(--primary-color); font-weight: 600; }
        .step-item.active .step-icon { border-color: var(--primary-color); color: var(--primary-color); }
        .step-item.completed .step-icon { border-color: var(--primary-color); background: var(--primary-color); color: #fff; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="header">
        <h1>Siparişlerim</h1>
        <a href="logout.php" style="background:#ef4444; color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none;">Çıkış Yap</a>
    </div>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="order-card">
            <div class="order-details">
                <div style="font-weight:700; font-size:18px;">Sipariş #<?php echo $row['order_id']; ?></div>
                <div style="margin:10px 0;"><strong>Ürün:</strong> <?php echo htmlspecialchars($row['item']); ?></div>
                <a href="admin_kurye.php" class="track-btn"><i class="fas fa-motorcycle"></i> Kurye Takip Sistemine Git</a>
            </div>
            <div class="stepper-container">
                <div class="stepper-line-back"></div>
                <div class="stepper-line-progress" id="progress_<?php echo $row['order_id']; ?>"></div>
                <div class="stepper-wrapper" data-id="<?php echo $row['order_id']; ?>" data-current="<?php echo htmlspecialchars($row['status']); ?>">
                    <div class="step-item" id="step1_<?php echo $row['order_id']; ?>"><div class="step-icon"><i class="fas fa-utensils"></i></div>Hazırlanıyor</div>
                    <div class="step-item" id="step2_<?php echo $row['order_id']; ?>"><div class="step-icon"><i class="fas fa-motorcycle"></i></div>Yolda</div>
                    <div class="step-item" id="step3_<?php echo $row['order_id']; ?>"><div class="step-icon"><i class="fas fa-check"></i></div>Teslim Edildi</div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.stepper-wrapper').forEach(stepper => {
        const orderId = stepper.getAttribute('data-id');
        let currentStatus = stepper.getAttribute('data-current'); 
        const progBar = document.getElementById('progress_' + orderId);
        const steps = [document.getElementById('step1_'+orderId), document.getElementById('step2_'+orderId), document.getElementById('step3_'+orderId)];
        const storageKey = 'order_' + orderId + '_start_time';

        // SIFIRLAMA MANTIĞI: Durum Hazırlanıyor ise eski veriyi temizle
        if (currentStatus === 'Hazırlanıyor') { localStorage.removeItem(storageKey); }

        let startTime = localStorage.getItem(storageKey) || Date.now();
        if (!localStorage.getItem(storageKey)) { localStorage.setItem(storageKey, startTime); }

        function updateSingleStatus(newStatus) {
            const formData = new FormData(); formData.append('order_id', orderId); formData.append('status', newStatus);
            fetch('update_status_ajax.php', { method: 'POST', body: formData });
        }

        function runSimulation() {
            const elapsed = (Date.now() - parseInt(startTime)) / 1000;
            steps.forEach(s => { if(s) s.classList.remove('active', 'completed'); });

            if (currentStatus === 'Teslim Edildi' || elapsed >= 35) {
                steps.forEach(s => { if(s) s.classList.add('completed'); });
                if(progBar) progBar.style.height = '100%';
                if (currentStatus !== 'Teslim Edildi') { currentStatus = 'Teslim Edildi'; updateSingleStatus('Teslim Edildi'); }
            } else if (currentStatus === 'Yolda' || elapsed >= 15) {
                if(steps[0]) steps[0].classList.add('completed');
                if(steps[1]) steps[1].classList.add('active');
                if(progBar) progBar.style.height = '50%';
                if (currentStatus === 'Hazırlanıyor') { currentStatus = 'Yolda'; updateSingleStatus('Yolda'); }
                setTimeout(runSimulation, 1000);
            } else {
                if(steps[0]) steps[0].classList.add('active');
                setTimeout(runSimulation, 1000);
            }
        }
        runSimulation();
    });
});
</script>
</body>
</html>