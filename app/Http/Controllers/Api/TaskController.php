<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\TranslatableTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    use TranslatableTrait; 

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'type_task' => 'required|in:1,2,3', // 1=Collect, 2=Settle, 3=Deliver Returns
                'number_order' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'date_implementation' => 'nullable|date',
                'duration' => 'nullable', // 1=Month, 2=2 Months, 3=3 Months
                'notes' => 'nullable|string',
                'receive_via' => 'nullable|in:1,2,3', // 1=Attendance, 2=Representative, 3=Bank
                'value_amount'=> 'nullable',
            ];

            // Validate the request
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Get validated data
            $data = $validator->validated();

            // Add person_id and person_type
            $data['person_id'] = $user->id;
            $data['person_type'] = get_class($user);

            // Create the task
            $task = Task::create($data);

            return response()->json([
                'message' => $this->translate('task_created_successfully'), // ترجمة الرسالة
                'data' => $task,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => $this->translate('validation_failed'), // ترجمة الرسالة
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error creating task: ' . $e->getMessage());

            return response()->json([
                'message' => $this->translate('error_creating_task'), // ترجمة الرسالة
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all tasks.
     */
    public function index(Request $request)
    {
        try {
            // Validate the request parameters
            $request->validate([
                'sort' => 'nullable|in:asc,desc', // Allow 'asc' or 'desc' for sorting
                'type_task' => 'nullable|in:1,2,3', // 1=Collect, 2=Settle, 3=Deliver Returns
                'duration' => 'nullable|in:1,2,3', // 1=Month, 2=2 Months, 3=3 Months
                'limit' => 'nullable|integer|min:1', // Limit the number of results
            ]);

            // Get the sort order from the request (default to 'desc' if not provided)
            $sortOrder = $request->input('sort', 'desc');

            // Get the limit from the request (default to no limit if not provided)
            $limit = $request->input('limit');

            // Start building the query
            $query = Task::where('person_id', auth()->user()->id)->with('person');

            // Apply filters if provided
            if ($request->has('type_task')) {
                $query->where('type_task', $request->input('type_task'));
            }

            if ($request->has('duration')) {
                $query->where('duration', $request->input('duration'));
            }

            $query->orderBy('created_at', $sortOrder);

            if ($limit) {
                $query->limit($limit);
            }

            $tasks = $query->get();

            return response()->json([
                'message' => $this->translate('tasks_retrieved_successfully'), // ترجمة الرسالة
                'data' => TaskResource::collection($tasks),
            ], 200);

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error retrieving tasks: ' . $e->getMessage());

            return response()->json([
                'message' => $this->translate('error_retrieving_tasks'), // ترجمة الرسالة
                'error' => $e->getMessage(), // Optional: Include the error message for debugging
            ], 500);
        }
    }

    /**
     * Get a specific task by ID.
     */
    public function show($id)
    {
        try {
            $task = Task::where('person_id', auth()->user()->id)->with('person')->find($id);

            if (!$task) {
                return response()->json([
                    'message' => $this->translate('task_not_found'),
                ], 404);
            }

            return response()->json([
                'message' => $this->translate('task_retrieved_successfully'),
                'data' => $task,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error retrieving task: ' . $e->getMessage());

            return response()->json([
                'message' => $this->translate('error_retrieving_task'), 
                'error' => $e->getMessage(), 
            ], 500);
        }
    }
}