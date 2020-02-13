<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use App\Models\Tenant\Mail;
use ZipArchive;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use App\Models\System\Hostname;

class ShareFilesController extends Controller
{

    public function storeFiles(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsm,xlsx|max:2048',
            'fqdn' => 'required|string'
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
                if(!Storage::disk('s3')->exists($account)) {
                    Storage::disk('s3')->makeDirectory($account, 0755, true); //creates directory
                }
                if(!Storage::disk('s3')->exists($full_path)) {
                    Storage::disk('s3')->makeDirectory($full_path, 0755, true); //creates directory
                }


                Storage::disk('s3')->put($full_path.$extenstion, $contents);
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

        $file = Storage::disk('s3')->get($record->path . '/' . $request['name']);

        return response($file);
    }

    public function getClientFiles($id)
    {
        $record = Mail::find($id);
        $fileName = date('Ymdhms', strtotime($record->created_at)).'_files.zip';
        $zipFileName = '../storage/app/'.$fileName;
        $attachments = json_decode($record->attachments, true);

        if(Storage::disk('s3')->exists($record->path.'/'.$fileName)) {
            return response(Storage::disk('s3')->get($record->path.'/'.$fileName));
        }
  
        try {
            // Create ZipArchive Obj
            $zip = new ZipArchive();
            if($zip->open($zipFileName, ZipArchive::CREATE)) {
                $files = Storage::disk('s3')->files($record->path);
                foreach ($files as $path) {
                    if(in_array(basename($path), $attachments)) {
                        $contents = Storage::disk('s3')->get($path);
                        $zip->addFromString(basename($path), $contents);  
                    }
                }
                $zip->close();
            }
        } catch( \Exception $e) {
            return response()->json(['message' => $e->getMessage()], 405);
        }

        Storage::disk('s3')->put($record->path.'/'.$fileName, file_get_contents($zipFileName));
        Storage::delete($fileName);
        return response(Storage::disk('s3')->get($record->path.'/'.$fileName));
    }

    public function archiveClientFiles($id)
    {
        $record = Mail::find($id);
        $record->archived = true;
        $record->save();

        return response(Mail::all());
    }

    public function deleteFiles($id)
    {
        $record = Mail::find($id);
        $files = json_decode($record->attachments, true);
        foreach($files as $ext) {
                Storage::disk('s3')->delete($record->path.'/'.$ext);
                $dir = Storage::disk('s3')->allFiles($record->path);
                if(count($dir) < 1) {
                    Storage::disk('s3')->deleteDirectory($record->path);
                }
            }
        $record->delete();

        return response('Files Deleted');
    }

    public function numberOfFiles()
    {
        return count(Mail::where('archived', false)->get());
    }
}

// $record = Mail::find($id);
//         $fileName = date('Ymdhms', strtotime($record->created_at)).'_files.zip';
//         $files = Storage::disk('s3')->files($record->path);

//         return response()->streamDownload(function() use ($files, $fileName) {
//             $opt = new ArchiveOptions();

//             $opt->setContentType('application/octet-stream');

//             $zip = new ZipStream($fileName, $opt);
            

//             try {
//                 foreach ($files as $path) {
//                     $s3Path = Storage::disk('s3')->url($path);
//                     if($streamRead = fopen($s3Path, 'r')) {
//                         $zip->addFile(basename($path), $streamRead, [], "store");  
//                     } else {
//                         return response('Something went wrong', 405);
//                     }   
//                 }
//             } catch( \Exception $e) {
//                 return response()->json(['message' => $e->getMessage()], 405);
//             }

//             $zip->finish();
//         }, $fileName); 