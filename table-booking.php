<?php
session_start();
require_once "includes/connection.php";

// Admin verisini getir
$adminQuery = "SELECT `id`, `email`, `name`, `password`, `resettoken`, `resettokenexpire`, `enable_table_booking` FROM `admin` WHERE 1";
$adminResult = mysqli_query($conn, $adminQuery);

if ($adminResult) {
    $adminData = mysqli_fetch_assoc($adminResult);

    if ($adminData['enable_table_booking'] == 1) {
        // Devam et
    } else {
        echo "<script>
                alert('Masa rezervasyon sayfası şu an kapalıdır, lütfen daha sonra tekrar deneyin [Yönetici tarafından devre dışı bırakıldı].');
                window.location.href = 'services.php';
              </script>";
        exit;
    }
} else {
    echo "Hata: " . mysqli_error($conn);
    exit;
}
?>

<?php require "includes/header.php"; ?>

<style>
  .clear { clear: both; }
  .content { margin-left: 20px; }
  .box {
    border: 5px solid #ccc;
    padding: 10px;
    border-radius: 10px;
    background-color: #f9f9f9;
    max-width: 600px;
    margin: auto;
    color: grey;
  }
  .button {
    background-image: linear-gradient(to right, #0d5215, green);
    color: white;
    max-width: 600px;
    font-size: var(--fs-7);
    text-transform: uppercase;
    padding: 20px 30px;
    text-align: center;
    border-radius: 7px;
    border: none;
    cursor: pointer;
  }
  .hero-banner img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: auto;
    margin-top: 13px;
  }
</style>

<center><button class="button" style="width:100%; padding-top:8em"><a href="vip-booking.php" style="color:white">VIP Rezervasyon Bölümüne Git</a></button></center>
<section class="contact-section" id="home">
  <br><br>
<center><u><h2 style="color:#0d5215">Normal Rezervasyon [Birinci Kat]</h2></u></center><br>
<marquee style="color: #0d5215;">Seçilen masa sizin için her zaman uygun olacaktır - [Kahvaltı - 07:00 - 11:00, Öğle Yemeği 11:00 - 16:00, Akşam Yemeği - 18:00 - 22:00]</marquee>
  <div class="contact-container">
    <div class="contact-content">

      <section>
        <form action="table-booking-handler.php" method="post">
          <center><h2 style="color:#0d5215">Birinci Kat</h2></center><br>

          <div class="box">
            <div class="content">
              <center><h3>Kahvaltı</h3></center>
              <hr>
              <div class="form-field">
                <label for="family_seat">Plan üzerinde belirtilen masayı seçin:</label>
                <select id="family_seat" name='section[Breakfast][]' onchange="handleSelectChange(this)">
                  <option value="NONE">Seçiniz</option>
                  <option value="B1">B1</option>
                  <option value="B2">B2</option>
                  <option value="B3">B3</option>
                  <option value="B4">B4</option>
                  <option value="B5">B5</option>
                  <option value="B6">B6</option>
                  <option value="B7">B7</option>
                  <option value="B8">B8</option>
                </select>
              </div>
            </div>
          </div>
          <br>

          <div class="box">
            <div class="content">
              <center><h3>Öğle Yemeği</h3></center>
              <hr>
              <div class="form-field">
                <label for="normal_seat">Plan üzerinde belirtilen masayı seçin:</label>
                <select id="normal_seat" name='section[Lunch][]' onchange="handleSelectChange(this)">
                  <option value="NONE">Seçiniz</option>
                  <option value="L1">L1</option>
                  <option value="L2">L2</option>
                  <option value="L3">L3</option>
                  <option value="L4">L4</option>
                  <option value="L5">L5</option>
                  <option value="L6">L6</option>
                  <option value="L7">L7</option>
                  <option value="L8">L8</option>
                </select>
              </div>
            </div>
          </div>
          <br>

          <div class="box">
            <div class="content">
              <center><h3>Akşam Yemeği</h3></center>
              <hr>
              <div class="form-field">
                <label for="dinner_seat">Plan üzerinde belirtilen masayı seçin:</label>
                <select id="dinner_seat" name='section[Dinner][]' onchange="handleSelectChange(this)">
                  <option value="NONE">Seçiniz</option>
                  <option value="D1">D1</option>
                  <option value="D2">D2</option>
                  <option value="D3">D3</option>
                  <option value="D4">D4</option>
                  <option value="D5">D5</option>
                  <option value="D6">D6</option>
                  <option value="D7">D7</option>
                  <option value="D8">D8</option>
                </select>
              </div>
            </div>
          </div>
          <br>

          <input type="hidden" id="hidden_time" name="hidden_time">

          <div class="box">
              <div class="form-field">
                  <label for="date">Tarih:</label>
                  <input type="date" id="date" name="date" required>
              </div>
              <div class="form-field">
                  <label for="time">Saat:</label>
                  <input type="text" id="time" name="hidden_time" placeholder="örn: 07:00 am to 11:00 am" required oninput="updateHiddenTime()">
              </div>
          </div>
      </section>
    </div>

    <figure class="hero-banner">
    <center><h2 style="color:#0d5215">Birinci Kat</h2></center><br>
      <img width='600' src="assets/images/table-book/blue_print.png" alt='Plan görseli'><br>
      <center>
        <input type="submit" class="button" value="Masa Ayırt" style="float:left">
        <button class="button" style="margin-right:0"><a href="payment-verification.php" style="color:white">Rezervasyonlarım</a></button>
      </center>
    </figure>
  </div>
  </form>

  <script>
  function updateHiddenTime() {
      const visibleTimeInput = document.getElementById('time');
      const hiddenTimeInput = document.getElementById('hidden_time');
      hiddenTimeInput.value = visibleTimeInput.value;
  }

  function handleSelectChange(selectedDropdown) {
      const familyDropdown = document.getElementById('family_seat');
      const normalDropdown = document.getElementById('normal_seat');
      const dinnerDropdown = document.getElementById('dinner_seat');
      const timeInput = document.getElementById('time');
      const hiddenTimeInput = document.getElementById('hidden_time');

      familyDropdown.disabled = true;
      normalDropdown.disabled = true;
      dinnerDropdown.disabled = true;
      timeInput.disabled = true;

      selectedDropdown.disabled = false;

      if (selectedDropdown.value === 'NONE') {
          familyDropdown.disabled = false;
          normalDropdown.disabled = false;
          dinnerDropdown.disabled = false;
          timeInput.disabled = false;
      }

      // Saat aralıklarını ayarla
      if (selectedDropdown.id === 'family_seat' && selectedDropdown.value !== 'NONE') {
          timeInput.value = '07:00 am to 11:00 am';
      } else if (selectedDropdown.id === 'normal_seat' && selectedDropdown.value !== 'NONE') {
          timeInput.value = '11:00 am to 04:00 pm';
      } else if (selectedDropdown.id === 'dinner_seat' && selectedDropdown.value !== 'NONE') {
          timeInput.value = '06:00 pm to 10:00 pm';
      } else {
          timeInput.value = '';
      }

      hiddenTimeInput.value = timeInput.value;
  }
  </script>
</section>

<?php require "includes/footer.php"; ?>