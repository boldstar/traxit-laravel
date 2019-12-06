<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use App\Models\Tenant\Document;
use ZipArchive;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

class DocumentPortalController extends Controller
{
    public function storeDocument(Request $request)
    {
        $validate = $request->validate([
            'files.*' => 'required|file|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsm,xlsx|max:2048',
            'client_id' => 'required|integer',
            'account' => 'required|string',
            'downloadable' => 'required|boolean',
            'payment_required' => 'required|boolean',
            'signature_required' => 'required|boolean'
        ]);

        $portal = $request['account'].'/portal';
        $folder = $portal . '/';
        $customer_folder = $request['client_id'];
        $full_path = $folder. '/' .$customer_folder. '/';

        if(count($validated) > 0) {
            $files = $request->file('files');
            foreach($files as $file) {
                $path = $file->getRealPath();
                $name = $file->getClientOriginalName();
                $extenstion = $name;
                $contents = file_get_contents($path);
                if(!Storage::exists($portal)) {
                    Storage::makeDirectory($portal, 0755, true); //creates directory
                }
                if(!Storage::exists($full_path)) {
                    Storage::makeDirectory($full_path, 0755, true); //creates directory
                }


                Storage::put($full_path.$extenstion, $contents);
            };
        }

        Document::create([
            'document_name' => $extenstion,
            'path' => $full_path,
            'message' => $request['message'],
            'payment_required' => $request['payment_required'],
            'signature_required' => $request['signature_required'],
            'downloadable' => $request['downloadable'],
            'uploaded_by' => auth()->user()->name,
            'account' => $request['account']
        ]);

        return response('Upload Succesful', 200);
    }
}
