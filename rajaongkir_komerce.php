<?php

class RajaOngkirKomerce
{
    private $apiKey = 'Ho0D8T1Ebf59683c23db234aV2uzrSn6';
    private $baseUrl = 'https://rajaongkir.komerce.id/api/v1/';
    
    public function getProvinces()
    {
        $url = $this->baseUrl . 'destination/province';
        $response = $this->makeRequest($url);
        
        // Check if response has error
        if (isset($response['error'])) {
            return [];
        }
        
        $data = isset($response['data']) ? $response['data'] : [];
        
        // Convert to key-value pairs for dropdown compatibility
        $provinces = [];
        foreach ($data as $province) {
            $provinces[$province['id']] = strtoupper($province['name']);
        }
        
        return $provinces;
    }
    
    public function getCities($provinceId)
    {
        $url = $this->baseUrl . 'destination/city/' . $provinceId;
        $response = $this->makeRequest($url);
        
        // Check if response has error
        if (isset($response['error'])) {
            return [];
        }
        
        $data = isset($response['data']) ? $response['data'] : [];
        
        // Convert to key-value pairs for dropdown compatibility
        $cities = [];
        foreach ($data as $city) {
            $cities[$city['id']] = strtoupper($city['name']);
        }
        
        return $cities;
    }
    
    public function getDistricts($cityId)
    {
        $url = $this->baseUrl . 'destination/district/' . $cityId;
        $response = $this->makeRequest($url);
        
        // Check if response has error
        if (isset($response['error'])) {
            return [];
        }
        
        $data = isset($response['data']) ? $response['data'] : [];
        
        // Convert to key-value pairs for dropdown compatibility
        $districts = [];
        foreach ($data as $district) {
            $districts[$district['id']] = strtoupper($district['name']);
        }
        
        return $districts;
    }
    
    public function getSubdistricts($districtId)
    {
        $url = $this->baseUrl . 'destination/sub-district/' . $districtId;
        return $this->makeRequest($url);
    }
    
    public function calculateShippingCost($origin, $destination, $weight, $courier)
    {
        $url = $this->baseUrl . 'calculate/district/domestic-cost';
        $data = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ];
        $response = $this->makePostRequest($url, $data);
        
        if (isset($response['error'])) {
            return [];
        }
        
        if (!isset($response['data']) || !is_array($response['data'])) {
            return [];
        }
        
        $shippingOptions = [];
        
        foreach ($response['data'] as $service) {
            $shippingOptions[] = [
                'courier' => $service['code'] ?? 'unknown',
                'courier_name' => $service['name'] ?? 'Unknown Courier',
                'service' => $service['service'] ?? 'Unknown Service',
                'description' => $service['description'] ?? '',
                'cost' => $service['cost'] ?? 0,
                'etd' => $service['etd'] ?? ''
            ];
        }
        
        return $shippingOptions;
    }
    
    private function makeRequest($url)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'key: ' . $this->apiKey,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            curl_close($ch);
            return [
                'error' => true,
                'message' => 'cURL Error: ' . curl_error($ch)
            ];
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'error' => true,
                'message' => 'HTTP Error: ' . $httpCode,
                'response' => $response
            ];
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => true,
                'message' => 'JSON Decode Error: ' . json_last_error_msg(),
                'raw_response' => $response
            ];
        }
        
        return $decodedResponse;
    }
    
    private function makePostRequest($url, $data)
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'key: ' . $this->apiKey,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            curl_close($ch);
            return [
                'error' => true,
                'message' => 'cURL Error: ' . curl_error($ch)
            ];
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'error' => true,
                'message' => 'HTTP Error: ' . $httpCode,
                'response' => $response
            ];
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => true,
                'message' => 'JSON Decode Error: ' . json_last_error_msg(),
                'raw_response' => $response
            ];
        }
        
        return $decodedResponse;
    }
}

function getRajaOngkirShippingCosts($origin, $destination, $weight, $courier = 'jne')
{
    $rajaOngkir = new RajaOngkirKomerce();
    $result = $rajaOngkir->calculateShippingCost($origin, $destination, $weight, $courier);
    
    if (isset($result['error'])) {
        return [];
    }
    
    if (!isset($result['data']) || !is_array($result['data'])) {
        return [];
    }
    
    $shippingOptions = [];
    
    foreach ($result['data'] as $courier) {
        if (isset($courier['costs']) && is_array($courier['costs'])) {
            foreach ($courier['costs'] as $service) {
                $shippingOptions[] = [
                    'courier' => $courier['code'] ?? 'unknown',
                    'courier_name' => $courier['name'] ?? 'Unknown Courier',
                    'service' => $service['service'] ?? 'Unknown Service',
                    'description' => $service['description'] ?? '',
                    'cost' => $service['cost'][0]['value'] ?? 0,
                    'etd' => $service['cost'][0]['etd'] ?? ''
                ];
            }
        }
    }
    
    return $shippingOptions;
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    echo "Testing RajaOngkir Komerce API...\n\n";
    
    $rajaOngkir = new RajaOngkirKomerce();
    
    echo "1. Getting provinces...\n";
    $provinces = $rajaOngkir->getProvinces();
    
    if (isset($provinces['data']) && is_array($provinces['data'])) {
        echo "Found " . count($provinces['data']) . " provinces\n";
        echo "First province structure:\n";
        print_r($provinces['data'][0]);
        
        $javaTimurId = null;
        foreach ($provinces['data'] as $province) {
            $provinceName = $province['province_name'] ?? $province['name'] ?? '';
            if (strpos(strtolower($provinceName), 'jawa timur') !== false) {
                $javaTimurId = $province['province_id'] ?? $province['id'];
                echo "Found Jawa Timur with ID: " . $javaTimurId . "\n";
                break;
            }
        }
        
        if ($javaTimurId) {
            echo "\n2. Getting cities in Jawa Timur...\n";
            $cities = $rajaOngkir->getCities($javaTimurId);
            
            if (isset($cities['data']) && is_array($cities['data'])) {
                echo "Found " . count($cities['data']) . " cities\n";
                echo "First city structure:\n";
                print_r($cities['data'][0]);
                
                $jombangId = null;
                foreach ($cities['data'] as $city) {
                    $cityName = $city['name'] ?? $city['city_name'] ?? '';
                    if (strpos(strtolower($cityName), 'jombang') !== false) {
                        $jombangId = $city['id'] ?? $city['city_id'];
                        echo "Found Jombang with ID: " . $jombangId . "\n";
                        break;
                    }
                }
                
                if ($jombangId) {
                    echo "\n3. Getting districts in Jombang...\n";
                    $districts = $rajaOngkir->getDistricts($jombangId);
                    
                    if (isset($districts['data']) && is_array($districts['data'])) {
                        echo "Found " . count($districts['data']) . " districts\n";
                        if (count($districts['data']) > 0) {
                            $firstDistrict = $districts['data'][0];
                            $districtName = $firstDistrict['name'] ?? $firstDistrict['district_name'] ?? 'Unknown';
                            $districtId = $firstDistrict['id'] ?? $firstDistrict['district_id'] ?? 'Unknown';
                            echo "First district: " . $districtName . " (ID: " . $districtId . ")\n";
                        }
                    }
                }
            }
        }
    } else {
        echo "Error getting provinces:\n";
        print_r($provinces);
    }
}
?>