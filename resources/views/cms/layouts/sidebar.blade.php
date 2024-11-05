<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex">
            <div class="image">
                <img src="{{ asset('assets/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link  @if(Route::currentRouteName() == 'dashboard') active @endif">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item @if (in_array(Route::currentRouteName(), [ 'user.index', 'role.index', 'permission.index', 'module.index' ])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Route::currentRouteName(), [ 'user.index', 'role.index', 'permission.index', 'module.index' ])) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'user.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'role.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Role List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permission.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'permission.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permission List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('module.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'module.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Module List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item @if (in_array(Route::currentRouteName(), [ 'task.index', 'task.create' ])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Route::currentRouteName(), [ 'task.index', 'task.create' ])) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Task Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('task.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'task.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Task List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('task.create') }}"
                                class="nav-link @if (Route::currentRouteName() == 'task.create') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Task Create</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('department.index') }}"
                        class="nav-link @if (Route::currentRouteName() == 'department.index') active @endif">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Department List</p>
                    </a>
                </li>
                <li class="nav-item @if (in_array(Route::currentRouteName(), [ 'leave.index', 'leave.create', 'leaveIndexAll' ])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Route::currentRouteName(), [ 'leave.index', 'leave.create', 'leaveIndexAll' ])) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Leave Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(auth()->user()->hasRole('admin'))
                        <li class="nav-item">
                            <a href="{{ route('leaveIndexAll') }}"
                                class="nav-link @if (Route::currentRouteName() == 'leaveIndexAll') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave History</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('leave.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'leave.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('leave.create') }}"
                                class="nav-link @if (Route::currentRouteName() == 'leave.create') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Leave Create</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item @if (in_array(Route::currentRouteName(), [ 'attendance.index', 'manuallyAttendance' ])) menu-open @endif">
                    <a href="#" class="nav-link @if (in_array(Route::currentRouteName(), [ 'attendance.index', 'manuallyAttendance' ])) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Attendance Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('attendance.index') }}"
                                class="nav-link @if (Route::currentRouteName() == 'attendance.index') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manuallyAttendance') }}"
                                class="nav-link @if (Route::currentRouteName() == 'manuallyAttendance') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mannual Attendance</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('activityLogs') }}" class="nav-link  @if(Route::currentRouteName() == 'activityLogs') active @endif">
                        <i class="fas fa-circle nav-icon"></i>
                        <p>Activity Logs</p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
