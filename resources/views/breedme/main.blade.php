<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('/landing/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="shortcut icon" type="image/jpg" href="{{asset('img/ogive.png')}}"/>
    <link href="/tailwind.css" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">

</head>

<body>
@include('breedme.includes.header')
<div id="intro">
</div>
<section class="search-sec">
    <div class="container">

        <form action="#" method="post" novalidate="novalidate">
                    <div class="row text-center">
                        <div class="col-lg-4 col-md-4 col-sm-12 pr-1">
                            <input type="text" class="form-control search-slt" placeholder="Enter Pickup City">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 pr-1">
                            <input type="text" class="form-control search-slt" placeholder="Enter Pet City">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 pr-1">
                            <select class="form-control search-slt" id="exampleFormControlSelect1">
                                <option>Select type</option>
                                <option>Dog</option>
                                <option>Cat</option>
                                <option>Birds</option>
                                <option>Spiders</option>
                                <option>Others</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 pr-1">
                            <button type="button" class="btn btn-danger wrn-btn">Search</button>
                        </div>
                    </div>
        </form>
    </div>
</section>


    <div class="test">
        <section class="details-card">
            <div class="container">
                <div class="row">
                    @if(!empty($pets))
                    @foreach($pets as $pet)

                    <div class="col-md-4">
                        <div class="card-content">
                            <div class="card-img">
                                <img src="{{$pet['image']}}" alt="" class="pet-image">
                                <span>
                                    <h4> {{$pet['status'] == 1? "Available" : "Token"}}</h4>
                                </span>
                            </div>
                            <div class="card-desc">
                                <h3>{{$pet['name']}}</h3>
                                <div class="row">
                                    <div class="col-6">
                                    <i class="fas fa-map-marker-alt"></i> {{$pet['user']['address']}}
                                    </div>
                                    <div class="col-6">
                                    <a class="see-more float-right" href="/pet/{{$pet['id']}}" title="{{$pet['name']}}">See more</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @endforeach
                    @endif

                    {{$pets->links()}}
                </div>
            </div>
        </section>
        <!-- details card section starts from here -->
    </div>

    <div class="donation">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <div class="text-center center">
                        <img src="{{asset('images/animals.png')}}" class="img-responsive footer-img">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <div class="text-center center">
                    <h3 class="">In addition, you can make a donation</h3>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <i class="far fa-envelope-open"></i>
                            <a href="#!" class="text-dark py-3">email@ogive.com</a>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <a href="#!" class="text-dark py-3">+02201289611214</a>
                        </li>

                    </ul>
                </div>

                </div>
            </div>
        </div>

    </div>
<!-- Footer -->
<footer class="bg-light text-center text-lg-start">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">For Questions and Suggestions</h5>
                <hr class="deep-orange accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">

                <ul class="list-unstyled mb-0">
                    <li>
                        <i class="far fa-envelope-open"></i>
                        <a href="#!" class="text-dark py-3">email@ogive.com</a>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <a href="#!" class="text-dark py-3">+02201289611214</a>
                    </li>

                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-0">Links</h5>

                <ul class="list-unstyled">
                    <li>
                        <a href="/pets" class="text-dark">Breed Me</a>
                    </li>
                    <li>
                        <a href="#!" class="text-dark">Feed Me</a>
                    </li>
                    <li>
                        <a href="/ahed/ahed" class="text-dark">عهد</a>
                    </li>
                    <li>
                        <a href="#!" class="text-dark">Link 4</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <div class="text-center center">
                    <img src="{{asset('images/161848416785290501.png')}}" class="img-responsive footer-img">
                </div>
            </div>


        </div>
    </div>

    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2021 Copyright:
        <a class="text-dark" href="https://Breedme.com/">Breedme.ogive.com</a>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <!--====== Jquery js ======-->
    <script src="{{asset('landing/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/vendor/modernizr-3.7.1.min.js')}}"></script>
    <!--====== Bootstrap js ======-->
    <script src="{{asset('landing/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/bootstrap.min.js')}}"></script>
    <!--====== Main js ======-->
    <script src="{{asset('landing/assets/js/main.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

</body>

</html>
