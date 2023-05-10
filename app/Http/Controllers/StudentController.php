<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function paidExamDashboard()
    {
        $exams = Exam::where('plan', 1)->with('subjects')->orderBy('date', 'DESC')->get();

        return view('student.paid-exams', ['exams' => $exams]);
    }
}
