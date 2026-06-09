<?php
session_start();

// Include database connection file
require_once "includes/connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the email address from the form
    $email = isset($_POST['email_address']) ? $_POST['email_address'] : '';

    // Validate and sanitize the email address if needed

    // Insert the data into the 'contact' table
    $query = "INSERT INTO `contact` (`email`, `timestamp`) VALUES (?, NOW())";
    $insertStmt = $conn->prepare($query);

    if ($insertStmt) {
    $insertStmt->bind_param("s", $email);
        $result = $insertStmt->execute();

        if ($result) {
            // Insertion successful
            echo "
            <script>alert('E-posta adresiniz başarıyla gönderildi!');
            window.location.href='contact.php';
            </script>
            ";
        } else {
            // Insertion failed
            echo "
            <script>alert('Gönderim sırasında bir hata oluştu!');
            window.location.href='contact.php';
            </script>
            ";
        }

        $insertStmt->close();
    } else {
        // Handle the error
        die("Hata oluştu: " . $conn->error);
    }
}
?>
<?php require "includes/header.php"; ?>

<section class="contact-section" id="home">
  <div class="contact-content">
      <img src="assets/images/logo.png" alt="ICON" width="70" height="70"> 
      <br>
      <h1 style="font-size:30px;">İletişim</h1>
      <br>
            <h2>Adres</h2>
            <p>
            Taaza Restoranı, Çayeli, Rize<br>
            Türkiye<br>
            </p>
            <br>
            <h2 style="font-size:30px">İletişim Bilgileri</h2>
            <p class="hero-text">
            MOBİL: +90 534936**** | TELEFON: 534936****<br>
            E-POSTA : emirleaa90@gmail.com<br>
            </p>
            <br>
      <form action="" class="contact-form" method="POST">
        <input type="email" name="email_address" aria-label="email" placeholder="E-posta Adresiniz..." required
          class="email-field">
        <button type="submit" class="btn">Geri Dönüş Al</button>
      </form>
    </div>
    <figure class="hero-banner">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125751.038072601..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </figure>
</section>
<br>

<?php require "includes/footer.php"; ?>