@extends('layouts.index') <!-- Reference the layout file -->

@section('title', 'Login') <!-- Define the title -->

@section('content') <!-- Define the content -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="text-center display-4"><b>Perumda Tirta Manuntung</b></h1>
            <h2 class="text-center">Aplikasi Ticket Entry</h2>
            <hr>
            @if(session('error'))
            <div class="alert alert-danger">
                <b>Oops!</b> {{ session('error') }}
            </div>
            @endif
            <form action="{{ route('actionlogin') }}" method="post">
                @csrf
                <div class="form-group">
                    <label style="font-size: 1.5rem;">User</label>
                    <input type="text" name="name" class="form-control input" placeholder="Enter User Name" required>
                </div>
                <div class="form-group">
                    <label style="font-size: 1.5rem;">Role</label>
                    <select name="role" class="form-control input" required>
                        <option value="" disabled selected>Select a Role</option>
                        @foreach($role as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-size: 1.5rem;">Password</label>
                    <input type="password" name="password" class="form-control input" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>
@endsection
