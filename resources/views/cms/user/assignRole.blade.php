@extends('cms.layouts.master')

@section('content')
<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2 row">
                    <div class="col-sm-6">
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User List</a></li>
                            <li class="breadcrumb-item active"> Assign Role</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card card-primary card-outline">
        {!! Form::open(['url' => route('submitRole'), 'method' => 'POST']) !!}
        <div class="card-header">
            <h3 class="card-title">{{ $user->name }}</h3>
        </div>
        <div class="card-body">
            <input type="hidden" name="id" value={{ $user->id }}>
            @php
                $assignRoles = $user->roles->isEmpty() ? [] : $user->roles->pluck('name','id')->toArray();
                // dd($assignRoles);
            @endphp
            @foreach($roles as $id => $name)
                {{-- {{ dd($roles) }} --}}
                {!! Form::label('role_id[]', ucFirst($name)) !!}
                <input name="role_id[]" class="checkbox" type="checkbox" id="chkbox_a1" value="{{ $id }}" {{ array_key_exists($id, $assignRoles) ? 'checked' : null }}><br>
            @endforeach
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
