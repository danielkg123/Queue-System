<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RunningText;
use App\Models\Carousel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;



class AdminController extends Controller
{
    public function indexUser()
    {
        $user = User::orderBy('id', 'asc')->get();
        $role = Role::orderBy('id', 'asc')->get();
        return view('admin/user', compact('user', 'role'));
    }
    
    public function indexRole()
    {
        $role = Role::orderBy('id', 'asc')->get();
        return view('admin/role', compact('role'));
    }
    
    public function indexRunningText()
    {
        $rt = RunningText::orderBy('id', 'asc')->get();
        return view('admin/runningtext', compact('rt'));
    }
    
    public function indexCarousel()
    {
        $carousel = Carousel::orderBy('id', 'asc')->get();
        return view('admin/carousel', compact('carousel'));
    }
    

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user')->with('success', 'User deleted successfully.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string', // Validate the role exists in the roles table
            'counter' => 'nullable|integer',
            'password' => 'required|string', // Validate password confirmation
        ]);
    
        // Create the new user
        User::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'counter' => $validated['counter'] ?? 0,
            'password' => Hash::make($validated['password']), // Hash the password before storing
        ]);
    
        return redirect()->route('user')->with('success', 'User added successfully.');
    }
    
    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'role' => 'required|string',
            'counter' => 'nullable|integer',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'counter' => $validated['counter'] ?? 0,
        ]);

        return redirect()->route('user')->with('success', 'User updated successfully.');
    }

    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('role')->with('success', 'Role deleted successfully.');
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string', // Ensure 'kode' is unique in the roles table
        ]);

        // Create the new role
        Role::create([
            'name' => $validated['name'],
            'kode' => $validated['kode'],
        ]);

        return redirect()->route('role')->with('success', 'Role added successfully.');
    }

    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'kode' => 'required|string' . $id, // Ensure 'kode' is unique but ignore current role's kode
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $validated['name'],
            'kode' => $validated['kode'],
        ]);

        return redirect()->route('role')->with('success', 'Role updated successfully.');
    }

    public function storeRunningText(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string', // Validating the content input
        ]);
        $status = "inactive";
        // Create the new running text
        RunningText::create([
            'content' => $validated['content'],
            'status' => $status,
        ]);
        Http::post('http://localhost:3000/update-leaderboard');

        return redirect()->route('runningtext')->with('success', 'Running Text added successfully.');
    }

    public function updateRunningText(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $runningText = RunningText::findOrFail($id);
        $runningText->update([
            'content' => $validated['content'],

        ]);
        Http::post('http://localhost:3000/update-leaderboard');

        return redirect()->route('runningtext')->with('success', 'Running Text updated successfully.');
    }

    public function destroyRunningText($id)
    {
        $runningText = RunningText::findOrFail($id);
        $runningText->delete();
        Http::post('http://localhost:3000/update-leaderboard');
        return redirect()->route('runningtext')->with('success', 'Running Text deleted successfully.');
    }

    public function toggleStatusRunningText(Request $request, $id)
    {
        $runningText = RunningText::findOrFail($id);
        $runningText->status = $runningText->status === 'active' ? 'inactive' : 'active';
        $runningText->save();
        Http::post('http://localhost:3000/update-leaderboard');
        return redirect()->route('runningtext')->with('success', 'Status updated successfully.');
    }

    public function storeCarousel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,mp4,mkv,avi,webm', // Validate file type and size (max 10MB)
            'status' => 'required|in:active,inactive', // Validate the status
        ]);
        
        // Store the uploaded file
        $file = $request->file('file');
        $filePath = $file->store('uploads/carousel', 'public');
    
        // Save the carousel details in the database
        Carousel::create([
            'image_path' => $filePath, // Can store both image and video file paths
            'status' => $request->input('status'),
        ]);
        Http::post('http://localhost:3000/update-leaderboard');
        return redirect()->route('carousel')->with('success', 'File uploaded successfully!');
    }
        
    public function destroyCarousel($id)
    {
        $carousel = Carousel::findOrFail($id);
        $carousel->delete();
        Http::post('http://localhost:3000/update-leaderboard');
        return redirect()->route('carousel')->with('success', 'Carousel deleted successfully.');
    }

    public function toggleStatusCarousel(Request $request, $id)
    {
        $carousel = Carousel::findOrFail($id);
        $carousel->status = $carousel->status === 'active' ? 'inactive' : 'active';
        $carousel->save();
        Http::post('http://localhost:3000/update-leaderboard');
    
        return redirect()->route('carousel')->with('success', 'Status updated successfully.');
    }


}
