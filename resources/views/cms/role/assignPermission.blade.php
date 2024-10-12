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
                            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role List</a></li>
                            <li class="breadcrumb-item active"> Assign Role Permission</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">{{ $role->name }}</h3>
        </div>
        {!! Form::open(['url' => route('submitPermission'), 'method' => 'POST']) !!}
        <div class="card-body">
            <input type="hidden" name="id" value={{ $role->id }}>
            @php
                $assignPermissions = $role->permissions->isEmpty() ? [] : $role->permissions->pluck('name','id')->toArray();
                // dd($assignPermissions);
                // dd($permissions);
            @endphp
            @foreach($permissions as $moduleName => $module)
                <p style="font-size: 24px; font-weight: bold;">{{ $moduleName }}</p>
                @foreach($module as $permission)
                {!! Form::label('permission_id[]', ucFirst($permission->name), ['style' => 'margin-left: 20px;']) !!}
                <input name="permission_id[]" class="checkbox" type="checkbox" id="chkbox_a1" value="{{ $permission->id }}" {{ array_key_exists($permission->id, $assignPermissions) ? 'checked' : null }}><br>
                @endforeach
            @endforeach
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
