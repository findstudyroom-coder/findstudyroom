@extends('admin.master')

@section('content')
<style>
    .custom-pagination .pagination {
        gap: 6px;
    }

    .custom-pagination .page-link {
        background-color: #1f2937;
        color: #e5e7eb;
        border: 1px solid #374151;
        border-radius: 8px;
        padding: 6px 12px;
        transition: 0.3s;
    }

    .custom-pagination .page-link:hover {
        background-color: #059669;
        color: #fff;
    }

    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg,#0d9488,#059669);
        border: none;
        color: #fff;
    }

    .custom-pagination .page-item.disabled .page-link {
        background-color: #111827;
        color: #6b7280;
    }

    .dark-index-card {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 1rem;
    }

    .dark-index-card .card-body {
        background: #111827;
    }

    .dark-index-table {
        table-layout: fixed;
        width: 100%;
        color: #e2e8f0;
    }

    .dark-index-table th,
    .dark-index-table td {
        border-color: #334155;
    }

    .dark-index-table tbody tr {
        background: #111827;
    }

    .dark-index-table tbody tr:hover {
        background: #17233b;
    }

    .dark-index-table th {
        background: #0f172a;
        color: #e2e8f0;
    }

    .alert.alert-success {
        background: #064e3b;
        color: #d1fae5;
        border-color: #064e3b;
    }
</style>
<div class="container-fluid mt-4">

    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-white">📅 Booking Requests</h4>
    </div>

<div class="card shadow-lg border-0 rounded-4 bg-dark text-white">

        <!-- Header -->
        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background: linear-gradient(135deg,#0d9488,#059669);">
            <h5 class="mb-0">All Bookings</h5>
        </div>

        <!-- Body -->
        <div class="card-body bg-dark rounded-bottom">

            <div class="table-responsive">

                <table class="table table-dark table-hover align-middle text-white">

                    <thead style="background: rgba(255,255,255,0.05);">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Library</th>
                            <th>User</th>
                            <th>Phone</th>
                            <th>Visit Date</th>
                            <!--<th>Submitted</th>-->
                        </tr>
                    </thead>

                    <tbody class="text-center">

                        @forelse($bookings as $key => $booking)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">

                            <td class="fw-bold text-warning">
                             {{ $bookings->firstItem() + $key }}
                            </td>

                            <!-- Library -->
                            <td>
                                <span class="badge bg-info px-3 py-2">
                                    {{ $booking->library->name ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- User -->
                            <td class="fw-semibold">
                                {{ $booking->name }}
                            </td>

                            <!-- Phone -->
                            <td>
                                <a href="tel:{{ $booking->phone }}" 
                                   class="text-decoration-none text-success fw-bold">
                                    📞 {{ $booking->phone }}
                                </a>
                            </td>

                            <!-- Date -->
                            <td>
                                <span class="badge bg-primary px-3 py-2">
                                    {{ date('d M Y', strtotime($booking->date)) }}
                                </span>
                            </td>

                            <!-- Submitted -->
                            <!--<td>-->
                            <!--    <small class="text-light">-->
                            <!--        {{ $booking->created_at->format('d M Y h:i A') }}-->
                            <!--    </small>-->
                            <!--</td>-->

                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                🚫 No booking requests found
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
    <div class="custom-pagination">
        {{ $bookings->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
</div>
        </div>

    </div>

</div>

@endsection