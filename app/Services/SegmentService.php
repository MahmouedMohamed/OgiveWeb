<?php

namespace App\Services;

use App\ConverterModels\Nationality;
use Exception;
use Illuminate\Support\Facades\Log;
use Segment\Segment;

class SegmentService
{
    public function __construct()
    {
        Segment::init(config('settings.segment_key'));
    }

    public function identify($user, $extraParams): void
    {
        try {
            Segment::identify(
                [
                    'userId' => $user['id'],
                    'traits' => [
                        'type' => 'User',
                        'name' => $user['name'] ?? 'Anonymous',
                        'email' => $user['email'] ?? null,
                        'phone' => $user['phone_number'] ?? null,
                        'created' => $user['created_at'],
                        'Country' => Nationality::$country[Nationality::$value[$user['nationality']]] ?? 'Egypt',
                        'Nationality' => $user['nationality'],
                        'ip' => $extraParams['ip'] ?? null,
                        'locale' => $extraParams['locale'] ?? app()->getLocale(),
                        'timezone' => $extraParams['timezone'] ?? 'Africa/Cairo',
                    ],
                ]
            );
        } catch (Exception $ex) {
            Log::warning($ex);
        }
    }

    public function track($event, $user, $extraParams = []): void
    {
        try {
            Segment::track([
                'userId' => $user['id'],
                'event' => $event,
                'properties' => array_merge([
                    'environment' => config('dayra.APP_ENV'),
                    'Country' => Nationality::$country[Nationality::$value[$user['nationality']]] ?? 'Egypt',
                    'Nationality' => $user['nationality'],
                ], $extraParams),
            ]);
        } catch (Exception $ex) {
            Log::warning($ex);
        }
    }

    public function flush()
    {
        Segment::flush();
    }
}
