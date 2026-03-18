document.addEventListener('DOMContentLoaded', () => {

    const itemsTable = document.getElementById('items-table');
    const addItemBtn = document.getElementById('add-item');
    const totalAmountInput = document.getElementById('total-amount');
    const rowTemplate = document.querySelector('.item-row-template');
    const form = document.querySelector('form');

    function recalcRow(row) {
        const qty = parseFloat(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        row.querySelector('.total').innerText = (qty * price).toFixed(2);
        recalcTotal();
    }

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('#items-table tbody .item-row').forEach(row => {
            total += parseFloat(row.querySelector('.total').innerText) || 0;
        });
        totalAmountInput.value = total.toFixed(2);
    }

    addItemBtn.addEventListener('click', () => {
        const tbody = itemsTable.querySelector('tbody');
        const index = tbody.querySelectorAll('tr.item-row').length;

        // создаём копию шаблона
        const newRow = rowTemplate.cloneNode(true);
        newRow.classList.remove('d-none', 'item-row-template');
        newRow.classList.add('item-row');

        // заменяем __INDEX__ на текущий индекс
        let html = newRow.innerHTML;
        html = html.replace(/__INDEX__/g, index);
        newRow.innerHTML = html;

        // сброс значений
        newRow.querySelectorAll('input').forEach(input => {
            if (input.classList.contains('quantity')) input.value = 1;
            if (input.classList.contains('price')) input.value = 0;
        });
        newRow.querySelector('.total').innerText = '0.00';

        tbody.appendChild(newRow);
    });

    itemsTable.addEventListener('input', e => {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            recalcRow(e.target.closest('tr'));
        }
    });

    itemsTable.addEventListener('change', e => {
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('tr');
            const selected = e.target.selectedOptions[0];
            const price = selected.dataset.price || 0;
            row.querySelector('.price').value = price;
            recalcRow(row);
        }
    });

    itemsTable.addEventListener('click', e => {
        if (e.target.classList.contains('remove-row')) {
            const rows = itemsTable.querySelectorAll('tbody .item-row');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                recalcTotal();
            }
        }
    });

    // --- 💥 Исправлено: перед отправкой очищаем мусор и переиндексируем ---
    form.addEventListener('submit', () => {
    // 🧹 Удаляем шаблонную строку с __INDEX__, чтобы Laravel её не увидел
    document.querySelectorAll('.item-row-template').forEach(el => el.remove());

    const rows = document.querySelectorAll('.item-row');
    const items = [];

    rows.forEach(row => {
        const supplyId = row.querySelector('[name*="[supply_id]"]').value;
        const quantity = row.querySelector('[name*="[quantity]"]').value;
        const price = row.querySelector('[name*="[price]"]').value;

        if (supplyId) {
            items.push({ supply_id: supplyId, quantity, price });
        }
    });

    // 💥 Удаляем все старые элементы items[]
    document.querySelectorAll('[name^="items["]').forEach(el => el.remove());

    // 🔁 Пересоздаём чистые скрытые поля
    items.forEach((item, index) => {
        ['supply_id', 'quantity', 'price'].forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${index}][${key}]`;
            input.value = item[key];
            form.appendChild(input);
        });
    });
});

    // пересчёт при загрузке
    document.querySelectorAll('#items-table tbody .item-row').forEach(row => recalcRow(row));

});
