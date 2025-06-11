<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classRooms = ClassRoom::with('teacher.user')
            ->withCount('students')
            ->paginate(10);
        return view('admin.classrooms.index', compact('classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:reguler,bimbel',
            'teacher_id' => 'nullable|exists:teachers,id',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        ClassRoom::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'description' => $request->description,
            'type' => $request->type,
            'teacher_id' => $request->teacher_id,
            'price' => $request->type === 'bimbel' ? $request->price : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $classRoom)
    {
        $classRoom->load('teacher.user', 'students.user', 'payments.student.user');
        $availableStudents = Student::whereDoesntHave('classRooms', function($query) use ($classRoom) {
            $query->where('class_student.class_room_id', $classRoom->id);
        })->with('user')->get();
        
        return view('admin.classrooms.show', compact('classRoom', 'availableStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.edit', compact('classRoom', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:reguler,bimbel',
            'teacher_id' => 'nullable|exists:teachers,id',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $classRoom->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'description' => $request->description,
            'type' => $request->type,
            'teacher_id' => $request->teacher_id,
            'price' => $request->type === 'bimbel' ? $request->price : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        $classRoom->delete();
        
        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Class deleted successfully.');
    }

    /**
     * Show form to assign students to class
     */
    public function assignStudents(ClassRoom $classRoom)
    {
        $classRoom->load('students');
        $availableStudents = Student::with('user')
            ->whereDoesntHave('classRooms', function($query) use ($classRoom) {
                $query->where('class_student.class_room_id', $classRoom->id);
            })
            ->get();
            
        return view('admin.classrooms.assign-students', compact('classRoom', 'availableStudents'));
    }

    /**
     * Assign students to class
     */
    public function storeStudentAssignment(Request $request, ClassRoom $classRoom)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,id',
        ]);

        $classRoom->students()->attach($request->students);

        return redirect()->route('admin.classrooms.show', $classRoom)
            ->with('success', 'Students assigned to class successfully.');
    }

    /**
     * Remove student from class
     */
    public function removeStudent(ClassRoom $classRoom, Student $student)
    {
        $classRoom->students()->detach($student->id);

        return redirect()->route('admin.classrooms.show', $classRoom)
            ->with('success', 'Student removed from class successfully.');
    }

    /**
     * Add student to class
     */
    public function addStudent(Request $request, ClassRoom $classRoom)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        
        // Check if already enrolled
        if (!$classRoom->students()->where('student_id', $student->id)->exists()) {
            $classRoom->students()->attach($student->id);
        }

        return redirect()->route('admin.classrooms.show', $classRoom)
            ->with('success', 'Student added to class successfully.');
    }
}
