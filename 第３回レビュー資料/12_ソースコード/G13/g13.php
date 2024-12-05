<?php
// データベース接続設定
try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// セッションスタート
session_start();

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header('Location: ../G1/g1.php');
    exit;
}

// ログイン中のユーザーID
$user_id = $_SESSION['user_id'];

// カート内の商品を取得
$stmt = $pdo->prepare("
    SELECT ci.cart_item_id, ci.quantity, pp.name, pp.price, pp.image
    FROM cart_items ci
    JOIN cart c ON ci.cart_id = c.cart_id
    JOIN protein_products pp ON ci.product_id = pp.product_id
    WHERE c.user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 数量変更処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_item_id = $_POST['cart_item_id']; // カートアイテムID
    $quantity = $_POST['quantity']; // 更新する数量

    if ($quantity > 0) {
        // 数量を更新
        $stmt = $pdo->prepare('UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id');
        $stmt->execute(['quantity' => $quantity, 'cart_item_id' => $cart_item_id]);
    } else {
        // 数量が0以下の場合は削除
        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_item_id = :cart_item_id');
        $stmt->execute(['cart_item_id' => $cart_item_id]);
    }

    // ページをリロードして変更を反映
    header('Location: g13.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート</title>
    <link rel="stylesheet" href="g13.css">
    <link rel="stylesheet" href="reset.css">
</head>
<body>
<div class="container">

    <form action="../G14/g14.php" method="post">
        <button class="purchase-button">購入</button>
    </form>

    <?php if (count($cart_items) > 0): ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="item">
                <img src="../G12/image/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="item-info">
                    <p><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="quantity-control">
                        <!-- マイナスボタン -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo max($item['quantity'] - 1, 0); ?>">
                            <button type="submit" name="update_quantity" class="quantity-button">-</button>
                        </form>
                        <!-- 数量表示 -->
                        <span class="quantity-display"><?php echo $item['quantity']; ?></span>
                        <!-- プラスボタン -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                            <button type="submit" name="update_quantity" class="quantity-button">+</button>
                        </form>
                    </div>
                    <p class="price">¥<?php echo number_format($item['price']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <div id="total-price">
            合計: ¥<?php echo number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart_items))); ?>
        </div>
    <?php else: ?>
        <p>カートに商品がありません。</p>
    <?php endif; ?>
</div>

<div class="footer">
    <a href="../G12/index.php" class="footer-icon">
        <img src="home.png" alt="HOME">
        <span>HOME</span>
    </a>
    <a href="../G16/g16.html" class="footer-icon">
        <img src="user.png" alt="USER">
        <span>USER</span>
    </a>
    <a href="./g13.php" class="footer-icon">
        <img src="cart.png" alt="CART">
        <span>CART</span>
    </a>
</div>

</body>
</html>
