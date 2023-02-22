@section('additional-footer-scripts')
    <script>
        let url = null;

        let confirmation  = $('#confirmation');
        let restoreButton = $('#restore-button');
        let loadingCircle = $('#loading-circle');

        $(document).ready(function () {
            generateUuid();
            restoreButton.prop('disabled', true);
        });

        confirmation.keyup(function () {
            let currentValue  = $(this).val();
            let requiredValue = $('td:nth-child(2)').text();

            if (currentValue === requiredValue && $('#no-credentials').length === 0) {
                restoreButton.prop('disabled', false);
            } else {
                restoreButton.prop('disabled', true);
            }
        });

        $(function () {
            restoreButton.on('beforeSend.ic', function () {

                updateProgress(0);

                confirmation.prop('disabled', true);
                loadingCircle.removeClass('visually-hidden');

                url = '/restore/status/' + $('#uuid').val();
            });
        });

        function generateUuid() {
            $.ajax({
                url:    '/generate-uuid',
                method: 'GET',
                async:  true,
            }).done(function (d, j, r) {
                $('#uuid').val(r.responseText);
            });
        }

        function getStatus(url) {
            return $.ajax({
                url:    url,
                method: 'GET',
                async:  false,
            });
        }

        function updateProgress(progress) {

            progress = parseInt(progress);

            $('#progress').text(progress);
            $('#progress-bar').css('width', progress + '%');

            restoreButton.prop('disabled', true);

            if (progress === 100) {
                confirmation.prop('disabled', false).val('').keyup();
                loadingCircle.addClass('visually-hidden');

                generateUuid();
                setTimeout(
                    function () {
                        $('ul').find('li').delay(2000).removeAttr('style');
                    }, 2000);
            }
        }

        function updateLog(log) {
            log.forEach((item) => {
                $('ul').find('li:contains(' + item['command'] + ') ').css('text-decoration', 'line-through');
            });
        }

        Echo.channel('phpbu.' + $('td:first-child').text()).listen('.BackupInProgress', (response) => {
            updateLog(response['status']['log']);
            updateProgress(response['status']['progress']);
        });
    </script>
@endsection