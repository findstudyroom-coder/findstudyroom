<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <!--<link rel="stylesheet" href="style.css">-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <style>

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

/* BODY */
body {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #fff;
    overflow-x: hidden;
}

/* LAYOUT */
.dashboard {
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    overflow-x: hidden;
    background: linear-gradient(180deg, #0f172a, #020617);
    padding: 25px 20px;
    box-shadow: 8px 0 25px rgba(0,0,0,0.4);
    scrollbar-width: thin;
    scrollbar-color: #38bdf8 transparent;
}

/* Scrollbar - Chrome/Edge/Safari */
.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar::-webkit-scrollbar-thumb {
    background: #38bdf8;
    border-radius: 10px;
}
.sidebar::-webkit-scrollbar-thumb:hover {
    background: #0ea5e9;
}

/* Subtle texture */
.sidebar::before {
    content: "";
    position: absolute;
    inset: 0;
    background: url('https://www.transparenttextures.com/patterns/wood-pattern.png');
    opacity: 0.05;
    pointer-events: none;
}

/* LOGO */
.logo {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #38bdf8;
    letter-spacing: 1px;
}

/* SECTION TITLE */
.section-title {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* MENU LIST */
.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    display: block;
    margin: 6px 0;
    border-radius: 12px;
    cursor: pointer;
    color: #cbd5f5;
    transition: all 0.3s ease;
    position: relative;
}

/* LINKS */
.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: #cbd5f5;
    text-decoration: none;
    border-radius: 10px;
    transition: 0.3s;
}

/* HOVER */
.sidebar ul li a:hover {
    background: rgba(255,255,255,0.08);
    transform: translateX(5px);
}

/* ICON */
.sidebar ul li i {
    font-size: 15px;
}

/* ================================
   ACTIVE - Regular items (sky blue)
   ================================ */
.sidebar ul li.active:not(.has-dropdown) a {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8);
    color: #fff;
    font-weight: bold;
}

/* Left glow line - only regular active items */
.sidebar ul li.active:not(.has-dropdown)::before {
    content: "";
    position: absolute;
    left: -10px;
    top: 50%;
    transform: translateY(-50%);
    height: 60%;
    width: 4px;
    background: #38bdf8;
    border-radius: 5px;
}

/* ================================
   DROPDOWN PARENT - Location
   ================================ */
.sidebar ul li.has-dropdown {
    background: transparent;
    padding: 0;
}

/* No yellow/blue card on parent */
.sidebar ul li.has-dropdown.active {
    background: transparent !important;
}

/* Subtle tint on parent link only */
.sidebar ul li.has-dropdown.active > a {
    background: rgba(14, 165, 233, 0.12) !important;
    color: #38bdf8 !important;
    font-weight: bold;
}

/* No left glow on dropdown parent */
.sidebar ul li.has-dropdown.active::before {
    display: none !important;
}

/* Arrow icon color when active */
.sidebar ul li.has-dropdown.active .dropdown-icon {
    color: #38bdf8;
}

/* Arrow rotation */
.dropdown-icon {
    margin-left: auto;
    transition: transform 0.3s ease;
    font-size: 12px;
}
.dropdown-icon.rotate {
    transform: rotate(180deg);
}

/* ================================
   DROPDOWN MENU
   ================================ */

/* Fix Bootstrap conflict */
.sidebar .dropdown-menu {
    position: static !important;
    background: rgba(255,255,255,0.03) !important;
    box-shadow: none !important;
    border: none !important;
    border-left: 2px solid rgba(56, 189, 248, 0.3) !important;
    border-radius: 0 0 8px 8px !important;
    margin-left: 20px;
    width: calc(100% - 20px);
    display: none;
    padding: 4px 0;
}

.sidebar .dropdown-menu.show {
    display: block;
}

.sidebar .dropdown-menu li {
    margin: 3px 0;
    padding: 0;
    border-radius: 0;
    background: transparent !important;
}

/* Remove glow line from sub-items */
.sidebar .dropdown-menu li::before {
    display: none !important;
}

.sidebar .dropdown-menu li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    font-size: 13.5px;
    border-radius: 8px;
    color: #94a3b8;
    background: transparent;
    font-weight: normal;
    white-space: normal;
}

/* Sub-item hover */
.sidebar .dropdown-menu li a:hover {
    background: rgba(255,255,255,0.06);
    color: #fff;
    transform: translateX(4px);
}

/* Sub-item ACTIVE — only the active one gets blue */
.sidebar .dropdown-menu li.sub-active a {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8) !important;
    color: #fff !important;
    font-weight: bold;
}

/* ================================
   MAIN CONTENT
   ================================ */
.main {
    margin-left: 260px;
    padding: 20px;
    width: calc(100% - 260px);
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* PROFILE BUTTON */
.profile {
    background: linear-gradient(135deg, #0ea5e9, #38bdf8);
    color: #fff;
    font-weight: bold;
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 14px;
}

/* SEARCH BOX */
.search-box {
    background: rgba(255,255,255,0.08);
    padding: 6px 12px;
    border-radius: 25px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}
.search-box input {
    background: transparent;
    border: none;
    outline: none;
    color: white;
    width: 180px;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 15px;
    transition: 0.3s;
    border: 1px solid rgba(255,255,255,0.1);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* TABLE */
.table-section {
    margin-top: 30px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 15px;
    overflow-x: auto;
}

table {
    width: 100%;
    margin-top: 10px;
    border-collapse: collapse;
}

table th {
    text-align: left;
    padding: 12px;
    color: #cbd5e1;
}

table td {
    padding: 12px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

/* STATUS */
.status-active {
    color: #22c55e;
    font-weight: bold;
}

.inactive {
    color: #ef4444;
    font-weight: bold;
}

/* RESPONSIVE */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    z-index: 998;
    backdrop-filter: blur(2px);
}
.sidebar-overlay.active {
    display: block;
}

@media(max-width: 991px) {
    .cards {
        grid-template-columns: repeat(2, 1fr);
    }

    .sidebar {
        position: fixed;
        left: -260px;
        transition: 0.3s;
        z-index: 999;
    }

    .sidebar.open {
        left: 0;
    }

    .main {
        margin-left: 0;
        width: 100%;
    }
}

@media(max-width: 575px) {
    .cards {
        grid-template-columns: 1fr;
    }
}

</style>
@yield('styles')
</head>


<body>

<div class="dashboard">

  <!-- Mobile Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
      @include('admin.navbar')


  <!-- Main -->
  <main class="main">

    <!-- Header -->
     @include('admin.header')


    <!-- Cards -->

      @yield('content')
    <!-- <section class="cards">
      <div class="card">
        <h3>Total Users</h3>
        <p>1,250</p>
      </div>
      <div class="card">
        <h3>Total Leads</h3>
        <p>320</p>
      </div>
      <div class="card">
        <h3>Revenue</h3>
        <p>₹75,000</p>
      </div>
      <div class="card">
        <h3>Orders</h3>
        <p>210</p>
      </div>
    </section> -->

    <!-- Table -->
    <!-- <section class="table-section">
      <h2>Recent Data</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Kajal</td>
            <td>kajal@gmail.com</td>
            <td class="active">Active</td>
          </tr>
          <tr>
            <td>Rahul</td>
            <td>rahul@gmail.com</td>
            <td class="inactive">Inactive</td>
          </tr>
        </tbody>
      </table>
    </section> -->

  </main>

</div>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
        });
    }
</script>
 @yield('scripts')
</body>
</html>