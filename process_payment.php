<?php
session_start();

// Ödeme formundan gelip gelmediğini kontrol et
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Basit bir "ödeme yapıldı" simülasyonu
    // Gerçek bir uygulamada burada banka API'si ile işlem yapılır
    
    // Sepeti temizle
    unset($_SESSION['cart']);
    
    // Kullanıcıya başarı mesajı göster ve ana sayfaya yönlendir
    echo "<script>
            alert('Ödemeniz başarıyla alındı! Siparişiniz hazırlanıyor.');
            window.location.href='index.php';
          </script>";
    exit();
} else {
    // Eğer direkt bu dosyaya girilirse menüye yönlendir
    header("Location: menu.php");
    exit();
}
?>