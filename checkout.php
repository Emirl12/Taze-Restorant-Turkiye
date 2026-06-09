<?php
session_start();
require_once "includes/connection.php";

if(mysqli_connect_error()) {
    echo "<script>
        alert('UNKNOWN ISSUE: cannot process your request.');
        window.location.href='menu.php';
    </script>";
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['checkout'])) {
        // Kullanıcı giriş yapmış mı kontrol et
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            
            // Kullanıcı bilgilerini güvenli bir şekilde çek
            $session_email = mysqli_real_escape_string($conn, $_SESSION['username']);
            $query = "SELECT * FROM registered_users WHERE email='{$session_email}'";
            $result = mysqli_query($conn, $query);

            if($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                // Veritabanı tırnak hatalarını önlemek için gelen verileri temizliyoruz
                $name = mysqli_real_escape_string($conn, $user_data['name']);
                $email = mysqli_real_escape_string($conn, $user_data['email']);
                $state = mysqli_real_escape_string($conn, $user_data['state']);
                $district = mysqli_real_escape_string($conn, $user_data['district']);
                $address = mysqli_real_escape_string($conn, $state . ' ' . $district);

                // Sepetteki ürün dizilerini doğrudan Session'dan veya POST'tan güvenle alıyoruz
                // Formdan dizi gelmiyorsa yedek olarak Session'daki sepet verisini işleyelim
                $items = isset($_POST['Item_name']) ? $_POST['Item_name'] : array();
                $prices = isset($_POST['price']) ? $_POST['price'] : array();
                $quantities = isset($_POST['Quantity']) ? $_POST['Quantity'] : array();

                // Eğer formdan dizi gelmediyse, Session üzerindeki sepeti döngüye alalım (Daha Güvenli Yapı)
                if(empty($items) && isset($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $cart_item) {
                        $items[] = $cart_item['Item_name']; // Sepet yapındaki anahtarlara göre burayı eşitle
                        $prices[] = $cart_item['price'];
                        $quantities[] = $cart_item['Quantity'];
                    }
                }

                $all_orders_successful = true; 
                $error_message = "";

                // Ürün döngüsü
                if (count($items) > 0) {
                    for($i = 0; $i < count($items); $i++) {
                        $item = mysqli_real_escape_string($conn, $items[$i]);
                        $price = (float)$prices[$i];
                        $quantity = (int)$quantities[$i];
                        $total_price = $price * $quantity; 

                        // Sipariş tablosuna 'Hazırlanıyor' statüsüyle kayıt atılıyor
                        $query1 = "INSERT INTO `orders`(`name`, `email`, `address`, `item`, `quantity`, `total_price`, `status`) 
                                   VALUES ('$name', '$email', '$address', '$item', '$quantity', '$total_price', 'Hazırlanıyor')";

                        if(!mysqli_query($conn, $query1)) {
                            $all_orders_successful = false;
                            $error_message = mysqli_error($conn);
                            break; 
                        }
                    }
                } else {
                    $all_orders_successful = false;
                    $error_message = "Sepetinizde işlenecek ürün bulunamadı.";
                }

                // İşlem başarılıysa sepeti boşalt ve yönlendir
                if($all_orders_successful) {
                    unset($_SESSION['cart']); // CRITICAL: Başarılı sipariş sonrası sepeti temizle!
                    echo "<script>
                        alert('Ödemeniz başarıyla alındı! Siparişiniz hazırlanıyor.');
                        window.location.href='dashboard.php'; // Direkt sipariş geçmişine yönlendiriyoruz
                    </script>";
                } else {
                    $safe_error = addslashes($error_message);
                    echo "<script>
                        alert('Sipariş Kaydedilirken Hata Oluştu: $safe_error');
                        window.location.href='menu.php';
                    </script>";
                }
            }
        } else {
            echo "<script>
                alert('Sipariş vermek için lütfen önce giriş yapın.');
                window.location.href='new-login.php';
            </script>";
        }
    }
}
?>