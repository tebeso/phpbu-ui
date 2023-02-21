@section('additional-footer-scripts')
    <script>
        let interval = null;
        let url      = null;

        let confirmation  = $('#confirmation');
        let restoreButton = $('#restore-button');

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
                confirmation.val('Restore in progress').prop('disabled', true);

                url      = '/restore/status/' + $('#uuid').val();
                interval = setInterval(function () {
                    updateProgress(getStatus(url).responseText);
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

        function updateProgress(percent) {

            $('#progress').text(percent);
            $('#progress-bar').css('width', percent + '%');

            restoreButton.prop('disabled', true);

            if (parseInt(percent) === 100) {
                confirmation.prop('disabled', false).val('').keyup();

                generateUuid();
                clearInterval(interval);
            }
        }
    </script>
@endsection