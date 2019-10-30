<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\Mail;
use ZipArchive;

class ShareFilesController extends Controller
{
    public function storeFiles(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsm,xlsx|max:2048'
        ]);


        $details = $request['data'];
        $account = $request['fqdn'];
        $folder = $account . '/';
        $customer = json_decode($details, true);
        $customer_folder = explode('@', $customer['client']['email']);
        $full_path = $folder. '/' .$customer_folder[0]. '/';
        $expire_date = date('Y-m-d', strtotime('+5 years'));
        $time = time();

        if(count($validated) > 0) {
            $files = $request->file('files');
            $attachments = array();
            foreach($files as $file) {
                $path = $file->getRealPath();
                $name = $file->getClientOriginalName();
                $extenstion = $time . '.'. $name;
                $contents = file_get_contents($path);
                array_push($attachments, $extenstion);
                if(!Storage::exists($account)) {
                    Storage::makeDirectory($account, 0755, true); //creates directory
                }
                if(!Storage::exists($full_path)) {
                    Storage::makeDirectory($full_path, 0755, true); //creates directory
                }


                Storage::put($full_path.$extenstion, $contents);
            };
        }

        Mail::create([
            'to' => $account,
            'name' => $customer['client']['first_name'] . ' ' . $customer['client']['last_name'],
            'from' => $customer['client']['email'],
            'subject' => $customer['subject'],
            'message' => $customer['message'],
            'attachments' => json_encode($attachments),
            'path' => $full_path,
            'archived' => false,
            'expires_on' => $expire_date 
        ]);

        
        return response('Success');
    }

    public function getFiles()
    {
        $files = Mail::all();

        return response($files);
    }

    public function getClientFile(Request $request) 
    {
        $record = Mail::where('id', $request['id'])->first();

        $file = Storage::get($record->path . '/' . $request['name']);

        return response($file);
    }

    public function getClientFiles($id)
    {
        $record = Mail::find($id);
        $filePath = $record->to.'/'.date('Ymdhms', strtotime($record->created_at)).'_files.zip';
        $zipFileName = '../storage/app/'.$filePath;

        if(file_exists($zipFileName)) {
            return response(Storage::get($filePath));
        }
  
        try {
            // Create ZipArchive Obj
            $zip = new ZipArchive();
            if($zip->open($zipFileName, ZipArchive::CREATE)) {
                $files = json_decode($record->attachments, true);
                foreach ($files as $ext) {
                    $path = $record->path.'/'.$ext;
                    $zip->addFile('../storage/app/'.$path, $ext);  
                }
                $zip->close();
            }
        } catch( \Exception $e) {
            return response()->json(['message' => $e->getMessage()], 405);
        }
        
        return response(Storage::get($filePath));
    }

    public function archiveClientFiles($id)
    {
        
    }
}
