@extends('layout')
@section('content')
    @if(session()->has('errors'))
        <div class="alert alert-success">
            {{ session('errors') }}
        </div>
    @endif

    <div class="row justify-content-center mt-7">
        <div class="col-md-4">
            <div class="card shadow">
                <h3 class="card-header text-center text-grey">Login</h3>
                <div class="card-body">
                    <form method="POST" action="{{ route('login.custom') }}">
                        @csrf
                        <div class="form-group mb-1">
                            <label for="email"></label>
                            <input type="text" autocomplete="off" placeholder="Email" id="email" class="form-control" name="email"
                                                              required
                                                              autofocus>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-2">
                            <label for="password"></label>
                            <input type="password" placeholder="Password" id="password" class="form-control"
                                                                 name="password" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="d-grid mx-auto">
                            <button type="submit" class="btn btn-danger btn-block shadow">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection