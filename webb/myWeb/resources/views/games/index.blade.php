@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Danh sách game</h2>
        <div class="row">
            @foreach($games as $game)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>{{ $game->title }}</h5>
                            <p>{{ $game->description }}</p>
                            <p>Giá: ${{ $game->price }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
