<?php namespace App/Http/Requests;
use App/Http/Requests/Request;
use App/User;
use Auth;
class PostFormRequest extends Request
{
//Determine user priviledges

public function authorize()
{
  if($this->user()->can_post())
  {
    return true;
  }
  return false;
}

//Required validation rules

public function rules()
{
  return [
    'title' => 'required|unique:posts|max:255',
    'title' => array('Regex:/^[A-Za-z0-9 ]+$/'),
    'body' => 'required',
  ];
}
}
