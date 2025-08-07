<?php

namespace app\Controllers;

class UserController
{
    public function index(): void
    {
        echo json_encode([
            ['id' => 1, 'nome' => 'João'],
            ['id' => 2, 'nome' => 'Maria'],
        ]);
    }

    public function show($id): void
    {
        echo json_encode(['id' => $id, 'nome' => 'João']);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode(['success' => true, 'dados' => $data]);
    }
}
