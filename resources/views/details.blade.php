@extends('layout')

@section('breadcrumb')
    <span style="color: grey"><a class="text-grey"
                                 href="{{ url('/list/'.$backup->getAttribute('type')) }}">{{ $config->get('name') }}</a></span> - {{ $backup->getAttribute('filename') }}
@endsection

@section('contentCard')
    @php($carbon = new \Carbon\Carbon())
    <table class="table table-sm">
        <thead>
        <tr>
            <th scope="col">Filename</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $backup->getAttribute('filename') }}</td>
            <td>{{  $carbon::createFromTimestamp($backup->getAttribute('file_created_at'), new DateTimeZone('Europe/Berlin'))->format('d.m.Y') }}</td>
            <td>{{  $carbon::createFromTimestamp($backup->getAttribute('file_created_at'), new DateTimeZone('Europe/Berlin'))->format('H:i') }}</td>
        </tr>
        </tbody>
    </table>

    <div class="mb-1">
        Progress: <span id="progress">0</span>%
    </div>
    <div class="progress mb-3">
        <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0"></div>
    </div>

    <div class="alert alert-danger" role="alert">
        You are about to restore a backup. The following commands will be issued:

        <ul class="mb-3">
            @foreach(\App\Helper\CommandHelper::prepareCommands($backup->getAttribute('filename')) as $command)
                <li>{{ $command['command'] }} <small class="text-grey">({{ $command['description'] }})</small></li>
            @endforeach
        </ul>

        <span>
            To continue, type <b>{{ $backup->getAttribute('filename') }}</b> into the field and click on "Start Restore Job"
        </span>
    </div>

    <div class="mb-3">
        <label for="confirmation"></label>
        <input class="form-control" type="text" id="confirmation" autocomplete="off" style="width:300px;">
    </div>

    <form>
        @csrf

        <div id="main">
            <input type="hidden" name="backupId" value="{{ $backup->getAttribute('id') }}">
            <input type="hidden" name="uuid" id="uuid" value="">
            <button id="restore-button" disabled="disabled" class="btn btn-danger shadow"
                    ic-post-to="/restore">Start Restore Job
            </button>
        </div>

        <script>
            let interval = null;
            let url      = null;

            $(document).ready(function () {
                generateUuid();
                $('#restore-button').prop('disabled', true);
            });

            $('#confirmation').keyup(function () {
                let currentValue  = $(this).val();
                let requiredValue = $('td:first-child').text();

                if (currentValue === requiredValue) {
                    $('#restore-button').prop('disabled', false);
                } else {
                    $('#restore-button').prop('disabled', true);
                }
            });

            $(function () {
                $('#restore-button').on('beforeSend.ic', function () {

                    updateProgress(0);

                    url      = '/restore/status/' + $('#uuid').val();
                    interval = setInterval(function () {
                        updateProgress(getStatus(url).responseText);
                    }, 1000);
                }).on('complete.ic', function () {
                    updateProgress(getStatus(url).responseText);
                    clearInterval(interval);
                    generateUuid();
                    $('#confirmation').val('').keyup();
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
            }
        </script>
    </form>
@endsection