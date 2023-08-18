<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupConversation;
use App\Models\MessageHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['users'] = User::where('id', '<>', Auth::id())->get();
        $data['groups'] = Group::get();
        return view('home', $data);
    }
    public function msgHistory($from, $to)
    {
        $dta = MessageHistory::where(["from_user" => $from, "to_user" => $to])->orWhere(["to_user" => $from, "from_user" => $to])->get();
        $res = [];
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
        return response()->json(['data' => $res], 200);
    }

    public function groupMsgHistory($group_id)
    {
        $dta = GroupConversation::where('group_id', $group_id)->get();
        $res = [];
        foreach ($dta as $ky) {
            $from_user = User::where('id', $ky->user_id)->first();
            $res[] = [
                'from_user' => $from_user->id,
                'from_user_details' => $from_user->name,
                'message' => $ky->message,
                'message_time' => date('h:i A', strtotime($ky->created_at)),
            ];
        }
        return response()->json(['data' => $res], 200);
    }
}
