<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Tag(name="Projects", description="CRUD for portfolio projects")
 */
class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="List all projects",
     *     tags={"Projects"},
     *     @OA\Response(response=200, description="List of projects")
     * )
     */
    public function index()
    {
        return Project::orderBy('created_at', 'desc')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Create a new project",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","summary"},
     *                 @OA\Property(property="title", type="string", example="Portfolio Website"),
     *                 @OA\Property(property="summary", type="string", example="React + Laravel portfolio"),
     *                 @OA\Property(property="stack", type="string", example="React, Laravel"),
     *                 @OA\Property(property="description", type="string", example="This is a website"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="github", type="string", example="https://github.com/..."),
     *                 @OA\Property(property="website", type="string", example="https://portfolio.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Project created"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $project = new Project();
        $project->title = $request->title;
        $project->slug = Str::slug($request->title) . '-' . time();
        $project->summary = $request->summary;
        $project->stack = $request->stack;
        $project->description = $request->description;
        $project->github = $request->github;
        $project->website = $request->website;

        if ($request->hasFile('image')) {
            $project->image = $request->file('image')->store('projects', 'public');
        }

        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'project' => $project
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/projects/{id}",
     *     summary="Update a project",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="title", type="string"),
     *         @OA\Property(property="summary", type="string"),
     *         @OA\Property(property="description", type="string"),
     *         @OA\Property(property="stack", type="string"),
     *         @OA\Property(property="github", type="string"),
     *         @OA\Property(property="website", type="string")
     *     )),
     *     @OA\Response(response=200, description="Project updated"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $project = Project::findOrFail($id);
        $project->title = $request->title;
        $project->summary = $request->summary;
        $project->stack = $request->stack;
        $project->description = $request->description;
        $project->github = $request->github;
        $project->website = $request->website;

        if ($request->hasFile('image')) {
            $project->image = $request->file('image')->store('projects', 'public');
        }

        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'project' => $project
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     summary="Delete a project",
     *     tags={"Projects"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Project deleted"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->image) {
            \Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return response()->json(['success' => true, 'message' => 'Project deleted']);
    }

     /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Get a single project",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Project details"),
     *     @OA\Response(response=404, description="Project not found")
     * )
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return response()->json([
            'success' => true,
            'project' => $project
        ]);
    }

}
