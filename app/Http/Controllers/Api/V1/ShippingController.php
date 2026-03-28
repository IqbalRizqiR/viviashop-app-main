<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController extends BaseController
{
    public function provinces()
    {
        try {
            require_once base_path('rajaongkir_komerce.php');
            $rajaOngkir = new \RajaOngkirKomerce();
            $provinces = $rajaOngkir->getProvinces();
            return $this->success($provinces ?: []);
        } catch (\Exception $e) {
            Log::error('Error fetching provinces: ' . $e->getMessage());
            return $this->error('Failed to fetch provinces', 500);
        }
    }

    public function cities($provinceId)
    {
        try {
            require_once base_path('rajaongkir_komerce.php');
            $rajaOngkir = new \RajaOngkirKomerce();
            $cities = $rajaOngkir->getCities($provinceId);

            $cityArray = [];
            foreach ($cities as $id => $name) {
                $cityArray[] = ['id' => $id, 'name' => $name];
            }

            return $this->success($cityArray);
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage());
            return $this->error('Failed to fetch cities', 500);
        }
    }

    public function districts($cityId)
    {
        try {
            require_once base_path('rajaongkir_komerce.php');
            $rajaOngkir = new \RajaOngkirKomerce();
            $districts = $rajaOngkir->getDistricts($cityId);

            $districtArray = [];
            foreach ($districts as $id => $name) {
                $districtArray[] = ['id' => $id, 'name' => $name];
            }

            return $this->success($districtArray);
        } catch (\Exception $e) {
            Log::error('Error fetching districts: ' . $e->getMessage());
            return $this->error('Failed to fetch districts', 500);
        }
    }

    public function cost(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
        ]);

        $destination = $request->input('district_id');
        $weight = $request->input('weight');

        try {
            require_once base_path('rajaongkir_komerce.php');
            $rajaOngkir = new \RajaOngkirKomerce();

            $origin = 3852; // Jombang District ID
            $couriers = ['jne', 'tiki', 'pos'];
            $results = [];

            foreach ($couriers as $courier) {
                try {
                    $options = $rajaOngkir->calculateShippingCost($origin, $destination, $weight, $courier);
                    if (is_array($options) && !empty($options)) {
                        foreach ($options as $option) {
                            if (is_array($option) && isset($option['service'], $option['cost'])) {
                                $results[] = [
                                    'service' => strtoupper($courier) . ' - ' . $option['service'],
                                    'cost' => $option['cost'],
                                    'etd' => $option['etd'] ?? '',
                                    'courier' => $courier,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Courier {$courier} failed: " . $e->getMessage());
                }
            }

            return $this->success([
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Shipping cost error: ' . $e->getMessage());
            return $this->error('Failed to calculate shipping cost', 500);
        }
    }
}
