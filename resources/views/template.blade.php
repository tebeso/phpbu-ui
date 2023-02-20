<form>
    @csrf
    @php($uuid = \App\Http\Controllers\BackupController::generateUuid())

    <div id="main" ic-get-from="/restore/{{ $uuid }}">
        <!-- This post to /job causes the outer div to refresh due to intercooler's dependencies system -->
        <button class="btn btn-primary" ic-post-to="/restore/{{ $uuid }}/terminal-20221220-0450">Start Job</button>
    </div>

    <script>
        let interval = null;
        let url      = null;

        $(function () {
            $('.btn').on('beforeSend.ic', function () {
                url      = $(this).parent().attr('ic-src');
                interval = setInterval(function () {
                    getStatus(url);
                }, 1000);
            }).on('complete.ic', function () {
                getStatus(url);
                clearInterval(interval);
            });
        });

        function getStatus(url) {
            $.get(url, function (data) {
                console.log(data);
            });
        }
    </script>
</form>