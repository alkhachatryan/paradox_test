<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todos\CreateTodoRequest;
use App\Http\Requests\Todos\DeleteTodoRequest;
use App\Http\Requests\Todos\EditTodoRequest;
use App\Http\Requests\Todos\GetTodoRequest;
use App\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodosController extends Controller
{
    public function index(GetTodoRequest $request){
        $todos = Todo::orderBy('done', 'DESC')
            ->with('user');

        if($request->filled('filter'))
            $todos->whereDone($request->input('filter') === 'finished');

        return $todos->get();
    }

    public function create(CreateTodoRequest $request){
        $todo = new Todo();

        $todo->title = $request->input('title');

        if(($user = Auth::guard('api')->user()) !== null)
            $todo->user_id = $user->id;

        $todo->done = $request->input('done') == 1;

        $todo->save();

        return response()->json($todo, 201);
    }

    public function edit(EditTodoRequest $request, $id){
        $todo = Todo::find((int)$id);

        if(! $todo)
            return response()->json('Not found', 404);

        if($request->filled('title'))
            $todo->title = $request->input('title');

        if($request->filled('done'))
            $todo->done = $request->input('done') == 1;

        $todo->save();

        return response()->json($todo, 200);
    }

    public function delete(DeleteTodoRequest $request, $id){
        $todo = Todo::find((int)$id);

        if(! $todo)
            return response()->json('Not found', 404);

        if(! $todo->done)
            return response()->json('Cannot delete not finished task', 403);

        $todo->delete();

        return response()->json('Deleted', 200);
    }
}
