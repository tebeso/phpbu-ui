@extends('layout')

@section('breadcrumb')
    <span style="color: grey"><a class="text-grey"
                                 href="{{ url('/list/'.$backup->getAttribute('type')) }}">{{ $config->get('name') }}</a></span> - {{ $backup->getAttribute('filename') }}
@endsection

@section('contentCard')
    @php($carbon = new \Carbon\Carbon())

    @if($hasCredentials === false)
        <div id="no-credentials" class="alert alert-danger">
            Credentials are not set. Please check your server configuration.
        </div>
    @endif
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

    <div class="alert alert-info" role="alert">
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
    </form>
@endsection

@section('additional-footer-scripts')
    @include('details-scripts')
@endsection
