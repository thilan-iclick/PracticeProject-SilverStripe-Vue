<?php

namespace Doggo\Task;

use Doggo\Model\Park;
use GuzzleHttp\Client;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class FetchParksPncc extends BuildTask
{
    private static $api_url;

    /**
     *
     * @param HTTPRequest $request
     */
    public function run($request)
    {
        $client = new Client();

        $response = $client->request(
            'GET',
            $this->config()->get('api_url'),
            ['User-Agent' => 'Doggo']
        );

        if ($response->getStatusCode() !== 200) {
            user_error('Could not access ' . $this->config()->get('api_url'));
            exit;
        }

        $data = json_decode($response->getBody());

        // print_r($data->features);
        // exit;

        /*
         * Mark existing records as IsToPurge.
         *
         * As we encounter each record in the API source, we unset this.
         * Once done, any still set are deleted.
         */
        // Updated parks
        $updatedParks = [];

        // Loop through and create/update the parks
        $parks = $data->features;
        foreach ($parks as $park) {
            // See if park already exists
            $parkObject = Park::get()->filter([
                'ParkCode' => $park->properties->OBJECTID,
            ])->first();
            $status = 'changed';

            // Create new park if it doesn't exist
            if (!$parkObject) {
                $status = 'created';
                $parkObject = Park::create();
                $parkObject->ParkCode = $park->properties->OBJECTID;
            }

            // Is dog allowed off leash?
            if ($park->properties->DESCRIPTION ===null | $park->properties->RESERVE_NAME===null | $park->properties->DESCRIPTION === 'Dogs prohibited') {
                continue;
            } else {
                $offLeash = $park->properties->DESCRIPTION === 'Dog exercise area';
            }

            // Transform into correct geojson
            
            if ($park->geometry->type === 'MultiPolygon') {
                $geometry = $park->geometry->coordinates[0][0][0];
            } else {
                $geometry = $park->geometry->coordinates[0][0];
            }

            // Update data object with values
            $parkObject->update([
                'IsToPurge' => false,
                'Title' => $park->properties->RESERVE_NAME,
                'Latitude' => $geometry[0],
                'Longitude' => $geometry[1],
                'Notes' => $park->properties->DESCRIPTION,
                'GeoJson' => json_encode($park),
                'OffLeash' => $offLeash,
                'Council'=>$this->config()->get('api_title')
            ]);
            $parkObject->write();
            $updatedParks[] = $parkObject->ID;

            DB::alteration_message('Updating [' . $parkObject->ParkCode . '] ' . $parkObject->Title, $status);
        }
    }
}
