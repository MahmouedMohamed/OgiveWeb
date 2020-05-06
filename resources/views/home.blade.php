<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../img/ogive.png">
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
        <section class="bg-dark full-height" id="header">
            <div class="container align-center">
                <div class="row justify-content-md-center">
                    <div class="bg-light col-md-10">
                        <h1 class="title font-weight-bold pb-3 display-1 align-middle">
                            <span class="flex-center" >Hello</span>
                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="" id="footer">
            <div class="container">
                <div class="media-body content text-white">
                    <div class="col-12 col-md-3">
                        <div class="media-body">
                            <a href="https://mobirise.com/">
                                <img src="" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 display-7">
                        <h5 class="pb-3">

                        </h5>
                        <p class="">
                        </p>
                    </div>
                    <div class="col-12 col-md-3 display-7">
                        <h5 class="pb-3">
                        </h5>
                        <p class="mbr-text">
                        </p>
                    </div>
                    <div class="col-12 col-md-3 display-7">
                        <h5 class="pb-3">
                        </h5>
                        <p class="text-body">
                        </p>
                    </div>
                </div>
                <div class="footer-lower">
                    <div class="media">
                        <div class="col-sm-12">
                            <hr>
                        </div>
                    </div>
                    <div class="media bg-light">
                        <div class="col-sm-6 copyright">
                            <p class="display-7">
                                © Copyright 2019 Ogive - All Rights Reserved
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="list-inline align-right">
                                <div class="list-inline-item">
                                    <a href="https://twitter.com/mobirise" target="_blank">
                                        <span class="socicon-twitter socicon mbr-iconfont mbr-iconfont-social"></span>
                                    </a>
                                </div>
                                <div class="list-inline-item">
                                    <a href="https://www.facebook.com/pages/Mobirise/1616226671953247" target="_blank">
                                        <span class="socicon-facebook socicon mbr-iconfont mbr-iconfont-social"></span>
                                    </a>
                                </div>
                                <div class="list-inline-item">
                                    <a href="https://www.youtube.com/c/mobirise" target="_blank">
                                        <span class="socicon-youtube socicon mbr-iconfont mbr-iconfont-social"></span>
                                    </a>
                                </div>
                                <div class="list-inline-item">
                                    <a href="https://instagram.com/mobirise" target="_blank">
                                        <span class="socicon-instagram socicon mbr-iconfont mbr-iconfont-social"></span>
                                    </a>
                                </div>
                                <div class="list-inline-item">
                                    <a href="https://plus.google.com/u/0/+Mobirise" target="_blank">
                                        <span class="socicon-googleplus socicon mbr-iconfont mbr-iconfont-social"></span>
                                    </a>
                                </div>
                                <div class="list-inline-item">
                                    <a href="https://www.behance.net/Mobirise" target="_blank">
                                        <span class="socicon-behance socicon mbr-iconfont mbr-iconfont-social"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
    @endsection

</html>
