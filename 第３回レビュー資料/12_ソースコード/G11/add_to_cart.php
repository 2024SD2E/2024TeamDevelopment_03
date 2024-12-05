<?php
session_start();  // セッションを開始

// ログインしているユーザーがいるか確認
if (!isset($_SESSION['user_id'])) {
    // ログインしていない場合はログインページにリダイレクト
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];  // 現在のユーザーID
$product_id = $_POST['product_id'];  // 商品ID
$quantity = $_POST['quantity'];  // 数量

// データベース接続
$pdo = new PDO('mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8', 'LAA1557127', 'Qaz73565');

// ユーザーのカートを取得
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cart) {
    // カートが存在しない場合、新しくカートを作成
    $stmt = $pdo->prepare("INSERT INTO cart (user_id) VALUES (:user_id)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // 新しく作成したカートIDを取得
    $cart_id = $pdo->lastInsertId();
} else {
    // 既存のカートIDを取得
    $cart_id = $cart['cart_id'];
}

// カートアイテムに商品を追加
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id");
$stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cart_item) {
    // すでにカートに入っている商品があれば、数量を増加
    $new_quantity = $cart_item['quantity'] + $quantity;
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE cart_item_id = :cart_item_id");
    $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
    $stmt->bindParam(':cart_item_id', $cart_item['cart_item_id'], PDO::PARAM_INT);
    $stmt->execute();
} else {
    // 新規に商品をカートに追加
    $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (:cart_id, :product_id, :quantity)");
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();
}

// カートページにリダイレクト
header('Location: cart.php');
exit();
