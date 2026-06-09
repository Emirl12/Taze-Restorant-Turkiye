<?php
session_start();
require_once "includes/header.php";
require_once "includes/connection.php";

// Kullanıcı girişi kontrolü
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    $userEmail = $_SESSION['username'];

    $query = "SELECT * FROM registered_users WHERE email='$userEmail'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);
            $userName = $userData['name'];

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $userName;
                $email = $userEmail;

                if (isset($_POST['section'])) {
                    $sectionArray = $_POST['section'];
                    $sections = [];
                    $seats = [];
                    $decor = [];

                    foreach ($sectionArray as $sectionName => $seatsArray) {
                        $selectedSeat = (isset($seatsArray[0])) ? $seatsArray[0] : 'YOK';
                        $selectedDecor = (isset($seatsArray[1])) ? $seatsArray[1] : 'YOK';

                        $seats[] = $selectedSeat;
                        $sections[] = $sectionName;
                        $decor[] = $selectedDecor;
                    }

                    $sectionsString = implode(",", $sections);
                    $seatsString = implode(",", $seats);
                    $decorString = implode(",", $decor);

                    $date = $_POST['date'];
                    $time = strtolower($_POST['time']);
                    $payment = 0;

                    $table = 'table_booking_vip';
                    $sql = "INSERT INTO $table (name, email, section, seat, decor, date, time, payment) 
                            VALUES ('$name', '$email', '$sectionsString', '$seatsString', '$decorString', '$date', '$time', $payment)";

                    if ($conn->query($sql) === TRUE) {
                        echo "<script>alert('VIP Masa Başarıyla Ayırtıldı');
                            window.location.href = 'vip-payment-verification.php';
                            </script>";
                        exit();
                    } else {
                        echo "<script>alert('VIP Masa Rezervasyonunda HATA oluştu');</script>";
                    }
                } else {
                    echo "<script>alert('Bölüm verisi alınamadı');</script>";
                }
            }
        } else {
            echo "<script>alert('E-posta kayıtlı değil');</script>";
            echo "<script>window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('İşleminiz gerçekleştirilirken HATA oluştu');</script>";
        exit();
    }
} else {
    echo "<script>alert('VIP rezervasyona erişmek için giriş yapın');</script>";
    echo "<script>window.location.href='new-login.php';</script>";
    exit();
}
$conn->close();
?>

<style>
  .box { border: 5px solid #ccc; padding: 10px; border-radius: 10px; background-color: #f9f9f9; max-width: 600px; margin: auto; color: grey; }
  .button { background-image: linear-gradient(to right, #0d5215, green); color: white; max-width: 600px; font-size: var(--fs-7); text-transform: uppercase; padding: 20px 30px; text-align: center; border-radius: 7px; border: none; cursor: pointer; display: block; width: 100%; margin-top: 10px; }
  .hero-banner img { max-width: 100%; height: auto; display: block; margin: auto; margin-top: 13px; }
</style>

<center><button class="button"><a href="table-booking.php" style="color:white">Normal Rezervasyon Bölümüne Git</a></button></center>

<section class="contact-section" id="home">
  <div class="contact-container">
    <div class="contact-content">
      <section>
        <form action="" method="post">
          <center><h2 style="color:#0d5215">Birinci Kat [VIP]</h2></center><br>

          <div class="box">
            <div class="content">
              <center><h3>VIP Bölümü</h3></center>
              <hr>
              <div class="form-field">
                <label for="vip_seat">Masa Seçimi:</label>
                <select id="vip_seat" name='section[VIP][]'>
                  <option value="NONE">Seçiniz</option>
                  <option value="v1">V1</option>
                  <option value="v2">V2</option>
                  <option value="v3">V3</option>
                  <option value="v4">V4</option>
                  <option value="v5">V5</option>
                  <option value="v6">V6</option>
                </select>
              </div>

              <div class="form-field">
                <label for="vip_decor">Dekor Seçimi:</label>
                <select id="vip_decor" name='section[VIP][]'>
                  <option value="NONE">Seçiniz</option>
                  <option value="Candle light">Mum Işığı</option>
                  <option value="Special live rose flower pot">Özel Canlı Gül Saksısı</option>
                  <option value="Chandeliers">Avizeler</option>
                </select>
              </div>
            </div>
          </div>
          <br>

          <div class="box">
            <div class="form-field">
              <label for="date">Tarih:</label>
              <input type="date" id="date" name="date" required>
            </div>
            <div class="form-field">
              <label for="time">Saat:</label>
              <input type="text" id="time" name="time" placeholder="örn: 01:30 pm" pattern="^(0[1-9]|1[0-2]):[0-5][0-9] [apAP][mM]$" title="Geçerli bir saat girin (hh:mm am/pm)" required>
            </div>
          </div>
          <br>
      </section>
    </div>

    <figure class="hero-banner">
      <center><h2 style="color:#0d5215">Birinci Kat [VIP]</h2></center><br>
      <img width='600' src="assets/images/table-book/first-floor.png" alt='Plan görseli'>
      <br>
      <input type="submit" class="button" value="VIP Masa Ayırt">
      <button class="button"><a href="vip-payment-verification.php" style="color:white">VIP Rezervasyonlarım</a></button>
      </form>
    </figure>
  </div>
</section>

<?php require "includes/footer.php"; ?>