@extends('layout.admin')

@section('space-work')


    <h2 class="mb-4">Exams</h2>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExamModal">

        Add Exam
    </button>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Time</th>
                <th>Attempt</th>
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
                        <td>{{ $exam->time }}</td>
                        <td>{{ $exam->attempt }}</td>
                        <td>
                            <button class="btn btn-info editButton" data-id="{{ $exam->id }}" data-toggle="modal"
                                data-target="#editExamModal">Edit</button>
                        </td>
                        <td>
                            <button class="btn btn-info deleteButton" data-id="{{ $exam->id }}" data-toggle="modal"
                                data-target="#deleteExamModal">Delete</button>
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
                        <input type="date" name="date" class="w-100" required min="@php echo date('Y-m-d'); @endphp">
                        <br><br>
                        <input type="time" name="time" class="w-100" required>
                        <br><br>
                        <input type="number" min="1" name="attempt" class="w-100"
                            placeholder="Enter Exam Attempt Time" required>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Exam</button>
                        </div>
                    </div>
                </form>
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
                            $('$attempt').val(examp[0].attempt);
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
            });
        </script>
    @endsection
