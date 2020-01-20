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
    public function getDocuments(Request $request)
    {
        return Document::where('client_id', $request->id)->get();
    }

    public function getDocument(Request $request)
    {
        $doc = Document::where('id', $request->id)->first();

        $s3_base_url = Storage::disk('s3')->get('/'.$doc['path'].$doc['document_name']);

        return $s3_base_url;
    }

    public function getDocumentDetails(Request $request)
    {
        $doc = Document::where('id', $request->id)->first();

        return $doc;
    }


    public function storeDocument(Request $request)
    {

        $validated = $request->validate([
            'files.*' => 'required|file|mimes:pdf|max:2048',
            'client_id' => 'required|integer',
            'account' => 'required|string',
            'downloadable' => 'required|string',
            'payment_required' => 'required|string',
            'signature_required' => 'required|string'
        ]);

        $payment_required = json_decode($request['payment_required']);
        $signature_required = json_decode($request['signature_required']);
        $downloadable = json_decode($request['downloadable']);

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
                if(!Storage::disk('s3')->exists($portal)) {
                    Storage::disk('s3')->makeDirectory($portal, 0755, true); //creates directory
                }
                if(!Storage::disk('s3')->exists($full_path)) {
                    Storage::disk('s3')->makeDirectory($full_path, 0755, true); //creates directory
                }


                Storage::disk('s3')->put($full_path.$extenstion, $contents);
            };
        }

        Document::create([
            'client_id' => $request['client_id'],
            'document_name' => $extenstion,
            'path' => $full_path,
            'message' => $request['message'],
            'payment_required' => $payment_required,
            'signature_required' => $signature_required,
            'downloadable' => $downloadable,
            'uploaded_by' => auth()->user()->name,
            'account' => $request['account'],
            'tax_year' => $request['tax_year']
        ]);

        $documents = Document::where('client_id', $request['client_id'])->get();

        return response()->json(['message' => 'Upload Succesful', 'documents' => $documents], 200);
    }

    public function getPortalFiles(Request $request)
    {
        return Document::where('client_id', $request->id)->get();
    }

    public function getPortalFile($id)
    {
        $doc = Document::where('id', $id)->first();

        $s3_base_url = Storage::disk('s3')->get('/'.$doc['path'].$doc['document_name']);

        return $s3_base_url;
    }

    public function updatePortalFile(Request $request, Document $doc)
    {   
        $data = $request->validate([
            'client_id' => 'required|integer',
            'document_name' => 'required|string',
            'path' => 'required|string',
            'payment_required' => 'required|boolean',
            'signature_required' => 'required|boolean',
            'downloadable' => 'required|boolean',
            'paid' => 'required|boolean',
            'signed' => 'required|boolean',
            'message' => 'nullable|string',
            'account' => 'required|string',
            'uploaded_by' => 'required|string',
            'tax_year' => 'required|string',
        ]);

        $doc->update($data);

        return response()->json(['message' => 'Document Updated', 'document' => $doc]);
    }

    public function deletePortalFile($id)
    {
        $doc = Document::where('id', $id)->first();
        Storage::disk('s3')->delete($doc->path.'/'.$doc->document_name);
        $doc->delete();

        return response('Document Deleted', 200);
    }

}
