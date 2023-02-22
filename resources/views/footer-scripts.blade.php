<script>
    $(document).ready(function () {
        let contentCard = $('#contentCard');
        let loadingDiv  = $('#loading');
        let loading     = new bootstrap.Modal(loadingDiv);

        let checkRunning = checkRestoreInProgress();
        if (checkRunning !== '' && $(location).attr('pathname') !== '/details/' + checkRunning) {
            window.location.replace('/details/' + checkRunning);
        }

        if ($.trim(contentCard.html())) {
            contentCard.attr('style', 'display: block !important');
        }

        loadingDiv.find('.modal-body').html('Scanning for Backups - please wait');

        $('#scan-backups').on('beforeSend.ic', function () {
            loading.toggle();
        }).on('complete.ic', function () {
            loading.toggle();
            location.reload();
        });
    });

    function checkRestoreInProgress() {
        return $.ajax({
            url:    '/restore/check-running',
            method: 'GET',
            async:  false,
        }).responseText;
    }
</script>