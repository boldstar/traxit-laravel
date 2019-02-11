<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Question;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\Client;
use App\Mail\StartConversation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'email' => 'required|boolean',
            'question' => 'required|string',
            'email_sent' => 'required|boolean',
            'answered' => 'required|boolean',
        ]);

        $question = Question::create($data);
        
        if($request->email === true) {
            $this->send($question);
            
            return response([ 'question' => $question, 'message' => 'Email Sent To Client'], 201);
        }

        return response([ 'question' => $question, 'message' => 'A new question has been added!'], 201);
    }

    public function send($question)
    {
        $engagement = Engagement::where('id', $question->engagement_id)->first();
        $client = CLient::where('id', $engagement->client_id)->first();

        Mail::to($client->email)->send(new StartConversation(['question' => $question, 'engagement' => $engagement, 'client' => $client]));

        return response('Email Was Sent');
    }

    public function sendMail(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer'
        ]);

        $question = Question::where('id', $request->id)->first();
        $engagement = Engagement::where('id', $question->engagement_id)->first();
        $client = CLient::where('id', $engagement->client_id)->first();

        Mail::to($client->email)->send(new StartConversation(['question' => $question, 'engagement' => $engagement, 'client' => $client]));

        $question->email_sent = true;
        $question->save();

        return response()->json([ 'question' => $question, 'message' => 'Email Was Sent']);
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
