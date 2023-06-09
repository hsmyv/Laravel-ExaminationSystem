<?php

namespace App\Http\Controllers;

use App\Exports\ExportStudent;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

use App\Imports\QnaImport;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\QnaExam;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function addSubject(Request $request)
    {
        try {
            Subject::insert([
                'subject' => $request->subject
            ]);
            return response()->json(['success' => true, 'msg' => 'Subject added Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function editSubject(Request $request)
    {
        try {
            $subject = Subject::find($request->id);
            $subject->subject = $request->subject;
            $subject->save();

            return response()->json(['success' => true, 'msg' => 'Subject updated Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteSubject(Request $request)
    {
        try {
            Subject::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Subject deleted Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function examDashboard()
    {
        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();
        return view('admin.exam-dashboard', ['subjects' => $subjects, 'exams' => $exams]);
    }

    public function addExam(Request $request)
    {
        try {

            $plan = $request->plan;
            $prices = null;

            if (isset($request->azn) && isset($request->usd)) {
                $prices = json_encode(['AZN' => $request->azn, 'USD' => $request->usd]);
            }
            $unique_id = uniqid('exid');
            Exam::insert([
                'name' => $request->name,
                'subject_id' => $request->subject_id,
                'date'      => $request->date,
                'time'       => $request->time,
                'attempt'    => $request->attempt,
                'entrance_id' => $unique_id,
                'plan'       => $plan,
                'prices'     => $prices
            ]);

            return response()->json(['success' => true, 'msg' => 'Exam added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function getExamDetail($id)
    {
        try {
            $exam = Exam::where('id', $id)->get();
            return response()->json(['success' => true, 'data' => $exam]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function updateExam(Request $request)
    {
        try {
            $plan = $request->plan;
            $prices = null;

            if (isset($request->azn) && isset($request->usd)) {
                $prices = json_encode(['AZN' => $request->azn, 'USD' => $request->usd]);
            }

            $exam = Exam::find($request->exam_id);
            $exam->name = $request->name;
            $exam->subject_id = $request->subject_id;
            $exam->date = $request->date;
            $exam->time = $request->time;
            $exam->attempt = $request->attempt;
            $exam->plan = $plan;
            $exam->prices = $prices;
            $exam->save();

            return response()->json(['success' => true, 'msg' => 'Exam updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteExam(Request $request)
    {
        try {
            Exam::where('id', $request->exam_id)->delete();

            return response()->json(['success' => true, 'msg' => 'Exam deleted Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function qnaDashboard()
    {
        $questions = Question::with('answers')->get();
        return view('admin.qnaDashboard', compact('questions'));
    }


    public function addQna(Request $request)
    {
        try {

            $explanation = null;
            if (isset($request->explanation)) {
                $explanation = $request->explanation;
            }

            $questionId = Question::insertGetId(['question' => $request->question, 'explanation' => $explanation]);
            foreach ($request->answers as $answer) {
                $is_correct = 0;
                if ($request->is_correct == $answer) {
                    $is_correct = 1;
                }

                Answer::insert([
                    'question_id' => $questionId,
                    'answer'      => $answer,
                    'is_correct'  => $is_correct
                ]);
            }

            return response()->json(['success' => true, 'msg' => 'Exam added Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getQnaDetails($id)
    {
        $qna = Question::where('id', $id)->with('answers')->get();
        return response()->json(['success' => true, 'data' => $qna]);
    }

    public function deleteAns(Request $request)
    {
        Answer::where('id', $request->id)->delete();
        return response()->json(['success' => true, 'msg' => 'Answer deleted successfully']);
    }

    public function updateQna(Request $request)
    {

        try {
            Question::where('id', $request->question_id)->update([
                'question'  => $request->question
            ]);

            // old answers update
            if (isset($request->answers)) {
                foreach ($request->answers as $key => $value) {
                    $is_correct = 0;
                    if ($request->is_correct  == $value) {
                        $is_correct = 1;
                    }
                    Answer::where('id', $key)->update([
                        'question_id' => $request->question_id,
                        'answer'      => $value,
                        'is_correct'  => $is_correct
                    ]);
                }
            }

            //new answers added
            if (isset($request->new_answers)) {
                foreach ($request->new_answers as $answer) {
                    $is_correct = 0;
                    if ($request->is_correct  == $answer) {
                        $is_correct = 1;
                    }
                    Answer::insert([
                        'question_id' => $request->question_id,
                        'answer'      => $answer,
                        'is_correct'  => $is_correct
                    ]);
                }
            }
            return response()->json(['success' => true, 'msg' => 'Q&A updated succesfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function deleteQna(Request $request)
    {
        Question::where('id', $request->id)->delete();
        Answer::where('question_id', $request->id)->delete();

        return response()->json(['success' => true, 'msg' => 'Q&A deleted successfully']);
    }

    public function importQna(Request $request)
    {
        try {
            Excel::import(new QnaImport, $request->file('file'));
            return response()->json(['success' => true, 'msg' => 'Import Q&A successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    //Student dashboard
    public function studentsDashboard()
    {
        $students = User::where('is_admin', 0)->get();
        return view('admin.studentsDashboard', compact('students'));
    }
    //Add Student
    public function addStudent(Request $request)
    {

        try {
            $password = Str::random(8);
            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            $url = URL::to('/');
            $data['url'] = $url;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = $password;
            $data['title'] = "Student Registration on OES";

            /* Mail::send('mail.registrationMail', ['data'=> $data], function($message) use ($data){
                $message->to($data['email'])->subject($data['title']);
            });*/
            return response()->json(['success' => true, 'msg' => "Student added Successfully"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    //Update Student
    public function UpdateStudent(Request $request)
    {

        try {
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            //Send Mail
            /* $url = URL::to('/');
            $data['url'] = $url;
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['title'] = "Update Student Profile on OES";

            Mail::send('mail.updateProfileMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });*/
            return response()->json(['success' => true, 'msg' => "Student updated Successfully"]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'msg' => $e->getMessage()]);
        }
    }

    //Delete student
    public function deleteStudent(Request $request)
    {
        try {
            User::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => "Student deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'msg' => $e->getMessage()]);
        }
    }

    //Export Students
    public function exportStudents()
    {
        return Excel::download(new ExportStudent, 'students.xlsx');
    }

    public function getQuestions(Request $request)
    {
        try {
            $questions = Question::all();
            if (count($questions) > 0) {
                $data = [];
                $counter = 0;

                foreach ($questions as $question) {
                    $qnaExam = QnaExam::where(['exam_id' => $request->exam_id, 'question_id' => $request->id])->get();
                    if (count($qnaExam) == 0) {
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $counter++;
                    }
                }
                return response()->json(['success' => true, 'msg' => "Questions data!", "data" => $data]);
            } else {
                return response()->json(['success' => false, 'msg' => "Questions not found"]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addQuestions(Request $request)
    {
        try {
            if (isset($request->questions_ids)) {
                foreach ($request->questions_ids as $qid) {
                    QnaExam::insert([
                        'exam_id' => $request->exam_id,
                        'question_id' => $qid
                    ]);
                }
            }
            return response()->json(['success' => true, 'msg' => "Questions added successfully!"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function getExamQuestions(Request $request)
    {
        try {
            $data = QnaExam::where('exam_id', $request->exam_id)->with('questions')->get();
            return response()->json(['success' => true, 'msg' => 'Questions details!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteExamQuestions(Request $request)
    {
        try {
            QnaExam::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Questions deleted!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function marksDashboard()
    {
        $exams = Exam::with('getQnaExam')->get();
        return view('admin.marksDashboard', compact('exams'));
    }

    public function updateMarks(Request $request)
    {
        try {
            Exam::where('id', $request->exam_id)->update([
                'marks' => $request->marks,
                'pass_marks' => $request->pass_marks
            ]);
            return response()->json(['success' => true, 'msg' => 'Marks Updated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    //Delete review
    public function deleteReview(Request $request)
    {
        try {
            ExamAttempt::where('id', $request->id)->delete();
            return response()->json(['success' => true, 'msg' => "Review deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function reviewExams()
    {
        $attempts = ExamAttempt::with(['user', 'exam'])->orderBy('id', 'desc')->get();

        return view('admin.review-exams', compact('attempts'));
    }

    public function reviewQna(Request $request)
    {
        try {
            $attemptData  = ExamAnswer::where('attempt_id', $request->attempt_id)->with(['question', 'answers'])->get();
            return response()->json(['success' => true, 'data' => $attemptData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function approvedQna(Request $request)
    {
        try {
            $attemptId = $request->attempt_id;

            $examData = ExamAttempt::where('id', $attemptId)->with(['user', 'exam'])->get();

            $marks = $examData[0]['exam']['marks'];

            $attemptData = ExamAnswer::where('attempt_id', $attemptId)->with('answers')->get();
            $totalMarks = 0;

            if (count($attemptData) > 0) {
                foreach ($attemptData as $attempt) {
                    if ($attempt->answers->is_correct == 1) {
                        $totalMarks += $marks;
                    }
                }
            }
            ExamAttempt::where('id', $attemptId)->update([
                'status' => 1,
                'marks'  => $totalMarks
            ]);

            $url = URL::to('/');
            $data['url'] = $url . '/results';
            $data['name'] = $examData[0]['user']['name'];
            $data['email'] = $examData[0]['user']['email'];
            $data['name'] = $examData[0]['exam']['name'];
            $data['title'] = $examData[0]['exam']['name'] . 'Result';

            /* Mail::send('mail.result-mail', ['data' => $data], function ($message) use ($data){    //I haven't check this code yet
                $message->to($data['email'])->subject($data['title']);
            });*/



            return response()->json(['success' => true, 'msg' => 'Approved Successfully', 'data' => $marks]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
