document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.container');
    
    container.addEventListener('click', function (event) {
        // 数量変更ボタン（+/-）がクリックされた場合
        if (event.target.classList.contains('quantity-button')) {
            const isIncrement = event.target.textContent === '+'; // "+" ボタンなら数量を増加
            const quantityDisplay = event.target.closest('.quantity-control').querySelector('.quantity-display');
            let quantity = parseInt(quantityDisplay.textContent);

            // 数量を変更（最低値は1）
            quantity = isIncrement ? quantity + 1 : Math.max(1, quantity);

            // 変更した数量を画面に反映
            quantityDisplay.textContent = quantity;

            // 合計金額を更新
            updateTotalPrice();
        }

        // 削除ボタンがクリックされた場合
        if (event.target.classList.contains('delete-button')) {
            const item = event.target.closest('.item');
            item.remove(); // アイテムを削除

            // 合計金額を再計算
            updateTotalPrice();
        }
    });

    // 初期の合計金額を設定
    updateTotalPrice();
});

// 合計金額を計算して更新する関数
function updateTotalPrice() {
    const allItems = document.querySelectorAll('.item');
    let total = 0;

    allItems.forEach(item => {
        const quantity = parseInt(item.querySelector('.quantity-display').textContent);
        const price = parseInt(item.querySelector('.price').textContent.replace('¥', ''));
        total += quantity * price;
    });

    const totalPriceElement = document.getElementById('total-price');
    totalPriceElement.textContent = `合計 ¥${total}`;
}