<div id="loadingOverlay" class="loading-overlay">
    <div class="spinner"></div>
</div>

<script>
function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
}
function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
}

document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        if (!form.hasAttribute('data-no-loading')) {
            showLoading();
        }
    });
});

document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = link.getAttribute('href');
        if (href && !href.startsWith('#') && !href.startsWith('javascript:') &&
            !link.hasAttribute('data-no-loading') && !link.target) {
            showLoading();
        }
    });
});
</script>
