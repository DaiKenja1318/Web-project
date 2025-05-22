@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard</h1>
        <p>Chào mừng, {{ Auth::user()->name }}!</p>
    </div>
@endsection
