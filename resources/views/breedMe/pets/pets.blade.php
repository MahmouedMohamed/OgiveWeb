@extends('breedMe.layouts.app')

@section('content')
<div class="container">
<a type="button" class="btn btn-sm btn-outline-primary" href="{{ route('pets.create') }}">Add your pet for Adoption</a>

		<div class="row">
			<div class="col-sm-4">
				<div class="card">
					<div class="image">
						<img src="{{asset('img/dog-1.jpg')}}" alt="dog" />
					</div>
					<div class="card-inner">
						<div class="header">
							<div class="row">
								<div class="col-sm-9">
									<h2>Leo</h2>
								</div>
								<div class="col-sm-3">
								
								<a type="button" class="btn btn-sm btn-outline-primary" href="{{ route('pets.create') }}">Request</a>
								</div>
							</div>
							<h6>1 Year - Male</h2>

						</div>
						<div class="content">
							<p>Full Vaccinations</p>
							<p class="available">Available</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="image">
						<img src="{{asset('img/pets/dog-2.jpg')}}" />
					</div>
					<div class="card-inner">
						<div class="header">
							<div class="row">
								<div class="col-sm-9">
									<h2>Diva</h2>
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-sm btn-outline-primary">Request</button>
								</div>
							</div>
							<h6>6 Months - Female</h2>
						</div>
						<div class="content">
							<p>No Vaccinations</p>
							<p class="available">Available</p>

						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="image">
						<img src="{{asset('img/pets/dog-3.jpg')}}" />
					</div>
					<div class="card-inner">
						<div class="header">
							<div class="row">
								<div class="col-sm-9">
									<h2>Rix</h2>
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-sm btn-outline-primary">Request</button>
								</div>
							</div>
							<h6>8 Months - Male</h2>
						</div>
						<div class="content">
							<p>Full vaccianation</p>
							<p class="available">Available</p>

						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="image">
						<img src="{{asset('img/pets/cat-1.jpg')}}" />
					</div>
					<div class="card-inner">
						<div class="header">
							<div class="row">
								<div class="col-sm-9">
									<h2>Jack</h2>
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-sm btn-outline-primary">Request</button>
								</div>
							</div>
							<h6>1 Year - Male</h2>
						</div>
						<div class="content">
							<p>Full vaccianation</p>
							<p class="available">Available</p>

						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="image">
						<img src="{{asset('img/pets/cat-2.png')}}" />
					</div>
					<div class="card-inner">
						<div class="header">
							<div class="row">
								<div class="col-sm-9">
									<h2>Bondok</h2>
								</div>
								<div class="col-sm-3">
									<button type="button" class="btn btn-sm btn-outline-primary">Request</button>
								</div>
							</div>
							<h6>1 Year - Male</h2>
						</div>
						<div class="content">
							<p>Full vaccianation</p>
							<p class="available">Available</p>

						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="card">
					<div class="image">
						<img src="{{asset('img/pets/cat-3.jpg')}}" />
					</div>
					<div class="card-inner">
						<div class="header">
							<div class="row">
								<div class="col-sm-9">
									<h2>Cattie</h2>
								</div>
								<div class="col-sm-3">
									<!-- <button type="button" class="btn btn-sm btn-outline-primary">Request</button> -->
								</div>
							</div>
							<h6>3 Months - Female</h2>
						</div>
						<div class="content">
							<p>Full vaccianation</p>
							<p class="not-available">Not available</p>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="bg-light text-center text-lg-start">
		<!-- Grid container -->
		<div class="container p-4">
			<!--Grid row-->
			<div class="row">
				<!--Grid column-->
				<div class="col-lg-6 col-md-12 mb-4 mb-md-0">
					<h5 class="text-uppercase">Footer Content</h5>

					<p>
						Lorem ipsum dolor sit amet consectetur, adipisicing elit. Iste atque ea quis
						molestias.
					</p>
				</div>
				<!--Grid column-->

				<!--Grid column-->
				<div class="col-lg-3 col-md-6 mb-4 mb-md-0">
					<h5 class="text-uppercase">Links</h5>

					<ul class="list-unstyled mb-0">
						<li>
							<a href="#!" class="text-dark">Link</a>
						</li>

					</ul>
				</div>
				<!--Grid column-->

				<!--Grid column-->
				<div class="col-lg-3 col-md-6 mb-4 mb-md-0">
					<h5 class="text-uppercase mb-0">Links</h5>

					<ul class="list-unstyled">
						<li>
							<a href="#!" class="text-dark">Link</a>
						</li>

					</ul>
				</div>
				<!--Grid column-->
			</div>
			<!--Grid row-->
		</div>
		<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
			© 2021 Copyright:
		</div>
		<!-- Grid container -->

		<!-- Copyright -->

		<!-- Copyright -->
	</footer>
@endsection



