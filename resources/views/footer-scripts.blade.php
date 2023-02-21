<script>
    $(document).ready(function () {
        let contentCard = $('#contentCard');

        if ($.trim(contentCard.html())) {
            contentCard.attr('style', 'display: block !important');
        }

        let loading = new bootstrap.Modal(document.getElementById('loading'));

        $('#scan-backups').on('beforeSend.ic', function () {
            loading.toggle();
        }).on('complete.ic', function () {
            loading.toggle();
            location.reload();
        });
    });
</script>