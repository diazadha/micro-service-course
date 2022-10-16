<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;

class CourseController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'required|integer'
        ];

        $data = $request->all(); //Mengambil data dari body

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentorId = $request->input("mentor_id");
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function show($id)
    {
        $course = Course::with('chapters.lesson')
            ->with('mentor')
            ->with('images')
            ->find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found',
            ]);
        }

        $reviews = Review::where('course_id', '=', $id)->get()->toArray();

        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);

            if ($users['status'] === 'error') {
                $reviews = [];
            } else {
                foreach ($reviews as $key => $review) {
                    $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                    $reviews[$key]['users'] = $users["data"][$userIndex];
                }
            }
        }

        $totalVideos = Chapter::where('course_id', '=', $id)->withCount('lesson')->get()->toArray();

        $finalTotalVideos = array_sum(array_column($totalVideos, 'lesson_count'));

        $totalStudent = MyCourse::where('course_id', '=', $id)->count();

        $course['reviews'] = $reviews;
        $course['totalStudent'] = $totalStudent;
        $course['totalVideos'] = $finalTotalVideos;

        return response()->json([
            'status' => 'success',
            'data' => $course,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string|url',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'integer'
        ];

        $data = $request->all(); //Mengambil data dari body

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $mentorId = $request->input("mentor_id");
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);

            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'mentor not found'
                ], 404);
            }
        }

        $course->fill($data);
        $course->save();

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function getAll(Request $request)
    {
        $courses = Course::query();


        if (!$courses) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $name = $request->query('name');
        $status = $request->query('status');

        $courses->when($name, function ($query) use ($name) {
            return $query->whereRaw("name LIKE '%" . strtolower($name) . "%'");
        });

        $courses->when($status, function ($query) use ($status) {
            return $query->whereRaw("status LIKE '%" . strtolower($status) . "%'");
        });
        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10)
        ]);
    }

    public function delete($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $course->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'course deleted'
        ]);
    }
}
