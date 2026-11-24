@extends('admin.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Breadcrumb & Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('commando-schedules.index') }}" class="text-warning text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Weekly Schedules
                </a>
            </div>
            <h4 class="fw-bold mb-0 text-white mt-2">Add New Schedule Day</h4>
            <p class="text-muted small mb-0 mt-1">Configure training routines across Dawn, Cadet, and Combat slots for The Movement timetable matrix.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card bg-dark border-secondary shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('commando-schedules.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    <!-- Section 1: Day & Display Parameters -->
                    <div class="col-12">
                        <h6 class="text-warning text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-calendar-day me-1"></i> 1. Day &amp; Display Order
                        </h6>
                    </div>

                    <!-- Day -->
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">Day Name <span class="text-danger">*</span></label>
                        <input type="text" name="day" list="dayList" value="{{ old('day') }}" required class="form-control bg-dark border-secondary text-white" placeholder="e.g., Monday, Saturday, Weekend Clinic">
                        <datalist id="dayList">
                            <option value="Monday">
                            <option value="Tuesday">
                            <option value="Wednesday">
                            <option value="Thursday">
                            <option value="Friday">
                            <option value="Saturday">
                            <option value="Sunday">
                        </datalist>
                        <div class="text-muted small mt-1">Select from list or type a custom day / intensive session label.</div>
                    </div>

                    <!-- Order No -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Display Order No</label>
                        <input type="number" name="order_no" value="{{ old('order_no', $nextOrder) }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 1, 2, 3...">
                        <div class="text-muted small mt-1">Determines display sequence on timetable table.</div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-3 d-flex align-items-center pt-3">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label text-white fw-bold" for="statusSwitch">
                                Active on Frontend
                            </label>
                            <div class="text-muted small">Visible on The Movement schedule table</div>
                        </div>
                    </div>

                    <!-- Section 2: Dawn Routine (06:30 - 08:30) -->
                    <div class="col-12 mt-4">
                        <h6 class="text-primary text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-sun me-1"></i> 2. Dawn Routine Slot (06:30 - 08:30)
                        </h6>
                    </div>

                    <!-- Dawn Title -->
                    <div class="col-md-5">
                        <label class="form-label text-white small fw-bold">Dawn Session Title</label>
                        <input type="text" name="dawn_title" value="{{ old('dawn_title') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Ashtanga Vinyasa, Spine & Mobility Yin">
                    </div>

                    <!-- Dawn Subtitle / Sensei -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Instructor &amp; Studio Suite</label>
                        <input type="text" name="dawn_subtitle" value="{{ old('dawn_subtitle') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Acharya Meera • Suite A">
                    </div>

                    <!-- Dawn Time -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Session Timing</label>
                        <input type="text" name="dawn_time" value="{{ old('dawn_time', '06:30 AM') }}" class="form-control bg-dark border-secondary text-white" placeholder="06:30 AM">
                    </div>

                    <!-- Section 3: Afternoon Cadets (16:30 - 18:00) -->
                    <div class="col-12 mt-4">
                        <h6 class="text-info text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-user-ninja me-1"></i> 3. Afternoon Cadets Slot (16:30 - 18:00)
                        </h6>
                    </div>

                    <!-- Cadet Title -->
                    <div class="col-md-5">
                        <label class="form-label text-white small fw-bold">Cadet Session Title</label>
                        <input type="text" name="cadet_title" value="{{ old('cadet_title') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Cadet Karate (U-14), Junior Judo Fundamentals">
                    </div>

                    <!-- Cadet Subtitle / Sensei -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Instructor &amp; Dojo Mat</label>
                        <input type="text" name="cadet_subtitle" value="{{ old('cadet_subtitle') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Sensei Devraj • Main Dojo">
                    </div>

                    <!-- Cadet Time -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Session Timing</label>
                        <input type="text" name="cadet_time" value="{{ old('cadet_time', '04:30 PM') }}" class="form-control bg-dark border-secondary text-white" placeholder="04:30 PM">
                    </div>

                    <!-- Section 4: Dusk Combat Prime (19:00 - 21:00) -->
                    <div class="col-12 mt-4">
                        <h6 class="text-danger text-uppercase fw-bold border-bottom border-secondary pb-2 mb-3">
                            <i class="fas fa-fist-raised me-1"></i> 4. Dusk Combat Prime Slot (19:00 - 21:00)
                        </h6>
                    </div>

                    <!-- Combat Title -->
                    <div class="col-md-5">
                        <label class="form-label text-white small fw-bold">Combat Session Title</label>
                        <input type="text" name="combat_title" value="{{ old('combat_title') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Adult Judo Randori, Shotokan Kumite Drill">
                    </div>

                    <!-- Combat Subtitle / Sensei -->
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">Instructor &amp; Mat Area</label>
                        <input type="text" name="combat_subtitle" value="{{ old('combat_subtitle') }}" class="form-control bg-dark border-secondary text-white" placeholder="e.g., Sensei K. Rathore • Mat 1">
                    </div>

                    <!-- Combat Time -->
                    <div class="col-md-3">
                        <label class="form-label text-white small fw-bold">Session Timing</label>
                        <input type="text" name="combat_time" value="{{ old('combat_time', '07:00 PM') }}" class="form-control bg-dark border-secondary text-white" placeholder="07:00 PM">
                    </div>

                    <!-- Form Actions -->
                    <div class="col-12 mt-4 pt-3 border-top border-secondary d-flex justify-content-end gap-2">
                        <a href="{{ route('commando-schedules.index') }}" class="btn btn-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">
                            <i class="fas fa-save me-1"></i> Save Schedule Day
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>
@endsection
