<?php

namespace Doggo\Model;

use JsonSerializable;
use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationResult;

class Park extends DataObject implements JsonSerializable
{
    private static $table_name = 'Park';

    private static $db = [
        'Title' => 'Varchar',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
        'Notes' => 'Text',
        'ParkCode' => 'Varchar(100)',
        'GeoJson' => 'Text',
        'OffLeash' => 'Boolean',
        // Adding additional fields
        'Council' => 'Varchar',
        'ApprovePhoto' => 'Boolean'
    ];

    // Making the relationship with File table
    private static $has_one = [
        'dogPhoto' => File::class
    ];

    private static $summary_fields = [
        'Title' => 'Title',
    ];

    private static $default_sort = "Title";

    /**
     * @return ValidationResult
     */
    public function validate()
    {
        $validate = parent::validate();

        if (empty($this->Title)) {
            $validate->addFieldError('Title', 'Title is required');
        }

        return $validate;
    }

    /**
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'ID' => $this->ID,
            'Title' => $this->Title,
            'Latitude' => $this->Latitude,
            'Longitude' => $this->Longitude,
            'Notes' => $this->Notes,
            'GeoJson' => $this->GeoJson,
            'OffLeash' => (bool) $this->OffLeash,
            'LastEdited' => $this->LastEdited,
            'Created' => $this->Created,
            // New fields added
            'dogPhoto' => ($this->ApprovePhoto) ? "assets/".$this->dogPhoto->getFilename() : false,
            'Council' => $this->Council
        ];
    }
}
