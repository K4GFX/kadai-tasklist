<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    public function index()
    {
        // ユーザ一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);

        // ユーザ一覧ビューでそれを表示
        return view('tasks.index', [
            'users' => $users,
        ]);
    }
    
     public function show($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザの投稿一覧を作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

        // ユーザ詳細ビューでそれらを表示
        return view('tasks.show', [
            'user' => $user,
            'tasklists' => $tasks,
        ]);
    }
    /*
        public function show($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.show',[
            'task' => $task,
            ]);
    }
    */
}
