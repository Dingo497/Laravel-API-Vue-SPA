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
    // Get Lists
    $lists = TodoList::with('cards')->get();
    $lists = $lists->toArray();

    // Send Json
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
    // Validation
    $validation = $this->validateRequest(request('title'));
    if($validation !== true) {
      return $validation;
    }

    // Create List
    TodoList::create($request->all());
    return $this->sendSuccessMessage(null, 'Your list has been created');
  }




  /**
   * Display the specified resource.
   *
   * @param  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    // Get List
    $list = TodoList::find($id);
    
    // Send Json
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
   * @param $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $list = TodoList::find($id);

    // Validation
    $validation = $this->validateRequest(request('title'));
    if($validation !== true) {
      return $validation;
    }
    if( !$list ) {
      return $this->sendErrorMessage('Nothing Found');
    }
    
    // Update List
    $list->update( $request->all() );
    return $this->sendSuccessMessage(null, 'Your list has been updated');
  }




  /**
   * Remove the specified resource from storage.
   * @param  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $list = TodoList::find($id);

    // Validation
    if( !$list ) {
      return $this->sendErrorMessage('Nothing Found');
    }

    // Delete List
    $list->delete();
    return $this->sendSuccessMessage(null, 'Your list has been deleted');
  }

}
