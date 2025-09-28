<div class="col-6">
    <form action="/Quest5/index.php?route=/review/update" method="post" style="display: flex; flex-direction: column; gap: 1rem;">
        <input type="text" value="<?= htmlspecialchars($review['name'] ?? '') ?>" name="name" placeholder="Полное имя" required>
        <input type="text" value="<?= htmlspecialchars($review['address'] ?? '') ?>" name="address" placeholder="Адрес">
        <input type="text" value="<?= htmlspecialchars($review['comment'] ?? '') ?>" name="comment" placeholder="Отзыв" required>
        <input type="hidden" name="entity" value="review">
        <input type="hidden" value="<?= (int) ($review['id'] ?? 0) ?>" name="id">

        <button type="submit">Сохранить</button>
    </form>
</div>