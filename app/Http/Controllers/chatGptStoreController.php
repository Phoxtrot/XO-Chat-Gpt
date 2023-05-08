<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatGptStoreRequest;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class chatGptStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ChatGptStoreRequest $request,String $id=null)
    {
        $messages = [];
        if($id){
            $chat = Chat::findOrFail($id);
            $messages = $chat->content;
        }
        $messages[]= [
            "role"=>"user",
            "content"=>$request->prompt
        ];

        $response = OpenAI::chat()->create([
            'model'=>"gpt-3.5-turbo",
            'messages'=> $messages
        ]);

        $messages[] = ["role"=>"assistant", "content"=>$response->choices[0]->message->content];
        $chat = Chat::updateOrCreate([
            'id'=>$id,
            "user_id"=>Auth::id(),
        ],[
            'content'=>$messages
        ]);
        return redirect()->route("chat",[$chat->id]);

    }
}
