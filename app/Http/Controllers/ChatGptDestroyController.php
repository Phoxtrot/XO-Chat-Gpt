<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatGptDestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Chat $chat)
    {
        $chat->delete();
        return to_route('chat');
    }
}
