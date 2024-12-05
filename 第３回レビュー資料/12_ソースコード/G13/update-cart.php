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

// POSTデータの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_item_id = $_POST['cart_item_id'];
    $quantity = max($_POST['quantity'], 0);

    if ($quantity > 0) {
        // 数量を更新
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id");
        $stmt->execute(['quantity' => $quantity, 'cart_item_id' => $cart_item_id]);
    } else {
        // 数量が0の場合は商品を削除
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = :cart_item_id");
        $stmt->execute(['cart_item_id' => $cart_item_id]);
    }
}

// 元のページにリダイレクト
header('Location: g13.php');
exit;
