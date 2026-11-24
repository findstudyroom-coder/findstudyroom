<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\State;
use App\Models\District;
use App\Models\Board;
use App\Models\ExamPreparation;

class TutorController extends Controller
{
   
    public function index()
    {
        $subject = Subject::get();
        $tutors = Tutor::latest()->get();
        return view('admin.tutors.index', compact('tutors','subject'));
    }

   
   public function create()
{
    $subject = Subject::get();
    $classes = ClassModel::get();
    $state = State::get();
    $district = District::get();
    $boards = Board::get();
    $examPreparations = ExamPreparation::get();

    return view(
        'admin.tutors.create',
        compact(
            'subject',
            'classes',
            'state',
            'district',
            'boards',
            'examPreparations'
        )
    );
}

  
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required_without:full_name|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'hourly_rate' => 'nullable|numeric',
            'demo_price' => 'nullable|numeric',
            'experience_years' => 'nullable|integer',
            'total_students_taught' => 'nullable|integer',
            'about_me' => 'nullable|string',
            'qualifications' => 'nullable|string|max:1000',
            'certifications' => 'nullable|string|max:1000',
            'achievements' => 'nullable|array',
            'achievements.*' => 'nullable|string|max:255',
            'location_area' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:50',
            'full_address' => 'nullable|string|max:1000',
            'subject' => 'nullable|array',
            'subject.*' => 'nullable|string|max:255',
            'classes' => 'nullable|array',
            'classes.*' => 'nullable|string|max:255',
            'boards' => 'nullable|array',
            'boards.*' => 'nullable|string|max:255',
            'exam_preparation' => 'nullable|array',
            'exam_preparation.*' => 'nullable|string|max:255',
            'state_id' => 'nullable|integer|exists:states,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'tehsil_id' => 'nullable|integer|exists:tehsils,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = null;
        $imageFile = $request->file('image') ?? $request->file('profile_image');

        if ($imageFile) {
            $imageName = time().'_'.$imageFile->getClientOriginalName();
            $imageFile->move(public_path('uploads/tutors'), $imageName);
        }

        $subjects = $request->input('subject', []);
        $fullName = $request->input('full_name', $request->input('name'));
        $slug = $this->createSlug(Str::slug($fullName));

        Tutor::create([
            'full_name' => $fullName,
            'slug' => $slug,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'email' => $request->email,
            'hourly_rate' => $request->hourly_rate,
            'demo_price' => $request->demo_price,
            'experience_years' => $request->experience_years,
            'total_students' => $request->input('total_students_taught'),
            'bio' => $request->about_me,
            'qualification' => $request->qualifications,
            'certifications' => $request->certifications,
            'achievements' => $request->input('achievements', []),
            'location_area' => $request->location_area,
            'pincode' => $request->pincode,
            'full_address' => $request->full_address,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'tehsil_id' => $request->tehsil_id,
            'subject' => $subjects,
            'classes' => $request->input('classes', []),
            'boards' => $request->input('boards', []),
            'exam_preparation' => $request->input('exam_preparation', []),
            'image' => $imageName,
            'demo_class_available' => $request->has('demo_available'),
            'availability' => $request->has('available'),
            'is_verified' => $request->has('is_verified'),
            'is_featured' => $request->has('is_featured'),
            'teaching_mode' => $request->teaching_mode,
        ]);

        return redirect()->route('tutors.index')->with('success', 'Tutor Added Successfully');
    }

   
    public function edit($id)
    {
        $subject = Subject::get();
        $classes = ClassModel::get();
        $state = State::get();
        $boards = Board::get();
        $examPreparations = ExamPreparation::get();
        $tutor = Tutor::findOrFail($id);

        return view(
            'admin.tutors.edit',
            compact('tutor', 'subject', 'classes', 'state', 'boards', 'examPreparations')
        );
    }


    public function update(Request $request, $id)
    {
        $tutor = Tutor::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:Male,Female',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'hourly_rate' => 'nullable|numeric',
            'demo_price' => 'nullable|numeric',
            'experience_years' => 'nullable|integer',
            'total_students_taught' => 'nullable|integer',
            'about_me' => 'nullable|string',
            'qualifications' => 'nullable|string|max:1000',
            'certifications' => 'nullable|string|max:1000',
            'achievements' => 'nullable|array',
            'achievements.*' => 'nullable|string|max:255',
            'location_area' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:50',
            'full_address' => 'nullable|string|max:1000',
            'subject' => 'nullable|array',
            'subject.*' => 'nullable|string|max:255',
            'classes' => 'nullable|array',
            'classes.*' => 'nullable|string|max:255',
            'boards' => 'nullable|array',
            'boards.*' => 'nullable|string|max:255',
            'exam_preparation' => 'nullable|array',
            'exam_preparation.*' => 'nullable|string|max:255',
            'state_id' => 'nullable|integer|exists:states,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'tehsil_id' => 'nullable|integer|exists:tehsils,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imageName = $tutor->image;
        $imageFile = $request->file('image') ?? $request->file('profile_image');

        if ($imageFile) {
            if ($tutor->image && file_exists(public_path('uploads/tutors/'.$tutor->image))) {
                unlink(public_path('uploads/tutors/'.$tutor->image));
            }

            $imageName = time().'_'.$imageFile->getClientOriginalName();
            $imageFile->move(public_path('uploads/tutors'), $imageName);
        }

        $fullName = $request->input('full_name', $request->input('name', $tutor->full_name));
        $slug = Str::slug($fullName);
        if ($slug !== $tutor->slug) {
            $slug = $this->createSlug($slug, $tutor->id);
        }

        $tutor->update([
            'full_name' => $fullName,
            'slug' => $slug,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'email' => $request->email,
            'hourly_rate' => $request->hourly_rate,
            'demo_price' => $request->demo_price,
            'experience_years' => $request->experience_years,
            'total_students' => $request->input('total_students_taught', $tutor->total_students),
            'bio' => $request->about_me ?? $tutor->bio,
            'qualification' => $request->qualifications ?? $tutor->qualification,
            'certifications' => $request->certifications ?? $tutor->certifications,
            'achievements' => $request->input('achievements', $tutor->achievements ?? []),
            'location_area' => $request->location_area ?? $tutor->location_area,
            'pincode' => $request->pincode ?? $tutor->pincode,
            'full_address' => $request->full_address ?? $tutor->full_address,
            'state_id' => $request->state_id ?? $tutor->state_id,
            'district_id' => $request->district_id ?? $tutor->district_id,
            'tehsil_id' => $request->tehsil_id ?? $tutor->tehsil_id,
            'subject' => $request->input('subject', $tutor->subject ?? []),
            'classes' => $request->input('classes', $tutor->classes ?? []),
            'boards' => $request->input('boards', $tutor->boards ?? []),
            'exam_preparation' => $request->input('exam_preparation', $tutor->exam_preparation ?? []),
            'image' => $imageName,
            'demo_class_available' => $request->has('demo_available'),
            'availability' => $request->has('available'),
            'is_verified' => $request->has('is_verified'),
            'is_featured' => $request->has('is_featured'),
            'teaching_mode' => $request->teaching_mode ?? $tutor->teaching_mode,
        ]);

        return redirect()->route('tutors.index')->with('success', 'Tutor Updated Successfully');
    }

    private function createSlug($slug, $ignoreId = null)
    {
        $originalSlug = $slug;
        $counter = 1;

        while (Tutor::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }

   
    public function destroy($id)
    {
        $tutor = Tutor::findOrFail($id);

        if ($tutor->image && file_exists(public_path('uploads/tutors/'.$tutor->image))) {
            unlink(public_path('uploads/tutors/'.$tutor->image));
        }

        $tutor->delete();

        return redirect()->back()->with('success', 'Tutor Deleted Successfully');
    }
}