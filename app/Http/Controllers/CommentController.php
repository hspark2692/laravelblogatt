<?php namespace App\Http\Controllers;
use App/Posts;
use App/Comments;
use Rediret;
use App/Https/Requests;
use App/Https/Controllers/Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
  public function store (Request $request)
  {
    //post, user, body
    $input['from_user'] = $request->user()->id;
    $input['on_post'] = $request->input('on_post');
    $input['body'] = $request->input('body');
    $slug = $request->input('slug');
    Comments::create($input);
    return redirect($slug)->with('message', 'Comment published successfully');
  }
}
