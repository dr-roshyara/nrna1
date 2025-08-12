<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PublisherController extends Controller
{
    /**
     * Display a listing of publishers
     */
    public function index()
    {
        $publishers = Publisher::with(['authorizations' => function($query) {
            $query->latest();
        }])->get();

        return Inertia::render('Admin/Publishers/Index', [
            'publishers' => $publishers->map(function($publisher) {
                return [
                    'id' => $publisher->id,
                    'name' => $publisher->name,
                    'title' => $publisher->title,
                    'email' => $publisher->email,
                    'is_active' => $publisher->is_active,
                    'last_authorization' => $publisher->authorizations->first() ? 
                        $publisher->authorizations->first()->created_at : null,
                    'created_at' => $publisher->created_at,
                ];
            })
        ]);
    }

    /**
     * Store a newly created publisher
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'required|email|unique:publishers,email',
            'password' => 'required|string|min:8|confirmed',
            'authorization_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $publisher = Publisher::create([
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'authorization_password' => Hash::make($request->authorization_password),
            'is_active' => true,
        ]);

        return back()->with('success', 'Publisher created successfully.');
    }

    /**
     * Update the specified publisher
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'required|email|unique:publishers,email,' . $publisher->id,
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'title' => $request->title,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $updateData['password'] = Hash::make($request->password);
        }

        // Only update authorization password if provided
        if ($request->filled('authorization_password')) {
            $request->validate([
                'authorization_password' => 'string|min:8',
            ]);
            $updateData['authorization_password'] = Hash::make($request->authorization_password);
        }

        $publisher->update($updateData);

        return back()->with('success', 'Publisher updated successfully.');
    }

    /**
     * Remove the specified publisher
     */
    public function destroy(Publisher $publisher)
    {
        // Check if publisher has any authorizations
        if ($publisher->authorizations()->count() > 0) {
            return back()->with('error', 'Cannot delete publisher with existing authorizations.');
        }

        $publisher->delete();

        return back()->with('success', 'Publisher deleted successfully.');
    }

    /**
     * Reset publisher password
     */
    public function resetPassword(Request $request, Publisher $publisher)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
            'new_authorization_password' => 'sometimes|string|min:8',
        ]);

        $updateData = [
            'password' => Hash::make($request->new_password),
        ];

        if ($request->filled('new_authorization_password')) {
            $updateData['authorization_password'] = Hash::make($request->new_authorization_password);
        }

        $publisher->update($updateData);

        return back()->with('success', 'Publisher password reset successfully.');
    }
}