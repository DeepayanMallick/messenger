<?php

namespace App\Http\Controllers;

use App\Models\MessageHistory;
use Illuminate\Http\Request;
use App\Events\Chat;
use App\Models\Group;
use App\Models\GroupConversation;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendMessage(Request $request)
    {
        if (!auth()) {
            return;
        }

        $data = [
            'from_user' => $request->input('fromUserID'),
            'to_user' => $request->input('toUserID'),
            'message' => $request->input('message'),
        ];
        $res = [];
        MessageHistory::create($data);
        $dta = MessageHistory::Where(["from_user" => $request->input('fromUserID'), "to_user" => $request->input("toUserID")])->orWhere(["to_user" => $request->input('fromUserID'), "from_user" => $request->input("toUserID")])->get();
        foreach ($dta as $ky) {
            $frm = User::where("id", $ky->from_user)->first()->name;
            $tom = User::where("id", $ky->to_user)->first()->name;
            $res[] = [
                'from_user' => $ky->from_user,
                'from_user_details' => $frm,
                'to_user' => $ky->to_user,
                'to_user_details' => $tom,
                'message' => $ky->message,
                'message_time' => date('h:i A', strtotime($ky->created_at)),
            ];
        }
        broadcast(new Chat($res))->toOthers();
        return true;
    }

    public function sendGroupMessage(Request $request)
    {
        if (!auth()) {
            return;
        }

        $groupData = [
            'group_id' => $request->input('groupID'),
            'user_id' => $request->input('memberID'),
            'message' => $request->input('message'),
        ];

        $res = [];
        $groupConversation = GroupConversation::create($groupData);
        $dta = $groupConversation->where('group_id', $request->input('groupID'))->get();
        foreach ($dta as $ky) {
            $res[] = [
                'from_user' => $ky->member ? $ky->member->id : '',
                'from_user_details' => $ky->member ? $ky->member->name : '',
                'message' => $ky->message,
                'message_time' => date('h:i A', strtotime($ky->created_at)),
            ];
        }
        broadcast(new Chat($res))->toOthers();
        return true;
    }

    public function createGroupForm(Request $request)
    {
        return view('create-group');
    }


    public function createGroup(Request $request)
    {
        if (!auth()) {
            return;
        }

        $groupMessage = Group::create([
            'group_name' => $request->input('group_name'),
        ]);

        if ($groupMessage) {
            $members = $request->input('groupMembers');
            $members[] = auth()->user()->id;
            $groupMembersData = [];
            foreach ($members as $member) {
                $groupMembersData[] = [
                    'group_id' => $groupMessage->id,
                    'user_id' => $member,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            GroupMember::insert($groupMembersData);
        }

        return redirect("/")->with("Group created successfully!");
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
                'user_id' => $member,
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
