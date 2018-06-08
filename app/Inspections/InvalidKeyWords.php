<?php

namespace App\Inspections;


class InvalidKeyWords implements SpamDetection
{

    protected $keywords = [
        'yahoo customer support'
    ];

    public function detect($body)
    {

        foreach ($this->keywords as $keyword) {

            if (stripos($body, $keyword) !== false) {

                throw new \Exception("Your reply contains spam");
            }
        }

    }

}
