<?php
session_start();
 
try {
    $pdo = new PDO(
        'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1557127-beast;charset=utf8',
        'LAA1557127',
        'Qaz73565'
    );
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}
 
// POSTデータを取得
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
 
// メールアドレスが存在するか確認
$stmt = $pdo->prepare('SELECT * FROM protein_user WHERE mail = :mail');
$stmt->execute(['mail' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
 
if ($user) {
    // パスワードの確認
    if ($password === $user['password']) {
        // セッションにユーザー情報を保存
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
 
        // ログイン成功後にリダイレクト
        header('Location: ../G12/index.php');
        exit;
    } else {
        // パスワードが一致しない場合
        $error_message = 'メールアドレスまたはパスワードが違います。';
    }
} else {
    // ユーザーが見つからない場合
    $error_message = 'メールアドレスまたはパスワードが違います。';
}
 
// エラーメッセージを表示
if (isset($error_message)) {
    echo '<p style="color:red;">' . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . '</p>';
}
?>