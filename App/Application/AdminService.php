<?php
namespace App\Application;
require_once '../Domain/Users/UserEntity.php'; use App\Domain\Users\UserEntity;
require_once '../Infrastructure/sdbh.php'; use sdbh\sdbh;

class AdminService {

    /** @var UserEntity */
    public $user;

    public function __construct()
    {
        $this->user = new UserEntity();
    }

    public function addNewProduct(String  $name, int $price, string $tarif)
    {
        if (!$this->user->isAdmin) {
            return;
        }
        else{
            $dbh = new sdbh();

            $values=array(
                'ID'=>"null",
                "NAME"=>$name,
                "PRICE"=>$price,
                "TARIFF"=>$tarif
            );
            $dbh->insert_row('a25_products',$values);

            echo "Товар добавлен";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = new AdminService();

    $tarifDays=$_POST["threshold"];
    $tarifPrice=$_POST["tariff"];

    if (!is_null($tarifDays) && count($tarifDays)!=0){
        $tarifText="a:".count($tarifDays).":{";
        for ($i=0;$i<count($tarifDays);$i++){
            $tarifText.="i:".$tarifDays[$i].";i:".$tarifPrice[$i].";";
        }
        $tarifText.="}";
    }
    else{
        $tarifText="NULL";
    }


    $instance->addNewProduct($_POST['name'], $_POST['price'], $tarifText);
}