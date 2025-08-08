<?php

namespace App\Controllers;

use App\Models\Usuario;

class UserController
{
    public function index(): void
    {
        $usuarios = Usuario::getList();
        echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
    }

    public function show($id): void
    {
        $usuario = new Usuario();
        $usuario->loadById($id);
        echo $usuario;
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['deslogin']) || !isset($data['dessenha'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Login e senha são obrigatórios']);
            return;
        }

        $usuario = new Usuario($data['deslogin'], $data['dessenha']);
        $usuario->insert($data['deslogin'], $data['dessenha']);

        echo $usuario;
    }

    public function update($id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['login']) || !isset($data['senha'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Login e senha são obrigatórios']);
            return;
        }

        $usuario = new Usuario();
        $usuario->loadById($id);
        $usuario->update($data['login'], $data['senha']);

        echo $usuario;
    }

    public function delete($id): void
    {
        try 
        {
            $usuario = new Usuario();
            $usuario->loadById($id);
            $usuario->delete();

            echo json_encode(['message' => 'Usuário excluído com sucesso']);
        } 
        catch (\Exception $e) 
        {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
