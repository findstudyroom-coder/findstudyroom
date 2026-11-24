<aside class="sidebar">

    <h2 class="logo">📚 Findstudyroom</h2>
    <p class="section-title">Library Admin</p>

    <ul>
        @if(auth()->user()->hasRole('Super Admin'))
        <li class="{{ request()->routeIs('subadmins.*') ? 'active' : '' }}">
            <a href="{{ route('subadmins.index') }}">
                <i class="fas fa-user-shield"></i> Sub Admins
            </a>
        </li>
        @endif
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        @can('library.view')
        <li class="{{ request()->routeIs('libraries.*') ? 'active' : '' }}">
            <a href="{{ route('libraries.index') }}">
                <i class="fas fa-book"></i> Libraries
            </a>
        </li>
        @endcan

        @canany(['plans.view', 'libraryDetails.view', 'students.view'])
        <li class="has-dropdown {{ request()->routeIs('plans.*') || request()->routeIs('library-details.*') || request()->routeIs('members.*') || request()->routeIs('seats.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" onclick="toggleDropdown('libraryDropdown', 'libraryDropdownArrow')" class="dropdown-toggle">
                <span>
                    <i class="fas fa-book"></i> App Library Management
                </span>
                <i class="fas fa-chevron-down dropdown-icon" id="libraryDropdownArrow"></i>
            </a>

            <ul id="libraryDropdown" class="dropdown-menu">
               
                @can('plans.view')
                <li class="{{ request()->routeIs('plans.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('plans.index') }}">
                        <i class="fas fa-list"></i> Plans
                    </a>
                </li>
                @endcan

                @can('libraryDetails.view')
                <li class="{{ request()->routeIs('library-details.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('library-details.index') }}">
                        <i class="fas fa-info-circle"></i> Library Details
                    </a>
                </li>
                @endcan

                @can('students.view')
                <li class="{{ request()->routeIs('students.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('students.index') }}">
                        <i class="fas fa-users"></i> Students
                    </a>
                </li>
                @endcan

                <!-- <li class="{{ request()->routeIs('seats.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('libraries.index') }}">
                        <i class="fas fa-th"></i> Seat Maps
                    </a>
                </li> -->
            </ul>
        </li>
        @endcanany

        @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Sub Admin']))
        <li class="has-dropdown {{ request()->routeIs('call-center.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" onclick="toggleDropdown('callCenterDropdown', 'callCenterDropdownArrow')" class="dropdown-toggle">
                <span>
                    <i class="fas fa-headset"></i> Call Center
                </span>
                <i class="fas fa-chevron-down dropdown-icon" id="callCenterDropdownArrow"></i>
            </a>

            <ul id="callCenterDropdown" class="dropdown-menu">
                <li class="{{ request()->routeIs('call-center.dashboard') ? 'sub-active' : '' }}">
                    <a href="{{ route('call-center.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('call-center.libraries') ? 'sub-active' : '' }}">
                    <a href="{{ route('call-center.libraries') }}">
                        <i class="fas fa-phone-alt"></i> Library Calls
                    </a>
                </li>
                <li class="{{ request()->routeIs('call-center.report') ? 'sub-active' : '' }}">
                    <a href="{{ route('call-center.report') }}">
                        <i class="fas fa-chart-bar"></i> Calling Report
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('library.view') || auth()->user()->can('bookings.view'))
        <li class="{{ request()->routeIs('library-visitors.*') ? 'active' : '' }}">
            <a href="{{ route('library-visitors.index') }}">
                <i class="fas fa-chart-line" style="color: #38bdf8;"></i> Library Visitors
            </a>
        </li>
        @endif

        @can('bookings.view')
        <li class="{{ request()->routeIs('booking.*') ? 'active' : '' }}">
            <a href="{{ route('booking.index') }}">
                <i class="fas fa-book"></i> Bookings
            </a>
        </li>
        @endcan

        <li class="has-dropdown {{ request()->routeIs('coachings.*') || request()->routeIs('commando-classes.*') || request()->routeIs('commando-instructors.*') || request()->routeIs('commando-schedules.*') || request()->routeIs('commando-trials.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" onclick="toggleDropdown('theMovementDropdown', 'theMovementDropdownArrow')" class="dropdown-toggle">
                <span>
                    <i class="fas fa-graduation-cap" style="color: #f97316;"></i> Coaching Management
                </span>
                <i class="fas fa-chevron-down dropdown-icon" id="theMovementDropdownArrow"></i>
            </a>

            <ul id="theMovementDropdown" class="dropdown-menu">
                <li class="{{ request()->routeIs('coachings.index') || request()->routeIs('coachings.edit') ? 'sub-active' : '' }}">
                    <a href="{{ route('coachings.index') }}">
                        <i class="fas fa-school"></i> All Coachings
                    </a>
                </li>
                <li class="{{ request()->routeIs('coachings.create') ? 'sub-active' : '' }}">
                    <a href="{{ route('coachings.create') }}">
                        <i class="fas fa-plus-circle"></i> Add Coaching
                    </a>
                </li>
                <li class="{{ request()->routeIs('commando-classes.index') || request()->routeIs('commando-classes.edit') ? 'sub-active' : '' }}">
                    <a href="{{ route('commando-classes.index') }}">
                        <i class="fas fa-list"></i> Core Disciplines
                    </a>
                </li>
                <li class="{{ request()->routeIs('commando-classes.create') ? 'sub-active' : '' }}">
                    <a href="{{ route('commando-classes.create') }}">
                        <i class="fas fa-plus-circle"></i> Add Discipline
                    </a>
                </li>
                <li class="{{ request()->routeIs('commando-instructors.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('commando-instructors.index') }}">
                        <i class="fas fa-user-ninja"></i> Master Instructors
                    </a>
                </li>
                <li class="{{ request()->routeIs('commando-schedules.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('commando-schedules.index') }}">
                        <i class="fas fa-calendar-alt"></i> Weekly Schedule
                    </a>
                </li>
                <li class="{{ request()->routeIs('commando-trials.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('commando-trials.index') }}" class="d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-user-check"></i> Trial Bookings</span>
                        @php
                            $pendingTrialCount = \Illuminate\Support\Facades\Schema::hasTable('commando_trials') ? \App\Models\CommandoTrial::where('status', 'Pending')->count() : 0;
                        @endphp
                        @if($pendingTrialCount > 0)
                            <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 10px;">{{ $pendingTrialCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('the-movement') }}" target="_blank">
                        <i class="fas fa-external-link-alt" style="color: #f97316;"></i> View The Movement
                    </a>
                </li>
            </ul>
        </li>
        
        @can('users.view')
        <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}">
                <i class="fas fa-users-cog"></i> Users
            </a>
        </li>
        @endcan
        
        @can('blogs.view')
        <li class="{{ request()->routeIs('blogs.index.*') ? 'active' : '' }}">
            <a href="{{ route('admin.blogs.index') }}">
                <i class="fas fa-book"></i> Blogs
            </a>
        </li>
        @endcan
        
        @can('subjects.view')
        <li class="{{ request()->routeIs('Subject.*') ? 'active' : '' }}">
            <a href="{{ route('Subject.index') }}">
                <i class="fas fa-book"></i> Subjects
            </a>
        </li>
        @endcan
        
        @can('classes.view')
        <li class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <a href="{{ route('classes.index') }}">
                <i class="fas fa-book"></i> Classes
            </a>
        </li>
        @endcan
        
        @can('tutors.view')
        <li class="{{ request()->routeIs('tutors.*') ? 'active' : '' }}">
            <a href="{{ route('tutors.index') }}">
                <i class="fas fa-book"></i> Tutor
            </a>
        </li>
        @endcan
            
        @can('boards.view')
        <li class="{{ request()->routeIs('boards.*') ? 'active' : '' }}">
            <a href="{{ route('boards.index') }}">
                <i class="fas fa-book"></i> Bords
            </a>
        </li>
        @endcan

        @can('examPreparations.view')
        <li class="{{ request()->routeIs('exam_preparations.*') ? 'active' : '' }}">
            <a href="{{ route('exam_preparations.index') }}">
                <i class="fas fa-book"></i> ExamPreparation
            </a>
        </li>
        @endcan
            
        @can('location.view')
        <li class="has-dropdown {{ request()->routeIs('states.*') || request()->routeIs('districts.*') || request()->routeIs('tehsils.*') ? 'active' : '' }}">

            <a href="javascript:void(0);" onclick="toggleDropdown('locationDropdown', 'dropdownArrow')" class="dropdown-toggle">
                <span>
                    <i class="fas fa-map-marker-alt"></i> Location
                </span>
                <i class="fas fa-chevron-down dropdown-icon" id="dropdownArrow"></i>
            </a>

            <ul id="locationDropdown" class="dropdown-menu">
                <li class="{{ request()->routeIs('states.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('states.index') }}">
                        <i class="fas fa-map"></i> State
                    </a>
                </li>
        
                <li class="{{ request()->routeIs('districts.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('districts.index') }}">
                        <i class="fas fa-city"></i> District
                    </a>
                </li>
        
                <li class="{{ request()->routeIs('tehsils.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('tehsils.index') }}">
                        <i class="fas fa-location-arrow"></i> Tehsil
                    </a>
                </li>
            </ul>
        </li>
        @endcan

        
        
        <li>
            <a  href="#" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt"></i> Logout</a>
           
        </li>
    </ul>

</aside>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    
function toggleDropdown(dropdownId, arrowId) {
    const dropdown = document.getElementById(dropdownId);
    const arrow = document.getElementById(arrowId);

    dropdown.classList.toggle("show");
    arrow.classList.toggle("rotate");
}

window.onload = function () {
    const dropdowns = [
        { menuId: 'theMovementDropdown', arrowId: 'theMovementDropdownArrow' },
        { menuId: 'locationDropdown', arrowId: 'dropdownArrow' },
        { menuId: 'libraryDropdown', arrowId: 'libraryDropdownArrow' },
        { menuId: 'callCenterDropdown', arrowId: 'callCenterDropdownArrow' }
    ];

    dropdowns.forEach(item => {
        const parent = document.getElementById(item.menuId)?.closest('.has-dropdown');

        if (parent?.classList.contains('active')) {
            document.getElementById(item.menuId).classList.add('show');
            document.getElementById(item.arrowId).classList.add('rotate');
        }
    });
}
</script>
<script>
    function confirmLogout(event) {
        event.preventDefault(); // Prevent the default action (redirecting to the logout page)

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to log out?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log out!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, proceed with logout
                window.location.href = "{{ route('admin.signOut') }}";
            }
        });
    }
</script>