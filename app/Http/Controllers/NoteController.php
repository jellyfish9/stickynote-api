<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Arr;

class NoteController extends Controller
{
	public function get_note_list()
    {
        $data = DB::table('note')
			->select(['id','note'])
			->orderBy('created', 'desc')
			->limit(10)
			->get();
        return response()->json($data);
    }
	public function add(Request $request)
	{
		//$request->validate(['note'=>'required|string','mark'=>'required|string']);
	
		/*
            ->withHeaders([
				'Access-Control-Allow-Origin' => '*',
				'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, OPTIONS',
				'Access-Control-Expose-Headers' => 'Authorization,authenticated',
				'Access-Control-Allow-Headers' => 'Origin, Content-Type, Cookie,X-CSRF-TOKEN, Accept,Authorization',
				'Access-Control-Allow-Credentials' => 'true',
				'Access-Control-Max-Age' => 86400,
			]);
		*/
		$note = $request->get('note');
		$mark = $request->get('mark');
		$created = time();
		$result = DB::table('note')->insert(['note'=>$note, 'mark'=>$mark, 'created'=>$created]);
		if ($result) {
			return response('添加成功');
		} else {
			return response('添加失败');
		}
	}

	public function edit($id, Request $request)
	{
		$note = $request->get('note');
		$mark = $request->get('mark');
		$result = DB::table('note')
			->where('id', $id)
			->update(['note'=>$note, 'mark'=>$mark]);
		if ($result) {
			return response('修改成功');
		}
	}

	public function show($id, Request $request)
	{
		//$id = $request->get('id');
		$data = DB::table('note')
			->select('note', 'mark')
			->where('id', $id)
			->first();
		return response()->json($data);
	}
}
