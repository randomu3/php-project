    <!-- jQuery скрипты with cache busting -->
    <script src="/assets/js/app.js?v=<?= ASSET_VERSION ?>"></script>
    
    <!-- Инициализация Lucide иконок -->
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    
    <!-- Дополнительные скрипты страницы -->
    <?php if (isset($additionalJS)): ?>
        <script><?= $additionalJS ?></script>
    <?php endif; ?>
</body>
</html>
