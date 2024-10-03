<?php

namespace App\Http\Controllers\Api\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Events\EventRequest;
use App\Http\Requests\Api\Events\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function store(EventRequest $request)
    {
        try {
            $data = $request->validated();
            $data['image'] = $request->file('image')->store('EventImages', 'public');
            $data['user_id'] = Auth::user()->id;
            $event = Event::create($data);
            return response()->json([
                'status' => 200,
                'message' => 'Data Stored Successfully',
                'data' => $event,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error storing data: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function edit(string $id)
    {
        try {
        $event = Event::findOrFail($id);
        return response()->json([
            'status' => 200,
            'message' => 'Data Reteved Successfully',
            'data' => $event,
        ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateEventRequest $request, string $id)
{
    try {
        $event = Event::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $path = $request->file( 'image')->store('EventImages', 'public');
            $data['image'] = $path;
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
        }
        $event->update($data);
        return response()->json([
            'status' => 200,
            'message' => 'Data Updated Successfully',
            'data' => $event,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}
    public function AllEvents()
    {
        try {
            $events = Event::get();
            return response()->json([
                'status' => 200,
                'message' => 'Data Reteved Successfully',
                'data' => $events,
            ], 200);
            }catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }
    }
    public function delete(string $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Data Deleted Successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
