<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Contact", description="Contact form endpoints")
 */
class ContactController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/contact",
     *     summary="Send a contact message",
     *     tags={"Contact"},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"name","email","message"},
     *         @OA\Property(property="name", type="string", example="John Doe"),
     *         @OA\Property(property="email", type="string", example="john@example.com"),
     *         @OA\Property(property="message", type="string", example="Hello! I like your portfolio.")
     *     )),
     *     @OA\Response(response=200, description="Message sent")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email|max:150',
            'message'=>'required|string|min:10'
        ]);

        $contact = ContactMessage::create($request->all());

        return response()->json(['success'=>true, 'message'=>'Message sent successfully', 'data'=>$contact]);
    }

    /**
     * @OA\Get(
     *     path="/api/contact",
     *     summary="Admin: List all contact messages",
     *     tags={"Contact"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of messages")
     * )
     */
    public function index()
    {
        return ContactMessage::orderBy('created_at','desc')->get();
    }
}
