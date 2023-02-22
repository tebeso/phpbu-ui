@section('additional-footer-scripts')
    <script>
        let interval = null;
        let url      = null;

        let confirmation  = $('#confirmation');
        let restoreButton = $('#restore-button');
        let loadingCircle = $('#loading-circle');

        $(document).ready(function () {
            generateUuid();
            restoreButton.prop('disabled', true);
        });

        confirmation.keyup(function () {
            let currentValue  = $(this).val();
            let requiredValue = $('td:first-child').text();

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

                url      = '/restore/status/' + $('#uuid').val();
                interval = setInterval(function () {
                    let status = $.parseJSON(getStatus(url).responseText);
                    updateLog(status['log']);
                    updateProgress(status['progress']);
                }, 1000);
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

            $('#progress').text(progress);
            $('#progress-bar').css('width', progress + '%');

            restoreButton.prop('disabled', true);

            if (parseInt(progress) === 100) {
                confirmation.prop('disabled', false).val('').keyup();
                loadingCircle.addClass('visually-hidden');

                generateUuid();
                clearInterval(interval);

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
    </script>
@endsection