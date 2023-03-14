@extends('layout.common')

@section('space-work')
    <h1>Recover Access</h1>
    @if ($errors->any())
        @foreach ($errors->all() as $error )
            <p style="color:red">{{$error}}</p>
        @endforeach
    @endif
    <form action="{{route('forgetPassword')}}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter email">
            <br><br>
            <input type="submit" value="Forget Password">
    </form>

    @if (Session::has('success'))
        <p style="color::green">{{Session::get('success')}}</p>
    @endif
     @if (Session::has('error'))
        <p style="color::red">{{Session::get('error')}}</p>
    @endif
@endsection
