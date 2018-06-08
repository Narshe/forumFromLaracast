<?php

namespace App\Inspections;


interface SpamDetection
{

    public function detect($body);
}
