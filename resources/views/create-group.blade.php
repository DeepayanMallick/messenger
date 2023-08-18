@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center CustomForm">
        <div class="col-md-5">

            <div class="formWrap">
                <h2>Create Group</h2>
                <form method="POST" action="{{ route('create-group') }}">
                    @csrf
                    <div class="row mb-3">
                        <label for="group-name" class="col-md-12 col-form-label">Group Name</label>

                        <div class="col-md-12">
                            <div class="InputWrap">
                                <input id="group_name" type="text" class="form-control @error('group_name') is-invalid @enderror" name="group_name" placeholder="Enter Your Group Name" value="{{ old('group_name') }}" required autocomplete="group_name" autofocus>
                                <i class="fa fa-user"></i>
                            </div>
                            @error('group_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="groupMembers" class="col-md-12 col-form-label">Add People</label>
                        <div class="col-md-12">
                            <div class="InputWrap">
                                <input id="groupMembers" type="text" class="form-control" placeholder="Add group member" name="groupMembers[]">
                                <i class="fa fa-user"></i>
                            </div>                         
                        </div>
                    </div>                    

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">
                                Create
                            </button>
                        </div>
                    </div>
                </form> 
            </div>
        </div>
    </div>
</div>
@endsection
