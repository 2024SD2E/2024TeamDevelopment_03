<?php
try {
    // データベース接続
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );

    // フォームから送信されたデータを取得
    $current_name = $_POST['current_name']; // 現在の商品名
    $new_name = $_POST['new_name'];        // 新しい商品名
    $details = $_POST['details'];          // 詳細
    $price = $_POST['price'];              // 価格
    $quantity = $_POST['quantity'];        // 数量
    $image = $_POST['image'];              // 画像URL

    // 商品が存在するか確認するクエリ
    $check_sql = $pdo->prepare("SELECT * FROM protein_products WHERE name = ?");
    $check_sql->execute([$current_name]);

    if ($check_sql->rowCount() > 0) {
        // 更新クエリの構築
        $update_sql = $pdo->prepare("
            UPDATE protein_products
            SET name = COALESCE(?, name),   -- 新しい商品名（指定があれば更新）
                shosai = COALESCE(?, shosai), -- 詳細
                price = COALESCE(?, price),   -- 価格
                quantity = COALESCE(?, quantity), -- 数量
                image = COALESCE(?, image)    -- 画像URL
            WHERE name = ?
        ");
        // クエリを実行
        $result = $update_sql->execute([
            $new_name, $details, $price, $quantity, $image, $current_name
        ]);

        if ($result) {
            echo "商品情報が正常に更新されました。";
        } else {
            echo "商品情報の更新に失敗しました。";
        }
    } else {
        // 商品が存在しない場合
        echo "指定された商品名が見つかりません。";
    }
} catch (Exception $e) {
    echo "エラーが発生しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
 <a href="../G4/g4.html" class="back">＜戻る</a>