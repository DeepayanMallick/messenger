<?php

namespace App\Http\Controllers;

use App\Models\MessageHistory;
use Illuminate\Http\Request;
use App\Events\Chat;
use App\Models\GroupConversation;
use App\Models\GroupMember;
use App\Models\GroupMessage;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        if (!auth()) {
            return;
        }

        $messageHistory = MessageHistory::create([
            'from_user' => auth()->id(),
            'to_user' => $request->input('toUserID'),
            'message' => $request->input('message'),
        ]);

        event(new Chat($request->input('fromUserID'), $request->input('message')));

        return true;
    }


    public function sendGroupMessage(Request $request)
    {
        if (!auth()) {
            return;
        }

        $groupConversation = GroupConversation::create([
            'group_id' => $request->input('groupID'),
            'member_id' => $request->input('memberID'),
            'message' => $request->input('message'),
        ]);

        event(new Chat($request->input('fromUserID'), $request->input('message')));

        return true;
    }

    public function createGroup(Request $request)
    {
        if (!auth()) {
            return;
        }

        $groupMessage = GroupMessage::create([
            'group_name' => $request->input('groupName'),
        ]);

        event(new Chat($request->input('fromUserID'), $request->input('message')));

        return true;
    }

    public function addMember(Request $request)
    {
        if (!auth()) {
            return;
        }

        $groupID = $request->input('group_id');
        $members = $request->input('groupMembers');

        $groupMembersData = [];
        foreach ($members as $member) {
            $groupMembersData[] = [
                'group_id' => $groupID,
                'member_id' => $member,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        GroupMember::insert($groupMembersData);


        event(new Chat($request->input('fromUserID'), $request->input('message')));

        return true;
    }

    public function upload(Request $request)
    {
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
