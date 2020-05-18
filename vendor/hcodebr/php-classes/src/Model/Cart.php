<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Cart extends Model
{
    const SESSION = 'Cart';

    public static function getFromSession()
    {
        $cart = new Cart();
        if (isset($_SESSION[Cart::SESSION]) && $_SESSION[Cart::SESSION]["iduser"] > 0) {
            $cart->get((int) $_SESSION[Cart::SESSION]["iduser"]);
        }
    }

    public function get(int $idcart)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart",[
            ':idcart'=>$idcart
        ]);
        $this->setData($results[0]);
    }

    public function save()
    {
        $sql = new Sql();

        $results = $sql->select("CALL sp_cart_save (:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", [
            ':idcart' => $this->getidcart(),
            ':dessessionid' => $this->getdessessionid(),
            ':iduser' => $this->getiduser(),
            ':deszipcode' => $this->getdeszipcode(),
            ':vlfreight' => $this->getvlfreight(),
            ':nrdays' => $this->getnrdays(),
        ]);

        $this->setData($results[0]);

    }

}
