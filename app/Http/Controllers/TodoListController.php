<?php

namespace App\Http\Controllers;

// Models
use App\Models\TodoList;
use Illuminate\Http\Request;
use App\Http\Traits\CustomMethods;


class TodoListController extends Controller
{
  use CustomMethods;

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $lists = TodoList::with('cards')->get();
    $lists = $lists->toArray();

    if( ! $lists ) {
      return $this->sendErrorMessage('Nothing Found');
    } else {
      $lists = $this->transformAll($lists);
      return $this->sendSuccessMessage($lists, 'success', count($lists));
    }
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $validation = $this->validateRequest(request('title'));
    if($validation !== true) {
      return $validation;
    }

    TodoList::create($request->all());
    return $this->sendSuccessMessage(null, 'Your list has been created');
  }



  /**
   * Display the specified resource.
   *
   * @param  integer $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $list = TodoList::find($id);
    
    if( ! $list ) {
      return $this->sendErrorMessage('Nothing Found');
    } else {
      return $this->sendSuccessMessage($this->transformOne($list), 'success');
    }
  }


  
  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param integer $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $list = TodoList::find($id);

    $validation = $this->validateRequest(request('title'));
    if($validation !== true) {
      return $validation;
    }
    if( !$list ) {
      return $this->sendErrorMessage('Nothing Found');
    }
    
    $list->update( $request->all() );
    return $this->sendSuccessMessage(null, 'Your list has been updated');
  }



  /**
   * Remove the specified resource from storage.
   * @param  integer $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $list = TodoList::find($id);

    if( !$list ) {
      return $this->sendErrorMessage('Nothing Found');
    }

    $list->delete();
    return $this->sendSuccessMessage(null, 'Your list has been deleted');
  }

}
