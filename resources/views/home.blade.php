@extends('layouts.app')
@section('content')
<div id="main-content">
    <video-chat :allusers="{{ $users }}" :allgroups="{{$groups}}" fromUserName="{{ auth()->user()->name }}" :authUserId="{{ auth()->id() }}"
        turn_url="{{ env('TURN_SERVER_URL') }}" frmUserId="{{ auth()->id() }}"
        turn_username="{{ env('TURN_SERVER_USERNAME') }}" turn_credential="{{ env('TURN_SERVER_CREDENTIAL') }}" />
</div>
@endsection