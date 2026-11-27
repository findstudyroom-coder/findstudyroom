<?php

namespace App\Http\Controllers\CoachingAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coaching;
use App\Models\CommandoClass;
use App\Models\CommandoInstructor;
use App\Models\CommandoSchedule;
use App\Models\CommandoPlan;
use App\Models\CommandoGallery;

class CoachingContentController extends Controller
{
    protected function getCoachingId(Request $request): int
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return (int)($request->get('coaching_id') ?: ($user->coaching_id ?: Coaching::value('id')));
        }
        return (int)$user->coaching_id;
    }

    public function classes(Request $request)
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);
        $classes = CommandoClass::where('coaching_id', $coachingId)->orderBy('order_no')->get();

        return view('coaching_admin.content.classes', compact('coaching', 'classes'));
    }

    public function instructors(Request $request)
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);
        $instructors = CommandoInstructor::where('coaching_id', $coachingId)->orderBy('order_no')->get();

        return view('coaching_admin.content.instructors', compact('coaching', 'instructors'));
    }

    public function schedules(Request $request)
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);
        $schedules = CommandoSchedule::where('coaching_id', $coachingId)->orderBy('order_no')->get();

        return view('coaching_admin.content.schedules', compact('coaching', 'schedules'));
    }

    public function plans(Request $request)
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);
        $plans = CommandoPlan::where('coaching_id', $coachingId)->orderBy('order_no')->get();

        return view('coaching_admin.content.plans', compact('coaching', 'plans'));
    }

    public function gallery(Request $request)
    {
        $coachingId = $this->getCoachingId($request);
        $coaching = Coaching::findOrFail($coachingId);
        $galleries = CommandoGallery::where('coaching_id', $coachingId)->orderBy('order_no')->get();

        return view('coaching_admin.content.gallery', compact('coaching', 'galleries'));
    }
}
