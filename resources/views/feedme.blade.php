<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Ogive</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #95c5ed;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                color: #545b62;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>

    <body>
    @extends('layouts.app')
    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-3 p-5">
                    <img src="/storage/">
                </div>
                <div class="col-9 pt-5">
                    <div class="d-flex justify-content-between align-items-baseline">
                        {{--                            <h1>{{$user->user_name}}</h1>--}}
                        {{--                            <button onclick="location.href='/p/create'">Add new Post</button>--}}
                    </div>
                    <div class="d-flex">
                        {{--                            <div class="pr-5"><strong>{{$user->posts->count()}}</strong> posts</div>--}}
                        <div class="pr-5"><strong>1</strong> followers</div>
                        <div class="pr-5"><strong>2</strong> following</div>
                    </div>
                    <div class="pt-4 font-weight-bold">
                        {{--                            {{$user->profile->title ?? "N/A"}}--}}
                    </div>
                    <div>
                        {{--                            {{$user->profile->bio ?? 'N/A'}}--}}
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                {{--                    @foreach($user->posts as $post)--}}
                {{--                        <div class="col-4 pb-5">--}}
                {{--                            <img src="/storage/{{ $post->image }}">--}}
                {{--                        </div>--}}
                {{--                    @endforeach--}}
            </div>
        </div>
    @endsection

    </body>
</html>
