<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of active campaigns/announcements for mobile app and web.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        $query = Campaign::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter out expired campaigns for student view if status is active
        if ($status === 'active') {
            $query->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            });
        }

        $campaigns = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $campaigns,
        ]);
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'badge' => 'required|string|max:100',
            'description' => 'required|string',
            'link_url' => 'nullable|url',
            'banner_color' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'boolean',
            'ends_at' => 'nullable|date',
        ]);

        $campaign = Campaign::create([
            'title' => $validated['title'],
            'badge' => $validated['badge'],
            'description' => $validated['description'],
            'link_url' => $validated['link_url'] ?? null,
            'banner_color' => $validated['banner_color'] ?? 'purple',
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured', true),
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Campaign created successfully',
            'data' => $campaign,
        ], 201);
    }

    /**
     * Display the specified campaign.
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $campaign,
        ]);
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'badge' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|required|string',
            'link_url' => 'nullable|url',
            'banner_color' => 'nullable|string',
            'status' => 'sometimes|required|in:active,inactive',
            'is_featured' => 'boolean',
            'ends_at' => 'nullable|date',
        ]);

        $campaign->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Campaign updated successfully',
            'data' => $campaign,
        ]);
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy($id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found',
            ], 404);
        }

        $campaign->delete();

        return response()->json([
            'success' => true,
            'message' => 'Campaign deleted successfully',
        ]);
    }
}
