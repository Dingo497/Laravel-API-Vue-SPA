<?php

namespace App\Http\Traits;

trait CustomMethods {
  
  /**
   * Send Success Data/message in Json
   * @param $data
   * @param $message
   * @param $count
   * @return Response
   */
  private function sendSuccessMessage($data = null, $message, $count = null)
  {
    return response()->json([
      'status' => [
        'message' => $message
      ],
      'count' => $count,
      'body' => $data
    ], 200);
  }



  /**
   * Send Error message in json
   * @param string $message
   * @param integer $code
   * @return Response
   */
  private function sendErrorMessage($message, $code = 404)
  {
    return response()->json([
      'status' => [
        'message' => $message
      ],
    ], $code);
  }



  /**
   * Transform database column into different API column
   * @param array $data
   * @return array
   */
  private function transformOne($data)
  {
    if(isset($data['cards'])){
      return [
        'id'    => $data['id'],
        'name'  => $data['title'],
        'created_at' => $data['created_at'],
        'cards' => $data['cards'],
      ];
    } else {
      return [
        'id'    => $data['id'],
        'name'  => $data['title'],
        'created_at' => $data['created_at'],
      ];
    }
  }



  /**
   * Transform database columns into different API columns
   * (more data)
   * @param array $data
   * @return array
   */
  private function transformAll($data)
  {
    return array_map([$this, 'transformOne'], $data);
  }



  /**
   * Validation of insert/update Todocard
   * @param string $title
   * @param string $description
   * @return bool|Response
   */
  private function validateRequest($title)
  {
    if(strlen($title) <= 3){
      return $this->sendErrorMessage('Title is too short or empty');
    } else 
    if (strlen($title) >= 200){
      return $this->sendErrorMessage('Your title is too long');
    }
    return true;
  }

}