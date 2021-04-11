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
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="#">
                <img src="{{asset('images/logoBreedMe.png')}}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTwo"
                aria-controls="navbarTwo" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
                <span class="toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-orange" href="{{url('pets/add')}}">أضف إعلانك</a>

                    </li>
                </ul>
            </div>


        </nav>
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



    <section id="contact" class="contact-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-10">
                    <div class="section-title text-center pb-30">
                        <h3 class="title">Add Your Pet</h3>
                        <p class="text">Stop wasting time and money designing and managing a website that doesn’t get results. Happiness guaranteed!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-map mt-30">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-wrapper form-style-two pt-115">
                        <h4 class="contact-title pb-10"><i class="lni lni-envelope"></i> Place <span>an Ad.</span></h4>

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
                                <div class="col-md-6">
                                    <div class="form-input mt-25">
                                        <label>Age</label>
                                        <div class="input-items default">
                                            <select name="name">
                                                <option>Choose</option>
                                                <option>Unknown</option>
                                                <option>Less than a year</option>
                                                <option>2-4</option>
                                                <option>5-8</option>
                                                <option>More than a year</option>

</select>
                                            <i class="lni lni-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-input mt-25">
                                        <label>Name</label>
                                        <div class="input-items default">
                                            <input name="name" type="text" placeholder="Name">
                                            <i class="lni lni-user"></i>
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


    <script src="{{asset('landing/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/vendor/modernizr-3.7.1.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('landing/assets/js/main.js')}}"></script>

</body>

</html>
