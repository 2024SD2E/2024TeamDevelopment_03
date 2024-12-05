<?php
// データベース接続
try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

// ジャンル選択値の取得
$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : 'all';
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// SQL生成
if ($selected_genre === 'all' && empty($search_query)) {
    $stmt = $pdo->query('SELECT * FROM protein_products');
} else {
    $sql = 'SELECT * FROM protein_products WHERE 1=1';
    
    // ジャンルフィルタ
    if ($selected_genre !== 'all') {
        $sql .= ' AND junle_name = :genre';
    }
    
    // 検索キーワードフィルタ
    if (!empty($search_query)) {
        $sql .= ' AND name LIKE :search_query';
    }

    $stmt = $pdo->prepare($sql);
    
    if ($selected_genre !== 'all') {
        $stmt->bindValue(':genre', strtoupper($selected_genre), PDO::PARAM_STR); // WHEY, SOY, CASEIN
    }

    if (!empty($search_query)) {
        $stmt->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protein Store</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- ヘッダー -->
        <header>
            <div class="logo">
                <img src="Beast.jpg" alt="Beast Logo" class="logo-image">
                <span class="logo-text">Beast</span>
            </div>
            <div class="search-bar">
                <form action="" method="GET">
                    <input type="text" name="query" placeholder="何をお探しですか？" value="<?= htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8') ?>" required>
                    <button type="submit">検索</button>
                </form>
            </div>
            <div class="category">
                <form method="GET" action="">
                    <label for="genre-select">ジャンル:</label>
                    <select id="genre-select" name="genre" onchange="this.form.submit()">
                        <option value="all" <?= $selected_genre === 'all' ? 'selected' : '' ?>>全て</option>
                        <option value="whey" <?= $selected_genre === 'whey' ? 'selected' : '' ?>>ホエイ</option>
                        <option value="soy" <?= $selected_genre === 'soy' ? 'selected' : '' ?>>ソイ</option>
                        <option value="casein" <?= $selected_genre === 'casein' ? 'selected' : '' ?>>カゼイン</option>
                    </select>
                </form>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main>
            <div class="product-grid">
            <?php
            // 商品一覧表示
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="product">';
                echo '<a href="../G11/g11.php?product_id=' . htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<img src="./image/' . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . '" alt="Product Image">';
                echo '</a>';
                echo '<p>' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '</div>';
            }
            ?>
            </div>
        </main>

        <!-- フッター -->
        <footer>
    <div class="footer-icon">
        <a href="index.php">
            <img src="home.png" alt="HOME">
            <p>HOME</p>
        </a>
    </div>
    <div class="footer-icon">
        <a href="../G16/g16.html">
            <img src="user.png" alt="USER">
            <p>USER</p>
        </a>
    </div>
    <div class="footer-icon">
        <a href="../G13/g13.php">
            <img src="cart.png" alt="CART">
            <p>CART</p>
        </a>
    </div>
</footer>
    </div>
</body>
</html>
