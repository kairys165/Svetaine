@extends('admin.layout')
@section('title', ($plan->exists ? 'Redaguoti' : 'Naujas') . ' sporto planas')

@section('admin')
    @php $isNew = !$plan->exists; @endphp
    @php
        $exerciseRows = old('exercises');
        if ($exerciseRows === null) {
            $exerciseRows = $plan->exists
                ? $plan->exercises->map(fn ($ex) => [
                    'exercise_id' => $ex->id,
                    'day' => $ex->pivot->day,
                    'sets' => $ex->pivot->sets,
                    'reps' => $ex->pivot->reps,
                    'rest_seconds' => $ex->pivot->rest_seconds,
                    'notes' => $ex->pivot->notes,
                    'sort_order' => $ex->pivot->sort_order,
                ])->values()->all()
                : [];
        }
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fw-bold m-0">{{ $isNew ? 'Naujas sporto planas' : 'Redaguoti planą' }}</h1>
        <a href="{{ route('admin.sport-plans.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Atgal</a>
    </div>

    <form method="POST" action="{{ $isNew ? route('admin.sport-plans.store') : route('admin.sport-plans.update', $plan) }}" class="admin-form-shell admin-form-card">
        @csrf @unless($isNew) @method('PUT') @endunless
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label small">Pavadinimas *</label><input type="text" name="name" value="{{ old('name', $plan->name) }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label small">Sporto šaka</label>
                    <select name="sport_id" class="form-select"><option value="">—</option>
                        @foreach($sports as $s)<option value="{{ $s->id }}" @selected(old('sport_id', $plan->sport_id) == $s->id)>{{ $s->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label small">Lygis *</label>
                    <select name="level" class="form-select">@foreach(['beginner','intermediate','advanced'] as $l)<option value="{{ $l }}" @selected(old('level', $plan->level) === $l)>{{ ucfirst($l) }}</option>@endforeach</select>
                </div>
                <div class="col-md-3"><label class="form-label small">Tikslas *</label>
                    <select name="goal" class="form-select">@foreach(['strength','hypertrophy','endurance','weight_loss','general'] as $g)<option value="{{ $g }}" @selected(old('goal', $plan->goal) === $g)>{{ ucfirst(str_replace('_',' ',$g)) }}</option>@endforeach</select>
                </div>
                <div class="col-md-3"><label class="form-label small">Trukmė (sav.) *</label><input type="number" name="duration_weeks" value="{{ old('duration_weeks', $plan->duration_weeks) }}" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label small">Dienų/sav. *</label><input type="number" id="days-per-week" name="days_per_week" value="{{ old('days_per_week', $plan->days_per_week) }}" min="1" max="7" class="form-control" required></div>
                <div class="col-12"><label class="form-label small">Aprašymas</label><textarea name="description" rows="5" class="form-control" placeholder="Trumpai: kas tai per planas, kam tinka ir kokį rezultatą padeda pasiekti.">{{ old('description', $plan->description) }}</textarea></div>
                <div class="col-md-10"><label class="form-label small">Paveikslėlio URL</label><input type="text" name="image" value="{{ old('image', $plan->image) }}" class="form-control"></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $plan->is_active ?? true))><label class="form-check-label" for="is_active">Aktyvus</label></div></div>

                <div class="col-12 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label small m-0">Pratimai plane</label>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-exercise-row">
                            <i class="bi bi-plus-lg"></i> Pridėti pratimą
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle" id="exercise-table">
                            <thead>
                                <tr>
                                    <th style="min-width:220px">Pratimas</th>
                                    <th style="width:90px">Diena</th>
                                    <th style="width:90px">Serijos</th>
                                    <th style="width:120px">Kartojimai</th>
                                    <th style="width:120px">Poilsis (s)</th>
                                    <th>Pastabos</th>
                                    <th style="width:90px">Eil.</th>
                                    <th style="width:50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exerciseRows as $i => $row)
                                    <tr>
                                        <td>
                                            <select name="exercises[{{ $i }}][exercise_id]" class="form-select form-select-sm">
                                                <option value="">— pasirinkti —</option>
                                                @foreach($exercises as $ex)
                                                    <option value="{{ $ex->id }}" @selected((int) ($row['exercise_id'] ?? 0) === $ex->id)>{{ $ex->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="exercises[{{ $i }}][day]" class="form-select form-select-sm day-select" data-selected="{{ $row['day'] ?? 1 }}"></select>
                                        </td>
                                        <td><input type="number" name="exercises[{{ $i }}][sets]" value="{{ $row['sets'] ?? 3 }}" min="1" max="20" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="exercises[{{ $i }}][reps]" value="{{ $row['reps'] ?? '8-12' }}" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="exercises[{{ $i }}][rest_seconds]" value="{{ $row['rest_seconds'] ?? 90 }}" min="0" max="3600" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="exercises[{{ $i }}][notes]" value="{{ $row['notes'] ?? '' }}" class="form-control form-control-sm"></td>
                                        <td><input type="number" name="exercises[{{ $i }}][sort_order]" value="{{ $row['sort_order'] ?? $i }}" min="0" max="1000" class="form-control form-control-sm"></td>
                                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-exercise-row"><i class="bi bi-x-lg"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-text">Jei nepasirinksi pratimo, eilutė nebus išsaugota.</div>
                </div>
            </div>
        </div>
        <div class="card-footer"><button class="btn btn-primary"><i class="bi bi-check2"></i> {{ $isNew ? 'Sukurti' : 'Išsaugoti' }}</button></div>
    </form>

    @push('scripts')
    <script>
        (function () {
            const addBtn = document.getElementById('add-exercise-row');
            const table = document.getElementById('exercise-table');
            const daysInput = document.getElementById('days-per-week');
            if (!addBtn || !table) return;

            const body = table.querySelector('tbody');
            const options = @json($exercises->map(fn ($e) => ['id' => $e->id, 'name' => $e->name])->values());

            function getDaysPerWeek() {
                const n = parseInt(daysInput?.value || '1', 10);
                return Math.max(1, Math.min(7, Number.isNaN(n) ? 1 : n));
            }

            function dayOptionsHtml(selectedDay = 1) {
                const days = getDaysPerWeek();
                let html = '';
                for (let d = 1; d <= days; d++) {
                    const selected = Number(selectedDay) === d ? ' selected' : '';
                    html += `<option value="${d}"${selected}>${d}</option>`;
                }
                return html;
            }

            function refreshDaySelects() {
                const maxDay = getDaysPerWeek();
                body.querySelectorAll('.day-select').forEach((select) => {
                    const prev = parseInt(select.value || select.dataset.selected || '1', 10);
                    const safe = Math.max(1, Math.min(maxDay, Number.isNaN(prev) ? 1 : prev));
                    select.innerHTML = dayOptionsHtml(safe);
                    select.value = String(safe);
                    select.dataset.selected = String(safe);
                });
            }

            function optionHtml(selectedId = '') {
                const base = ['<option value="">— pasirinkti —</option>'];
                options.forEach((opt) => {
                    const selected = String(selectedId) === String(opt.id) ? ' selected' : '';
                    base.push(`<option value="${opt.id}"${selected}>${opt.name}</option>`);
                });
                return base.join('');
            }

            function rowHtml(index) {
                return `
                    <tr>
                        <td><select name="exercises[${index}][exercise_id]" class="form-select form-select-sm">${optionHtml()}</select></td>
                        <td><select name="exercises[${index}][day]" class="form-select form-select-sm day-select" data-selected="1">${dayOptionsHtml(1)}</select></td>
                        <td><input type="number" name="exercises[${index}][sets]" value="3" min="1" max="20" class="form-control form-control-sm"></td>
                        <td><input type="text" name="exercises[${index}][reps]" value="8-12" class="form-control form-control-sm"></td>
                        <td><input type="number" name="exercises[${index}][rest_seconds]" value="90" min="0" max="3600" class="form-control form-control-sm"></td>
                        <td><input type="text" name="exercises[${index}][notes]" value="" class="form-control form-control-sm"></td>
                        <td><input type="number" name="exercises[${index}][sort_order]" value="${index}" min="0" max="1000" class="form-control form-control-sm"></td>
                        <td><button type="button" class="btn btn-sm btn-outline-danger remove-exercise-row"><i class="bi bi-x-lg"></i></button></td>
                    </tr>`;
            }

            addBtn.addEventListener('click', function () {
                const index = body.querySelectorAll('tr').length;
                body.insertAdjacentHTML('beforeend', rowHtml(index));
            });

            daysInput?.addEventListener('input', refreshDaySelects);
            daysInput?.addEventListener('change', refreshDaySelects);

            body.addEventListener('click', function (event) {
                const btn = event.target.closest('.remove-exercise-row');
                if (!btn) return;
                btn.closest('tr')?.remove();
            });

            refreshDaySelects();
        })();
    </script>
    @endpush
@endsection
