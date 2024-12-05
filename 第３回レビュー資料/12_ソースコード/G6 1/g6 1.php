<?php
try {
    // データベース接続
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;
    dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );

    // フォームのデータを取得
    $product_name = $_POST['product_name'];

    // SQLクエリで商品名を検索
    $sql = $pdo->prepare("SELECT  protein_products WHERE name = ?");
    $sql->execute([$product_name]);
    $result = $sql->fetch();

    // 結果の確認
    if ($result) {
        // 一致した場合に画面遷移
        header("../G6/g6.html"); // 遷移先ページを指定
        exit();
    } else {
        // 一致しない場合のエラーメッセージ
        echo "商品名が一致しませんでした。";
    }
} catch (Exception $e) {
    echo "エラーが発生しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>