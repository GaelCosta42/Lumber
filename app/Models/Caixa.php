<?php

namespace App\Models;

use App\Models\Sql;
use DateTime;

class Caixa
{
    private $idcaixa;
    private $valor_inicial;
    private $idbanco;
    private $dt_abertura;

    public function getIdCaixa()
    {
        return $this->idcaixa;
    }

    public function setIdCaixa($id)
    {
        $this->idcaixa = $id;
    }

    public function getValorInicial()
    {
        return $this->valor_inicial;
    }

    public function setValorInicial($valor)
    {
        $this->valor_inicial = $valor;
    }

    public function getIdBanco()
    {
        return $this->idbanco;
    }

    public function setIdBanco($idbanco)
    {
        $this->idbanco = $idbanco;
    }

    public function getDataAbertura()
    {
        return $this->dt_abertura;
    }

    public function setDataAbertura($data)
    {
        $this->dt_abertura = $data;
    }

    public function insert()
    {
        $sql = new Sql();

        $sql->execQuery("
            INSERT INTO tb_caixa (valor_inicial, idbanco) 
            VALUES (:VALOR, :BANCO)",
            [
                ":VALOR" => $this->getValorInicial(),
                ":BANCO" => $this->getIdBanco()
            ]
        );

        $result = $sql->select("SELECT * FROM tb_caixa ORDER BY idcaixa DESC LIMIT 1");

        if (count($result) > 0) {
            $this->setData($result[0]);
        }
    }

    public function setData($data)
    {
        $this->setIdCaixa($data['idcaixa']);
        $this->setValorInicial($data['valor_inicial']);
        $this->setIdBanco($data['idbanco']);
        $this->setDataAbertura(new DateTime($data['dt_abertura']));
    }

    public function __toString()
    {
        return json_encode([
            "idcaixa" => $this->getIdCaixa(),
            "valor_inicial" => number_format($this->getValorInicial(), 2),
            "idbanco" => $this->getIdBanco(),
            "dt_abertura" => $this->getDataAbertura() instanceof DateTime
                ? $this->getDataAbertura()->format("d/m/Y H:i:s")
                : null
        ], JSON_UNESCAPED_UNICODE);
    }
}
