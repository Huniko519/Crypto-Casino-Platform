@extends('installer::layouts.install')

@section('content')
    <p>Please create a MySQL database and input its access credentials below.</p>
    <form method="POST" action="{{ route('install.process', ['step' => $step]) }}">
        @csrf
        <div class="form-group">
            <label>Database host</label>
            <input type="text" name="host" placeholder="host" class="form-control" value="{{ old('host', config('database.connections.mysql.host')) }}" required>
        </div>
        <div class="form-group">
            <label>Database port</label>
            <input type="text" name="port" placeholder="port" class="form-control" value="{{ old('port', config('database.connections.mysql.port')) }}" required>
        </div>
        <div class="form-group">
            <label>Database name</label>
            <input type="text" name="name" placeholder="name" class="form-control" value="{{ old('name', config('database.connections.mysql.database')) }}" required>
        </div>
        <div class="form-group">
            <label>Database username</label>
            <input type="text" name="username" placeholder="username" class="form-control" value="{{ old('username', config('database.connections.mysql.username')) }}" required>
        </div>
        <div class="form-group">
            <label>Database password</label>
            <input type="password" name="password" placeholder="password" class="form-control" value="{{ old('password', config('database.connections.mysql.password')) }}" required>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Next</button>
        </div>
    </form>
@endsection