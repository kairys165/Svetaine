<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Sport;
use App\Models\SportPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SportPlanController extends Controller
{
    public function index()
    {
        $plans = SportPlan::with('sport')->latest()->paginate(20);
        return view('admin.sports.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.sports.form', [
            'plan' => new SportPlan(['is_active' => true, 'level' => 'beginner', 'goal' => 'general', 'duration_weeks' => 4, 'days_per_week' => 3]),
            'sports' => Sport::orderBy('name')->get(),
            'exercises' => Exercise::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $plan = SportPlan::create($this->validated($request));
        $this->syncExercises($plan, $this->validatedExercises($request));
        return redirect()->route('admin.sport-plans.index')->with('success', 'Planas sukurtas.');
    }

    public function edit(SportPlan $plan)
    {
        $plan->load('exercises');

        return view('admin.sports.form', [
            'plan' => $plan,
            'sports' => Sport::orderBy('name')->get(),
            'exercises' => Exercise::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, SportPlan $plan)
    {
        $plan->update($this->validated($request, $plan));
        $this->syncExercises($plan, $this->validatedExercises($request));
        return redirect()->route('admin.sport-plans.index')->with('success', 'Planas atnaujintas.');
    }

    public function destroy(SportPlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Planas pašalintas.');
    }

    protected function validated(Request $request, ?SportPlan $plan = null): array
    {
        $id = $plan?->id;
        $data = $request->validate([
            'sport_id' => 'nullable|exists:sports,id',
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:sport_plans,slug,{$id}",
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'goal' => 'required|in:strength,hypertrophy,endurance,weight_loss,general',
            'duration_weeks' => 'required|integer|min:1|max:52',
            'days_per_week' => 'required|integer|min:1|max:7',
            'image' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }

    protected function validatedExercises(Request $request): array
    {
        $data = $request->validate([
            'exercises' => 'nullable|array',
            'exercises.*.exercise_id' => 'nullable|exists:exercises,id',
            'exercises.*.day' => 'nullable|integer|min:1|lte:days_per_week',
            'exercises.*.sets' => 'nullable|integer|min:1|max:20',
            'exercises.*.reps' => 'nullable|string|max:50',
            'exercises.*.rest_seconds' => 'nullable|integer|min:0|max:3600',
            'exercises.*.notes' => 'nullable|string|max:1000',
            'exercises.*.sort_order' => 'nullable|integer|min:0|max:1000',
        ]);

        return collect($data['exercises'] ?? [])
            ->filter(fn ($row) => !empty($row['exercise_id']))
            ->values()
            ->all();
    }

    protected function syncExercises(SportPlan $plan, array $rows): void
    {
        $payload = [];

        foreach ($rows as $index => $row) {
            $exerciseId = (int) $row['exercise_id'];
            $payload[$exerciseId] = [
                'day' => (int) ($row['day'] ?? 1),
                'sets' => (int) ($row['sets'] ?? 3),
                'reps' => (string) ($row['reps'] ?? '8-12'),
                'rest_seconds' => (int) ($row['rest_seconds'] ?? 90),
                'notes' => (string) ($row['notes'] ?? ''),
                'sort_order' => isset($row['sort_order']) ? (int) $row['sort_order'] : $index,
            ];
        }

        $plan->exercises()->sync($payload);
    }
}
