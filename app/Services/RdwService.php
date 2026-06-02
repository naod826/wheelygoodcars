<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RdwService
{
    public function lookup(string $licensePlate): ?array
    {
        $plate = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $licensePlate));

        $response = Http::timeout(10)->get(
            'https://opendata.rdw.nl/resource/m9d7-ebf2.json',
            ['kenteken' => $plate]
        );

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();

        if (empty($data[0])) {
            return null;
        }

        $vehicle = $data[0];

        return [
            'brand' => $vehicle['merk'] ?? null,
            'model' => $vehicle['handelsbenaming'] ?? null,
            'production_year' => isset($vehicle['datum_eerste_toelating'])
                ? (int) substr($vehicle['datum_eerste_toelating'], 0, 4)
                : null,
            'seats' => isset($vehicle['aantal_zitplaatsen']) ? (int) $vehicle['aantal_zitplaatsen'] : null,
            'doors' => isset($vehicle['aantal_deuren']) ? (int) $vehicle['aantal_deuren'] : null,
            'weight' => isset($vehicle['massa_ledig_voertuig']) ? (int) $vehicle['massa_ledig_voertuig'] : null,
            'color' => $vehicle['eerste_kleur'] ?? null,
        ];
    }
}
