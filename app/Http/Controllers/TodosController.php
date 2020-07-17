<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todos\CreateTodoRequest;
use App\Http\Requests\Todos\DeleteTodoRequest;
use App\Http\Requests\Todos\EditTodoRequest;
use App\Http\Requests\Todos\GetTodoRequest;
use App\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * The resource controller class to work with requests related with Todos model
 *
 * Implemented resource actions: list (with filters), create, edit, delete
*/
class TodosController extends Controller
{
    /**
     * List the todos ordered by done column.
     * If "filter" parameter passed and it equals "finished" - get only finished todos,
     * Otherwise not finished todos will be returned (in case of filled "filter" parameter)
     *
     * @param GetTodoRequest $request
     *
     * @return JsonResponse
    */
    public function index(GetTodoRequest $request){
        $todos = Todo::orderBy('done', 'DESC')
            ->with('user');

        if($request->filled('filter'))
            $todos->whereDone($request->input('filter') === 'finished');

        return response()->json($todos->get(), 200);
    }

    /**
     * Create a Todo model.
     * If the user is logged in (Bearer token present and it's right) - save the user ID
     *
     * @param CreateTodoRequest $request
     *
     * @return JsonResponse
    */
    public function create(CreateTodoRequest $request){
        $todo = new Todo();

        $todo->title = $request->input('title');

        if(($user = Auth::guard('api')->user()) !== null)
            $todo->user_id = $user->id;

        $todo->done = $request->input('done') == 1;

        $todo->save();

        return response()->json($todo, 201);
    }

    /**
     * Edit the Todo model
     *
     * If user filled the title or the status - update it.
     * If the updating data is empty - return 400 response.
     *
     * @param EditTodoRequest $request
     * @param integer $id
     *
     * @return JsonResponse
    */
    public function edit(EditTodoRequest $request, $id){
        $todo = Todo::find((int)$id);

        if(! $todo)
            return response()->json('Not found', 404);

        if(! $request->anyFilled(['title', 'done']))
            return response()->json('Nothing to update', 400);

        if($request->filled('title'))
            $todo->title = $request->input('title');

        if($request->filled('done'))
            $todo->done = $request->input('done') == 1;

        $todo->save();

        return response()->json($todo, 200);
    }

    /**
     * Delete the Todo model
     *
     * If the todo has done = false - inform user, as thenot finished todo cannot be deleted.
     *
     * @param DeleteTodoRequest $request
     * @param integer $id
     *
     * @return JsonResponse
    */
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
