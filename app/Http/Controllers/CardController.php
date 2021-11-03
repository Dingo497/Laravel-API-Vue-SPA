<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Traits\CrudListsCards;

class CardController extends Controller
{
  use CrudListsCards;

  /**
   * Display a cards of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
   // Get cards
    $limit = request('limit');
    $cards = Card::paginate($limit);
    $cards = $cards->toArray();

    // Send Json
    if( ! $cards ) {
      return $this->sendErrorMessage('Nothing Found');
    } else {
      $cards['data'] = $this->transformAll($cards['data']);
      return $this->sendSuccessMessage($cards, 'success');
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
    if(!request('todo_list_id')){
      return $this->sendErrorMessage('You dont have todo_list_id');
    }

    // Create Card
    Card::create($request->all());
    return $this->sendSuccessMessage(null, 'Your list was created');
  }




  /**
   * Display the specified resource.
   *
   * @param  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    // Get card
    $card = Card::find($id);
    
    // Send Json
    if( ! $card ) {
      return $this->sendErrorMessage('Nothing Found');
    } else {
      return $this->sendSuccessMessage($this->transformOne($card), 'success');
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
    $card = Card::find($id);

    // Validation
    $validation = $this->validateRequest(request('title'));
    if($validation !== true) {
      return $validation;
    }
    if( !$card ) {
      return $this->sendErrorMessage('Nothing Found');
    }
    if(!request('todo_list_id')){
      return $this->sendErrorMessage('You dont have todo_list_id');
    }
    
    // Update card
    $card->update( $request->all() );
    return $this->sendSuccessMessage(null, 'Your list was updated');
  }




  /**
   * Remove the specified resource from storage.
   * @param  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $card = Card::find($id);

    // Validation
    if( !$card ) {
      return $this->sendErrorMessage('Nothing Found');
    }

    // Delete card
    $card->delete();
    return $this->sendSuccessMessage(null, 'Your card was deleted');
  }


}