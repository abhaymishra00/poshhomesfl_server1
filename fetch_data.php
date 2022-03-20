<?php

// A function that will make a GET request to the /campaigns endpoint

include_once 'core/config.php';
include_once 'core/function.php';
include_once 'core/mysql.php';

// header("Content-Type: application/json");
$base_url = "https://api.bridgedataoutput.com/api/v2/OData/miamire/Property?access_token=24c416b7cae326b1b4472daa55038bdc";


$next_list_url = "";

function add_property($list)
{

    $q = "INSERT INTO `properies_list`(`mlsid`, `mlskey`, `mlsstatus`, `addedon`, `upadateat`, `addr1`, `addr_street_number`, `addr_street_name`, `addr_city`, `addr_state`, `addr_zipcode`, `rate`, `bedrooms`, `bathrooms`, `property_area`, `property_media`) VALUES ";

    $q_value = "";
    for ($i = 0; $i < count($list); $i++) {

        $item = $list[$i];

        $timestamp = time();


        $mlsId = $item['ListingId'];
        $mlsKey = $item['ListingKey'];
        $mlsStatus = $item['MlsStatus'];
        $addr = $item['UnparsedAddress'];
        $street_number = $item['StreetNumber'];
        $street_name = $item['StreetName'];
        $city = $item['City'];
        $state = $item['StateOrProvince'];
        $zip_code = $item['PostalCode'];
        $list_rate = $item['ListPrice'];
        $bd = $item['BedroomsTotal'];
        $br = $item['BathroomsFull'];
        $propertry_area = $item['LivingArea'] . " " . $item['LivingAreaUnits'];
        $media = $item['Media'];

        $img_list = [];
        foreach ($media as $img) {
            array_push($img_list, $img['MediaURL']);
        }
        $media = json_encode($img_list, true);
        $media = addslashes($media);
        $rate = $item['ListPrice'];


        if ($i == count($list) - 1) {

            $q_value .= "('$mlsId','$mlsKey','$mlsStatus','$timestamp','$timestamp','$addr','$street_number','$street_name','$city','$state','$zip_code','$rate','$bd','$br','$propertry_area','$media')";
        } else {
            $q_value .= "('$mlsId','$mlsKey','$mlsStatus','$timestamp','$timestamp','$addr','$street_number','$street_name','$city','$state','$zip_code','$rate','$bd','$br','$propertry_area','$media'),";
        }
    }



    if (query($q . $q_value)) {
        echo "Record Updatad";
        global $next_list_url;
        if ($next_list_url != "") {
            load_property($next_list_url);
        }
    } else {
        echo "Can't complete record";
    }
}


function load_property($url)
{
    $data =  call_api($url);
    global $next_list_url;

    $next_list_url = $data['@odata.nextLink'];
    add_property($data['value']);
}


load_property($base_url);
