<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

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
            Subject::where('id',$request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Subject deleted Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function examDashboard()
    {
        $subjects = Subject::all();
        $exams = Exam::with('subjects')->get();
        return view('admin.exam-dashboard', ['subjects' => $subjects , 'exams' => $exams]);
    }

    public function addExam(Request $request)
    {
        try {
            Exam::insert([
                'name' => $request->name,
                'subject_id' => $request->subject_id,
                'date'      => $request->date,
                'time'       => $request->time,
                'attempt'    => $request->attempt
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
            return response()->json(['success'=> true, 'data' => $exam]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function updateSubject(Request $request)
    {
        try {
            $exam = Exam::find($request->exam_id);
            $exam->exam_name = $request->exam_name;
            $exam->subject_id = $request->subject_id;
            $exam->date = $request->date;
            $exam->time = $request->time;
            $exam->attempt = $request->attempt;
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
           $questionId = Question::insertGetId(['question' => $request->question]);
            foreach ($request->answers as $answer) {
                $is_correct = 0;
                if($request->is_correct == $answer){
                    $is_correct = 1;
                }

                Answer::insert([
                    'question_id' => $questionId,
                    'answer'      => $answer,
                    'is_correct'  => $is_correct
                ]);
            }

            return response()->json(['success' => true, 'msg' => 'Exam deleted Successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getQnaDetails(Request $request)
    {
        $qna = Question::where('id', $request->qid)->with('answers')->get();
        return response()->json(['data' => $qna]);
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
            if(isset($request->answers))
            {
                foreach($request->answers as $key => $value)
                {
                    $is_correct = 0;
                    if($request->is_correct  == $value)
                    {
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


}
