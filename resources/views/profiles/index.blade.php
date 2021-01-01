@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-3 p-5">
                <img src="/storage/">
            </div>
            <div class="col-9 pt-5">
                <div class="d-flex justify-content-between align-items-baseline">
                    <h1>{{$user->user_name}}</h1>
                    <button onclick="location.href='/p/create'">Add new Post</button>
                </div>
                <div class="d-flex">
                    <div class="pr-5"><strong>{{$user->posts->count()}}</strong> posts</div>
                    <div class="pr-5"><strong>1</strong> followers</div>
                    <div class="pr-5"><strong>2</strong> following</div>
                </div>
                <div class="pt-4 font-weight-bold">
                    {{$user->profile->title ?? "N/A"}}
                </div>
                <div>
                    {{$user->profile->bio ?? 'N/A'}}
                </div>
            </div>
        </div>

        <div class="row pt-5">
            @foreach($user->posts as $post)
                <div class="col-4 pb-5">
                    <img src="/storage/{{ $post->image }}">
                </div>
            @endforeach
        </div>
    </div>
@endsection
