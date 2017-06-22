<?php namespace App\Http\Controllers;
use App/Posts;
use App/User;
use Redirect;
use App/Http/Controllers/Controller;
use App/Http/Requests/PostFormRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //


public function index()
{

//Latest 5 Posts
$posts = Posts::where('active', 1)->orderBy('created_at','desc')->paginate(5);
//Heading
$title = "Recent Posts";
//Return home.blade
return view('home')->withPosts($posts)->withTitle($title);


}

public function create(Request $request)

{
  //User Authorization
  if($request->user()->can_post())
  {
    return view ('posts.create');
  }
  else
  {
  return redirect('/')->withErrors('Please Log-In or Sign Up to submit a post');
  }
}

public function store(PostFormRequest $request)
{
  $post = new Posts();
  $post->title = $request->get('title');
  $post->body = $request->get('body');
  $post->slug = str_slug($post->title);
  $post->author_id = $request->user()->id;
  if($request->has('save'))

  {
    $post->active = 0;
    $message = 'Post saved Successfully';
  }
  else
  {
  $post->active = 1;
  $message = 'Post published successfully';
  }
  $post->save();
  return redirect('edit/'.$post->slug)->withMessage($message);
}

public function show($slug)
//single comment thread
{
  $post = Posts::where('slug',$slug)->first();
  if(!$post)
  {
    return redirect('/')->withErrors('requested page not found');
  }
  $comments = $post ->comments;
  return view ('posts.show')->withPost($post)->withComments($comments);
}

public function edit(Request $request, $slug)
//edit post function
{
  $post = Posts::where('slug',$slug)->first();
  if($post && ($request->user()->id == $post->author_id || $request-> user()->is_admin()))
  return view ('posts.edit')->with('post', $post);
  return redirect ('/')->withErrors('You do not have permission to edit this post');
}

public function update(Request $request)
{
  //update edited post serverside
  $post_id = $request->input('post_id');
  $post = Posts::find($post_id);
  if($post && ($post->author_id == $request->user()-> || $request->user()->is_admin()))

  {
    $title = $request->input('title');
    $slug = str_slug($title);
    $duplicate = Posts::where('slug', $slug)->first();
    if($duplicate)
    {
      if($duplicate->id != $post_id)
      {
        return redirect('edit/'. $post->slug)->withErrors('A post with this title already exists.')->withInput();
      }
      else
      {
      $post->slug = $slug;
      }
    }
    $post->title = $title;
    $post->body = $request->input('body');
    if($request->has('save'))
    {
      $post->active = 0;
      $message = 'Post saved successfully!';
      $landing = 'edit/'.$post->slug;
    }
    else {
      $post->active = 1;
      $message = 'Post Updated Successfully!';
      $landing = $post->slug;
    }
    $post->save();
      return redirect($landing)->withMessage($message);
  }
  else
    {
    return redirect('/')->withErrors('You do not have permission to edit this post.')
    }
  }

  public function destroy(Request $request, $id)
  {
    //deletes a post, admin and author priviledge only
    $post = Posts::find($id);
    if($post && ($post->author_id == $request->user()->id || $request->user()->is_admin()))
    {
      $post->delete();
      $data['message'] = 'Post deleted Successfully';
    }
    else
      {
        $data['errors'] = 'Invalid Request: You do not have the required permissions to delete this post';
      }
    return redirect('/')->with($data);
  }
}
