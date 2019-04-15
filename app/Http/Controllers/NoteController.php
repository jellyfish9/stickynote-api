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
		$note = $request->get('note');
		$mark = $request->get('mark');
		DB::table('note')->insert();
	}
}
