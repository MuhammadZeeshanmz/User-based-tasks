<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser($request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,

            ]);
            $this->updateTasks($request, $user);
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function upadateUser($request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name
            ]);
            $this->updateTasks($request, $user);
            return $user;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    private function updateTasks($request, $user)
    {
        try {
            if (!empty($request->tasks)) {
                foreach ($request->tasks as $task) {
                    $task = Task::updateOrCreate(
                        [
                            'id' => $task['id'] ?? null,
                            'user_id' => $user['id'],
                        ],
                        [

                            'title' => $task['title'],
                            'description' => $task['description'],
                        ]
                    );
                }
            }
            return $task;
        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return $task;
    }
    public function filter($request){
        $name = $request->name;
        $users = User::query()
            ->when($name, fn($q) =>
                $q->where('name', $name)
                ->nestedWhere(fn($q) =>
                    $q->where('email', 'like', "%{$name}%")
                )
            )
           
            ->get();

        return $users;
    }
}

