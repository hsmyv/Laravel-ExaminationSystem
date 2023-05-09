@extends('layout.admin')

@section('space-work')


    <h2 class="mb-4">Exams</h2>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExamModal">

        Add Exam
    </button>

    <table class="table mt-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Time</th>
                <th>Attempt</th>
                <th>Plan</th>
                <th>Prices</th>
                <th>Add Questions</th>
                <th>Show </th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody>
            @if (count($exams) > 0)
                @foreach ($exams as $exam)
                    <tr>
                        <td>{{ $exam->id }}</td>
                        <td>{{ $exam->name }}</td>
                        <td>{{ $exam->subjects[0]['subject'] }}</td>
                        <td>{{ $exam->date }}</td>
                        <td>{{ $exam->time }} Hrs</td>
                        <td>{{ $exam->attempt }} Time</td>
                        <td>
                            @if ($exam->plan != 0)
                                <span style="color:red;">PAID</span>
                            @else
                                <span style="color:green;">Free</span>
                            @endif
                        </td>
                        <td>
                            @if ($exam->plan != null)
                                @php $planPrices = json_decode($exam->prices); @endphp
                                @foreach ($planPrices as $key => $price)
                                        <span>{{$key}} {{$price}}</span>
                                @endforeach
                            @else
                                <span>Not Prices</span>
                            @endif
                        </td>
                        <td>
                            <a href="" class="addQuestion" data-id="{{ $exam->id }}" data-toggle="modal"
                                data-target="#addQnaModal">Add Questions</a>
                        </td>
                        <td>
                            <a href="" class="seeQuestions" data-id="{{ $exam->id }}" data-toggle="modal"
                                data-target="#seeQnaModal">See Questions</a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info editButton" data-id="{{ $exam->id }}"
                                data-toggle="modal" data-target="#editExamModal">Edit</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info deleteButton" data-id="{{ $exam->id }}"
                                data-toggle="modal" data-target="#deleteExamModal">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Exams not Found!</td>
                </tr>
            @endif
        </tbody>

    </table>

    <!-- Add Answer Modal -->
    <div class="modal fade" id="addQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Q&A</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addQna">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="exam_id" id="addExamId">
                        <input type="search" name="search" id="search" onkeyup="searchTable()" class="w-100"
                            placeholder="Search here">
                        <br><br>
                        <table class="table" id="questionsTable">
                            <thead>
                                <th>Select</th>
                                <th>Question</th>
                            </thead>
                            <tbody class="addBody">

                            </tbody>
                        </table>
                        {{-- <select name="questions" multiple multiselect-search="true" multiselect-select-all="true">
                            <option value="">Select Questions</option>
                            <option value="">hii</option>
                        </select> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- See Questions Modal -->
    <div class="modal fade" id="seeQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Q&A</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <th>S.No</th>
                                <th>Question</th>
                                <th>Delete</th>
                            </thead>
                            <tbody class="seeQuestionsTable">

                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Exam</button>
                    </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addExamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addExam">
                    @csrf
                    <div class="modal-body">
                        <label for="">Exam Name</label>
                        <input type="text" name="name" placeholder="Enter Exam name" class="w-100" required>
                        <br><br>
                        <select name="subject_id" id="" required class="w-100">
                            <option value="">Select Subject</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
                        <br><br>
                        <input type="date" name="date" class="w-100" required
                            min="@php echo date('Y-m-d'); @endphp">
                        <br><br>
                        <input type="time" name="time" class="w-100" required>
                        <br><br>
                        <input type="number" min="1" name="attempt" class="w-100"
                            placeholder="Enter Exam Attempt Time" required>
                        <br><br>
                        <select name="plan" required class="w-100 mb-4 plan">
                            <option value="">Select Plan</option>
                            <option value="0">Free</option>
                            <option value="1">Paid</option>
                        </select>
                        <input type="number" placeholder="In AZN" name="azn" disabled>
                        <input type="number" placeholder="In USD" name="usd" disabled>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Exam</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editExamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="editExam">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Exit Exam</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">Exam Name</label>
                        <input type="hidden" name="exam_id" id="exam_id">
                        <input type="text" id="name" name="name" placeholder="Enter Exam name"
                            class="w-100" required>
                        <br><br>
                        <select name="subject_id" id="subject_id" required class="w-100">
                            <option value="">Select Subject</option>
                            @if (count($subjects) > 0)
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                @endforeach
                            @endif
                        </select>
                        <br><br>
                        <input type="date" name="date" id="date" class="w-100" required
                            min="@php echo date('Y-m-d'); @endphp">
                        <br><br>
                        <input type="time" name="time" id="time" class="w-100" required>
                        <br><br>
                        <input type="number" min="1" id="attempt" name="attempt" class="w-100"
                            placeholder="Enter Exam Attempt Time" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Exam Modal -->
    <div class="modal fade" id="deleteExamModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="deleteExam">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Delete Exam</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="deleteExam">
                        @csrf
                        <div class="modal-body">
                            <p>Are you Sure want to delete Exam?</p>
                            <input type="hidden" name="exam_id" id="delete_exam_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Delete</button>
                        </div>
                    </form>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#addExam").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('addExam') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });

            });
        });

        $(document).ready(function() {

            $("#editExam").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('updateExam') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });
        });



        $(".editButton").click(function() {
            var id = $(this).attr('data-id');
            $('#exam_id').val(id);

            var url = "{{ route('getExamDetail', 'id') }}";
            url = url.replace('id', id);

            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    if (data.success == true) {
                        var exam = data.data;
                        $('#name').val(exam[0].name);
                        $('#subject_id').val(exam[0].subject_id);
                        $('#date').val(exam[0].date);
                        $('#time').val(exam[0].time);
                        $('#attempt').val(exam[0].attempt);
                    } else {
                        alert(data.msg);
                    }
                }
            });

        });



        $(".deleteButton").click(function() {
            var id = $(this).attr('data-id');
            $("#delete_exam_id").val(id);
        });
        $(document).ready(function() {

            $("#deleteExam").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('deleteExam') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });

            });

            //add questions part
            $('.addQuestion').click(function() {
                var id = $(this).attr('data-id');
                $('#addExamId').val(id);
                $.ajax({
                    url: "{{ route('getQuestions') }}",
                    type: "GET",
                    data: {
                        exam_id: id
                    },
                    success: function(data) {
                        if (data.success == true) {
                            var questions = data.data;
                            var html = '';
                            if (questions.length > 0) {
                                for (let i = 0; i < questions.length; i++) {
                                    html += `
                                        <tr>
                                            <td><input type="checkbox" value="` + questions[i]['id'] + `" name="questions_ids[]" ></td>
                                            <td> ` + questions[i]['questions'] + `</td>
                                        </tr>
                                    `;

                                }
                            } else {
                                html += `
                                <tr>
                                    <td collspan="2">Questions now Available</td>
                                </tr>;
                                `
                            }
                            $(".addBody").html(html);
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            $('#addQna').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('addQuestions') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });


            $('.seeQuestions').click(function(){
                var id  = $(this).attr('data-id');
                $.ajax({
                    url: "{{route('getExamQuestions')}}",
                    type: "GET",
                    data:{exam_id:id},
                    success: function(data){
                        var html = '';
                        var questions = data.data;
                        if(questions.length > 0)
                        {
                            for (let i = 0; i < questions.length; i++) {
                                html +=`
                                    <tr>
                                        <td> `+(i+1)+` </td>
                                        <td> ` + questions[i]['questions'][0]['question'] + ` </td>
                                         <td><button class="btn btn-danger deleteQuestion" data-id="`+questions[i]['id']+`">Delete</button></td>
                                    </tr>
                                `;
                            }
                        }
                        else{
                            html += `
                                <tr>
                                    <td colspan="1">Questions not available!</td>
                                </tr>
                            `;
                        }
                        $('.seeQuestionsTable').html(html);
                    }
                });
            });

            //delete quetion
            $(document).on('click', '.deleteQuestion', function(){
                var id = $(this).attr('data-id');
                var obj = $(this);
                $.ajax({
                    url: "{{route('deleteExamQuestions')}}",
                    type: "GET",
                    data: {id:id},
                    success:function(data){
                        if(data.success == true)
                        {
                            obj.parent().parent().remove();
                        }else{
                            alert(data.msg);
                        }
                    }

                });
            });

            //plan
            $('.plan').change(function(){
                var plan = $(this).val();
                if(plan == 1)
                {
                    $(this).next().attr('required', 'required');
                    $(this).next().next().attr('required', 'required');

                    $(this).next().prop('disabled', false);
                    $(this).next().next().prop('disabled', false);
                }
                else{
                    $(this).next().removeAttr('required', 'required');
                    $(this).next().next().removeAttr('required', 'required');

                    $(this).next().prop('disabled', true);
                    $(this).next().next().prop('disabled', true);
                }
            });
        });
    </script>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            table = document.getElementById('questionsTable');
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }

            }
        }
    </script>
@endsection
