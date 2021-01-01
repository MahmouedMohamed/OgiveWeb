@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="/p" enctype="multipart/form-data" method="post">
            @csrf
            <div class="row">
                <div class="col-8 offset-2">

                    <div><h1>Add New Post</h1></div>

                    <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label">{{ __('Description') }}</label>

                        <input id="description" type="text"
                               class="form-control @error('description') is-invalid @enderror"
                               name="description" value="{{ old('description') }}" autocomplete="description" autofocus>

                        @error('description')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                    </div>

                    <div class="row">
                        <label for="image" class="col-md-4 col-form-label">Post Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">

                        @if($errors->has('image'))
                                <strong>{{$errors->first('image')}}</strong>
                        @endif
                    </div>

                    <div class="row pt-4">
                        <button class="btn btn-primary">Add New Post</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
