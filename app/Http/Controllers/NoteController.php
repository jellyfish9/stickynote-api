<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
	public function get_note_list()
    {
        $data = DB::table('note')
			->select('title')
			->orderBy('created', 'desc')
			->limit(10)
			->get();
        return response()->json($data);
    }
	public function add(Request $request)
	{
		//$request->validate(['note'=>'required|string','mark'=>'required|string']);
		$note = $request->get('note');
		$mark = $request->get('mark');
		$created = time();
		$result = DB::table('note')->insert(['note'=>$note, 'mark'=>$mark, 'created'=>$created]);
		if ($result)
			return response('添加成功')
            ->withHeaders([
				'Access-Control-Allow-Origin' => 'http://note.coolhand.vip',
				'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
				'Access-Control-Allow-Headers' => 'Origin, Content-Type',
				'Access-Control-Max-Age' => 86400,
			]);
	}

	public function edit(Request $request)
	{
	}

	public function show(Request $request)
	{
	}
}
