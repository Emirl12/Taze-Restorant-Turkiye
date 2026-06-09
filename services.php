<?php
session_start();
?>
<?php require "includes/header.php"; ?>

<style>
  body {
    font-family: "Oxygen", sans-serif;
    color: #050505;
    margin-top: 120px;
  }

  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  .main {
    max-width: 1200px;
    margin: 0 auto;
  }

  .cards {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .cards_item {
    display: flex;
    padding: 1rem;
  }

  .card_image {
    position: relative;
    max-height: 250px;
  }

  .card_image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .card_price {
    position: absolute;
    bottom: 8px;
    right: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 45px;
    height: 45px;
    border-radius: 0.25rem;
    background-color: #c89b3f;
    font-size: 18px;
    font-weight: 700;
  }

  .card_price span {
    font-size: 12px;
    margin-top: -2px;
  }

  .note {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 4px 8px;
    border-radius: 0.25rem;
    background-color: #c89b3f;
    font-size: 14px;
    font-weight: 700;
  }

  @media (min-width: 40rem) {
    .cards_item {
      width: 50%;
    }
  }

  @media (min-width: 56rem) {
    .cards_item {
      width: 33.3333%;
    }
  }

  .card {
    background-color: white;
    border-radius: 0.25rem;
    box-shadow: 0 20px 40px -14px rgba(0, 0, 0, 0.25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .card_content {
    position: relative;
    padding: 16px 12px 32px 24px;
    margin: 16px 8px 8px 0;
    max-height: 290px;
    overflow-y: scroll;
  }

  .card_content::-webkit-scrollbar {
    width: 8px;
  }

  .card_content::-webkit-scrollbar-track {
    box-shadow: 0;
    border-radius: 0;
  }

  .card_content::-webkit-scrollbar-thumb {
    background: #c89b3f;
    border-radius: 15px;
  }

  .card_title {
    position: relative;
    margin: 0 0 24px;
    padding-bottom: 10px;
    text-align: center;
    font-size: 20px;
    font-weight: 700;
  }

  .card_title::after {
    position: absolute;
    display: block;
    width: 50px;
    height: 2px;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    background-color: #c89b3f;
    content: "";
  }

  hr {
    margin: 24px auto;
    width: 50px;
    border-top: 2px solid #c89b3f;
  }

  .card_text p {
    margin: 0 0 24px;
    font-size: 14px;
    line-height: 1.5;
  }

  .card_text p:last-child {
    margin: 0;
  }
</style>

<div class="main">
  <ul class="cards">
    <li class="cards_item">
      <div class="card">
        <div class="card_image">
          <img src="./assets/images/service1.png" alt="Karışık salata" />
        </div>
        <div class="card_content">
          <h2 class="card_title"><a href="menu.php">Hemen Yemek Siparişi Ver</a></h2>
          <div class="card_text">
            <p>Evinizin konforunda, enfes menümüzden en sevdiğiniz yemekleri kolayca sipariş edebilirsiniz. Tüm menümüzü keşfedin, favorilerinizi seçin ve siparişinizi oluşturun.
            </p>
            <hr />
            <p>Menümüz vejetaryen, et yemekleri, yerel lezzetler, deniz ürünleri ve Çin mutfaktarı olarak kategorilere ayrılmıştır. Kolayca gezinebilir, giriş yapıp sepetinizi doldurabilir ve ödemenizi güvenle tamamlayabilirsiniz.
            </p>
          </div>
        </div>
      </div>
    </li>

    <li class="cards_item">
      <div class="card">
        <div class="card_image">
          <img src="./assets/images/service2.png" alt="Sandviç" />
        </div>
        <div class="card_content">
          <h2 class="card_title"><a href="table-booking.php">Masa Rezervasyonu</a></h2>
          <div class="card_text">
            <p>Restoranımızda önceden masa rezervasyonu yapabilirsiniz. Geldiğinizde seçtiğiniz masa sizin için ücretsiz ve tamamen hazır durumda olacaktır.
            </p>
            <hr />
            <p>Rezervasyon alanlarımız <strong>Normal Bölüm</strong> ve <strong>VIP Bölüm</strong> olarak ikiye ayrılmaktadır. VIP bölümümüz televizyon, klima, kişiselleştirilebilir masa seçenekleri ve yüksek gizlilik gibi ayrıcalıklara sahiptir.
            </p>
          </div>
        </div>
      </div>
    </li>

    <li class="cards_item">
      <div class="card">
        <div class="card_image">
          <img src="./assets/images/service3.png" alt="Meyve tabağı" />
        </div>
        <div class="card_content">
          <h2 class="card_title"><a href="event-booking/event-booking.php">Etkinlik Rezervasyonu</a></h2>
          <div class="card_text">
            <p>Restoranımız profesyonel catering hizmeti sunmaktadır. Düğün, nişan, doğum günü gibi tüm toplu organizasyonlarınız için büyük ölçekli yemek siparişleri verebilirsiniz.
            </p>
            <hr />
            <p>Özel günlerinizde misafirlerinize unutulmaz lezzetler sunmak için menümüzü dilediğiniz gibi şekillendirebilirsiniz. Profesyonel ekibimiz ve kaliteli sunumlarımızla hizmetinizdeyiz.
            </p>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>



<div class="main">
  <ul class="cards">
    <li class="cards_item">
      <div class="card">
        <div class="card_image">
          <img src="./assets/images/service4.png" alt="Salata kavanozu" />
        </div>
        <div class="card_content">
          <h2 class="card_title"><a href="vip/premium.php">Premium Üyemiz Olun</a></h2>
          <div class="card_text">
            <p>Premium üyelerimiz arasına katılarak sadece size özel olarak hazırlanan heyecan verici fırsatlardan, dönemsel kampanyalardan ve özel indirimlerden hemen yararlanın!
            </p>
            <hr />
            <p>Restoranımızda yapacağınız harcamalarda ekstra puanlar kazanabilir, QR kod sistemimizi kullanarak anında VIP indirimleri aktif hale getirebilirsiniz. Taaza ayrıcalıklarını keşfedin.
            </p>
          </div>
        </div>
      </div>
    </li>

    <li class="cards_item">
      <div class="card">
        <div class="card_image">
          <img src="./assets/images/service5.png" alt="Reuben sandviç" />
        </div>
        <div class="card_content">
          <h2 class="card_title"><a href="lend-hand/lend-hand.php">Bize Destek Olun</a></h2>
          <div class="card_text">
            <p>Yemek tutkunları olarak toplumumuzdaki her birey için yiyeceğin ne kadar değerli olduğunu biliyoruz. Bu yüzden her dönem düzenli olarak ihtiyaç sahiplerine yemek bağışında bulunuyoruz.
            </p>
            <p>Bunu tek başımıza yapmamız zor, sizin de desteğinize ihtiyacımız var. Kampanyalarımıza dilediğiniz ölçüde katkıda bulunarak bir kalbe dokunabilirsiniz.
            </p>
            <hr />
            <p>Toplanan tüm yardımlar ve bağış bütçeleri doğrudan gıda paketlerine dönüştürülerek elden teslim edilmektedir. Destek olan tüm güzel kalplere şimdiden teşekkür ederiz.
            </p>
          </div>
        </div>
      </div>
    </li>


  </ul>
</div>

<?php require "includes/footer.php"; ?>