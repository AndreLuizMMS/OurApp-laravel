<?php

namespace App\Http\Controllers;

use App\Events\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller {

    public function displayChat(Request $req) {
        $formFields = $req->validate([
            'textValue' => 'required',
        ]);

        if (!trim(stripTags($formFields['textValue']))) {
            return response()->noContent();
        }

        broadcast(new ChatMessage([
            'username' => auth()->user()->username,
            'textValue' => stripTags($req->textValue),
            'avatar' => auth()->user()->getAvatar
        ]))->toOthers();

        return response()->noContent();
    }
}
