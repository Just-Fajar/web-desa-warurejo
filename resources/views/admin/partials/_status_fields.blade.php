{{-- Status & Scheduling Partial --}}
{{-- Variables: $statusList, $currentStatus (optional), $publishedAt (optional), $dateFieldName (default: published_at) --}}

@php
    $statusList = $statusList ?? [
        'draft' => 'Draft',
        'scheduled' => 'Dijadwalkan',
        'published' => 'Published',
    ];
    $currentStatus = $currentStatus ?? old('status', 'published');
    $publishedAt = $publishedAt ?? old($dateFieldName ?? 'published_at', '');
    $dateFieldName = $dateFieldName ?? 'published_at';
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Status --}}
    <div>
        <label for="status" class="block text-sm font-bold text-gray-900 mb-2">
            Status <span class="text-red-500">*</span>
        </label>
        <select name="status" id="status"
            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-200 text-sm font-medium @error('status') border-red-300 @enderror"
            required onchange="handleStatusChange(this.value)">
            @foreach($statusList as $value => $label)
                <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500" id="statusHint">
            @if($currentStatus === 'draft')
                Konten disimpan sebagai draft dan tidak tampil di website.
            @elseif($currentStatus === 'scheduled')
                Konten akan dipublish otomatis sesuai jadwal.
            @else
                Konten langsung tampil di website.
            @endif
        </p>
    </div>

    {{-- Tanggal Publikasi --}}
    <div id="publishedAtWrapper" style="{{ in_array($currentStatus, ['scheduled', 'published']) ? '' : 'display: none;' }}">
        <label for="{{ $dateFieldName }}" class="block text-sm font-bold text-gray-900 mb-2">
            Tanggal Publikasi <span class="text-red-500" id="dateRequiredStar" style="{{ $currentStatus === 'scheduled' ? '' : 'display: none;' }}">*</span>
        </label>
        <input type="text" name="{{ $dateFieldName }}" id="{{ $dateFieldName }}" 
            value="{{ $publishedAt }}"
            placeholder="Kosongkan untuk menggunakan waktu saat ini"
            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all duration-200 text-sm font-medium @error($dateFieldName) border-red-300 @enderror">
        @error($dateFieldName)
            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500" id="dateHint">
            @if($currentStatus === 'scheduled')
                Pilih tanggal dan waktu untuk penjadwalan.
            @else
                Pilih tanggal jika ingin mengubah waktu rilis, atau kosongkan untuk waktu saat ini.
            @endif
        </p>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    // Initialize Flatpickr for scheduled/published date
    const datePicker = flatpickr("#{{ $dateFieldName }}", {
        enableTime: true,
        altInput: true,
        altFormat: "j F Y, H:i",
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        locale: "id",
        placeholder: "Kosongkan untuk menggunakan waktu saat ini"
    });

    function handleStatusChange(status) {
        const wrapper = document.getElementById('publishedAtWrapper');
        const hint = document.getElementById('statusHint');
        const dateHint = document.getElementById('dateHint');
        const dateRequiredStar = document.getElementById('dateRequiredStar');
        const dateInput = document.getElementById('{{ $dateFieldName }}');

        if (status === 'scheduled') {
            wrapper.style.display = '';
            hint.textContent = 'Konten akan dipublish otomatis sesuai jadwal.';
            hint.className = 'mt-1 text-xs text-blue-600';
            if (dateHint) dateHint.textContent = 'Pilih tanggal dan waktu untuk penjadwalan (harus di masa depan).';
            if (dateRequiredStar) dateRequiredStar.style.display = '';
            
            // Set minDate to current time (not 'today' which is midnight)
            datePicker.set('minDate', new Date());
            datePicker.set('maxDate', null);

            // Clear date if it's in the past
            if (dateInput.value) {
                const selectedDate = new Date(dateInput.value);
                if (selectedDate <= new Date()) {
                    datePicker.clear();
                }
            }
        } else if (status === 'draft') {
            wrapper.style.display = 'none';
            hint.textContent = 'Konten disimpan sebagai draft dan tidak tampil di website.';
            hint.className = 'mt-1 text-xs text-yellow-600';
            
            // Clear the date for draft
            datePicker.clear();
            datePicker.set('minDate', null);
            datePicker.set('maxDate', null);
        } else {
            // published
            wrapper.style.display = '';
            hint.textContent = 'Konten langsung tampil di website.';
            hint.className = 'mt-1 text-xs text-green-600';
            if (dateHint) dateHint.textContent = 'Kosongkan untuk menggunakan waktu saat ini.';
            if (dateRequiredStar) dateRequiredStar.style.display = 'none';
            
            datePicker.set('minDate', null);
            datePicker.set('maxDate', null);

            // Clear date if it's in the future (was scheduled, now published)
            if (dateInput.value) {
                const selectedDate = new Date(dateInput.value);
                if (selectedDate > new Date()) {
                    datePicker.clear();
                }
            }
        }

        // Dynamic Submit Button Text
        const submitBtnText = document.getElementById('submitBtnText');
        if (submitBtnText) {
            const moduleName = submitBtnText.dataset.module || 'Data';
            if (status === 'scheduled') {
                submitBtnText.textContent = 'Jadwalkan ' + moduleName;
            } else if (status === 'draft') {
                submitBtnText.textContent = 'Draft ' + moduleName;
            } else {
                submitBtnText.textContent = 'Publish ' + moduleName;
            }
        }
    }

    // Initialize state on page load
    document.addEventListener("DOMContentLoaded", function() {
        const currentStatus = document.getElementById('status').value;
        handleStatusChange(currentStatus);
    });
</script>
@endpush
