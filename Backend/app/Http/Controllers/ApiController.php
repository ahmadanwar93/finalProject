<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;
use App\Http\Traits\JsonTrait;
use App\Models\Category;
use App\Models\User;
use App\Models\TaskCategory;
use App\Models\Tasks;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    use JsonTrait;

    //

    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['login', 'registerNewUser', 'scoreboard', "importantTask", "getAccount"]]);
    }
    // test api retrieveAll buang
    // , 'showCategory''showTask','createTask',,'updateImportant','updateDelete', 'updateCompleted',   'createCategory',,  'updateDelete', 'updateCompleted'





    // after generate secret key then copy the JSON trait from Day 17
    public function jsonResponse($data, $message = '', $code = 200)
    {
        return response()->json([
            'status' => ($code != 200) ? false : true,
            'code' => $code,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(
                $validator->errors(),
                'Invalid Input Parameters',
                422
            );
            // response()->json($validator->errors(), 422);
        }

        if (!$token = JWTAuth::attempt($validator->validated())) {
            // return response()->json(['error' => 'Unauthorized'], 401);
            return $this->jsonResponse(
                '',
                'Unauthorised',
                401
            );
        }

        return $this->createNewToken($token);
    }


    public function registerNewUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'A new user has been created. Welcome.',
            'user' => $user
        ], 201);
    }

    public function getAuthenticatedUser()
    {
        return response()->json(auth()->user());
    }



    protected function createNewToken($token)
    {

        return $this->jsonResponse(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 600,
                'user' => auth()->user()
            ],
            'Invalid Input Parameters',
            200
        );
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }






    // retrieve category
    public function showCategory($id)
    {
        // do we need to consume token here as well?
        $taskCategory = DB::table('task_categories')
            ->where('user_id', '=', $id)
            ->get();
        return $taskCategory;
    }

    public function showTask($id)
    {
        // do we need to consume token here as well?
        $taskList = DB::table('tasks')
            ->where('cat_id', '=', $id)
            ->get();
        return $taskList;
    }

    // for desktop to retrieve all category and tasks of a given user id
    public function retrieveAll($id)
    // public function retrieveAll(Request $request)
    {
        // do we need to consume token here as well?
        $retrieveAll = DB::table('task_categories')
            ->join('tasks', 'task_categories.id', '=', 'tasks.cat_id')
            ->where('task_categories.user_id', '=', $id)
            ->get();
        return $retrieveAll;
    }


    public function scoreboard()
    // public function retrieveAll(Request $request)
    {
        // retrieve tasks other than completed
        $retrieveNotImportant = DB::table('task_categories')
            ->select('task_categories.user_id', DB::raw('count(*) as not_complete'))
            ->join('tasks', 'task_categories.id', '=', 'tasks.cat_id')
            ->where('tasks.task_stat', '!=', 2)
            ->groupBy('task_categories.user_id')
            ->get();
        return $retrieveNotImportant;
    }

    // retrive completed task
    public function importantTask()
    {
        $retrieveImportant = DB::table('task_categories')
            ->select('task_categories.user_id', DB::raw('count(*) as complete'))
            // ->select('task_categories.user_id','users.name', DB::raw('count(*) as complete'))
            ->join('tasks', 'task_categories.id', '=', 'tasks.cat_id')
            // ->join('users', 'task_categories.user_id', '=', 'users.id')
            ->where('tasks.task_stat', '=', 2)
            ->groupBy('task_categories.user_id')
            // ->union($retrieveNotImportant)
            ->get();
        return $retrieveImportant;
    }

    public function getAccount()
    {
        $retrieveImportant = DB::table('users')
            ->select('id', 'name')
            ->get();
        return $retrieveImportant;
    }


    // create category
    public function createCategory($id, Request $request)
    {
        // $category = new TaskCategory;
        // $category->user_id = $id;
        // $category->category_stat = 1;
        // $category->category_desc = $request->category_desc;
        // $category->save();

        // return response()->json([
        //     "message" => "The new category is added."
        // ], 201);


        $category = new TaskCategory;
        if (TaskCategory::where('category_desc', $request->category_desc)->exists()) {
            return response()->json([
                "message" => "The category description must be unique."
            ], 404);
        } else {
            $category->user_id = $id;
            $category->category_stat = 1;
            $category->category_desc = $request->category_desc;
            $category->save();

            return response()->json([
                "message" => "The new category is added."
            ], 201);
        }
    }

    // create task
    public function createTask($id, Request $request)
    {
        $task = new Tasks;
        $task->cat_id = $id;
        $task->task_stat = 1;
        $task->task_desc = $request->task_desc;
        $task->save();

        return response()->json([
            "message" => "The new task is added."
        ], 201);
    }

    // delete task (update status)
    public function updateDelete($id, Request $request)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            // $task->task_stat == 2? $task->task_stat = 2: $task->task_stat = 0;
            $task->task_stat = 0;
            $task->save();
            //     return response()->json([
            //         "message" => "The task has successfully been deleted."
            //     ], 404);
            // } else {
            //     return response()->json([
            //         "message" => "There is a error in deleting the task.Please contact the admin."
            //     ], 404);
        }
    }


    public function updateImportant($id, Request $request)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->task_stat !== 4 ? $task->task_stat = 4 : $task->task_stat = 1;
            $task->save();
            // return response()->json([
            //     "message" => "The task has been changed to important"
            // ], 201);
        }
        // else {
        //     return response()->json([
        //         "message" => "There is a error in updating the task.Please contact the admin."
        //     ], 404);
        // }
    }

    public function updateCompleted($id, Request $request)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->task_stat = 2;
            $task->completed_at = now();
            $task->save();
            // return response()->json([
            //     "message" => "The task has successfully been completed."
            // ], 404);
        }
        // else {
        //     return response()->json([
        //         "message" => "There is a error in completing the task.Please contact the admin."
        //     ], 404);
        // }
    }
}
