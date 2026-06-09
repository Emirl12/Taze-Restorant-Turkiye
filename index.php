<?php
session_start();
require_once "includes/connection.php";
?>
<?php require "includes/header.php"; ?>

    <main>
      <section class="home" id="home">
        <div class="home-left">
          <?php
          $adminMessageQuery = "SELECT `message`, `enable_message` FROM `admin_message` LIMIT 1";
          $adminMessageResult = mysqli_query($conn, $adminMessageQuery);
          if ($adminMessageResult && mysqli_num_rows($adminMessageResult) > 0) {
              $adminMessageData = mysqli_fetch_assoc($adminMessageResult);
              if ($adminMessageData['enable_message'] == 1) {
                  echo "<marquee style='color:green'>" . htmlspecialchars($adminMessageData['message']) . "</marquee>";
              }
          }
          ?>
          <p class="home-subtext">Merhaba, yeni dostum!</p>
          <h1 class="main-heading">Biz sadece yemek pişirmiyoruz, duygularınızı inşa ediyoruz!</h1>
          <p class="home-text">Dilinizin de kendine ait hayalleri vardır. Taaza, lezzet fantezileriniz için mükemmel bir yerdir.</p>

          <div class="btn-group">
            <a href="login.php" style="color: black;"><button class="btn btn-primary btn-icon">Giriş Yap</button></a>
            <a href="about.php" style="color: black;"><button class="btn btn-secondary btn-icon">Hakkımızda</button></a>
          </div>
        </div>

        <div class="home-right">
          <img src="./assets/images/food1.png" alt="food" class="food-img food-1" width="200" loading="lazy">
          <img src="./assets/images/food2.png" alt="food" class="food-img food-2" width="200" loading="lazy">
          <img src="./assets/images/food3.png" alt="food" class="food-img food-3" width="200" loading="lazy">
        </div>
      </section>

      <?php if(isset($_SESSION['user_email'])): ?>
      <section style="background: #fff; padding: 30px; text-align: center; border: 2px solid #75F; margin: 20px auto; width: 80%; border-radius: 10px;">
          <h3 style="color: #333; margin-bottom: 10px;">Sipariş Takip</h3>
          <?php
          $email = $_SESSION['user_email'];
          $query = "SELECT status FROM orders WHERE email = '$email' ORDER BY timestamp DESC LIMIT 1";
          $result = mysqli_query($conn, $query);
          
          if($result && mysqli_num_rows($result) > 0){
              $row = mysqli_fetch_assoc($result);
              echo "<p style='font-size: 22px; font-weight: bold; color: #75F;'>Son Durum: " . htmlspecialchars($row['status']) . "</p>";
          } else {
              echo "<p>Henüz aktif bir siparişiniz bulunmuyor.</p>";
          }
          ?>
      </section>
      <?php endif; ?>

      </main>

    <footer>
      <p class="copyright">&copy; Telif Hakkı 2026 Taaza. Tüm Hakları Saklıdır.</p>
    </footer>

  <script src="./assets/js/taaza.js"></script>
</body>
</html>