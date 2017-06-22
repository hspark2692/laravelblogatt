<?php namespace App;
use Illuminate\Database\Eloquent\Model;
//Refernce of Posts to Database Table
class Posts extends Model{
  //column mofidication restriction
  protected $guarded = [];
  //Post Thread
  //return all thread comments
  public function comments()
  {
    return $this->hasMany('App\Comments','on_post');
  }
  //returns author of post
  public function author()
  {
    return $this->belongsTo('App\User','author_id');
    
  }
}
