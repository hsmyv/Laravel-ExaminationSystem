<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [AuthController::class, 'loadRegister']);
Route::post('/register', [AuthController::class, 'studentRegister'])->name('studentRegister');


Route::get('/login', function () {
    return redirect('/');
})->name('showLogin');

Route::get('/', [AuthController::class, 'loadLogin']);
Route::post('/login', [AuthController::class, 'userLogin'])->name('userLogin');


Route::get('/logout', [AuthController::class, 'logout']);



Route::group(['middleware' => ['web', 'checkAdmin']], function () {
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');


    Route::post('/add-subject', [AdminController::class, 'addSubject'])->name('addSubject');
    Route::post('/edit-subject', [AdminController::class, 'editSubject'])->name('editSubject');
    Route::post('/delete-subject', [AdminController::class, 'deleteSubject'])->name('deleteSubject');


    Route::get('/admin/exam', [AdminController::class, 'examDashboard'])->name('admin.exam');
    Route::post('/add-exam', [AdminController::class, 'addExam'])->name('addExam');
    Route::get('/get-exam-detail/{id}', [AdminController::class, 'getExamDetail'])->name('getExamDetail');
    Route::post('/update-exam', [AdminController::class, 'updateExam'])->name('updateExam');
    Route::post('/delete-exam', [AdminController::class, 'deleteExam'])->name('deleteExam');


    //Q&A Routes
    Route::get('/admin/qna-ans', [AdminController::class, 'qnaDashboard'])->name('admin.qna-ans');
    Route::post('/add-qna-ans', [AdminController::class, 'addQna'])->name('addQna');
    Route::get('/get-qna-details/{id}', [AdminController::class, 'getQnaDetails'])->name('getQnaDetails');
    Route::get('/delete-ans', [AdminController::class, 'deleteAns'])->name('deleteAns');
    Route::post('/update-qna-ans', [AdminController::class, 'updateQna'])->name('updateQna');
    Route::post('/delete-qna-ans', [AdminController::class, 'deleteQna'])->name('deleteQna');
    Route::post('/import-qna-ans', [AdminController::class, 'importQna'])->name('importQna');

    //Students Routes
    Route::get('/admin/students', [AdminController::class, 'studentsDashboard'])->name('admin.studentDashboard');
    Route::post('/add-student', [AdminController::class, 'addStudent'])->name('addStudent');
    Route::post('/edit-student', [AdminController::class, 'updateStudent'])->name('editStudent');
    Route::post('/delete-student', [AdminController::class, 'deleteStudent'])->name('deleteStudent');

    //qna exams routing
    Route::get('/get-questions', [AdminController::class, 'getQuestions'])->name('getQuestions');
    Route::post('/add-questions', [AdminController::class, 'addQuestions'])->name('addQuestions');
    Route::get('/get-exam-questions', [AdminController::class, 'getExamQuestions'])->name('getExamQuestions');
    Route::get('/delete-exam-questions', [AdminController::class, 'deleteExamQuestions'])->name('deleteExamQuestions');

    //exam marks routes
    Route::get('/admin/marks', [AdminController::class, 'marksDashboard'])->name('admin.marks');
    Route::post('/update-marks', [AdminController::class, 'updateMarks'])->name('updateMarks');

    //exam review routes
    Route::get('/admin/review-exams', [AdminController::class, 'reviewExams'])->name('admin.reviewExams');
    Route::get('/get-reviewed-qna', [AdminController::class, 'reviewQna'])->name('reviewQna');

    Route::post('/approved-qna', [AdminController::class, 'approvedQna'])->name('approvedQna');


});



Route::group(['middleware' => ['web', 'checkStudent']], function () {
    Route::get('/dashboard', [AuthController::class, 'loadDashboard'])->name('student.dashboard');
    Route::get('/exam/{id}', [ExamController::class, 'loadExamDashboard'])->name('student.examDashbaord');
    Route::post('/exam-submit', [ExamController::class, 'examSubmit'])->name('examSubmit');
});

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('forgetPassword');

Route::get('/reset-password', [AuthController::class, 'resetPasswordLoad']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');
