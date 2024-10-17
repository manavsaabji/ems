@extends('cms.layouts.master')
@section('headerLinks')
<style>
.pdf-viewer {
    width: 30vw;
    height: 50vh; /* Set a fixed height for the PDF viewer */
    margin: 20px auto;
    border: 1px solid #ccc;
    /* scrollbar-width: none; */
}
/*
input.form-control, button {
    width: calc(100% - 40%);
    padding-left: 20%;
    padding-right: 20%;
    box-sizing: border-box;
    margin-bottom: 10px;
} */

</style>
@endsection
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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active"> Edit Profile</li>
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
            <h3 class="card-title">Edit Profile</h3>
        </div>
        {!! Form::open(['url' => route('submitProfile'), 'method' => 'PUT', 'files' => true]) !!}
        <div class="card-body">
            {{-- <input type="hidden" name="id" value={{ $object->id }}> --}}
            <div class="form-group">
                {!! Form::label(null, 'Name') !!}
                : <span>{{ $user->name }}</span>
            </div>
            <div class="form-group">
                {!! Form::label(null, 'Email') !!}
                : <span>{{ $user->email }}</span>
            </div>


            <div class="card card-primary" style="display: none;" id="passwordSection">
                <div class="card-body">
                <button id="passwordChangeBtn" style="border-radius: 10px; width: 50%; display: block; margin: 0 auto; background-color:mediumturquoise"  type="button">Reset Password</button>
                    <!-- Div for password change bar -->
                    <div style="display: none;" id="passwordChangeBar">
                        <h3>Change Password</h3>
                        <div class="form-group">
                            {!! Form::label('password', 'Password') !!}
                            {!! Form::password('password', ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('confirm_password', 'Confirm Password') !!}
                            {!! Form::password('confirm_password', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('address', 'Address') !!}
                {!! Form::text('address', $user->employee->address, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('city', 'City') !!}
                {!! Form::text('city', $user->employee->city, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('phone_no', 'Phone No') !!}
                {!! Form::text('phone_no', $user->employee->phone_no, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('addhar_card', 'Addhar Card') !!}
                <div class="custom-file">
                    {!! Form::file('addhar_card', ['id'=> 'pdf-aadharCard', 'class' => 'custom-file-input', 'accept' => 'application/pdf, image/png, image/jpeg']) !!}
                    {!! Form::label('addhar_card','Choose file',['class' => 'custom-file-label']) !!}
                    <iframe id="aadharCard-viewer" class="pdf-viewer" hidden></iframe>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('pan_card', 'PAN Card') !!}
                <div class="custom-file">
                    {!! Form::file('pan_card', ['id'=> 'pdf-panCard', 'class' => 'custom-file-input', 'accept' => 'application/pdf']) !!}
                    {!! Form::label('pan_card','Choose file',['class' => 'custom-file-label']) !!}
                    <iframe id="panCard-viewer" class="pdf-viewer" hidden></iframe>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('bank_document', 'Bank Document') !!}
                <div class="custom-file">
                    {!! Form::file('bank_document', ['id'=> 'pdf-bankDoc', 'class' => 'custom-file-input', 'accept' => 'application/pdf']) !!}
                    {!! Form::label('bank_document','Choose file',['class' => 'custom-file-label']) !!}
                    <iframe id="bankDoc-viewer" class="pdf-viewer" hidden></iframe>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('salary', 'Salary') !!}
                <span>{{ $user->employee->salary }}</span>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection


@section('footerScripts')

<script>

    if ('{{ session()->has('password entry') }}'){
        document.getElementById('passwordChangeBar').style.display = 'block';
        document.getElementById('passwordChangeBtn').style.display = 'none';
    }
    document.getElementById('passwordSection').style.display = 'block';



    function handleFileSelect(inputElement, viewerElement) {
        const file = inputElement.files[0];

        // Check if a file is selected and if it's of an allowed type (PDF, PNG, JPG)
        const allowedTypes = ['application/pdf'];
        if (file && allowedTypes.includes(file.type)) {
            // Check if the file size is less than 2MB (2 * 1024 * 1024 bytes)
            const maxSizeInBytes = 2 * 1024 * 1024; // 2MB
            if (file.size <= maxSizeInBytes) {
                const fileReader = new FileReader();

                fileReader.onload = function(e) {
                    viewerElement.src = e.target.result;
                    viewerElement.hidden = false; // Show the viewer when a file is loaded
                };

                fileReader.readAsDataURL(file);
            } else {
                alert('Please select a file less than 2MB.'); // Custom notification for file size
                inputElement.value = ''; // Clear the file input
            }
        } else {
            alert('Please select a valid PDF file.');
            inputElement.value = ''; // Clear the file input
        }
    }

    document.getElementById('pdf-aadharCard').addEventListener('change', function() {
        handleFileSelect(this, document.getElementById('aadharCard-viewer'));
    });

    document.getElementById('pdf-panCard').addEventListener('change', function() {
        handleFileSelect(this, document.getElementById('panCard-viewer'));
    });

    document.getElementById('pdf-bankDoc').addEventListener('change', function() {
        handleFileSelect(this, document.getElementById('bankDoc-viewer'));
    });

    // Get references to the button and the password change bar
    const passwordChangeBtn = document.getElementById('passwordChangeBtn');
    const passwordChangeBar = document.getElementById('passwordChangeBar');

    // Default state: hidden
    let isVisible = false;

    // Add click event listener to the button
    passwordChangeBtn.addEventListener('click', function() {
        // Toggle visibility state
        isVisible = !isVisible;

        // Show the password change bar and hide the button
        if (isVisible) {
            passwordChangeBar.style.display = 'block'; // Show the bar
            passwordChangeBtn.style.display = 'none'; // Hide the button
        }
    });
</script>

@endsection

