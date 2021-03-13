<?php

declare(strict_types=1);
include "services.class.php";

use PHPUnit\Framework\TestCase;

final class ServicesTest extends TestCase
{
    public function testWithGoodCsv(): void
    {
        $servicesCsv = array_map('str_getcsv', file('Services.csv'));
        $services = new Services($servicesCsv);        
        $this->assertNotEquals($services->get_by_field(ServicesValuesEnum::country, "pt"), "No matches Found");
    }

    public function testWithBadCsv(): void
    {
        $servicesCsv = array_map('str_getcsv', file('Services.csv'));
        $services = new Services($servicesCsv);
        $this->assertEquals($services->get_by_field(ServicesValuesEnum::country, "hkhj"), "No matches Found");
    }
}
