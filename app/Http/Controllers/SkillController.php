<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;

use App\Models\Skill;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Skills",
 *     description="CRUD for skills"
 * )
 */
class SkillController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/skills",
     *     operationId="getSkills",
     *     summary="Get all skills",
     *     tags={"Skills"},
     *     @OA\Response(
     *         response=200,
     *         description="List of skills",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="category", type="string"),
     *                 @OA\Property(property="level", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Skill::orderBy('category')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/skills",
     *     operationId="createSkill",
     *     summary="Create a new skill",
     *     tags={"Skills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="React"),
     *             @OA\Property(property="category", type="string", example="Frontend"),
     *             @OA\Property(property="level", type="integer", example=90)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="skill", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'level' => 'nullable|integer|min:0|max:100'
        ]);

        $skill = Skill::create($request->all());

        return response()->json(['success' => true, 'message' => 'Skill created', 'skill' => $skill]);
    }

    /**
     * @OA\Put(
     *     path="/api/skills/{id}",
     *     operationId="updateSkill",
     *     summary="Update a skill",
     *     tags={"Skills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="React"),
     *             @OA\Property(property="category", type="string", example="Frontend"),
     *             @OA\Property(property="level", type="integer", example=90)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="skill", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        $skill->update($request->only(['name','category','level']));
        return response()->json(['success' => true, 'message' => 'Skill updated', 'skill' => $skill]);
    }

    /**
     * @OA\Delete(
     *     path="/api/skills/{id}",
     *     operationId="deleteSkill",
     *     summary="Delete a skill",
     *     tags={"Skills"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();
        return response()->json(['success' => true, 'message' => 'Skill deleted']);
    }
}
