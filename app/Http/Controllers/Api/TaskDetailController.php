<?php

namespace App\Http\Controllers\Api;

use App\Models\TaskDetail;
use App\Models\TaskHeader;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class TaskDetailController extends Controller
{
    public function index()
    {
        $query = TaskDetail::query();

        if (request()->has('is_active') && (request()->is_active == 1 || request()->is_active == 0)) {
            $query->where('is_active', request()->is_active);
        }

        if (!empty(request()->task_header_id)) {
            $query->where('task_header_id', request()->task_header_id);
        }

        $details = $query->get();

        //return collection of posts as a resource
        return new TaskResource(true, 'List Data Detail', $details);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'task_header_id' => 'required',
            'sub_task_name'  => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create detail
        $detail = TaskDetail::create([
            'task_header_id'     => $request->task_header_id,
            'sub_task_name'      => $request->sub_task_name,
            'is_ticked'          => false,
            'is_active'          => true,
        ]);

        //return response
        return new TaskResource(true, 'Data Detail Berhasil Ditambahkan!', $detail);
    }

    public function show($id)
    {
        //find header by ID
        $detail = TaskDetail::find($id);

        //return single header as a resource
        return new TaskResource(true, 'Detail Data Task Header!', $detail);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'task_header_id' => 'required',
            'sub_task_name'  => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find detail by ID
        $detail = TaskDetail::find($id);

        //update detail
        $detail->update([
            'task_header_id'     => $request->task_header_id,
            'sub_task_name'        => $request->sub_task_name,
        ]);

        //return response
        return new TaskResource(true, 'Data Task Detail Berhasil Diubah!', $detail);
    }

    public function destroy($id)
    {
        //find header by ID
        $detail = TaskDetail::find($id);

        //soft delete detail
        $detail->update([
            'is_active' => false,
        ]);

        //return response
        return new TaskResource(true, 'Data Task Detail Berhasil Dihapus!', null);
    }

    public function ticked($id){
        $detail = TaskDetail::find($id);

        $detailsTicked = TaskDetail::where('task_header_id', '=', $detail->task_header_id, 'and')
            ->where('is_ticked', '=', 1, 'and')
            ->where('is_active', '=', 1)->get();

        $detail->update([
            'is_ticked' => true,
        ]);

        $listDetail = TaskDetail::where('task_header_id', '=', $detail->task_header_id, 'and')
            ->where('is_active', '=', 1)->get();

        $header = TaskHeader::where('id', $detail->task_header_id)->first();

        if (count($listDetail) == count($detailsTicked) + 1){
            $header->update([
                'status' => 'Complete'
            ]);
        }

        return new TaskResource(true, 'Sub Task Berhasil Diselesaikan!', $detail);
    }
}
