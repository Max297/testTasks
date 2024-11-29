<?php
namespace App;
require_once 'Infrastructure/sdbh.php'; use sdbh\sdbh; 

class Calculate
{   
    public function getRubToCnyExchangeRate() {
        $url = 'https://www.cbr-xml-daily.ru/daily_json.js';
    
       
        $response = file_get_contents($url);
    
        if ($response === FALSE) {
            http_response_code(500);
            return json_encode(['error' => 'Ошибка при получении данных']);
        }
    
        
        $data = json_decode($response, true);
    
        
        
        $rubToCny = $data['Valute']['CNY']['Value'];

        return $rubToCny;
        
    }
    public function calculate1()
    {
        $dbh = new sdbh();
        $days = isset($_POST['days']) ? $_POST['days'] : 0;
        $product_id = isset($_POST['product']) ? $_POST['product'] : 0;
        $selected_services = isset($_POST['services']) ? $_POST['services'] : [];
        $product = $dbh->make_query("SELECT * FROM a25_products WHERE ID = $product_id");
        if ($product) {
            $product = $product[0];
            $price = $product['PRICE'];
            $tarif = $product['TARIFF'];
        } else {
            echo "Ошибка, товар не найден!";
            return;
        }

        $tarifs = unserialize($tarif);
        if (is_array($tarifs)) {
            $product_price = $price;
            foreach ($tarifs as $day_count => $tarif_price) {
                if ($days >= $day_count) {
                    $product_price = $tarif_price;
                }
            }
            $total_price = $product_price * $days;
        }else{
            $total_price = $price * $days;
        }

        $services_price = 0;
        foreach ($selected_services as $service) {
            $services_price += (float)$service * $days;
        }

        $total_price += $services_price;

        $rate= $this->getRubToCnyExchangeRate();

        $resultArray=array(
            "rub"=>$total_price,
            "cny"=>round($total_price/$rate,2)
        );
        echo json_encode($resultArray);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = new Calculate();
    $instance->calculate1();
}
