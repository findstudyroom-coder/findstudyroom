<!-- UNIVERSAL LIBRARY CALL & LEAD MODAL -->
<div id="libraryCallLeadModal" 
     style="display:none; position:fixed !important; inset:0 !important; top:0 !important; left:0 !important; width:100vw !important; height:100vh !important; z-index:999999 !important; background:rgba(0,0,0,0.65) !important; align-items:center !important; justify-content:center !important; padding:16px !important; box-sizing:border-box !important;" 
     class="fixed inset-0 z-[999999] items-center justify-center bg-black/65 backdrop-blur-sm px-4">
    
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl relative overflow-hidden border border-gray-100 p-6 sm:p-8 animate-fade-in text-center my-auto">
        <!-- Close Button -->
        <button type="button" onclick="closeLibraryCallModal()" 
                class="absolute top-4 right-4 w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-400 hover:text-gray-700 flex items-center justify-center transition cursor-pointer"
                aria-label="Close modal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- STEP 1: LEAD CAPTURE FORM -->
        <div id="callModalLeadStep">
            <!-- Icon -->
            <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-4 shadow-sm border border-teal-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>

            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight mb-2" id="callModalLibTitle">Contact Library Desk</h3>
            <p class="text-gray-500 text-xs sm:text-sm mb-6 leading-relaxed">
                Please enter your details to view the verified phone number and connect directly with the library owner.
            </p>

            <form id="callModalLeadForm" onsubmit="handleCallLeadSubmit(event)" class="space-y-4 text-left">
                @csrf
                <input type="hidden" id="callLeadLibId" name="library_id" value="">

                <div>
                    <label for="callLeadName" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Your Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="callLeadName" name="name" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none text-sm font-medium transition" 
                           placeholder="e.g. Rahul Verma" required>
                </div>

                <div>
                    <label for="callLeadPhone" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500 font-semibold text-sm">+91</span>
                        <input type="tel" id="callLeadPhone" name="phone" maxlength="10" pattern="[6-9][0-9]{9}" 
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-100 outline-none text-sm font-mono tracking-wider transition" 
                               placeholder="10-digit number" required>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">We will connect you directly with the verified library desk.</p>
                </div>

                <div id="callLeadError" class="hidden text-xs text-red-600 bg-red-50 border border-red-200 p-2.5 rounded-xl font-medium"></div>

                <button type="submit" id="callLeadSubmitBtn" 
                        class="w-full bg-teal-600 hover:bg-teal-700 active:scale-[0.99] text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-teal-600/20 transition flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer">
                    <span id="callLeadSubmitText">View Contact Number & Call</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="mt-4 pt-3 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 text-teal-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>100% Direct Contact with Verified Library Desk</span>
            </div>
        </div>

        <!-- STEP 2: PHONE NUMBER REVEALED -->
        <div id="callModalRevealStep" style="display:none;">
            <!-- Success Icon -->
            <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center mx-auto mb-3 shadow-sm border border-green-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>

            <span class="inline-block bg-teal-50 text-teal-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">Verified Contact</span>
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight mb-1" id="revealLibTitle">Library Owner</h3>
            <p class="text-gray-500 text-xs sm:text-sm mb-4">Direct contact number unlocked. You can call now:</p>

            <!-- Phone Display Box -->
            <div class="bg-slate-50 border-2 border-teal-500/20 rounded-2xl p-4 my-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-xl">📞</span>
                    <span class="text-2xl sm:text-3xl font-black text-gray-900 font-mono tracking-wider" id="revealPhoneDisplay"></span>
                </div>
                <button type="button" onclick="copyRevealedPhone()" id="copyPhoneBtn" 
                        class="text-xs bg-white border border-gray-200 text-gray-700 hover:text-teal-700 hover:border-teal-300 font-bold px-3 py-1.5 rounded-lg shadow-sm transition cursor-pointer">
                    Copy
                </button>
            </div>

            <!-- Call Now Action Button -->
            <a href="#" id="revealCallActionBtn" 
               class="w-full bg-green-600 hover:bg-green-700 active:scale-[0.99] text-white font-extrabold py-3.5 px-4 rounded-xl shadow-lg shadow-green-600/25 transition text-center flex items-center justify-center gap-2 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>Call Now</span>
            </a>

            <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                <span id="leadUserInfoText">Inquiry logged</span>
                <button type="button" onclick="resetLeadDetails()" class="text-teal-600 hover:underline font-medium cursor-pointer">Change details</button>
            </div>
        </div>

    </div>
</div>

<script>
(function() {
    let currentTargetPhone = '';
    let currentTargetLibName = '';
    let currentTargetLibId = null;

    function getModalEl() {
        return document.getElementById('libraryCallLeadModal');
    }

    // Check if valid phone
    function isValidPhone(phone) {
        if (!phone) return false;
        const cleaned = String(phone).trim().toLowerCase();
        return cleaned !== '' && cleaned !== 'n/a' && cleaned !== 'na' && cleaned !== 'not available' && cleaned !== 'null' && cleaned !== 'undefined';
    }

    // Open Modal
    window.openLibraryCallModal = function(phone, libraryName, libraryId) {
        if (!isValidPhone(phone)) {
            alert('Phone number is currently not available for this library (N/A).');
            return;
        }

        currentTargetPhone = String(phone).trim();
        currentTargetLibName = libraryName || 'Library Desk';
        currentTargetLibId = libraryId || null;

        // Ensure modal is direct child of body to prevent any parent CSS transform trapping
        const modal = getModalEl();
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }

        // Track library click via existing endpoint if libraryId provided
        if (currentTargetLibId) {
            try {
                fetch("{{ route('library.trackClick') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        library_id: currentTargetLibId,
                        action_type: 'call_click'
                    })
                });
            } catch(e) {}
        }

        // ALWAYS show Lead Capture Form first
        showLeadStep(currentTargetLibName, currentTargetLibId);

        // Pre-fill previously entered user details so user does not need to retype
        try {
            const storedUserJson = localStorage.getItem('findstudy_lead_user');
            if (storedUserJson) {
                const user = JSON.parse(storedUserJson);
                if (user && user.name) {
                    const nameInput = document.getElementById('callLeadName');
                    if (nameInput) nameInput.value = user.name;
                }
                if (user && user.phone) {
                    const phoneInput = document.getElementById('callLeadPhone');
                    if (phoneInput) phoneInput.value = user.phone;
                }
            }
        } catch(e) {}

        showModalContainer();
    };

    // Backward compatibility alias
    window.openCallModal = window.openLibraryCallModal;

    function showModalContainer() {
        const modal = getModalEl();
        if (modal) {
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('visibility', 'visible', 'important');
            modal.style.setProperty('opacity', '1', 'important');
            document.body.style.overflow = 'hidden';
        }
    }

    window.closeLibraryCallModal = function() {
        const modal = getModalEl();
        if (modal) {
            modal.style.setProperty('display', 'none', 'important');
            document.body.style.overflow = 'auto';
        }
    };
    window.closeCallModal = window.closeLibraryCallModal;

    function showLeadStep(libName, libId) {
        const leadStep = document.getElementById('callModalLeadStep');
        const revealStep = document.getElementById('callModalRevealStep');
        if (leadStep) leadStep.style.display = 'block';
        if (revealStep) revealStep.style.display = 'none';

        const titleEl = document.getElementById('callModalLibTitle');
        if (titleEl) titleEl.innerText = 'Contact ' + (libName || 'Library Desk');

        const libIdInput = document.getElementById('callLeadLibId');
        if (libIdInput) libIdInput.value = libId || '';

        const errorDiv = document.getElementById('callLeadError');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            errorDiv.innerText = '';
        }

        const submitText = document.getElementById('callLeadSubmitText');
        if (submitText) submitText.innerText = 'View Contact Number & Call';

        const submitBtn = document.getElementById('callLeadSubmitBtn');
        if (submitBtn) submitBtn.disabled = false;
    }

    function showRevealStep(phone, libName, userName) {
        const leadStep = document.getElementById('callModalLeadStep');
        const revealStep = document.getElementById('callModalRevealStep');
        if (leadStep) leadStep.style.display = 'none';
        if (revealStep) revealStep.style.display = 'block';

        const titleEl = document.getElementById('revealLibTitle');
        if (titleEl) titleEl.innerText = libName || 'Library Owner';

        const phoneDisplay = document.getElementById('revealPhoneDisplay');
        if (phoneDisplay) phoneDisplay.innerText = phone;
        
        const actionBtn = document.getElementById('revealCallActionBtn');
        if (actionBtn) {
            const telHref = 'tel:' + phone.replace(/[^0-9+]/g, '');
            actionBtn.href = telHref;
        }
        
        const infoText = document.getElementById('leadUserInfoText');
        if (infoText) {
            if (userName) {
                infoText.innerText = 'Logged for ' + userName;
            } else {
                infoText.innerText = 'Verified Direct Line';
            }
        }
    }

    // Handle form submit
    window.handleCallLeadSubmit = function(e) {
        e.preventDefault();
        const nameInput = document.getElementById('callLeadName');
        const phoneInput = document.getElementById('callLeadPhone');
        const libIdInput = document.getElementById('callLeadLibId');
        const errorDiv = document.getElementById('callLeadError');
        const submitBtn = document.getElementById('callLeadSubmitBtn');
        const submitText = document.getElementById('callLeadSubmitText');

        const name = (nameInput.value || '').trim();
        const phone = (phoneInput.value || '').trim();
        const libId = libIdInput.value || currentTargetLibId;

        if (name.length < 2) {
            errorDiv.innerText = 'Please enter a valid name.';
            errorDiv.classList.remove('hidden');
            return;
        }

        if (!/^[6-9][0-9]{9}$/.test(phone)) {
            errorDiv.innerText = 'Please enter a valid 10-digit mobile number.';
            errorDiv.classList.remove('hidden');
            return;
        }

        errorDiv.classList.add('hidden');
        submitBtn.disabled = true;
        submitText.innerText = 'Connecting...';

        fetch("{{ route('booking.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                library_id: libId,
                name: name,
                phone: phone,
                date: new Date().toISOString().slice(0, 10)
            })
        })
        .then(res => res.json().catch(() => ({})))
        .then(data => {
            // Save user details in localStorage
            try {
                localStorage.setItem('findstudy_lead_user', JSON.stringify({ name: name, phone: phone }));
            } catch(e) {}
            showRevealStep(currentTargetPhone, currentTargetLibName, name);
        })
        .catch(err => {
            // Even on error, reveal phone for smooth user experience
            try {
                localStorage.setItem('findstudy_lead_user', JSON.stringify({ name: name, phone: phone }));
            } catch(e) {}
            showRevealStep(currentTargetPhone, currentTargetLibName, name);
        });
    };

    // Copy Phone
    window.copyRevealedPhone = function() {
        if (!currentTargetPhone) return;
        navigator.clipboard.writeText(currentTargetPhone).then(() => {
            const btn = document.getElementById('copyPhoneBtn');
            if (btn) {
                btn.innerText = 'Copied!';
                btn.classList.add('bg-teal-50', 'text-teal-700', 'border-teal-300');
                setTimeout(() => {
                    btn.innerText = 'Copy';
                    btn.classList.remove('bg-teal-50', 'text-teal-700', 'border-teal-300');
                }, 2000);
            }
        });
    };

    // Reset Lead Details back to form
    window.resetLeadDetails = function() {
        showLeadStep(currentTargetLibName, currentTargetLibId);
    };

    // Setup events on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        const modal = getModalEl();
        if (modal) {
            // Move to body to prevent stacking context or transform trapping
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.addEventListener('click', function(e) {
                if (e.target === this) closeLibraryCallModal();
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLibraryCallModal();
        });
    });
})();
</script>
