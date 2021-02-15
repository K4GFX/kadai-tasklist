<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            //$tasks = Task::all();
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
        
        $data = [
            'user' => $user,
            'tasks' => $tasks,
            ];
        
        //return view('welcome',$data);
        return view('tasks.index',[
        'tasks' => $tasks
        ]);
        } else return view('welcome');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',[
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        /*
        $task = new Task;
        $task -> user_id = $request->user_id;
        $task -> status = $request->status;
        $task -> content = $request -> content;
        $task -> save();
        */
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show',[
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit',[
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $task = Task::findOrFail($id);
        //$task->user_id = $request->user_id;
        if (\Auth::id() === $task->user_id) {
        $task->user_id = $request->user()->id;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        }
        /*$request->user()->tasks()->create([
            'content' => $request->content,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);
        */
        
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $task = Task::findOrFail($id);
        /*
        $task->delete();
        */
        
        $task= \App\Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        return redirect('/');
    }
}
