class Urbis_Geoloc
{ 
  private function createJson(bool $structured = true, string $StreetName, ?string $StreetNumber = null, ?int $PostalCode = null, string $lang = "fr", int $crs = 31370)
  {
      $request = "http://geoservices.irisnet.be/localization/Rest/Localize/getaddressesfields?json=%7B%22language%22%3A%22".$lang."%22%2C%22address%22%3A%7B%22street%22%3A%7B%22name%22%3A%22".urlencode($StreetName)."%22%2C%22postcode%22%3A%22".$PostalCode."%22%7D%2C%22number%22%3A%22".$StreetNumber."%22%7D%2C%22spatialReference%22%3A%22".$crs."%22%7D";
      echo $request;
      return $request;
  }

  private function doRequest($request)
  {
      $client   = new GuzzleHttp\Client();
      $response = $client->request('GET', $request);
      
      $data = json_decode((string)$response->getBody());
      return $data;
  }

  public function getAddress_Structured(string $StreetName, ?string $StreetNumber = null, ?int $PostalCode = null, string $lang = "fr", int $crs = 31370)
  {
      $request        = $this->createJson(true, $StreetName, $StreetNumber, $PostalCode, $lang, $crs);
      $this->_address = $this->doRequest($request);
  }

  public function getAllAddress()
  {
      return $this->_address??null;
  }

   public function getGeographicalLocation()
  {

         $data['lat'] = $this->_address->result[0]->point->y;
         $data['lon'] = $this->_address->result[0]->point->x;
         return $data;
  }

}  
