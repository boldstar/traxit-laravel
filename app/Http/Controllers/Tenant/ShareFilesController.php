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

        $file = !$record->archived ? Storage::get($record->path . '/' . $request['name']) : Storage::disk('s3')->get($record->path . '/' . $request['name']);

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
                $files = $record->archived ? Storage::disk('s3')->files($record->path) : json_decode($record->attachments, true);
                if($record->archived) {
                    foreach ($files as $path) {
                        $contents = Storage::disk('s3')->get($path);
                        $zip->addFromString(basename($path), $contents);  
                    }
                } else {
                    foreach ($files as $ext) {
                        $path = $record->path.'/'.$ext;
                        $zip->addFile('../storage/app/'.$path, $ext);  
                    }
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
        $FileSystem = new FileSystem();

        $record = Mail::find($id);
        $dir = '../storage/app/'.$record->path;
        $files = json_decode($record->attachments, true);
        foreach ($files as $ext) {
            $path = $record->path.'/'.$ext;
            Storage::disk('s3')->put($path, file_get_contents('../storage/app/'.$path));
            Storage::delete($path);

            $file = $FileSystem->files($dir);
            if(empty($file)) {
                Storage::deleteDirectory($record->path);
            }
        }

        $record->archived = true;
        $record->save();

        return response(Mail::all());
    }

    public function deleteFiles($id)
    {
        $FileSystem = new FileSystem();
        $record = Mail::find($id);
        $dir = '../storage/app/'.$record->path;
        $files = json_decode($record->attachments, true);

        foreach($files as $ext) {
            if($record->archived) {
                Storage::disk('s3')->delete($record->path.'/'.$ext);
            } else {
                Storage::delete($record->path.'/'.$ext);
                
                $file = $FileSystem->files($dir);
                if(empty($file)) {
                    Storage::deleteDirectory($record->path);
                }
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
