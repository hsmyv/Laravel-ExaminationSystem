@extends('layout.student')

@section('space-work')
<h2>Free Exams</h2>
     <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Subject Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Total Attempt</th>
                <th>Available Attempt</th>
                <th>Copy Link </th>
            </tr>
        </thead>
        <tbody>
            @if(count($exams) > 0)
                @php $count = 1; @endphp
                @foreach ($exams as $exam )
                    <tr>
                        <td style="display:none;">{{$exam->id}}</td>
                        <td>{{$count++}}</td>
                        <td>{{$exam->name}}</td>
                        <td>{{$exam->subjects[0]['subject']}}</td>
                        <td>{{$exam->date}}</td>
                        <td>{{$exam->time}} Hrs</td>
                        @if ($exam->attempt <= 1)
                            <td>{{$exam->attempt}} time</td>
                        @else
                            <td>{{$exam->attempt}} times</td>
                        @endif
                        <td>{{$exam->attempt_counter}}</td>
                        <td></td>
                        <td><a href="" data-code="{{$exam->entrance_id}}" class="copy"><i class="fa fa-copy"></i></a></td>
                    </tr>
                @endforeach
            @else
            <tr>
                <td colspan="8">No Exams Available!</td>
            </tr>
            @endif
        </tbody>
     </table>

     <script>
        $(document).ready(function(){
            $('.copy').click(function(){
                $(this).parent().prepend('<span class="copied_text">Copied</span>');

                var code = $(this).attr('data-code');
                var url  = "{{URL::to('/')}}/exam/"+code;
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(url).select();
                document.execCommand("copy");

                $temp.remove();
                setTimeout(() => {
                    $('.copied_text').remove();
                }, 1000);
            });
        });
     </script>
@endsection
