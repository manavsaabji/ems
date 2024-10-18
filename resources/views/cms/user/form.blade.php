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
                            <li class="breadcrumb-item active"> User Create</li>
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
            <h3 class="card-title">Create User</h3>
        </div>
        {!! Form::model($object, ['url' => $url, 'method' => $method, 'files'=>true]) !!}
        <div class="card-body">
            <input type="hidden" name="id" value={{ $object->id }}>
            <div class="form-group">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email', 'Email') !!}
                {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="custom-file">
                {!! Form::file('profile_pic', ['class' => 'custom-file-input','id' => 'profile_pic']) !!}
                {!! Form::label('profile_pic','Choose file',['class' => 'custom-file-label']) !!}
            </div>
            @if (!empty($object->id))
                <div class="form-check">
                    {!! Form::checkbox('is_active', 1, $object->is_active == 1 ? true : false, ['class' => 'form-check-input']) !!}
                    {!! Form::label('is_active', 'Is Active') !!}
                </div>
            @endif
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
