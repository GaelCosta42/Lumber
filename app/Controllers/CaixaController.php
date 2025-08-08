<?php

namespace App\Controllers;

use App\Models\Caixa;

class CaixaController
{
    public function AbrirCaixa($valorInicial, $idBanco): void
    {
        if (!is_numeric($valorInicial) || !is_numeric($idBanco)) {
            http_response_code(400);
            echo json_encode(['error' => 'Valor inicial e ID do banco devem ser numéricos']);
            return;
        }

        $caixa = new Caixa();
        $caixa->setValorInicial($valorInicial);
        $caixa->setIdBanco($idBanco);
        $caixa->insert();

        echo $caixa;
    }

    public function abrirViaRequest(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['valor_inicial']) || !isset($data['idbanco'])) {
            http_response_code(400);
            echo json_encode(['error' => 'valor_inicial e idbanco são obrigatórios']);
            return;
        }

        $this->AbrirCaixa($data['valor_inicial'], $data['idbanco']);
    }
}
