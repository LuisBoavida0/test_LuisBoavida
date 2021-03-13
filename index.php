<?php
include "services.class.php";

//Gets the CSV values as an array
$servicesCsv = array_map('str_getcsv', file('Services.csv'));
//Stores the Services Values
$services = new Services($servicesCsv);

echo "Here are the results founded when searched for de: \n";
echo $services->get_by_field(ServicesValuesEnum::country, "pt");

echo "\n\n";

echo "Here is the number of services in each country: \n";
echo 'este e o resultado ' . $services->get_by_field(ServicesValuesEnum::country, "ptt");

echo $services->get_count_by_field(ServicesValuesEnum::country);

?>