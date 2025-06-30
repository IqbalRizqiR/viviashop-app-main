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
		// 'pos' => 'POS Indonesia',
		'tiki' => 'Titipan Kilat'
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
	 * @return json
	 */
	protected function rajaOngkirRequest($resource, $params = [], $method = 'GET')
    {
        $this->rajaOngkirBaseUrl = 'https://api.rajaongkir.com/starter';
        $this->rajaOngkirApiKey = config('ongkir.api_key');
        // Check if base URL is available
        if (empty($this->rajaOngkirBaseUrl)) {
            Log::error('RajaOngkir base URL is not set');
            throw new \Exception('RajaOngkir base URL is not configured properly.');
        }

        // Check if API key is available
        if (empty($this->rajaOngkirApiKey)) {
            Log::error('RajaOngkir API key is not set');
            throw new \Exception('RajaOngkir API key is not configured properly.');
        }

        $client = new \GuzzleHttp\Client(['verify' => false]);

        $headers = ['key' => $this->rajaOngkirApiKey];
        $requestParams = [
            'headers' => $headers,
        ];

        if (!str_starts_with($resource, '/')) {
            $resource = '/' . $resource;
        }

        $url = $this->rajaOngkirBaseUrl . $resource;

        // Log request information for debugging
        // Log::debug('RajaOngkir request', [
        //     'url' => $url,
        //     'method' => $method,
        //     'params' => $params
        // ]);
        if ($method == 'POST') {
            // For POST requests, add form params to request body
            $requestParams['form_params'] = $params;
        } else if ($method == 'GET' && !empty($params)) {
            // For GET requests, add params to URL as query string
            $query = is_array($params) ? '?'.http_build_query($params) : '';
            $url = $this->rajaOngkirBaseUrl . $resource . $query;
        }

        // Make the request
        $response = $client->request($method, $url, $requestParams);
        $responseBody = $response->getBody()->getContents();

        // Log response for debugging
        Log::debug('RajaOngkir response', [
            'status' => $response->getStatusCode(),
            'body' => substr($responseBody, 0, 300) . '...' // Log first 300 chars to avoid huge logs
        ]);

        // Parse and return response
        return json_decode($responseBody, true);


        // try {

        // } catch (\GuzzleHttp\Exception\RequestException $e) {
        //     // Log detailed error information
        //     if ($e->hasResponse()) {
        //         $errorBody = $e->getResponse()->getBody()->getContents();
        //         Log::error('RajaOngkir error response', [
        //             'status' => $e->getResponse()->getStatusCode(),
        //             'body' => $errorBody
        //         ]);
        //     } else {
        //         Log::error('RajaOngkir request failed: ' . $e->getMessage());
        //     }
        //     throw $e;
        // }
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
			$response = $this->rajaOngkirRequest('/province');
			Storage::disk('local')->put($provinceFilePath, serialize($response['rajaongkir']['results']));
		}

		$province = unserialize(Storage::get($provinceFilePath));

		$provinces = [];
		if (!empty($province)) {
			foreach ($province as $province) {
				$provinces[$province['province_id']] = strtoupper($province['province']);
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
			$response = $this->rajaOngkirRequest('/city', ['province' => $provinceId]);
			Storage::disk('local')->put($cityFilePath, serialize($response['rajaongkir']['results']));
		}

		$cityList = unserialize(Storage::get($cityFilePath));

		$cities = [];
		if (!empty($cityList)) {
			foreach ($cityList as $city) {
				$cities[$city['city_id']] = strtoupper($city['type'].' '.$city['city_name']);
			}
        }

		return $cities;
	}

	protected function getShippingCost($destination, $weight)
    {
        // Get origin from class property rather than config directly
        $origin = $this->rajaOngkirOrigin;

        // Ensure origin is set
        if (empty($origin)) {
            Log::error('RajaOngkir origin is not set');
            $origin = '501'; // Default to Jakarta as fallback (you can change this)
        }

        $params = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
        ];

        // Log params for debugging
        Log::info('RajaOngkir shipping cost parameters', $params);

        $results = [];
        foreach ($this->couriers as $code => $courier) {
            $params['courier'] = $code;

            // Log each courier request
            Log::info('RajaOngkir request for courier: ' . $code, $params);

            try {
                $response = $this->rajaOngkirRequest('/cost', $params, 'POST');

                // Log response
                Log::info('RajaOngkir response for ' . $code, [
                    'status' => $response['rajaongkir']['status'] ?? 'unknown',
                    'results_count' => count($response['rajaongkir']['results'] ?? [])
                ]);

                if (!empty($response['rajaongkir']['results'])) {
                    foreach ($response['rajaongkir']['results'] as $cost) {
                        if (!empty($cost['costs'])) {
                            foreach ($cost['costs'] as $costDetail) {
                                $serviceName = strtoupper($cost['code']) .' - '. $costDetail['service'];
                                $costAmount = $costDetail['cost'][0]['value'];
                                $etd = $costDetail['cost'][0]['etd'];

                                $result = [
                                    'service' => $serviceName,
                                    'cost' => $costAmount,
                                    'etd' => $etd,
                                    'courier' => $code,
                                ];

                                $results[] = $result;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('RajaOngkir request failed for courier ' . $code . ': ' . $e->getMessage());
            }
        }

        $response = [
            'origin' => $params['origin'],
            'destination' => $destination,
            'weight' => $weight,
            'results' => $results,
        ];

        return $response;
    }
}
