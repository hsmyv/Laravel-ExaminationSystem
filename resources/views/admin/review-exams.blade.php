@extends('layout.admin')

@section('space-work')
    <h2 class="mb-4">Students Exam</h2>

    <table class="table mt-5">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>Exam</th>
            <th>Status</th>
            <th>Review</th>
        </thead>
        <tbody>
            @if (count($attempts) > 0)
            @php
                $x = 1;
            @endphp
            @foreach($attempts as $attempt)
            <tr>
                <td>{{ $x }}</td>
                <td>{{$attempt->user->name}}</td>
                <td>{{$attempt->exam->name}}</td>
                <td>
                    @if ($attempt->status == 0)
                        <span style="color:red">Pending</span>
                    @else
                        <span style="color:green">Approved</span>
                    @endif
                </td>
                <td>
                    @if ($attempt->status == 0)
                        <a href="">Review & Approved</a>
                    @else
                        completed
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5">Students not Attempt Exams!</td>
            </tr>

            @endif
        </tbody>
    </table>
@endsection
