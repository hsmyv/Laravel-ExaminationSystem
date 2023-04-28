<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Anwser;

use Maatwebsite\Excel\Concerns\ToModel;

class QnaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        \Log::info($row);

        if($row[0] != 'question'){
            $questionId = Question::insertGetId([
                'question' => $row[0]
            ]);
            $rowCount = count($row)-1;
            for ($i=0; $i < count($row)-1; $i++) {
                if($row[$i] != null)
                {
                    $is_correct = 0;
                    if($row[$rowCount] == $row[$i]){
                        $is_correct = 1;
                    }

                }

                Answer::insert([
                    'question_id' => $questionId,
                    'answer' => $row[$i],
                    'is_correct' => $is_correct
                ]);
            }
        }
        // return new Question([
        //     //
        // ]);
    }
}
