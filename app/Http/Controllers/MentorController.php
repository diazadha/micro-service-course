<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Mentor;

class MentorController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'profile' => 'required',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];

        $data = $request->all(); //Mengambil data dari body

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email'
        ];

        $data = $request->all(); //Mengambil data dari body

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        };
        $mentor->fill($data);
        $mentor->save();

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }

    public function getAll()
    {

        $mentors = Mentor::all();

        if (!$mentors) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        };

        return response()->json([
            'status' => 'success',
            'data' => $mentors
        ]);
    }

    public function show($id)
    {
        $mentor = Mentor::find($id);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                "message" => 'mentor not found'
            ], 404);
        };

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    }

    public function delete($id)
    {
        $mentor = Mentor::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        };

        $mentor->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'mentor deleted'
        ]);
    }
}
