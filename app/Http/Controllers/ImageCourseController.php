<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageCourse;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'course_id' => 'required|integer',
            'image' => 'required|url',
        ];

        $data = $request->all();
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        };

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        };

        $imageCourse = ImageCourse::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $imageCourse,
        ]);
    }

    public function delete($id)
    {
        $imageCourse = ImageCourse::find($id);
        if (!$imageCourse) {
            return response()->json([
                'status' => 'error',
                'message' => 'image not found'
            ], 404);
        };
        $imageCourse->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'image deleted'
        ]);
    }
}