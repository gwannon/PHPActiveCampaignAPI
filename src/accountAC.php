<?php

namespace Gwannon\PHPActiveCampaignAPI;

use Gwannon\PHPActiveCampaignAPI\curlAC;

/*
 * TODO:
 *
 */

/* ----------------- CLASS accountAC -------------------- */
class accountAC {
  public $id;
  public $nombre;
  public $url;
  public $fields;

  public function __construct($id) {
    $response = false;
    if (is_numeric($id)) {
      $response = curlAC::curlCallGet("/accounts/".$id)->account;
    } else {
      $temp = curlAC::curlCallGet("/accounts?search=".urlencode($id));
      if(isset($temp->accounts[0])) $response = $temp->accounts[0];
    }

    if($response) {
      $this->id = $response->id;
      $this->nombre = $response->name;
      $this->url = $response->accountUrl;
      $this->fields = $this->getApiFields(); //Campos personalizados
    } else if (!is_numeric($id)) { //Si no existe y tenemos un nombre comercial lo creamos
      $response = createAccountAC($id);
      $this->id = $response->id;
      $this->nombre = $response->nombre;
      $this->url = $response->url;
      $this->fields = $response->getApiFields();
    }
  }

  //SETs --------------------------------	
  function setNombre($val) { $this->nombre = $val; }
  function setUrl($val) { $this->url = $val; }
  function setField($field_label, $value) { 
    $this->fields[$field_label] = $value;
  } 

  //UPDATEs --------------------------------
	function updateAccountAC() {
    global $accountFields;
    foreach ($accountFields as $field_label => $field_id) {
      if($this->fields[$field_label] != '') {
        $myfields[] = [
          "customFieldId" => $field_id,
          "fieldValue" => $this->fields[$field_label]
        ];
      }
    }
    $data['account'] = [
      'name'       => $this->nombre, 
			'accountUrl'    => $this->url,
      'fields' => $myfields
		];
    $response = curlAC::curlCallPut("/accounts/".$this->id, json_encode($data));
    return $response;
	}

  //APIs calls --------------------------------
  function getApiFields() {
    global $accountFields;
    $fields = curlAC::curlCallGet("/accounts/".$this->id."/accountCustomFieldData")->customerAccountCustomFieldData;
    foreach ($accountFields as $field_label => $field_id) {
      $currentfields[$field_label] = false;
      foreach ($fields as $field) {
        if ($field_id == $field->custom_field_id) {
          if (strpos($field_label, "_date")) $currentfields[$field_label] = $field->custom_field_date_value;
          else if (strpos($field_label, "_number")) $currentfields[$field_label] = $field->custom_field_number_value;
          else $currentfields[$field_label] = $field->custom_field_text_value;
          break;
        }
      }
    }
    return $currentfields;
  }

  public static function createAccountAC($name) {
    $data['account'] = [
      'name' => $name 
    ];
    $response = curlAC::curlCallPost("/accounts", json_encode($data))->account;
    /*echo "\n\nCREATE ACCOUNT-----------------\n";
    print_r($response);
    echo "--------------\n";*/
    $account = new AccountAC($response->id);
    return $account;
  }

  public static function getAllAccountFields ($max = 100) {
    $fields = array();
    foreach (curlAC::curlCallGet("/accountCustomFieldMeta?limit={$max}")->accountCustomFieldMeta as $field) {
      $fields[$field->id] = $field->fieldLabel;
    }
    return $fields;
  }
}
