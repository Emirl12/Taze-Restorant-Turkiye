<?php
session_start();
include_once 'includes/connection.php';

// 1. Kategori Filtreleme Mantığı
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// 2. Veritabanından Yemekleri Çekme Sorgusu
$menuItems = [];

try {
    if ($category == 'all') {
        $query = "SELECT * FROM `menu`";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $menuItems[] = $row;
                }
            }
            $stmt->close();
        }
    } else {
        $query = "SELECT * FROM `menu` WHERE `category` = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $category);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $menuItems[] = $row;
                }
            }
            $stmt->close();
        }
    }
} catch (Exception $e) {
    // Hata durumunda boş bırakıldı
}

// 3. GÜNCEL YEDEK MENÜ
if (empty($menuItems)) {
    $backupItems = [
        ['id' => 1, 'name' => 'Özel Taaza Salata', 'price' => 120, 'description' => 'Mevsim yeşillikleri, özel sosumuz ve taze kruton taneleri ile.', 'image' => './assets/images/salata.png', 'category' => 'veg'],
        ['id' => 2, 'name' => 'VIP Reuben Sandviç', 'price' => 240, 'description' => 'Özel tütsülenmiş et, eritilmiş kaşar peyniri ve enfes Rus sosu eşliğinde.', 'image' => './assets/images/ruben.png', 'category' => 'non-veg'],
        ['id' => 3, 'name' => 'Geleneksel Köri Tabağı', 'price' => 190, 'description' => 'Taaza usulü yoğun baharatlı ve nefis aromalı yerel köri lezzeti.', 'image' => './assets/images/köri.png', 'category' => 'local'],
        ['id' => 4, 'name' => 'Tatlı Ekşi Soslu Tavuk', 'price' => 210, 'description' => 'Uzak doğu esintisi, taze sebzeler ve özel Çin sosu ile sotelenmiş tavuk göğsü.', 'image' => './assets/images/tavuk.png', 'category' => 'chinese'],
        ['id' => 5, 'name' => 'Soslu Karides Tava', 'price' => 320, 'description' => 'Tereyağında sarımsak ve taze otlarla harmanlanmış Akdeniz karidesi.', 'image' => './assets/images/service1.png', 'category' => 'sea-food'],
        ['id' => 6, 'name' => 'Yöresel Muhlama', 'price' => 160, 'description' => 'Karadeniz\'in eşsiz lezzeti; uzayan nefis kolot peyniri, halis tereyağı ve mısır unu ile sıcak servis edilir.', 'image' => './assets/images/muhlama.png', 'category' => 'local'],
        ['id' => 7, 'name' => 'Adana Kebap', 'price' => 260, 'description' => 'Zırhta çekilmiş kuzu kıyması, özel kuyruk yağı ve baharatlar; közlenmiş biber, domates ve lavaş eşliğinde.', 'image' => './assets/images/adanakebap.png', 'category' => 'non-veg'],
        ['id' => 8, 'name' => 'Kuzu Çevirme', 'price' => 380, 'description' => 'Odun ateşinde saatlerce ağır ağır pişmiş, lokum kıvamında ve çıtır derili taptaze kuzu eti.', 'image' => './assets/images/kuzu.png', 'category' => 'non-veg'],
        ['id' => 9, 'name' => 'Hamsikoli', 'price' => 155, 'description' => 'Hamsiyi hiç böyle denemediniz, taptaze hamsiyi mısır unu ve diğer malzemelerle özenle pişirip tütsülüyoruz.', 'image' => './assets/images/hamsikoli.png', 'category' => 'sea-food']
    ];
    if ($category == 'all') {
        $menuItems = $backupItems;
    } else {
        foreach ($backupItems as $item) {
            if ($item['category'] == $category) {
                $menuItems[] = $item;
            }
        }
    }
}
?>

<?php require "includes/header.php"; ?>

<style>
    body { font-family: "Oxygen", sans-serif; color: #222; margin-top: 130px; background-color: #fcfcfc; }
    .menu-section { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .menu-title { text-align: center; font-size: 32px; color: #0a5e10; margin-bottom: 10px; font-weight: 700; }
    .menu-subtitle { text-align: center; color: #777; margin-bottom: 30px; font-size: 16px; }
    .category-tabs { display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin-bottom: 40px; list-style: none; padding: 0; }
    .category-tabs a { text-decoration: none; color: #555; padding: 10px 20px; border: 2px solid #e0e0e0; border-radius: 30px; font-weight: 600; font-size: 14px; transition: all 0.3s ease; }
    .category-tabs a:hover, .category-tabs a.active { background-color: #c89b3f; color: white; border-color: #c89b3f; }
    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    .menu-card { background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease; border: 1px solid #f0f0f0; }
    .menu-card:hover { transform: translateY(-5px); }
    .menu-card-img { width: 100%; height: 220px; object-fit: cover; }
    .menu-card-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .menu-item-title { font-size: 20px; font-weight: 700; color: #111; margin-bottom: 8px; }
    .menu-item-desc { font-size: 14px; color: #666; line-height: 1.5; margin-bottom: 20px; flex-grow: 1; }
    .menu-item-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
    .menu-item-price { font-size: 22px; font-weight: 700; color: #0a5e10; }
    .add-to-cart-btn { background-color: #c89b3f; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease; }
    .add-to-cart-btn:hover { background-color: #0a5e10; }
</style>

<div class="menu-section">
    <h1 class="menu-title">Enfes Menümüz</h1>
    <p class="menu-subtitle">Taaza kalitesiyle hazırlanan, her damak tadına uygun taze lezzetler.</p>

    <ul class="category-tabs">
        <li><a href="menu.php?category=all" class="<?php echo $category == 'all' ? 'active' : ''; ?>">Tümü</a></li>
        <li><a href="menu.php?category=veg" class="<?php echo $category == 'veg' ? 'active' : ''; ?>">Vejetaryen</a></li>
        <li><a href="menu.php?category=non-veg" class="<?php echo $category == 'non-veg' ? 'active' : ''; ?>">Et Yemekleri</a></li>
        <li><a href="menu.php?category=local" class="<?php echo $category == 'local' ? 'active' : ''; ?>">Yerel Lezzetler</a></li>
        <li><a href="menu.php?category=sea-food" class="<?php echo $category == 'sea-food' ? 'active' : ''; ?>">Deniz Ürünleri</a></li>
        <li><a href="menu.php?category=chinese" class="<?php echo $category == 'chinese' ? 'active' : ''; ?>">Çin Mutfağı</a></li>
    </ul>

    <div class="menu-grid">
        <?php foreach ($menuItems as $item): 
            $fiyat_text = htmlspecialchars($item['price']) . ' TL'; 
        ?>
            <div class="menu-card">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="menu-card-img">
                <div class="menu-card-body">
                    <h3 class="menu-item-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="menu-item-desc"><?php echo htmlspecialchars($item['description']); ?></p>
                    
                    <div class="menu-item-footer">
                        <span class="menu-item-price"><?php echo $fiyat_text; ?></span>
                        
                        <form action="manage_cart.php" method="POST">
                            <input type="hidden" name="Item_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                            <input type="hidden" name="price" value="<?php echo $fiyat_text; ?>">
                            <button type="submit" name="Add_To_Cart" class="add-to-cart-btn">Sepete Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require "includes/footer.php"; ?>