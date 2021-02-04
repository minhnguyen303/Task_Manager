<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Models\Task;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.list', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */

    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaskRequest $request
     * @return RedirectResponse
     */

    public function store(CreateTaskRequest $request): RedirectResponse
    {
        $task = new Task();
        $task->title = $request->input('title');
        $task->content = $request->input('content');

        //upload file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('images', 'public');
            $task->image = $path;
        }

        $task->due_date = $request->input('due_date');
        $task->save();

        //dung session de dua ra thong bao
        Session::flash('success', 'Tạo mới thành công');
        //tao moi xong quay ve trang danh sach task
        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|Response
     */
    public function edit(int $id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $task = Task::findOrFail($id);
        $task->title = $request->input('title');
        $task->content = $request->input('content');

        //cap nhat anh
        if ($request->hasFile('image')) {

            //xoa anh cu neu co
            $currentImg = $task->image;
            if ($currentImg) {
                Storage::delete('/public/' . $currentImg);
            }
            // cap nhat anh moi
            $image = $request->file('image');
            $path = $image->store('images', 'public');
            $task->image = $path;
        }

        $task->due_date = $request->input('due_date');
        $task->save();

        //dung session de dua ra thong bao
        Session::flash('success', 'Cập nhật thành công');
        //tao moi xong quay ve trang danh sach task
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $task = Task::findOrFail($id);
        $image = $task->image;

        //delete image
        if ($image) {
            Storage::delete('/public/' . $image);
        }

        $task->delete();

        //dung session de dua ra thong bao
        Session::flash('success', 'Xóa thành công');
        //xoa xong quay ve trang danh sach task
        return redirect()->route('tasks.index');
    }
}
