@extends('layout.admin')

@section('space-work')
    <h2 class="mb-4">Q&A</h2>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addQnaModal">
        Add Q&A
    </button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importQnaModal">
        Import Q&A
    </button>

    <table class="table mt-5">
        <thead>
            <th>#</th>
            <th>Question</th>
            <th>Answers</th>
            <th>Edit</th>
            <th>Delete</th>
            
        </thead>
        <tbody>
            @if (count($questions) > 0)
                @foreach ($questions as $question)
                    <tr>
                        <td>{{ $question->id }}</td>
                        <td>{{ $question->question }}</td>
                        <td>
                            <a href="#" class="ansButton" data-id="{{ $question->id }}" data-toggle="modal"
                                data-target="#showAnsModal">See Answers</a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info editButton" data-id="{{ $question->id }}" data-toggle="modal"
                                data-target="#editQnaModal">Edit</button>
                        </td>
                         <td>
                            <button type="button" class="btn btn-info deleteButton" data-id="{{ $question->id }}" data-toggle="modal"
                                data-target="#deleteQnaModal">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">Questions & Answers not found!</td>
                </tr>
            @endif
        </tbody>
    </table>
    <!-- Modal -->
    <div class="modal fade" id="addQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Q&A</h5>

                    <button id="addAnswer" class="ml-5 btn btn-info">Add Answer</button>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addQna">
                    @csrf
                    <div class="modal-body addModalAnswers">
                        <div class="row">
                            <div class="col">
                                <input type="text" class="w-100" name="question" placeholder="Enter Question" required>
                            </div>
                        </div>
                         <div class="row mt-2">
                            <div class="col">
                                <textarea name="explanation" class="w-100" placeholder="Enter your explanation" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="error" style="color:red;"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Q&A </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editQnaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Q&A</h5>

                    <button id="addEditAnswer" class="ml-5 btn btn-info">Add Answer</button>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editQna">
                    @csrf
                    <div class="modal-body editModalAnswers">
                        <div class="row">
                            <div class="col">
                                <input type="hidden" name="question_id" id="question_id" required>
                                <input type="text" class="w-100" id="question_name" name="question" placeholder="Enter Question" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="editError" style="color:red;"></span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Q&A </button>
                    </div>
                 </form>
            </div>
        </div>
    </div>

    <!-- Show Ans Modal -->
    <div class="modal fade" id="showAnsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Show Answers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <th>#</th>
                            <th>Answer</th>
                            <th>Is Correct</th>
                        </thead>
                        <tbody class="showAnswers"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <span class="error" style="color:red;"></span>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>



     <!-- Delete Qna Modal -->
        <div class="modal fade" id="deleteQnaModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Delete Qna</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="deleteQna">
                            @csrf
                            <div class="modal-body">
                                <p>Are you Sure want to delete Question?</p>
                                <input type="hidden" name="id" id="delete_qna_id">
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

        <!-- Import Q&a Modal -->
        <div class="modal fade" id="importQnaModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Import Q&A</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="importQna" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                               <input type="file" name="file" id="fileupload" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet ,application/vnd.ms.excel">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Import Q&A</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    <script>
        $(document).ready(function() {
            $("#addQna").submit(function(e) {
                e.preventDefault();
                if ($(".answers").length < 2) {
                    $(".error").text("Please add minimum two answers.")
                    setTimeout(function() {
                        $(".error").text("");
                    }, 2000);
                } else {
                    var checkIsCorrect = false;

                    for (let i = 0; i < $(".is_correct").length; i++) {
                        if ($(".is_correct:eq(" + i + ")").prop('checked') == true) {
                            checkIsCorrect = true;
                            $(".is_correct:eq(" + i + ")").val($(".is_correct:eq(" + i + ")").next().find(
                                'input').val());
                        }

                    }

                    if (checkIsCorrect) {
                        var formData = $(this).serialize();
                        $.ajax({
                            url: "{{ route('addQna') }}",
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
                    } else {
                        $(".error").text("Please select anyone correct answer")
                        setTimeout(function() {
                            $(".error").text("");
                        }, 2000);
                    }
                }
            });

            $("#addAnswer").click(function() {
                if ($(".answers").length >= 6) {
                    $(".error").text("You cad add maximum 6 answers.")
                    setTimeout(function() {
                        $(".error").text("");
                    }, 2000);
                } else {
                    var html = `
                        <div class="row mt-2 answers">
                            <input type="radio" name="is_correct" class="is_correct">
                            <div class="col">
                                <input type="text" class="w-100" name="answers[]" placeholder="Enter Answer" required>
                            </div>
                            <button class="btn btn-danger removeButton">Remove</button>
                        </div>
                    `;
                    $(".addModalAnswers").append(html);
                }
            });

            $(document).on("click", ".removeButton", function() {
                $(this).parent().remove();
            });


            //Show Answers
            $(".ansButton").click(function() {
                var questions = @json($questions);
                var qid = $(this).attr('data-id');
                var html = '';
                for (let i = 0; i < questions.length; i++) {
                    if (questions[i]['id'] == qid) {
                        var answersLength = questions[i]['answers'].length;
                        for (let j = 0; j < answersLength; j++) {
                            let is_correct = 'No';
                            if (questions[i]['answers'][j]['is_correct'] == 1) {
                                is_correct = 'Yes';
                            }
                            html += `
                                    <tr>
                                        <td>` + (j + 1) + `</td>
                                        <td> ` + questions[i]['answers'][j]['answer'] + ` </td>
                                        <td> ` + is_correct + `</td>
                                    </tr>
                                `;

                        }


                        break;
                    }
                }
                $('.showAnswers').html(html);
            });

            //edit or update Q&a
            $("#addEditAnswer").click(function() {
                if ($(".editAnswers").length >= 6) {
                    $(".editError").text("You cad add maximum 6 answers.")
                    setTimeout(function() {
                        $(".editError").text("");
                    }, 2000);
                } else {
                    var html = `
                        <div class="row mt-2 editAnswers">
                            <input type="radio" name="is_correct" class="edit_is_correct">
                            <div class="col">
                                <input type="text" class="w-100" name="new_answers[]" placeholder="Enter Answer" required>
                            </div>
                            <button class="btn btn-danger removeButton">Remove</button>
                        </div>
                    `;
                    $(".editModalAnswers").append(html);
                }
            });


            $(".editButton").click(function() {
                var qid = $(this).attr('data-id');
                var url = "{{route('getQnaDetails', 'id')}}";
                url = url.replace('id', qid);
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(data) {
                        if(data.success == true){
                        var qna = data.data[0];
                        $("#question_id").val(qna['id']);
                        $("#question_name").val(qna['question']);
                        $(".editAnswers").remove();
                        }else{
                            alert(data.msg);
                        }
                        var html = '';

                        for (let i = 0; i < qna['answers'].length; i++) {

                            var checked = '';
                            if (qna['answers'][i]['is_correct'] == 1) {
                                checked = 'checked';
                            }
                            html += `
                            <div class="row mt-2 editAnswers">
                                <input type="radio" name="is_correct" class="edit_is_correct" ` + checked + `>
                                <div class="col">
                                    <input type="text" class="w-100" name="answers[` + qna['answers'][i]['id'] +
                                `]" placeholder="Enter Answer" value="` + qna['answers'][i][
                                    'answer'
                                ] + `" required>
                                </div>
                                <button class="btn btn-danger removeButton removeAnswer" data-id="` + qna['answers'][i]['id'] + `">Remove</button>
                            </div>
                            `;
                            }
                            $(".editModalAnswers").append(html);



                    }
                });
            });

            //Update Qna
                $("#editQna").submit(function(e) {
                    e.preventDefault();
                    if ($(".editAnswers").length < 2) {
                        $(".editError").text("Please add minimum two answers.")
                        setTimeout(function() {
                            $(".editError").text("");
                        }, 2000);
                    } else {
                        var checkIsCorrect = false;

                        for (let i = 0; i < $(".edit_is_correct").length; i++) {
                            if ($(".edit_is_correct:eq(" + i + ")").prop('checked') == true) {
                                checkIsCorrect = true;
                                $(".edit_is_correct:eq(" + i + ")").val($(".edit_is_correct:eq(" +
                                        i + ")")
                                    .next().find(
                                        'input').val());
                            }

                        }

                        if (checkIsCorrect) {
                            var formData = $(this).serialize();
                            $.ajax({
                                url: "{{ route('updateQna') }}",
                                type: "POST",
                                data: formData,
                                success: function(data) {
                                    if (data.success == true) {
                                        location.reload();
                                    } else {
                                        alert(data.msg)
                                    }
                                }
                            });
                        } else {
                            $(".editError").text("Please select anyone correct answer")
                            setTimeout(function() {
                                $(".editError").text("");
                            }, 2000);
                        }
                    }
                });


            //remove Answers

            $(document).on('click', '.removeAnswer', function() {
                var ansId = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('deleteAns') }}",
                    type: "GET",
                    data: {
                        id: ansId
                    },
                    success: function(data) {
                        if (data.success == true) {
                            console.log(data.msg);
                        } else {
                            alert(data.msg)
                        }
                    }
                });
            });


            //delete Q&A

            $('.deleteButton').click(function(){
                var id = $(this).attr('data-id');
                $('#delete_qna_id').val(id);
            });

            $('#deleteQna').submit(function(e){
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{route('deleteQna')}}",
                    type:"POST",
                    data:formData,
                    success:function(data){
                        if(data.success == true){
                            location.reload();
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            })

            //Import Q&A
             $('#importQna').submit(function(e){
                e.preventDefault();

                let formData = new FormData();

                formData.append("file", fileupload.files[0]);

                $.ajaxSetup({
                    headers:{
                        "X-CSRF-TOKEN":"{{csrf_token()}}"
                    }
                });

                $.ajax({
                    url: "{{route('importQna')}}",
                    type:"POST",
                    data:formData,
                    processData:false,
                    contentType:false,
                    success:function(data){
                        if(data.success == true){
                            location.reload();
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            })

        });
    </script>
@endsection
