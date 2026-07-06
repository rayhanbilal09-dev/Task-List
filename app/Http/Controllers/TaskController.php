<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        if (class_exists(\Spatie\Permission\PermissionServiceProvider::class)) {
            $this->middleware('permission:task.read')->only(['index', 'show']);
            $this->middleware('permission:task.create')->only(['create', 'store']);
            $this->middleware('permission:task.update')->only(['edit', 'update']);
            $this->middleware('permission:task.delete')->only(['destroy']);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::latest()->get();

        return view('tasks.index', compact('tasks'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        // Only Admin can create tasks
        if ($user->name !== 'Admin') {
            abort(403, 'Hanya Admin yang bisa membuat task.');
        }

        return view('tasks.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Only Admin can create tasks
        if ($user->name !== 'Admin') {
            abort(403, 'Hanya Admin yang bisa membuat task.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $taskData = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => false,
            'user_id' => $request->user_id,
        ];

        Task::create($taskData);

        return redirect()->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dihapus.');
    }

    public function toggleStatus(Task $task)
    {
        $task->status = !$task->status;
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Status task diperbarui.');
    }

    /**
     * Confirm a completed task (Admin only by name 'Admin').
     */
    public function confirm(Task $task)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        // Only allow Admin named 'Admin' to confirm
        if ($user->name !== 'Admin') {
            abort(403);
        }

        if (! $task->status) {
            return redirect()->route('tasks.index')->with('error', 'Hanya task yang selesai dapat dikonfirmasi.');
        }

        // Delete task after confirmation
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dikonfirmasi dan dihapus.');
    }

    /**
     * Show form for uploading task photo (Rayhan only).
     */
    public function showUploadPhoto(Task $task)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        // Only Rayhan can upload photos
        if ($user->name !== 'Rayhan') {
            abort(403, 'Hanya Rayhan yang bisa upload foto task.');
        }

        return view('tasks.upload-photo', compact('task'));
    }

    /**
     * Store task photo (Rayhan only).
     */
    public function storePhoto(Request $request, Task $task)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        // Only Rayhan can upload photos
        if ($user->name !== 'Rayhan') {
            abort(403, 'Hanya Rayhan yang bisa upload foto task.');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old photo if exists
        if ($task->photo) {
            \Storage::disk('public')->delete($task->photo);
        }

        // Store new photo
        $photoPath = $request->file('photo')->store('task-photos', 'public');
        
        $task->update([
            'photo' => $photoPath,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Foto task berhasil diupload.');
    }
}
