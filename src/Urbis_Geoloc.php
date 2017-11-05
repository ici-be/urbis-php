<?php
class Urbis_Geoloc
{ 
  private function createJson(bool $structured = true, string $StreetName, ?string $StreetNumber = null, ?int $PostalCode = null, string $lang = "fr", int $crs = 31370)
  {
      if($structured) { $request = "http://geoservices.irisnet.be/localization/Rest/Localize/getaddressesfields?json=%7B%22language%22%3A%22".$lang."%22%2C%22address%22%3A%7B%22street%22%3A%7B%22name%22%3A%22".urlencode($StreetName)."%22%2C%22postcode%22%3A%22".$PostalCode."%22%7D%2C%22number%22%3A%22".$StreetNumber."%22%7D%2C%22spatialReference%22%3A%22".$crs."%22%7D"; }
      else {
        $request = "http://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?language=".$lang."&address=".urlencode($StreetName)."&spatialReference=".$crs;
      };
      return $request;
  }
  private function doRequest($request, string $lang = null, int $crs = null)
  {
      $client   = new GuzzleHttp\Client();
      $response = $client->request('GET', $request);
      
      $json = json_decode((string)$response->getBody());

        $this->_address = new stdClass();
        $this->_address->StreetName = new stdClass();
        $this->_address->StreetName->{$lang??$this->_call->lang}         = $json->result[0]->address->street->name;
        $this->_address->StreetNumber               = $json->result[0]->address->number;
        $this->_address->PostalCode                 = $json->result[0]->address->street->postCode;
        $this->_address->MunicipalityName->{$lang??$this->_call->lang}           = $json->result[0]->address->street->municipality;
        $this->_address->coord = new stdClass();
        $this->_address->coord->$crs = new stdClass();
        $this->_address->coord->$crs->lat                  = $json->result[0]->point->y;
        $this->_address->coord->$crs->lon                  = $json->result[0]->point->x;
        $this->_address->coord->$crs->wkt = "POINT(".$json->result[0]->point->x." ".$json->result[0]->point->y.")";
  }
  public function getAddress_Structured(string $StreetName, ?string $StreetNumber = null, ?int $PostalCode = null, string $lang = "fr", int $crs = 31370)
  {
        $this->_call->lang    = $lang;
        $this->_call->crs     = $crs;
        $request              = $this->createJson(true, $StreetName, $StreetNumber, $PostalCode, $lang, $crs);
        $this->doRequest($request, $lang, $crs);
  }
  public function getAddress_Unstructured(string $Address, string $lang = "fr", int $crs = 31370)
  {
        $this->_call->lang    = $lang;
        $this->_call->crs     = $crs;     
        $request              = $this->createJson(false, $Address, null, null, $lang, $crs);
        $this->doRequest($request, $lang, $crs);
  }

  public function getUrl_from_address(string $StreetFR = null, ?string $StreetNumber = null, ?int $PostalCode = null)
    {
        $StreetNumber = trim(strtok(strtok(strtok($StreetNumber??$this->_address->StreetNumber, "-"),";"),","));
        $PostalCode = $PostalCode??$this->_address->PostalCode;
        $StreetFR = $StreetFR??$this->_address->StreetName->fr;
        if($PostalCode!="" && $StreetNumber!="") 
        {
            $this->_address->url = $PostalCode.'::'.urlencode(utf8_decode($StreetFR)).'::'.str_replace("/", "%3A", $StreetNumber);
        };
    }

  public function getAllAddress()
  {
      return $this->_address??null;
  }

  public function getPostalCode()
  {
      return $this->_address->PostalCode??null;
  } 

  public function getWKTpoint(int $crs = null)
  {
      return $this->_address->coord->{$crs??$this->_call->crs}->wkt??null;
  }

   public function getStructuredAddress(string $lang = null)
  {
          $data['StreetName'][$lang??$this->_call->lang]        = $this->_address->StreetName->{$lang??$this->_call->lang};
          $data['StreetNumber']             = $this->_address->StreetNumber;
          $data['PostalCode']               = $this->_address->PostalCode;
          $data['MunicipalityName'][$lang??$this->_call->lang]  = $this->_address->MunicipalityName->{$lang??$this->_call->lang};     
          return $data;
  }
   public function getGeographicalLocation(int $crs = null)
  {
         return ["lat" => $this->_address->coord->{$crs??$this->_call->crs}->lat, "lon" => $this->_address->coord->{$crs??$this->_call->crs}->lon];
  }
}
?>
