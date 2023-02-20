@extends('layout')

@section('breadcrumb')
    {{ $config->get('name') }}
@endsection

@section('contentCard')
    @php($carbon = new \Carbon\Carbon())
    <table class="table table-sm">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Filename</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($backupList as $index => $backup)
            <tr class="h-35px
                    @if($backup->getAttribute('deleted') === 1)
                        text-danger
                    @endif
            ">
                <th scope="row">{{ $index + 1 }}</th>
                <td>{{ $backup->getAttribute('filename') }}</td>
                <td>{{  $carbon::createFromTimestamp($backup->getAttribute('file_created_at'), new DateTimeZone('Europe/Berlin'))->format('d.m.Y') }}</td>
                <td>{{  $carbon::createFromTimestamp($backup->getAttribute('file_created_at'), new DateTimeZone('Europe/Berlin'))->format('H:i') }}</td>
                <td>
                    @if($backup->getAttribute('deleted') === 0)
                        <a href="{{ url('/details/'.$backup->getAttribute('id')) }}">
                            <box-icon class="icon" color="grey" name="detail"></box-icon>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection