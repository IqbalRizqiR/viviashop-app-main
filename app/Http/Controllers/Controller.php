<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $data = [];
	protected $uploadsFolder = 'uploads/';

	protected $rajaOngkirApiKey = null;
	protected $rajaOngkirBaseUrl = null;
	protected $rajaOngkirOrigin = null;
	protected $couriers = [
		'jne' => 'JNE',
		'sicepat' => 'SiCepat',
		'jnt' => 'J&T Express',
		'tiki' => 'TIKI',
		'pos' => 'Pos Indonesia',
		'anteraja' => 'AnterAja',
		'wahana' => 'Wahana',
	];

    protected $provinces = [];

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->rajaOngkirApiKey = config('ongkir.api_key');
		$this->rajaOngkirBaseUrl = config('ongkir.base_url');
		$this->rajaOngkirOrigin = config('ongkir.origin');
	}
    /**
	 * Raja Ongkir Request (Shipping Cost Calculation)
	 *
	 * @param string $resource resource url
	 * @param array  $params   parameters
	 * @param string $method   request method
	 *
	 * @return array
	 */
	protected function rajaOngkirRequest($resource, $params = [], $method = 'GET')
    {
        $this->rajaOngkirBaseUrl = config('ongkir.base_url', 'https://api.binderbyte.com');
        $this->rajaOngkirApiKey = config('ongkir.api_key');
        
        if (empty($this->rajaOngkirBaseUrl)) {
            Log::error('Binderbyte base URL is not set');
            throw new \Exception('Binderbyte base URL is not configured properly.');
        }

        if (empty($this->rajaOngkirApiKey)) {
            Log::error('Binderbyte API key is not set');
            throw new \Exception('Binderbyte API key is not configured properly.');
        }

        $client = new \GuzzleHttp\Client(['verify' => false]);

        if (!str_starts_with($resource, '/')) {
            $resource = '/' . $resource;
        }

        $params['api_key'] = $this->rajaOngkirApiKey;
        
        if ($method == 'GET') {
            $query = '?' . http_build_query($params);
            $url = $this->rajaOngkirBaseUrl . $resource . $query;
            $requestParams = [];
        } else {
            $url = $this->rajaOngkirBaseUrl . $resource;
            $requestParams = ['form_params' => $params];
        }

        try {
            $response = $client->request($method, $url, $requestParams);
            $responseBody = $response->getBody()->getContents();
            $data = json_decode($responseBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from RajaOngkir API');
            }

            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('RajaOngkir API request failed', [
                'url' => $url,
                'method' => $method,
                'params' => $params,
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw $e;
        }
    }

    /**
	 * Get provinces
	 *
	 * @return array
	 */
	protected function getProvinces()
	{
		$provinceFile = 'provinces.txt';
		$provinceFilePath = $this->uploadsFolder. 'files/' . $provinceFile;

		$isExistProvinceJson = Storage::disk('local')->exists($provinceFilePath);

		if (!$isExistProvinceJson) {
			$response = $this->rajaOngkirRequest('/wilayah/provinsi');
			
			// Handle different API response formats
			$results = [];
			if (isset($response['rajaongkir']['results'])) {
				// Old RajaOngkir format
				$results = $response['rajaongkir']['results'];
			} elseif (isset($response['value']) && is_array($response['value'])) {
				// Binderbyte format
				$results = $response['value'];
			} elseif (is_array($response) && !isset($response['rajaongkir'])) {
				// Direct array response
				$results = $response;
			}
			
			Storage::disk('local')->put($provinceFilePath, serialize($results));
		}

		$province = unserialize(Storage::get($provinceFilePath));

		$provinces = [];
		if (!empty($province)) {
			foreach ($province as $prov) {
				// Handle different field names
				$provinceId = $prov['province_id'] ?? $prov['id'] ?? $prov['kode'];
				$provinceName = $prov['province'] ?? $prov['nama'] ?? $prov['name'];
				$provinces[$provinceId] = strtoupper($provinceName);
			}
		}

        return $provinces;
	}

	/**
	 * Get cities by province ID
	 *
	 * @param int $provinceId province id
	 *
	 * @return array
	 */
	protected function getCities($provinceId)
	{
		$cityFile = 'cities_at_'. $provinceId .'.txt';
		$cityFilePath = $this->uploadsFolder. 'files/' .$cityFile;

		$isExistCitiesJson = Storage::disk('local')->exists($cityFilePath);

		if (!$isExistCitiesJson) {
			$response = $this->rajaOngkirRequest('/wilayah/kabupaten', ['id_provinsi' => $provinceId]);
			
			// Handle different API response formats
			$results = [];
			if (isset($response['rajaongkir']['results'])) {
				// Old RajaOngkir format
				$results = $response['rajaongkir']['results'];
			} elseif (isset($response['value']) && is_array($response['value'])) {
				// Binderbyte format
				$results = $response['value'];
			} elseif (is_array($response) && !isset($response['rajaongkir'])) {
				// Direct array response
				$results = $response;
			}
			
			Storage::disk('local')->put($cityFilePath, serialize($results));
		}

		$cityList = unserialize(Storage::get($cityFilePath));

		$cities = [];
		if (!empty($cityList)) {
			foreach ($cityList as $city) {
				// Handle different field names
				$cityId = $city['city_id'] ?? $city['id'] ?? $city['kode'];
				$cityName = $city['city_name'] ?? $city['nama'] ?? $city['name'];
				$cityType = $city['type'] ?? '';
				$displayName = $cityType ? strtoupper($cityType.' '.$cityName) : strtoupper($cityName);
				$cities[$cityId] = $displayName;
			}
		}

        return $cities;
	}

	/**
	 * Get districts by city ID
	 *
	 * @param int $cityId city id
	 *
	 * @return array
	 */
	protected function getDistricts($cityId)
	{
		try {
			require_once base_path('rajaongkir_komerce.php');
			$rajaOngkir = new \RajaOngkirKomerce();
			$response = $rajaOngkir->getDistricts($cityId);
			
			$districts = [];
			if (is_array($response)) {
				foreach ($response as $district) {
					// Handle different field names from API
					$districtId = $district['subdistrict_id'] ?? $district['id'] ?? $district['kode'];
					$districtName = $district['subdistrict_name'] ?? $district['nama'] ?? $district['name'];
					$districts[$districtId] = $districtName;
				}
			}
			
			return $districts;
		} catch (\Exception $e) {
			Log::error('Error fetching districts: ' . $e->getMessage());
			return [];
		}
	}

		/**
	 * Get shipping cost - DEPRECATED: Use OrderController's shippingCostRequest instead
	 * This method was for RajaOngkir's shipping costs, but Binderbyte only provides address data
	 */
	protected function getShippingCost($destination, $weight)
    {
        // This method is deprecated since Binderbyte doesn't provide shipping costs
        // Use OrderController's _getShippingCost method instead which uses komerce.id API
        return [];
    }
}
