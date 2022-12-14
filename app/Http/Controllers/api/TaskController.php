<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\User;
class TaskController extends Controller
{
    /**
     * 
     * 
     *  Admin function
     * 
     * 
     */

    public function admin_index()
    {
        if (!Gate::allows('admin')) {
            return response_error('403 Forbidden', 403);
        }

        $tasks = TaskResource::collection(Task::with('users')->paginate())->response()->getData(true);

        return response_success($tasks);
    }

    public function admin_update(StoreTaskRequest $request,Task $task)
    {

        if (!Gate::allows('admin')) {
            return response_error('403 Forbidden', 403);
        }
        
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
            
        $task->users()->sync($request->users);

        $task = new TaskResource($task->load('users'));

        return response_success($task);

    }

    public function admin_mention(Request $request, Task $task)
    {
        if (!Gate::allows('admin')) {
            return response_error('403 Forbidden', 403);
        }

        $sync = $task->users()->pluck('user_id')->toArray();
        $sync [] = Auth::user()->id;

        $task->users()->sync($sync);

        $task = new TaskResource($task->load('users'));

        return response_success($task);

    }

    public function admin_delete(Request $request, Task $task)
    {
        $task->delete();

        return response_success('task deleted');
    }


    /***
     * 
     * 
     *  Member function
     * 
     * 
     */


    public function member_index()
    {
        $tasks = Task::where('creator_id', Auth::user()->id)->with(['users']);
        $tasks = TaskResource::collection($tasks->paginate())->response()->getData(true);

        return response_success($tasks);
    }

    public function member_task_store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => Auth::user()->id,
        ]);

        $id = Auth::user()->id;

        $task->users()->sync($id);

       $task = new TaskResource($task->load('users'));

        return response_success($task);

    }

    public function member_task_delete(Request $request, Task $task)
    {
        if(!Auth::user()->creator()->where('id', $task->id)->exists())
        {
            return response_error('403 Forbidden', 403);
        }
        
        $task->delete();
        
        return response_success('task deleted');
    }


}
