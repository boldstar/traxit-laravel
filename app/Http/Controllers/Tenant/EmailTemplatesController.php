<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\EmailTemplate;
use Sunra\PhpSimple\HtmlDomParser;
use App\Http\Controllers\Controller;

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
            $document->find('span[class=phone]', 0)->innertext = '<strong>Phone Number</strong><br>';
            $document->find('span[class=email]', 0)->innertext = '<strong>Users Email</strong><br><br>';
            $document->find('p[class=thanks]', 0)->innertext = '<p>Looking forward to hearing from you, Thanks!</p><br>';
            $document->find('h3[class=account]', 0)->innertext = '<strong>Company Name</strong><br>';
            $document->find('h3[class=account-email]', 0)->innertext = '<strong>Account Email</strong><br>';
            $document->find('h3[class=phone]', 0)->innertext = '<strong>Company Phone Number</strong><br>';
            $document->find('h3[class=fax]', 0)->innertext = '<strong>Company Fax Number</strong>';
            $template->html_template = $document->plaintext;
        }
        return response($templates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
