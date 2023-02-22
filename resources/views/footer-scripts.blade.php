<script>
    $(document).ready(function () {
        let loadingDiv  = $('#loading');
        let contentCard = $('#contentCard');

        if ($.trim(contentCard.html())) {
            contentCard.attr('style', 'display: block !important');
        }

        loadingDiv.find('.modal-body').html('Scanning for Backups - please wait');
        let loading = new bootstrap.Modal(loadingDiv);

        $('#scan-backups').on('beforeSend.ic', function () {
            loading.toggle();
        }).on('complete.ic', function () {
            loading.toggle();
            location.reload();
        });
    });
</script>
