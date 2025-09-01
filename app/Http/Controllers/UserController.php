<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = User::with('tasks')->get();
        return $users;
    }

    public function store(Request $request)
    {
        $user = $this->userService->createUser($request);
        return $user;
    }
    public function update(Request $request, $id)
    {
        try {
             $data = $this->userService->upadateUser($request, $id);
            return $data;
            // $query = User::findOrFail($id);
            // $query->update([
            //     'name' => $request->name,
            // ]);
            // if (!empty($request->tasks)) {
            //     foreach ($request->tasks as $task) {
            //         $task = Task::updateOrCreate(
            //             [
            //                 'user_id' => $query['id'],
            //                 'id' => $task['id'] ?? null,
            //             ],
            //             [
            //                 'title' => $task['title'],
            //                 'description' => $task['description'],
            //             ]
            //         );
            //     }
            // }
            // return $query;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function destroy($id)
    {
        return $this->userService->deleteTask($id);
    }

    public function show($id)
    {
        $user = User::with('tasks')->findOrFail($id);
        return $user;
    }
}
