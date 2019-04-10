<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\EmailTemplate;
use App\Models\Tenant\Client;
use App\Models\Tenant\Question;
use App\Models\Tenant\Engagement;
use App\Models\Tenant\Account;
use App\Models\Tenant\Status;
use KubAT\PhpSimple\HtmlDomParser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\StartConversation;
use App\Mail\StatusUpdate;

class EmailTemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        //try to parse data into readable text for user
        //HtmlDomParser is used to grab html of email templates stored in database
        try {
            foreach($templates as $template) {
                $document = HtmlDomParser::str_get_html($template->html_template);
                $document->find('i', 0)->innertext = '<strong>Contacts Name</strong>,<br><br>';
                $document->find('span[class=year]', 0)->innertext = '<strong>Year</strong>';
                $document->find('span[class=return]', 0)->innertext = '<strong>Return Type</strong>';
                $document->find('span[class=name]', 0)->innertext = '<strong>Name On Return</strong>';
                $pending = $document->find('h3[class=pending]', 0);
                if($pending != null) {
                    $pending->innertext = '<br><br><div style="border: 1px solid blue; border-radius: 5px; padding: 10px;">Pending Questions:<br>';
                }
                $question = $document->find('p[class=question]', 0);
                if($question != null) {
                    $question->innertext = '<strong>List Of Questions</strong></div><br>';
                }
                $status = $document->find('span[class=status]', 0);
                if($status != null) {
                    $status->innertext = '';
                }
                $current = $document->find('h3[class=current]', 0);
                if($current != null) {
                    $current->innertext = '<br><br><div style="border: 1px solid blue; border-radius: 5px; padding: 10px;">Current Status: <strong>Current Status</strong></div><br>';
                }
                $current = $document->find('div[class=message-body]', 0);
                if($current != null) {
                    $current->innertext = '<div style="border: 1px solid blue; border-radius: 5px; padding: 10px;"><strong>This is where a custom message would go. To add a mesage find the status on your workflow that has a check mark.</strong></div><br>';
                }
                $document->find('span[class=phone]', 0)->innertext = '<strong>Phone Number</strong><br>';
                $document->find('span[class=email]', 0)->innertext = '<strong>Users Email</strong><br><br>';
                $document->find('p[class=looking]', 0)->innertext = '<p>Looking forward to hearing from you, Thanks!</p><br>';
                $document->find('h3[class=account]', 0)->innertext = '<strong>Company Name</strong><br>';
                $document->find('h3[class=account-email]', 0)->innertext = '<strong>Account Email</strong><br>';
                $document->find('h3[class=phone]', 0)->innertext = '<strong>Company Phone Number</strong><br>';
                $document->find('h3[class=fax]', 0)->innertext = '<strong>Company Fax Number</strong>';
                $template->html_template = $document->plaintext;
            }
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response($templates);
    }

    /**
     * Send test email
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendTest(Request $request)
    {
        $template = EmailTemplate::where('id', $request->id)->first();
        $account = Account::first();
        $email = $account->email;
        $message = Status::where('notify_client', true)->first();
        $message->message = 'This is where a custom message would go, to add a message to a status navigate to workflows and find a status with a check mark next to it';
        if($template->title == 'Status Update') {
            $engagement = Engagement::first();
            $client = Client::first();


            try {
                Mail::to($email)->send(new StatusUpdate([
                    'engagement' => $engagement, 
                    'client' => $client, 
                    'test' => true,
                    'send_to' => null,
                    'message' => $message
                ]));
    
                return response()->json(['message' => 'A Test Email Has Been Sent To ' . $email]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        } 

        if($template->title == 'Pending Questions') {
            $engagement = Engagement::first();
            $client = Client::first();
            $question = Question::first();

            try {
                Mail::to($email)->send(new StartConversation([
                    'question' => $question, 
                    'engagement' => $engagement, 
                    'client' => $client, 
                    'test' => true,
                    'send_to' => null
                ]));

                return response()->json(['message' => 'A Test Email Has Been Sent To ' . $email]);
            } catch (\Exception $e) {
                return response($e->getMessage());
            }
        }

        return response('fail');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
