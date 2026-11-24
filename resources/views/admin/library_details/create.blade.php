@extends('admin.master')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0 rounded-4 bg-dark text-white">

        <div class="card-header d-flex justify-content-between align-items-center text-white rounded-top-4"
             style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
            <h5 class="mb-0">➕ Add Library Detail</h5>
            <a href="{{ route('library-details.index') }}" class="btn btn-light btn-sm">Back</a>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>⚠ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('library-details.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label">Search Library (keywords)</label>
                        <input type="text" id="library_search" class="form-control custom-input" placeholder="Type library name, location, phone, or description...">
                        <input type="hidden" name="library_id" id="library_id">
                        <div id="library_suggestions" class="list-group mt-1" style="z-index:9999; position:relative;"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Owner Name</label>
                        <input type="text" id="library_name" class="form-control custom-input" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Location</label>
                        <input type="text" id="library_location" class="form-control custom-input" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="text" id="library_phone" class="form-control custom-input" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Select Plan</label>
                        <select name="plan_id" id="plan_id" class="form-control custom-input">
                            <option value="">Choose plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }} ({{ $plan->validity }} days)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control custom-input" value="{{ old('start_date') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control custom-input" value="{{ old('end_date') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control custom-input" value="{{ old('capacity') }}" min="0">
                    </div>

                  
                    <div class="col-md-6">
                        <label class="form-label">Logo URL</label>
                        <input type="text" name="logo_url" class="form-control custom-input" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.png">
                    </div>

                    <input type="hidden" name="name" id="library_name_input">
                    <input type="hidden" name="location" id="library_location_input">
                    <input type="hidden" name="phone" id="library_phone_input">

                </div>

                <div class="text-end mt-4">
                    <button class="btn btn-success px-4 shadow">💾 Save</button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const input = document.getElementById('library_search');
    const suggestions = document.getElementById('library_suggestions');
    const hidden = document.getElementById('library_id');
    const nameField = document.getElementById('library_name');
    const locationField = document.getElementById('library_location');
    const phoneField = document.getElementById('library_phone');

    let currentFetch = null;

    input.addEventListener('input', function(){
        const q = this.value.trim();
        hidden.value = '';
        nameField.value = '';
        locationField.value = '';
        phoneField.value = '';
        if(q.length < 2){ suggestions.innerHTML = ''; return; }

        if (currentFetch) currentFetch.abort?.();

        currentFetch = fetch('/get-libraries-search?q=' + encodeURIComponent(q))
            .then(res => res.json())
            .then(list => {
                suggestions.innerHTML = '';
                list.forEach(item => {
                    const el = document.createElement('button');
                    el.type = 'button';
                    el.className = 'list-group-item list-group-item-action';
                    el.textContent = `${item.name} — ${item.location || ''}`;
                    el.dataset.id = item.id;
                    el.dataset.name = item.name;
                    el.dataset.location = item.location;
                    el.dataset.phone = item.phone;
                    el.addEventListener('click', function(){
                        input.value = this.dataset.name;
                        hidden.value = this.dataset.id;
                        nameField.value = this.dataset.name;
                        locationField.value = this.dataset.location;
                        phoneField.value = this.dataset.phone;
                        document.getElementById('library_name_input').value = this.dataset.name;
                        document.getElementById('library_location_input').value = this.dataset.location;
                        document.getElementById('library_phone_input').value = this.dataset.phone;
                        suggestions.innerHTML = '';
                    });
                    suggestions.appendChild(el);
                });
            }).catch(()=>{});
    });

});
</script>
@endsection
