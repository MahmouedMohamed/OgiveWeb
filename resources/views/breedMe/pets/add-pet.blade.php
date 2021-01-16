@extends('breedMe.layouts.app')

@section('content')
<div class="container">
	<form class="form" action="{{ route('pets.store') }}" method="POST">
		@csrf
		<div class="form-group">
			<label>Pet Type: </label>
			<select class="form-control">
				<option>Dog</option>
				<option>Cat</option>
				<option>Bird</option>
				<option>zwahed</option>
			</select>
		</div>
		<div class="form-group">
			<label>Age: </label>
			<input type="text"  class="form-control">
		</div>
		<div class="form-group">
			<label>Pet Type: </label>
			<input type="text"  class="form-control">
		</div>
		<div class="form-group">
			<label>Notes about the pet: </label>
			<input type="textarea"  class="form-control">
		</div>
		<div class="form-group">
			<label>Pet Sex </label>
			<select class="form-control">
				<option>Male</option>
				<option>Female</option>

			</select>
		</div>
		<div class="form-submit">
	<button class="btn btn-secondary">Submit
		</div>
	</form>
</div>
@endsection