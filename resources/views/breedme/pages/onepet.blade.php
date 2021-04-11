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
                    <i class="fas fa-map-marker-alt"></i> Owner Place
                    <div class="row">
                        <div class="col-4">
                            <span class="border-box text-center">
                                <b>{{$pet['sex']}}</b>
                                <p>Sex</p>
                            </span>
                        </div>
                        <div class="col-4">
                            <span class="border-box text-center">
                                <b>{{$pet['age']}}</b>
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
                                    src="{{url('img/mahmoued.jpg')}}" width="40" height="50">
                                <div class="d-flex flex-column justify-content-start ml-2"><span
                                        class="d-block font-weight-bold name">Mahmoued Mohamed</span><span
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
    <!-- details card section starts from here -->
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
                                    <input class="form-control" type="text" name="address">
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

                        <div class="form-group">
                            <label>Address:</label>
                            <textarea class="

                            -control" type="text" name="address"></textarea>
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
    <!-- <section id="testimonial" class="testimonial-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <div class="single-testimonial">
                        <div class="testimonial-text">
                            <p class="text">
                                HI
                            </p>
                        </div>
                        <div class="testimonial-author d-sm-flex justify-content-between">
                            <div class="author-info d-flex align-items-center">
                                <div class="author-image">
                                    <img src="{{asset('landing/assets/images/author-1.jpg')}}" alt="author">
                                </div>
                                <div class="author-name media-body">
                                    <h5 class="name">Mr. Jems Bond</h5>
                                    <span class="sub-title">CEO Mbuild Firm</span>
                                </div>
                            </div>
                            <div class="author-review">
                                <ul class="star">
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                </ul>
                                <span class="review">( 7 Reviews )</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-testimonial">
                        <div class="testimonial-text">
                            <p class="text">“Praesent scelerisque, odio eu fermentum malesuada, nisi arcu volutpat nisl, sit amet convallis nunc turp.”</p>
                        </div>
                        <div class="testimonial-author d-sm-flex justify-content-between">
                            <div class="author-info d-flex align-items-center">
                                <div class="author-image">
                                    <img src="{{asset('landing/assets/images/author-1.jpg')}}" alt="author">
                                </div>
                                <div class="author-name media-body">
                                    <h5 class="name">Mr. Jems Bond</h5>
                                    <span class="sub-title">CEO Mbuild Firm</span>
                                </div>
                            </div>
                            <div class="author-review">
                                <ul class="star">
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                </ul>
                                <span class="review">( 7 Reviews )</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-testimonial">
                        <div class="testimonial-text">
                            <p class="text">“Praesent scelerisque, odio eu fermentum malesuada, nisi arcu volutpat nisl, sit amet convallis nunc turp.”</p>
                        </div>
                        <div class="testimonial-author d-sm-flex justify-content-between">
                            <div class="author-info d-flex align-items-center">
                                <div class="author-image">
                                    <img src="{{asset('landing/assets/images/author-1.jpg')}}" alt="author">
                                </div>
                                <div class="author-name media-body">
                                    <h5 class="name">Mr. Jems Bond</h5>
                                    <span class="sub-title">CEO Mbuild Firm</span>
                                </div>
                            </div>
                            <div class="author-review">
                                <ul class="star">
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                    <li><i class="lni lni-star-filled"></i></li>
                                </ul>
                                <span class="review">( 7 Reviews )</span>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </section> -->



    <!-- <section id="contact" class="contact-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-10">
                    <div class="section-title text-center pb-30">
                        <h3 class="title">Contact</h3>
                        <p class="text">Stop wasting time and money designing and managing a website that doesn’t get results. Happiness guaranteed!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-map mt-30">
                        <iframe id="gmap_canvas" src="https://maps.google.com/maps?q=Mission%20District%2C%20San%20Francisco%2C%20CA%2C%20USA&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                    </div>
                </div>
            </div>
            <div class="contact-info pt-30">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="single-contact-info contact-color-1 mt-30 d-flex ">
                            <div class="contact-info-icon">
                                <i class="lni lni-map-marker"></i>
                            </div>
                            <div class="contact-info-content media-body">
                                <p class="text"> Elizabeth St, Melbourne<br>1202 Australia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-contact-info contact-color-2 mt-30 d-flex ">
                            <div class="contact-info-icon">
                                <i class="lni lni-envelope"></i>
                            </div>
                            <div class="contact-info-content media-body">
                                <p class="text">hello@ayroui.com</p>
                                <p class="text">support@uideck.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="single-contact-info contact-color-3 mt-30 d-flex ">
                            <div class="contact-info-icon">
                                <i class="lni lni-phone"></i>
                            </div>
                            <div class="contact-info-content media-body">
                                <p class="text">+333 789-321-654</p>
                                <p class="text">+333 985-458-609</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-wrapper form-style-two pt-115">
                        <h4 class="contact-title pb-10"><i class="lni lni-envelope"></i> Leave <span>A Message.</span></h4>

                        <form id="contact-form" action="assets/contact.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-input mt-25">
                                        <label>Name</label>
                                        <div class="input-items default">
                                            <input name="name" type="text" placeholder="Name">
                                            <i class="lni lni-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-input mt-25">
                                        <label>Email</label>
                                        <div class="input-items default">
                                            <input type="email" name="email" placeholder="Email">
                                            <i class="lni lni-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-input mt-25">
                                        <label>Massage</label>
                                        <div class="input-items default">
                                            <textarea name="massage" placeholder="Massage"></textarea>
                                            <i class="lni lni-pencil-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="form-message"></p>
                                <div class="col-md-12">
                                    <div class="form-input light-rounded-buttons mt-30">
                                        <button class="main-btn light-rounded-two">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

                        -->

    <!-- <section class="footer-area footer-dark">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="footer-logo text-center">
                        <a class="mt-30" href="index.html"><img src="assets/images/logo.svg" alt="Logo"></a>
                    </div>
                    <ul class="social text-center mt-60">
                        <li><a href="https://facebook.com/uideckHQ"><i class="lni lni-facebook-filled"></i></a></li>
                        <li><a href="https://twitter.com/uideckHQ"><i class="lni lni-twitter-original"></i></a></li>
                        <li><a href="https://instagram.com/uideckHQ"><i class="lni lni-instagram-original"></i></a></li>
                        <li><a href="#"><i class="lni lni-linkedin-original"></i></a></li>
                    </ul>
                    <div class="footer-support text-center">
                        <span class="number">+8801234567890</span>
                        <span class="mail">support@uideck.com</span>
                    </div>
                    <div class="copyright text-center mt-35">
                    </div>
                </div>
            </div>
        </div>
    </section> -->




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