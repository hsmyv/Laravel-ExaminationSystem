@extends('layout.common')

@section('space-work')
    <div class="container">
        <div class="text-center">
            <h2>Thanks for submit your Exam, {{auth()->user()->name}}</h2>
            <p>We will review your Exam, and update you soon by mail</p>
            <a href="{{route('student.dashboard')}}" class="btn btn-info">Go Back</a>
        </div>

    </div>
@endsection
