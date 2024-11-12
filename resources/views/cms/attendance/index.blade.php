@extends('cms.layouts.master')

@section('headerLinks')
<style>
.card-body {
    overflow-x: auto;
}

.table th, .table td {
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
}

.table {
    table-layout: auto;
    width: 100%;
}

.table-bordered {
    border-collapse: collapse;
}

.table th, .table td {
    min-width: 80px;
}
.sunday-clr{
    color: white;
    background-color: #624DF0;   /*majorelle-blue */
}
.leave-clr{
    color: white;
    background-color: #303D3A;    /* crayola */
}
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
                                <li class="breadcrumb-item active"> Attendance List</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card-primary card">
        <div class="card-header">
            <h3 class="card-title">Filter</h3>
        </div>
            <div class="card-body">
                <div class="form-group col-md-4">
                    {!! Form::open(['method' => 'GET', 'route' => 'attendance.index']) !!}
                    {!! Form::label('select_month', 'Select Month: ') !!}
                    {!! Form::month('select_month', $selectedMonth, ['class' => 'form-control', 'id' => 'select_month', 'onchange' => 'this.form.submit()']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card-primary card-outline ">
            <div class="card-header">
                <h3 class="card-title">Attendance list</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="overflow-x: auto;">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if(auth()->user()->hasRole('admin'))
                            <th>Name</th>
                            @endif

                            @for ($day = 1; $day <= $daysInMonth; $day++)
                            <th>{{ $day }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $userId => $userAttendances)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @if(auth()->user()->hasRole('admin'))
                                <td>{{ $userAttendances->first()->user->name ?? 'Unknown' }}</td>
                            @endif

                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $currentDate = Carbon\Carbon::createFromFormat('Y-m-d', $selectedMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT))->toDateString();
                                    $attendanceForDay = $userAttendances->firstWhere('date', $currentDate);
                                    $isSunday = Carbon\Carbon::parse($currentDate)->isSunday();
                                @endphp
                                @php
                                    $onLeave = false;
                                @endphp

                                @if (isset($leaves[$userId]))
                                    @foreach ($leaves[$userId] as $leave)
                                        @php
                                            if ($leave->start_date <= $currentDate && $leave->end_date >= $currentDate) {
                                                $onLeave = true;
                                                $reason = $leave->reason;
                                                $duration = $leave->leave_duration;
                                                // dd($leave->start_date, $leave->end_date, $currentDate);
                                                break;
                                            }
                                        @endphp
                                    @endforeach
                                @endif

                                <td class="{{ $isSunday ? 'sunday-clr' : ($onLeave ? 'leave-clr' : ($attendanceForDay ? 'badge-success' : 'badge-danger')) }}">
                                    @if($isSunday)
                                        <div class="sunday-clr">
                                        <strong>Sunday</strong>
                                        <div>
                                    @elseif($onLeave || $attendanceForDay)
                                        @if($onLeave)
                                            @if($duration == 'first half' || $duration == 'second half')
                                                <strong>Duration: </strong> {{ $duration }} <br>
                                            @endif
                                            <strong>Leave: </strong> {{ $reason }}
                                        @endif
                                        @if($attendanceForDay)
                                            <div class='badge-success'>
                                                <strong>Punch In:</strong> {{ Carbon\Carbon::createFromFormat('H:i:s', $attendanceForDay->arrival_time)->format('h:i A') ?? 'N/A' }}<br>
                                                <strong>Punch Out:</strong> @if(!empty($attendanceForDay->end_time)) {{ Carbon\Carbon::createFromFormat('H:i:s', $attendanceForDay->end_time)->format('h:i A')  }}@else 'N/A'@endif
                                            <div>
                                        @endif
                                    @else
                                        <strong>Absent</strong>
                                    @endif
                                </td>

                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

