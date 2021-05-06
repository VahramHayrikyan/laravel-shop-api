@extends('layouts.app')
@section('title', 'Changed')

@section('sidebar')
    @parent
    <p>aksnd</p>
@endsection

@section('content')
    <p>This is my body content.</p>

    <form method="POST" action="{{route('profile')}}">
        @csrf
        <label for="title">Post Title</label>

        <input id="title" type="text" class="@error('title') is-invalid @enderror">

        @error('title')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit">Submit</button>
    </form>
@endsection
