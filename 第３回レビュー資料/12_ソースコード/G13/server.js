

const express = require('express');
const mysql = require('mysql');
const app = express();

// JSONデータの受信を可能にする
app.use(express.json());

// データベース接続設定
const db = mysql.createConnection({
    host: 'mysql309.phy.lolipop.lan',
    user:  'LAA1557137',
    password:  'Pass0104',
    database: 'LAA1557137-beast',
});


db.connect((err) => {
    if (err) {
        console.error('データベース接続エラー:', err);
        return;
    }
    console.log('データベース接続成功');
});

// カートの数量を更新するエンドポイント
app.post('/update-cart', (req, res) => {
    const { id, quantity } = req.body;
    console.log('受け取ったデータ:', req.body); // 追加: リクエストデータを確認
    
    const query = 'UPDATE cart SET quantity = ? WHERE product_id = ?';
    db.query(query, [quantity, id], (error, results) => {
        if (error) {
            console.error('データベースエラー:', error);
            return res.status(500).json({ success: false });
        }
        res.json({ success: true });
    });
});
const cors = require('cors');
app.use(cors());

// サーバーの起動
app.listen(3000, () => console.log('サーバーが起動しました (ポート: 3000)'));