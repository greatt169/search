<?php


$data = file_get_contents('../data.json');
$dataEncoded = json_decode($data, true);

$references = [];
$properties = [];

foreach ($dataEncoded as $dataItem) {
    foreach ($dataItem['singleAttributes'] as $attrCode => $arAttribute) {
        $properties[$attrCode]['title'] = $arAttribute['name'];
        if($arAttribute['value']['code']) {
            $properties[$attrCode]['values'][$arAttribute['value']['code']]['title'] = $arAttribute['value']['value'];
        }

    }

    foreach ($dataItem['multipleAttributes'] as $attrCode => $arAttribute) {
        $properties[$attrCode]['title'] = $arAttribute['name'];
        foreach ($arAttribute['values'] as $attributeValue) {
            $properties[$attrCode]['values'][$attributeValue['code']]['title'] = $attributeValue['value'];
        }

    }
}

$references['properties'] = $properties;
$referencesJson = json_encode($references);
file_put_contents('../references.json', $referencesJson);

echo 'Script completed!';