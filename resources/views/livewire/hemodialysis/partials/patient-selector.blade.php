<div class="patient-selector" wire:ignore>
    <label class="form-label fw-semibold">Paciente</label>
    <select id="{{ $id ?? 'patient-selector' }}" class="form-select patient-tomselect" placeholder="Buscar por DNI, nombre o historia clínica"></select>
</div>
<input type="hidden" wire:model.live="patientId" id="{{ ($id ?? 'patient-selector').'-value' }}">

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('livewire:navigated', initHemodialysisPatientSelectors);
            document.addEventListener('DOMContentLoaded', initHemodialysisPatientSelectors);

            function initHemodialysisPatientSelectors() {
                document.querySelectorAll('.patient-tomselect').forEach((element) => {
                    if (element.tomselect || typeof TomSelect === 'undefined') return;

                    new TomSelect(element, {
                        valueField: 'id',
                        labelField: 'text',
                        searchField: ['text'],
                        maxItems: 1,
                        loadThrottle: 350,
                        load: function(query, callback) {
                            if (!query.length || query.length < 2) return callback();
                            fetch(`{{ route('patients.search') }}?q=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(json => callback(json.results ?? []))
                                .catch(() => callback());
                        },
                        render: {
                            option: function(item, escape) {
                                return `<div><strong>${escape(item.text)}</strong><div class="small text-muted">DNI: ${escape(item.dni ?? '')} · HC: ${escape(item.numero_historia ?? '')}</div></div>`;
                            },
                        },
                        onChange: function(value) {
                            const hidden = document.getElementById(`${element.id}-value`);
                            if (!hidden) return;
                            hidden.value = value;
                            hidden.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                    });
                });
            }
        </script>
    @endpush
@endonce
