<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, 'http://httpbin.org/headers');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
        $header = json_decode($out, true);
        $header_array = $header['headers'];

        $arFilter = array(
            'IBLOCK_ID' => $arParams['IBLOCK_ID'], // выборка элементов из инфоблока
            'ACTIVE' => 'Y',  // выборка только активных элементов
        );

        $res = CIBlockElement::GetList(array(), $arFilter, false, false, array('ID', 'IBLOCK_ID', 'NAME'));
        while ($element = $res->GetNext()) {
            echo '<pre>';
            print_r($element);
            echo '</pre>';

            $el = new CIBlockElement;
            $rand_keys = array_rand($header_array);
            $arLoadArray = Array(
                "DETAIL_TEXT" => $rand_keys . ': ' . $header_array[$rand_keys],
            );
            //$up_res = $el->Update($element['ID'], $arLoadArray);
            if ($up_res) {
                echo "Элемент ID: " . $element['ID'] . " обновлен</ br>";
            } else {
                echo "Элемент ID: " . $element['ID'] . " не обновлен</ br>";
            }
        }
    }
}
$this->IncludeComponentTemplate();
