@extends('layout')

@section('content')
    @foreach($configs->chunk(2) as $chunk)
        <div class="row mb-3">
            @foreach($chunk as $index => $config)
                <div class="col-sm-6">
                    <div class="card shadow border-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <span class="badge bg-secondary shadow">{{ $config['server'] }}</span>
                                {{ $config['name'] }}
                            </h5>
                            <p class="card-text">
                                {{ $config['description'] }}
                            </p>
                            <a href="{{ url('/list/'.$index) }}"
                               class="btn btn-danger shadow">
                                show {{ \App\Http\Controllers\BackupController::getBackupList($index,true) }}
                                Backup(s)
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection