<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

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
	public function tags($pure = false)
	{
		$tags = Redis::executeRaw(['keys', 'note:tag:*']);
		if ($pure)
			return $tags;
		return response()->json(array_map(function($t){ return str_replace('note:tag:','',$t);}, $tags));
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
		$tag = $request->get('tag');
		$note = $request->get('note');
		$mark = $request->get('mark');
		
		$created = time();
		$insertId = DB::table('note')->insertGetId(['note'=>$note, 'mark'=>$mark, 'created'=>$created]);
		if ($insertId) {
			
			if ('' != $tag) {
				$tags = explode(',', $tag);
				foreach ($tags as $tag) {
					Redis::executeRaw(['sadd', 'note:tag:'.$tag, $insertId]);
				}
			}
			return response('添加成功');
		} else {
			return response('添加失败');
		}
	}

	public function edit($id, Request $request)
	{
		$tag = $request->get('tag');
		if ('' != $tag) {
			$tags = explode(',', $tag);
			foreach ($tags as $tag) {
				Redis::executeRaw(['sadd', 'note:tag:'.$tag, $id]);
			}
		}
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
		$tags = $this->tags(true);
		$arr = [];
		foreach ($tags as $tag) {
			if (Redis::executeRaw(['sismember', 'note:tag:'.$tag, $id])) {
				array_push($arr, $tag);
			}
		}
		$data->tag = implode(',', $arr);
		return response()->json($data);
	}
	public function search(Request $request)
	{
		//$isok = Redis::exists('tag_linux');

		$tag = $request->get('tag');
		$wrappedTag = 'note:tag:'.$tag;
		if ('' != $tag && Redis::exists($wrappedTag)) {
			$note_ids = Redis::executeRaw(['smembers', $wrappedTag]);
		}
		$kw = $request->get('kw');
		$data = DB::table('note')
			->select('id', 'note', 'mark');
		
		if (!empty($note_ids)) {
			$data = $data->whereIn('id', $note_ids);
		}
		if ('' != $kw) {
			$data = $data->where('note', 'like', "%$kw%");
		}
		
		return response()->json($data->get());
	}
}
