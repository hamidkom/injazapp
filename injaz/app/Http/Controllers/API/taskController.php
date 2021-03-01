<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BassController as BassController;
use Illuminate\Support\Facades\Validator;
use App\Models\task;
use App\Models\User;
use App\Http\Resources\task as taskResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Illuminate\Support\Facades\DB;

class taskController extends BassController
{
    //use to show all tasks (postman linked)
    public function userTask(task $task)
    {
        $task = task::where('user_id' , Auth::id())->get();
        return $this->sendResponse(taskResource::collection($task), 'All Tasks retrieved Successfully!' );
    }

//-------------------------------------------------------------------------------

    //create and store tasks (all will be ongoing tasks by default) //for today by default
    //(postman linked)
    //create Today task
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = validator::make($input, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('All fields are required', $validator->errors());
        }

        $user = Auth::user();
        $input['user_id'] = $user->id;

        $task = task::create($input);
        //اضافة
        $task->the_date = Carbon::parse(now())->toDateString();
        $task->save();

        return $this->sendResponse(new taskResource($task), 'Task Created successfully');
    }

    //create Tomorrow task
    public function createTomorrowTask(Request $request)
    {
        $input = $request->all();
        $validator = validator::make($input, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('All fields are required', $validator->errors());
        }

        $user = Auth::user();
        $input['user_id'] = $user->id;

        $task = task::create($input);
        //اضافة
       // $task->the_date = Carbon::parse(carbon::tomorrow());
        $task->the_date = carbon::tomorrow()->toDateString();
        $task->the_day = 1;
        $task->save();

        return $this->sendResponse(new taskResource($task), 'Task Created successfully');
    }

//----------------------------------------------------------------------------------

    //use to show today tasks (postman linked)
    public function showTodayTask(task $task)
    {
        foreach($task as $task){
            DB::table('tasks')->whereDate('the_date', '=',  Carbon::today()->toDateString())->where('the_day', 1)->update([
                'the_day' => 0,
            ]);
        }

        $task = task::where('user_id' , Auth::id())
        ->whereDate('the_date', '=',  Carbon::today()->toDateString())
        ->where('the_day', '=',  0)
        ->get();

        return $this->sendResponse(taskResource::collection($task), 'Today Tasks' );
    }

//----------------------------------------------------------------------------------

    //use to show tomorrow tasks (postman linked)
    public function showTomorrowTask(task $task)
    {
        foreach($task as $task){
            DB::table('tasks')->whereDate('the_date', '>',  Carbon::today()->toDateString())->where('the_day', 0)->update([
                'the_day' => 1,
            ]);
        }

        $task = task::where('user_id' , Auth::id())
        ->where('the_day', '=',  1)
        ->whereDate('the_date', '=',  Carbon::tomorrow()->toDateString())->get();
        return $this->sendResponse(taskResource::collection($task), 'Tomorrow Tasks' );

        //->where('status', 0)
    }

//----------------------------------------------------------------------------------

    //to show specific task (postman linked)
    public function showSpecificTask($id)
    {
        $task = task::find($id);

        if (is_null($task))
        {
            return $this->sendError('Task not found.');
        }

        $errorMessage = [] ;

        if ( $task->user_id != Auth::id())
        {
            return $this->sendError('you dont have rights' , $errorMessage);
        }
         return $this->sendResponse(new taskResource($task), 'Task retrieved successfully');
    }

//----------------------------------------------------------------------------------

    //to edit task (postman linked)
    public function update(Request $request, task $task)
    {
         $input = $request->all();
        $validator = validator::make($input, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('All fields are required', $validator->errors());
           }

        if ( $task->user_id != Auth::id()) {
            return $this->sendError('you dont have rights' , $validator->errors());
        }

        $task->content = $input['content'];
        $task->save();

        return $this->sendResponse(new taskResource($task), 'Task updated successfully');
    }

//----------------------------------------------------------------------------------

    //convert task to complete (postman linked)
    public function convertToCompletedTask($id)
    {
        $task = task::find($id);
        if (is_null($task))
        {
            return $this->sendError('Task not found.');
        }

        $errorMessage = [] ;
        if ( $task->user_id != Auth::id())
        {
            return $this->sendError('you dont have rights' , $errorMessage);
        }

        DB::table('tasks')->where('id', $id)->where('status', 0)->update([
            'status' => 1,
        ]);
        return $this->sendResponse(new taskResource($task), 'Task is Completed');
    }

//----------------------------------------------------------------------------------

    //transfer task from today to tomorow by user (postman linked)
    public function transToTomorrow($id)
    {
        $task = task::find($id);
        if (is_null($task))
        {
            return $this->sendError('Task not found.');
        }

        if ($task->status == 1){
            return $this->sendResponse(new taskResource($task), 'Task was completed');
        }

        $errorMessage = [] ;
        if ( $task->user_id != Auth::id())
        {
            return $this->sendError('you dont have rights' , $errorMessage);
        }

        DB::table('tasks')->where('id', $id)->where('status', 0)->where('the_day', 0)->update([
            'the_day' => 1,
            'the_date' => Carbon::tomorrow()->toDateString(),
        ]);
        return $this->sendResponse(new taskResource($task), 'Task is postponed for tomorrow');
    }

//----------------------------------------------------------------------------------

    //Delete task (postman linked)
    public function destroy(task $task)
    {
        $errorMessage = [] ;

        if ( $task->user_id != Auth::id())
        {
            return $this->sendError('you dont have rights' , $errorMessage);
        }

        $task->delete();
        return $this->sendResponse(new taskResource($task), 'Task deleted successfully');
    }
}

