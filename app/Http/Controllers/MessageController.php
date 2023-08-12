<?php

namespace App\Http\Controllers;

use App\Models\MessageHistory;
use Illuminate\Http\Request;
use App\Events\Chat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index(Request $request)
    {    
        if (!auth()) {
            return;
        }
        Log::info($request->input('fromUserID'));
        Log::info($request->input('toUserID'));

        $messageHistory = MessageHistory::create([
            'from_user' => auth()->id(),
            'to_user' => $request->input('toUserID'),
            'message' => $request->input('message'),
        ]);

        event(new Chat($request->input('fromUserID'), $request->input('message')));

        return true;
    }

    public function upload(Request $request){
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(" ", "", $file->getClientOriginalName());
            $file->storeAs('uploads', $fileName, 'public');

            $fileUrl = asset('storage/uploads/' . $fileName); // Get the full public URL
            // $encodedUrl = urlencode($fileUrl);
            return response()->json([
                'url' => $fileUrl, // Return the file URL
                'message' => $file->getClientOriginalName(),
            ]);
        }

        return response()->json(['error' => 'No file selected'], 400);
    }
}
