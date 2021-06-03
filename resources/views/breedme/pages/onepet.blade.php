<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('landing/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="shortcut icon" type="image/jpg" href="{{asset('img/ogive.png')}}" />

</head>

<body>
    @include('breedme.includes.header')
    <div class="test">
        <section class="details-card">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-img">
                            <img src="/{{$pet['image']}}" alt="" class="onepet-image">
                            <span>
                                <h4> {{$pet['status'] == 1? "Available" : "Token"}}</h4>
                            </span>
                        </div>
                    </div>

                </div>

                <h3> Facts About Me </h3>
                <div class="card-desc">
                    <h3>{{$pet['name']}}</h3>
                    <i class="fas fa-map-marker-alt"></i>  {{$pet['user']['address']}}
                    <div class="row">
                        <div class="col-4">
                            <span class="border-box text-center">
                                <b>{{$pet['sex']}}</b>
                                <p>Sex</p>
                            </span>
                        </div>
                        <div class="col-4">
                            <span class="border-box text-center">
                                <b>{{$pet['age']}} Months</b>
                                <p>Age</p>
                            </span>
                        </div>
                        <div class="col-4">
                            <span class="border-box text-center">
                                <b>{{$pet['type']}}</b>
                                <p>Type</p>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <h3>Pet Story</h3>
                        <p>Pet Story. Blind Woman and Boy with Glasses with Stick and Dog Guide Walking.
                            Little Girl
                            Patting Animal Flyer. Old Character Going with Walker and Puppy Flat Cartoon Vector
                            Illustration.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex flex-row user-info"><img class="rounded-circle"
                                    src="{{url($pet['user']['image'])}}" width="40" height="50">
                                <div class="d-flex flex-column justify-content-start ml-2"><span
                                        class="d-block font-weight-bold name"> {{$pet['user']['user_name']}}</span><span
                                        class="date text-black-50">Shared publicly - Jan 2021</span></div>
                            </div>
                        </div>
                        <div class="col-md-6 float-right">
                            <!-- Button trigger modal -->
                            <div class="float-right">
                                <button type="button" class="btn-request" data-toggle="modal"
                                    data-target="#exampleModal">
                                    Contact The owner
                                </button>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
    </div>
    </section>
    </div>

    <!-- Modal -->
    <div class="modal fade  bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Adoption Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="#" class="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile Number:</label>
                                    <input class="form-control" type="text" name="mobilenumber">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Adoption Place:</label>
                                    <select name="address" class="form-control">
                                        <option>House</option>
                                        <option>Garden</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address:</label>
                                    <textarea class="form-control" type="text" name="address"></teaxtarea>
                                </div>
                            </div>


                        <div id="wrapper" class=" form-group form-check-inline">
                            <label for="yes_no_radio">Do you agree to the terms?</label>
                            <p>
                                <input type="radio" name="accepted_terms" class="form-check-input" value="yes"
                                    checked>Yes</input>
                            </p>
                            <p>
                                <input type="radio" name="accepted_terms" class="form-check-input">No</input>
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Do you have experience in treating pets?:</label>
                            <p>
                                <input type="radio" name="accepted_terms" class="form-check-input" value="yes"
                                    checked>Yes</input>
                            </p>
                            <p>
                                <input type="radio" name="accepted_terms" class="form-check-input">No</input>
                            </p>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('landing/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/vendor/modernizr-3.7.1.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/main.js')}}"></script>

</body>

</html>