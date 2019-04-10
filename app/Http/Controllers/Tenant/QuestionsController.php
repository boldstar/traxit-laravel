<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Question;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\Client;
use App\Models\Tenant\User;
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
        //save created question
        $question = Question::create($data);
        $question->username = User::where('id', auth()->user()->id)->value('name');
        $question->save();
        //if email is set to true, send email to client
        if($request->email === true) {
            $request->validate([
                'send_to' => 'required|string',
            ]);

            $this->send($question, $request->send_to);
            $question->email_sent = true;
            $question->save();
            //if email sent is successful, return question to client with success message
            return response([ 
                'question' => $question, 
                'message' => 'Email Sent To Client'], 
                201
            );
        }
        //if no email is sent, return new question added only
        return response([ 
            'question' => $question, 
            'message' => 'A new question has been added!'], 
            201
        );
    }

    public function send($question, $send_to)
    {
        $engagement = Engagement::where('id', $question->engagement_id)->first();
        $client = CLient::where('id', $engagement->client_id)->first();
        //determine which person to send to or both
        try {
            if($send_to == 'both') {
                Mail::to($client->email)->send(new StartConversation([
                    'question' => $question, 
                    'engagement' => $engagement, 
                    'client' => $client, 
                    'test' => false, 
                    'send_to' => $send_to
                ]));
            }
            if($send_to == 'taxpayer' && $client->email != null) {
                Mail::to($client->email)->send(new StartConversation([
                    'question' => $question, 
                    'engagement' => $engagement, 
                    'client' => $client, 
                    'test' => false, 
                    'send_to' => $send_to
                ]));
            }
            if($send_to == 'spouse' && $client->spouse_email != null) {
                Mail::to($client->spouse_email)->send(new StartConversation([
                    'question' => $question, 
                    'engagement' => $engagement, 
                    'client' => $client, 
                    'test' => false, 
                    'send_to' => $send_to
                ]));
            }
        } catch(\Exception $e) {
            //if fail, question sent = false return error to user
            $question->email_sent = false;
            $question->save();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return;
    }

    public function sendMail(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer'
        ]);
        //grab needed data to send email to client
        $question = Question::where('id', $request->id)->first();
        $engagement = Engagement::where('id', $question->engagement_id)->first();
        $client = CLient::where('id', $engagement->client_id)->first();
        //try to send mail, if failed catch error and send back message
        try {
            Mail::to($client->email)->send(new StartConversation([
                'question' => $question, 
                'engagement' => $engagement, 
                'client' => $client, 
                'test' => false,
                'send_to' => 'both'
            ]));
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
        $question->email_sent = true;
        $question->save();
        return response()->json([ 
            'question' => $question, 
            'message' => 'Email Was Sent'
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editanswer(Request $request, Question $question)
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
