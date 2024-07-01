<?php

namespace App\Http\Controllers\Api;

use App\Models\TaskHeader;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class TaskHeaderController extends Controller
{
    public function index()
    {
        $query = TaskHeader::query();

        if (request()->has('is_active') && (request()->is_active == 1 || request()->is_active == 0)) {
            $query->where('is_active', request()->is_active);
        }

        if (!empty(request()->status)) {
            $query->where('status', request()->status);
        }

        $headers = $query->get();

        return new TaskResource(true, 'List Data Header', $headers);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'task_name'   => 'required',
            'start_date'  => 'required',
            'due_date'    => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create header
        $header = TaskHeader::create([
            'task_name'     => $request->task_name,
            'status'        => 'Open',
            'start_date'    => $request->start_date,
            'due_date'      => $request->due_date,
            'is_active'     => true,
        ]);

        //return response
        return new TaskResource(true, 'Data Header Berhasil Ditambahkan!', $header);
    }

    public function show($id)
    {
        //find header by ID
        $header = TaskHeader::find($id);

        //return single header as a resource
        return new TaskResource(true, 'Detail Data Task Header!', $header);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'task_name'   => 'required',
            'start_date'  => 'required',
            'due_date'    => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find header by ID
        $header = TaskHeader::find($id);

        //update header
        $header->update([
            'task_name'     => $request->task_name,
            'status'        => $header->status,
            'start_date'    => $request->start_date,
            'due_date'      => $request->due_date,
            'is_active'     => $header->is_active,
        ]);

        //return response
        return new TaskResource(true, 'Data Task Header Berhasil Diubah!', $header);
    }

    public function destroy($id)
    {

        //find header by ID
        $header = TaskHeader::find($id);

        //soft delete header
        $header->update([
            'is_active' => false,
        ]);

        //return response
        return new TaskResource(true, 'Data Task Header Berhasil Dihapus!', null);
    }
}
