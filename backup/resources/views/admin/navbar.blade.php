<aside class="sidebar">

    <h2 class="logo">📚 Findstudyroom</h2>
    <p class="section-title">Library Admin</p>

    <ul>
        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="{{ request()->routeIs('libraries.*') ? 'active' : '' }}">
            <a href="{{ route('libraries.index') }}">
                <i class="fas fa-book"></i> Libraries
            </a>
        </li>
        <li class="{{ request()->routeIs('coachings.*') ? 'active' : '' }}">
            <a href="{{ route('coachings.index') }}">
                <i class="fas fa-graduation-cap"></i> Coachings
            </a>
        </li>

        <li class="has-dropdown {{ request()->routeIs('plans.*') || request()->routeIs('library-details.*') || request()->routeIs('members.*') || request()->routeIs('seats.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" onclick="toggleDropdown('libraryDropdown', 'libraryDropdownArrow')" class="dropdown-toggle">
                <span>
                    <i class="fas fa-book"></i> App Library Management
                </span>
                <i class="fas fa-chevron-down dropdown-icon" id="libraryDropdownArrow"></i>
            </a>

            <ul id="libraryDropdown" class="dropdown-menu">
               

                <li class="{{ request()->routeIs('plans.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('plans.index') }}">
                        <i class="fas fa-list"></i> Plans
                    </a>
                </li>

                <li class="{{ request()->routeIs('library-details.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('library-details.index') }}">
                        <i class="fas fa-info-circle"></i> Library Details
                    </a>
                </li>

                <li class="{{ request()->routeIs('students.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('students.index') }}">
                        <i class="fas fa-users"></i> Students
                    </a>
                </li>

                <!-- <li class="{{ request()->routeIs('seats.*') ? 'sub-active' : '' }}">
                    <a href="{{ route('libraries.index') }}">
                        <i class="fas fa-th"></i> Seat Maps
                    </a>
                </li> -->
            </ul>
        </li>

        <li class="{{ request()->routeIs('booking.*') ? 'active' : '' }}">
            <a href="{{ route('booking.index') }}">
                <i class="fas fa-book"></i> Bookings
            </a>
        </li>
        
        <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}">
                <i class="fas fa-users-cog"></i> Users
            </a>
        </li>
        
        <li class="{{ request()->routeIs('blogs.index.*') ? 'active' : '' }}">
            <a href="{{ route('admin.blogs.index') }}">
                <i class="fas fa-book"></i> Blogs
            </a>
        </li>
        
        
        
            <li class="{{ request()->routeIs('Subject.*') ? 'active' : '' }}">
                <a href="{{ route('Subject.index') }}">
                    <i class="fas fa-book"></i> Subjects
                </a>
           </li>
        
          <li class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <a href="{{ route('classes.index') }}">
                <i class="fas fa-book"></i> Classes
            </a>
        </li>
        
           <li class="{{ request()->routeIs('tutors.*') ? 'active' : '' }}">
            <a href="{{ route('tutors.index') }}">
                <i class="fas fa-book"></i> Tutor
            </a>
           </li>
            

            <li class="{{ request()->routeIs('boards.*') ? 'active' : '' }}">
            <a href="{{ route('boards.index') }}">
                <i class="fas fa-book"></i> Bords
            </a>
           </li>

            <li class="{{ request()->routeIs('exam_preparations.*') ? 'active' : '' }}">
            <a href="{{ route('exam_preparations.index') }}">
                <i class="fas fa-book"></i> ExamPreparation
            </a>
           </li>
            
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
        { menuId: 'locationDropdown', arrowId: 'dropdownArrow' },
        { menuId: 'libraryDropdown', arrowId: 'libraryDropdownArrow' }
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