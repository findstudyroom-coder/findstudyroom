@extends('coaching_admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="pb-4">

    <!-- 1. HEADER WELCOME BANNER -->
    <div class="card-custom p-4 mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #131d32 0%, #0d1e38 100%);">
        <div class="row align-items-center position-relative z-2">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="{{ $coaching->logo_url }}" class="rounded-3 border border-teal-500/30" style="width: 48px; height: 48px; object-fit: cover; background: #fff;" alt="Logo">
                    <div>
                        <h3 class="fw-bold text-white mb-0 font-display">{{ $coaching->coaching_name }}</h3>
                        <p class="text-teal-400 small mb-0 font-monospace">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $coaching->city ?? 'Location not set' }} &bull; {{ $coaching->coaching_type ?? 'Sports & Fitness' }}
                        </p>
                    </div>
                </div>
                <p class="text-secondary small mb-0 mt-2" style="max-width: 650px;">
                    Welcome to your private administration suite. Monitor incoming prospective student inquiries, track conversion funnels, and manage coaching disciplines.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <button class="btn btn-teal btn-sm px-3" data-bs-toggle="modal" data-bs-target="#quickLeadModal">
                        <i class="fas fa-plus me-1.5"></i> Log New Lead
                    </button>
                    <a href="{{ route('coaching.leads.index') }}" class="btn btn-outline-light btn-sm px-3">
                        <i class="fas fa-list me-1.5"></i> View All Leads
                    </a>
                </div>
                @if($conversionRate > 0)
                <div class="text-secondary small mt-2">
                    Conversion Rate: <strong class="text-success">{{ $conversionRate }}%</strong>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. KPI STAT CARDS (6 CARDS) -->
    <div class="row g-3 mb-4">
        <!-- Total Leads -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-custom p-3 h-100">
                <div class="d-flex align-items-center justify-content-between text-muted mb-2" style="font-size: 11px;">
                    <span class="fw-bold text-uppercase">Total Leads</span>
                    <i class="fas fa-users text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mb-1">{{ number_format($totalLeads) }}</div>
                <div class="small text-muted" style="font-size: 11px;">All inquiries</div>
            </div>
        </div>

        <!-- New Leads -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-custom p-3 h-100 border-info border-opacity-25" style="background: rgba(2, 132, 199, 0.08);">
                <div class="d-flex align-items-center justify-content-between text-info mb-2" style="font-size: 11px;">
                    <span class="fw-bold text-uppercase">New / Fresh</span>
                    <i class="fas fa-bell"></i>
                </div>
                <div class="h3 fw-bold text-info mb-1">{{ number_format($newLeads) }}</div>
                <div class="small text-info opacity-75" style="font-size: 11px;">Needs follow-up</div>
            </div>
        </div>

        <!-- Contacted -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-custom p-3 h-100">
                <div class="d-flex align-items-center justify-content-between text-muted mb-2" style="font-size: 11px;">
                    <span class="fw-bold text-uppercase">Contacted</span>
                    <i class="fas fa-phone-alt text-primary"></i>
                </div>
                <div class="h3 fw-bold text-white mb-1">{{ number_format($contactedLeads) }}</div>
                <div class="small text-muted" style="font-size: 11px;">In discussion</div>
            </div>
        </div>

        <!-- Interested -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-custom p-3 h-100 border-warning border-opacity-25" style="background: rgba(245, 158, 11, 0.08);">
                <div class="d-flex align-items-center justify-content-between text-warning mb-2" style="font-size: 11px;">
                    <span class="fw-bold text-uppercase">Interested</span>
                    <i class="fas fa-star"></i>
                </div>
                <div class="h3 fw-bold text-warning mb-1">{{ number_format($interestedLeads) }}</div>
                <div class="small text-warning opacity-75" style="font-size: 11px;">High potential</div>
            </div>
        </div>

        <!-- Converted -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-custom p-3 h-100 border-success border-opacity-25" style="background: rgba(16, 185, 129, 0.08);">
                <div class="d-flex align-items-center justify-content-between text-success mb-2" style="font-size: 11px;">
                    <span class="fw-bold text-uppercase">Converted</span>
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="h3 fw-bold text-success mb-1">{{ number_format($convertedLeads) }}</div>
                <div class="small text-success opacity-75" style="font-size: 11px;">Enrolled students</div>
            </div>
        </div>

        <!-- Closed -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card-custom p-3 h-100">
                <div class="d-flex align-items-center justify-content-between text-muted mb-2" style="font-size: 11px;">
                    <span class="fw-bold text-uppercase">Closed / Lost</span>
                    <i class="fas fa-archive"></i>
                </div>
                <div class="h3 fw-bold text-secondary mb-1">{{ number_format($closedLeads) }}</div>
                <div class="small text-muted" style="font-size: 11px;">Not interested</div>
            </div>
        </div>
    </div>

    <!-- 3. ANALYTICS GRAPHS SECTION -->
    <div class="row g-4 mb-4">
        <!-- Lead Trend Area Graph -->
        <div class="col-lg-8">
            <div class="card-custom p-4 h-100">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold text-white mb-1"><i class="fas fa-chart-line text-teal-400 me-2"></i> Lead Inquiries Trend</h5>
                        <p class="text-secondary small mb-0">Daily prospective student acquisition activity</p>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="btnDailyView" onclick="switchTrendView('daily')">14 Days</button>
                        <button type="button" class="btn btn-outline-secondary" id="btnMonthlyView" onclick="switchTrendView('monthly')">6 Months</button>
                    </div>
                </div>
                
                <div style="height: 290px; position: relative;">
                    <canvas id="leadsTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Breakdown Doughnut -->
        <div class="col-lg-4">
            <div class="card-custom p-4 h-100 d-flex flex-col justify-content-between">
                <div>
                    <h5 class="fw-bold text-white mb-1"><i class="fas fa-chart-pie text-warning me-2"></i> Pipeline Health</h5>
                    <p class="text-secondary small mb-3">Distribution of leads across funnel</p>
                    
                    <div style="height: 210px; position: relative;" class="my-2">
                        <canvas id="leadsStatusChart"></canvas>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top border-white/10">
                    <div class="d-flex justify-content-between small text-secondary">
                        <span>Active In Pipeline:</span>
                        <strong class="text-white">{{ $activePipeline }} leads</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mt-1">
                        <span>Success Conversion:</span>
                        <strong class="text-success">{{ $conversionRate }}%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. RECENT LEADS & TOP COURSES -->
    <div class="row g-4 mb-4">
        <!-- Recent Leads Table -->
        <div class="col-lg-8">
            <div class="card-custom h-100 overflow-hidden">
                <div class="card-custom-header">
                    <div>
                        <h5 class="fw-bold text-white mb-0"><i class="fas fa-user-clock text-teal-400 me-2"></i> Recent Inquiries</h5>
                        <small class="text-secondary">Latest leads received for {{ $coaching->coaching_name }}</small>
                    </div>
                    <a href="{{ route('coaching.leads.index') }}" class="btn btn-outline-light btn-sm" style="font-size: 12px;">
                        Manage All ({{ $totalLeads }}) &rarr;
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeads as $lead)
                            <tr>
                                <td>
                                    <div class="fw-bold text-white">{{ $lead->student_name }}</div>
                                    <div class="text-secondary" style="font-size: 11px;">
                                        <i class="fas fa-phone me-1"></i> {{ $lead->phone }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-dark border border-secondary text-secondary" style="font-size: 11px;">
                                        {{ $lead->course_interested ?: 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 12px;">{{ optional($lead->enquiry_date)->format('d M, Y') ?: optional($lead->created_at)->format('d M, Y') }}</div>
                                    <div class="text-muted" style="font-size: 10px;">{{ optional($lead->created_at)->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <!-- Inline Status Select -->
                                    <select class="form-select form-select-sm form-select-dark py-1 px-2" style="font-size: 11px; width: 120px;" onchange="updateLeadStatus({{ $lead->id }}, this.value)">
                                        <option value="New" {{ $lead->status == 'New' ? 'selected' : '' }}>🔵 New</option>
                                        <option value="Contacted" {{ $lead->status == 'Contacted' ? 'selected' : '' }}>📞 Contacted</option>
                                        <option value="Interested" {{ $lead->status == 'Interested' ? 'selected' : '' }}>⭐ Interested</option>
                                        <option value="Converted" {{ $lead->status == 'Converted' ? 'selected' : '' }}>✅ Converted</option>
                                        <option value="Closed" {{ $lead->status == 'Closed' ? 'selected' : '' }}>❌ Closed</option>
                                    </select>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1.5">
                                        <!-- WhatsApp -->
                                        <a href="https://wa.me/{{ $lead->whatsapp_phone }}?text={{ urlencode('Hello ' . $lead->student_name . ', thank you for inquiring about ' . $coaching->coaching_name . '! How can we assist you with our classes?') }}" target="_blank" class="btn btn-sm btn-outline-success p-1 px-2" title="WhatsApp Chat">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <!-- Call -->
                                        <a href="tel:{{ $lead->phone }}" class="btn btn-sm btn-outline-info p-1 px-2" title="Call">
                                            <i class="fas fa-phone-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fs-2 mb-2 d-block opacity-40"></i>
                                    No student leads recorded yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Courses & Quick Profile Summary -->
        <div class="col-lg-4">
            <!-- Top Inquired Courses -->
            <div class="card-custom p-4 mb-4">
                <h6 class="fw-bold text-white mb-3"><i class="fas fa-fire text-danger me-2"></i> Most Inquired Disciplines</h6>
                @if($topCourses->count() > 0)
                    <div class="d-flex flex-column gap-2.5">
                        @foreach($topCourses as $tc)
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-white fw-semibold">{{ $tc->course_interested }}</span>
                                <span class="text-teal-400 fw-bold">{{ $tc->total }} leads</span>
                            </div>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.08);">
                                <div class="progress-bar bg-teal-500" style="width: {{ $totalLeads > 0 ? ($tc->total / $totalLeads) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small">No course preference stats available yet.</div>
                @endif
            </div>

            <!-- Institute Summary Box -->
            <div class="card-custom p-4">
                <h6 class="fw-bold text-white mb-3"><i class="fas fa-cubes text-teal-400 me-2"></i> Institute Content Status</h6>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2" style="font-size: 13px;">
                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-white/5">
                        <span class="text-secondary"><i class="fas fa-fist-raised me-2 text-danger"></i> Classes / Disciplines:</span>
                        <strong class="text-white">{{ $coaching->classes_count }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-white/5">
                        <span class="text-secondary"><i class="fas fa-user-ninja me-2 text-warning"></i> Master Senseis:</span>
                        <strong class="text-white">{{ $coaching->instructors_count }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-white/5">
                        <span class="text-secondary"><i class="fas fa-calendar-alt me-2 text-info"></i> Weekly Batches:</span>
                        <strong class="text-white">{{ $coaching->schedules_count }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-white/5">
                        <span class="text-secondary"><i class="fas fa-tags me-2 text-purple-400"></i> Membership Plans:</span>
                        <strong class="text-white">{{ $coaching->plans_count }}</strong>
                    </li>
                    <li class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-secondary"><i class="fas fa-images me-2 text-pink-400"></i> Gallery Photos:</span>
                        <strong class="text-white">{{ $coaching->galleries_count }}</strong>
                    </li>
                </ul>

                <div class="mt-3 pt-2">
                    <a href="{{ route('coaching.profile') }}" class="btn btn-outline-teal btn-sm w-100">
                        <i class="fas fa-edit me-1"></i> Update Complete Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- QUICK LOG LEAD MODAL -->
<div class="modal fade" id="quickLeadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-card border border-secondary text-white" style="background: #131d32;">
            <div class="modal-header border-bottom border-white/10">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-plus text-teal-400 me-2"></i> Log New Student Lead</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('coaching.leads.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Student Full Name *</label>
                        <input type="text" name="student_name" class="form-control form-control-dark" placeholder="e.g. Rahul Sharma" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Mobile Number *</label>
                        <input type="text" name="phone" class="form-control form-control-dark" placeholder="10-digit mobile number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Email Address (Optional)</label>
                        <input type="email" name="email" class="form-control form-control-dark" placeholder="student@example.com">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Course / Discipline</label>
                            <input type="text" name="course_interested" class="form-control form-control-dark" placeholder="e.g. Karate, Judo, Yoga">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Preferred Slot</label>
                            <input type="text" name="slot" class="form-control form-control-dark" placeholder="e.g. Dawn (06:30 AM)">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Enquiry Date</label>
                            <input type="date" name="enquiry_date" class="form-control form-control-dark" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-secondary">Initial Status</label>
                            <select name="status" class="form-select form-select-dark">
                                <option value="New" selected>New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Interested">Interested</option>
                                <option value="Converted">Converted</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small text-secondary">Notes &amp; Inquiry Details</label>
                        <textarea name="notes" class="form-control form-control-dark" rows="3" placeholder="Student requirements, goals, preferred batch..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-white/10">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-teal btn-sm px-3">Save Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Leads Trend Chart
    const dailyLabels = @json($dailyLabels);
    const dailyValues = @json($dailyValues);
    const monthlyLabels = @json($monthlyLabels);
    const monthlyValues = @json($monthlyValues);

    const ctxTrend = document.getElementById('leadsTrendChart').getContext('2d');
    
    // Gradient fill
    const gradient = ctxTrend.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(13, 148, 136, 0.45)');
    gradient.addColorStop(1, 'rgba(13, 148, 136, 0.0)');

    let trendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Inquiries',
                data: dailyValues,
                borderColor: '#14b8a6',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#14b8a6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#fff',
                    bodyColor: '#14b8a6',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    function switchTrendView(type) {
        if (type === 'daily') {
            trendChart.data.labels = dailyLabels;
            trendChart.data.datasets[0].data = dailyValues;
            document.getElementById('btnDailyView').classList.add('active');
            document.getElementById('btnMonthlyView').classList.remove('active');
        } else {
            trendChart.data.labels = monthlyLabels;
            trendChart.data.datasets[0].data = monthlyValues;
            document.getElementById('btnMonthlyView').classList.add('active');
            document.getElementById('btnDailyView').classList.remove('active');
        }
        trendChart.update();
    }

    // 2. Leads Status Doughnut Chart
    const statusData = @json(array_values($statusDistribution));
    const ctxStatus = document.getElementById('leadsStatusChart').getContext('2d');
    
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['New', 'Contacted', 'Interested', 'Converted', 'Closed'],
            datasets: [{
                data: statusData,
                backgroundColor: [
                    '#0284c7', // New
                    '#3b82f6', // Contacted
                    '#f59e0b', // Interested
                    '#10b981', // Converted
                    '#64748b'  // Closed
                ],
                borderWidth: 2,
                borderColor: '#131d32'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#cbd5e1',
                        font: { size: 10 },
                        boxWidth: 10,
                        padding: 8
                    }
                }
            },
            cutout: '70%'
        }
    });

    // 3. Inline Lead Status Updater
    function updateLeadStatus(leadId, newStatus) {
        fetch(`/coaching/leads/${leadId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Flash toast or alert
                console.log(data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endpush
