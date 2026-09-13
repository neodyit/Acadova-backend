<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\College;
use App\Models\Department;
use App\Models\Course;
use App\Models\Branch;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Subsection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicController extends Controller
{
    // --- UNIVERSITIES ---
    public function getUniversities()
    {
        return response()->json(['success' => true, 'data' => University::withCount('colleges')->get()]);
    }

    public function storeUniversity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'nullable|string|unique:universities,code',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $university = University::create($request->all());
        return response()->json(['success' => true, 'message' => 'University created successfully', 'data' => $university], 201);
    }

    public function updateUniversity(Request $request, $id)
    {
        $university = University::findOrFail($id);
        $university->update($request->all());
        return response()->json(['success' => true, 'message' => 'University updated', 'data' => $university]);
    }

    public function deleteUniversity($id)
    {
        University::destroy($id);
        return response()->json(['success' => true, 'message' => 'University deleted']);
    }

    // --- COLLEGES ---
    public function getColleges()
    {
        return response()->json(['success' => true, 'data' => College::with('university')->withCount('departments')->get()]);
    }

    public function storeCollege(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
            'city' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $college = College::create($request->all());
        return response()->json(['success' => true, 'message' => 'College created', 'data' => $college->load('university')], 201);
    }

    public function updateCollege(Request $request, $id)
    {
        $college = College::findOrFail($id);
        $college->update($request->all());
        return response()->json(['success' => true, 'message' => 'College updated', 'data' => $college->load('university')]);
    }

    public function deleteCollege($id)
    {
        College::destroy($id);
        return response()->json(['success' => true, 'message' => 'College deleted']);
    }

    // --- DEPARTMENTS ---
    public function getDepartments()
    {
        return response()->json(['success' => true, 'data' => Department::with('college')->withCount('courses')->get()]);
    }

    public function storeDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'college_id' => 'nullable|exists:colleges,id',
            'code' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $department = Department::create($request->all());
        return response()->json(['success' => true, 'message' => 'Department created', 'data' => $department->load('college')], 201);
    }

    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $department->update($request->all());
        return response()->json(['success' => true, 'message' => 'Department updated', 'data' => $department->load('college')]);
    }

    public function deleteDepartment($id)
    {
        Department::destroy($id);
        return response()->json(['success' => true, 'message' => 'Department deleted']);
    }

    // --- COURSES ---
    public function getCourses()
    {
        return response()->json(['success' => true, 'data' => Course::with('department')->withCount('branches')->get()]);
    }

    public function storeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'code' => 'nullable|string',
            'duration_years' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $course = Course::create($request->all());
        return response()->json(['success' => true, 'message' => 'Course created', 'data' => $course->load('department')], 201);
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->all());
        return response()->json(['success' => true, 'message' => 'Course updated', 'data' => $course->load('department')]);
    }

    public function deleteCourse($id)
    {
        Course::destroy($id);
        return response()->json(['success' => true, 'message' => 'Course deleted']);
    }

    // --- BRANCHES ---
    public function getBranches()
    {
        return response()->json(['success' => true, 'data' => Branch::with('course')->withCount(['subjects', 'sections'])->get()]);
    }

    public function storeBranch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $branch = Branch::create($request->all());
        return response()->json(['success' => true, 'message' => 'Branch created', 'data' => $branch->load('course')], 201);
    }

    public function updateBranch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update($request->all());
        return response()->json(['success' => true, 'message' => 'Branch updated', 'data' => $branch->load('course')]);
    }

    public function deleteBranch($id)
    {
        Branch::destroy($id);
        return response()->json(['success' => true, 'message' => 'Branch deleted']);
    }

    // --- SUBJECTS ---
    public function getSubjects()
    {
        return response()->json(['success' => true, 'data' => Subject::with('branch')->get()]);
    }

    public function storeSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'code' => 'nullable|string',
            'semester' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $subject = Subject::create($request->all());
        return response()->json(['success' => true, 'message' => 'Subject created', 'data' => $subject->load('branch')], 201);
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->all());
        return response()->json(['success' => true, 'message' => 'Subject updated', 'data' => $subject->load('branch')]);
    }

    public function deleteSubject($id)
    {
        Subject::destroy($id);
        return response()->json(['success' => true, 'message' => 'Subject deleted']);
    }

    // --- SECTIONS ---
    public function getSections()
    {
        return response()->json(['success' => true, 'data' => Section::with('branch')->withCount('subsections')->get()]);
    }

    public function storeSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'academic_year' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $section = Section::create($request->all());
        return response()->json(['success' => true, 'message' => 'Section created', 'data' => $section->load('branch')], 201);
    }

    public function updateSection(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $section->update($request->all());
        return response()->json(['success' => true, 'message' => 'Section updated', 'data' => $section->load('branch')]);
    }

    public function deleteSection($id)
    {
        Section::destroy($id);
        return response()->json(['success' => true, 'message' => 'Section deleted']);
    }

    // --- SUBSECTIONS ---
    public function getSubsections()
    {
        return response()->json(['success' => true, 'data' => Subsection::with('section')->get()]);
    }

    public function storeSubsection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:sections,id',
            'name' => 'required|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $sub = Subsection::create($request->all());
        return response()->json(['success' => true, 'message' => 'Subsection created', 'data' => $sub->load('section')], 201);
    }

    public function updateSubsection(Request $request, $id)
    {
        $sub = Subsection::findOrFail($id);
        $sub->update($request->all());
        return response()->json(['success' => true, 'message' => 'Subsection updated', 'data' => $sub->load('section')]);
    }

    public function deleteSubsection($id)
    {
        Subsection::destroy($id);
        return response()->json(['success' => true, 'message' => 'Subsection deleted']);
    }
}
