@extends('layouts.app')
@section('content')
<div id="main-content">
    @auth
    <video-chat :allusers="{{ $users }}" fromUserName="{{ auth()->user()->name }}" :authUserId="{{ auth()->id() }}"
        turn_url="{{ env('TURN_SERVER_URL') }}" frmUserId="{{ auth()->id() }}"
        turn_username="{{ env('TURN_SERVER_USERNAME') }}" turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" />
    @else
    <video-chat :allusers="{{ $users }}" fromUserName="" :authUserId=""
        turn_url="{{ env('TURN_SERVER_URL') }}" frmUserId=""
        turn_username="{{ env('TURN_SERVER_USERNAME') }}" turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" />
    @endauth
</div>
@endsection