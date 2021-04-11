<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="{{asset('landing/assets/css/bootstrap.min.css')}}"> -->
    <!-- <link rel="stylesheet" href="{{asset('landing/assets/css/default.css')}}"> -->
    <link rel="stylesheet" href="{{asset('landing/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" /> -->
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="shortcut icon" type="image/jpg" href="{{asset('img/ogive.png')}}"/>
</head>

<body>
@include('breedme.includes.header')

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
                                    <i class="fas fa-map-marker-alt"></i> Owner Place
                                    </div>
                                    <div class="col-6">
                                    <a class="see-more float-right" href="/pet/{{$pet['id']}}" title="{{$pet['name']}}">See more</a>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-6">
                                        <p>Sex: {{$pet['sex']}}</p>
                                    </div>
                                    <div class="col-6">
                                        <p>Age: {{$pet['age']}}</p>
                                    </div>
                                </div>
                                <p>Notes: {{$pet['notes']}}</p> -->
                                <!-- Button trigger modal -->
                                <!-- <button type="button" class="btn btn-card" data-toggle="modal"
                                    data-target="#exampleModal">
                                    Request
                                </button> -->


                                <!-- <a href="#" class="btn-card">Request</a> -->
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @endif
                </div>
            </div>
        </section>
        <!-- details card section starts from here -->
    </div>



    <!--====== Jquery js ======-->
    <script src="{{asset('landing/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/vendor/modernizr-3.7.1.min.js')}}"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{asset('landing/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/bootstrap.min.js')}}"></script>


    <!--====== Main js ======-->
    <script src="{{asset('landing/assets/js/main.js')}}"></script>

</body>

</html>
