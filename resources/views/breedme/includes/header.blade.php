<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <!-- navbar -->
            <nav class="navbar navbar-expand-lg scrolling-navbar
             <?php if ($_SERVER['REQUEST_URI'] == '/') {
    echo 'fixed-top';
} else {
    echo 'what';
}
?>">

                <div class="container">
                    <a class="navbar-brand" href="{{url('/')}}">
                        <img src="{{asset('images/logoBreedMe.png')}}" alt="Logo">
                    </a>
                    <!-- Collapse button -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
                        aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Collapsible content -->
                    <div class="collapse navbar-collapse" id="basicExampleNav">

                        <!-- Links -->
                        <ul class="navbar-nav mr-auto smooth-scroll">
                            <li class="nav-item">
                                <a class="nav-link" href="#intro">Home</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="/articles">Articles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#examples">Examples</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#gallery">Gallery</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">Contact</a>
                            </li>
                        </ul>
                        <!-- Links -->

                        <!-- Social Icon  -->
                        <ul class="navbar-nav nav-flex-icons">
                            <li class="nav-item">
                                <a class="nav-link"><i class="fab fa-facebook"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"><i class="fab fa-instagram"></i></a>
                            </li>
                        </ul>
                    </div>
                    <!-- Collapsible content -->

                </div>

            </nav>
        </div>
    </div> <!-- row -->
</div> <!-- container -->