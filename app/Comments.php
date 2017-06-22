<?php namespace App;
use Illuminate\Database\Eloquent\Model;
class Comments extends Model {
  //database comments table
  protected $guarded = [];
  //commented user
  public function author ()
  {
    return $this->belongsTo('App\User','from_user');
  }
  // returns any comment posts
  public function post()
  {
    return $this->belongsTo('App\Posts','on_post');
  }
}
