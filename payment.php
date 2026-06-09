<?php
session_start();
// Eğer sepet boşsa ödeme sayfasına girmesini engelle
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: menu.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güvenli Ödeme Sayfası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0a5e10;
            --primary-gradient: linear-gradient(135deg, #0d5215, #0a5e10);
            --card-gradient: linear-gradient(135deg, #1e293b, #0f172a);
            --text-main: #334155;
            --bg-body: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            padding: 35px 30px;
            position: relative;
            overflow: hidden;
        }

        /* --- 3D KART GÖRSELİ STİLLERİ --- */
        .card-space {
            perspective: 1000px;
            width: 100%;
            height: 200px;
            margin-bottom: 30px;
        }

        .credit-card {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .credit-card.flipped {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
            background: var(--card-gradient);
            color: #fff;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
        }

        .card-back {
            transform: rotateY(180deg);
            justify-content: flex-start;
            padding: 22px 0;
        }

        /* Kart Ön Yüz Detayları */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chip {
            width: 45px;
            height: 35px;
            background: linear-gradient(135deg, #fef08a, #eab308);
            border-radius: 6px;
            position: relative;
        }

        .card-logos i {
            font-size: 32px;
            opacity: 0.9;
        }

        .live-number {
            font-size: 20px;
            letter-spacing: 3px;
            margin: 20px 0 10px 0;
            text-align: center;
            font-weight: 500;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            text-transform: uppercase;
        }

        .footer-label {
            font-size: 9px;
            color: #94a3b8;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .live-name, .live-expiry {
            font-weight: 500;
            letter-spacing: 1px;
        }

        /* Kart Arka Yüz Detayları */
        .black-strip {
            width: 100%;
            height: 40px;
            background: #000;
            margin-top: 10px;
        }

        .ccv-space {
            padding: 0 22px;
            margin-top: 25px;
            text-align: right;
        }

        .ccv-white-bar {
            background: #fff;
            height: 35px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 12px;
            color: #334155;
            font-weight: 600;
            font-style: italic;
            letter-spacing: 1px;
        }

        /* --- FORM ELEMANLARI --- */
        h2 {
            color: var(--text-main);
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2 i {
            color: var(--primary-color);
        }

        .input-group {
            margin-bottom: 18px;
            position: relative;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 6px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            color: var(--text-main);
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(10, 94, 16, 0.15);
        }

        /* Alt Alan Grid (SKT ve CVV yan yana) */
        .row-fields {
            display: flex;
            gap: 15px;
        }

        .row-fields .input-group {
            flex: 1;
        }

        /* --- ÖDEME BUTONU --- */
        .pay-btn {
            width: 100%;
            padding: 15px;
            background: var(--primary-gradient);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(10, 94, 16, 0.2);
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .pay-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(10, 94, 16, 0.3);
            opacity: 0.95;
        }

        .pay-btn:active {
            transform: translateY(0);
        }

        /* Güvenlik Rozeti */
        .security-badge {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            font-size: 12px;
            color: #94a3b8;
        }

        .security-badge i {
            color: #22c55e;
        }
    </style>
</head>
<body>

<div class="payment-container">
    
    <div class="card-space">
        <div class="credit-card" id="creditCard">
            <div class="card-front">
                <div class="card-header">
                    <div class="chip"></div>
                    <div class="card-logos">
                        <i class="fab fa-cc-visa" id="logoVisa"></i>
                    </div>
                </div>
                <div class="live-number" id="cardNoView">•••• •••• •••• ••••</div>
                <div class="card-footer">
                    <div>
                        <div class="footer-label">KART SAHİBİ</div>
                        <div class="live-name" id="cardNameView">AD SOYAD</div>
                    </div>
                    <div style="text-align: right;">
                        <div class="footer-label">S. K. T</div>
                        <div class="live-expiry" id="cardExpiryView">AA/YY</div>
                    </div>
                </div>
            </div>
            <div class="card-back">
                <div class="black-strip"></div>
                <div class="ccv-space">
                    <div class="footer-label" style="text-align: right; margin-right: 25px;">CVV</div>
                    <div class="ccv-white-bar" id="cardCvvView">•••</div>
                </div>
            </div>
        </div>
    </div>

    <form action="checkout.php" method="POST" autocomplete="off">
    <input type="hidden" name="checkout" value="1">
        <h2><i class="fas fa-shield-halved"></i> Güvenli Ödeme</h2>
        
        <div class="input-group">
            <label for="card_name">Kart Üzerindeki İsim</label>
            <input type="text" id="card_name" name="card_name" placeholder="Örn. Ahmet Yılmaz" required>
        </div>

        <div class="input-group">
            <label for="card_number">Kart Numarası</label>
            <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>
        </div>

        <div class="row-fields">
            <div class="input-group">
                <label for="expiry">Son Kullanma</label>
                <input type="text" id="expiry" name="expiry" placeholder="AA/YY" maxlength="5" required>
            </div>

            <div class="input-group">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
            </div>
        </div>

        <button type="submit" class="pay-btn">
            <i class="fas fa-lock"></i> Ödemeyi Tamamla
        </button>
        
        <div class="security-badge">
            <i class="fas fa-circle-check"></i> 256-Bit SSL ile Güvenli Altyapı
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const card = document.getElementById("creditCard");
    
    // Form Input Elementleri
    const inputName = document.getElementById("card_name");
    const inputNumber = document.getElementById("card_number");
    const inputExpiry = document.getElementById("expiry");
    const inputCvv = document.getElementById("cvv");

    // Canlı Önizleme Alanları
    const viewName = document.getElementById("cardNameView");
    const viewNumber = document.getElementById("cardNoView");
    const viewExpiry = document.getElementById("cardExpiryView");
    const viewCvv = document.getElementById("cardCvvView");

    // 1. İsim Senkronizasyonu
    inputName.addEventListener("input", function() {
        if(this.value.trim() === "") {
            viewName.innerText = "AD SOYAD";
        } else {
            viewName.innerText = this.value.toUpperCase();
        }
    });

    // 2. Kart Numarası Biçimlendirme & Maskeleme
    inputNumber.addEventListener("input", function(e) {
        let val = this.value.replace(/\D/g, ''); // Sadece rakamları al
        let formatted = "";
        
        // Her 4 karakterde bir boşluk bırak
        for(let i = 0; i < val.length; i++) {
            if(i > 0 && i % 4 === 0) {
                formatted += " ";
            }
            formatted += val[i];
        }
        this.value = formatted;

        // Kart görselini senkronize et
        if(formatted === "") {
            viewNumber.innerText = "•••• •••• •••• ••••";
        } else {
            viewNumber.innerText = formatted;
        }
    });

    // 3. SKT (AA/YY) Biçimlendirme
    inputExpiry.addEventListener("input", function() {
        let val = this.value.replace(/\D/g, '');
        if(val.length > 2) {
            this.value = val.substring(0, 2) + "/" + val.substring(2, 4);
        } else {
            this.value = val;
        }

        if(this.value === "") {
            viewExpiry.innerText = "AA/YY";
        } else {
            viewExpiry.innerText = this.value;
        }
    });

    // 4. CVV & 3D Kart Döndürme Efekti
    inputCvv.addEventListener("input", function() {
        if(this.value === "") {
            viewCvv.innerText = "•••";
        } else {
            viewCvv.innerText = this.value;
        }
    });

    inputCvv.addEventListener("focus", function() {
        card.classList.add("flipped");
    });

    inputCvv.addEventListener("blur", function() {
        card.classList.remove("flipped");
    });
});
</script>

</body>
</html>