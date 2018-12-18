<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'engagement_id' => 'required|integer',
            'question' => 'required|string',
            'answered' => 'required|boolean',
        ]);

        $question = Question::create($data);

        return response($question, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id);

        return response($question);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'engagement_id' => 'required|integer',
            'question' => 'required|string',
            'answered' => 'required|boolean',
        ]);

        $question->update($data);

        return response($question, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateanswer(Request $request, Question $question)
    {

            $data = $request->validate([
                'answer' => 'required|string',
                'answered' => 'required|boolean',
            ]);
    
            $question->update($data);
            
            return response($question, 201);


    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return response('Deleted Question', 200);
    }
}
