<?php

namespace App\Models;

use App\Models\Sql;
use DateTime;
use Exception;

class Usuario{
	private $idusuario;
	private $deslogin;
	private $dessenha;
	private $dtcadastro;

	public function getIdusuario()
	{
		return $this->idusuario;
	}

	public function setIdusuario($idusuario)
	{
		$this->idusuario = $idusuario;
	}

	public function getLogin()
	{
		return $this->deslogin;
	}

	public function setLogin($deslogin)
	{
		$this->deslogin = $deslogin;
	}

	public function getSenha()
	{
		return $this->dessenha;
	}

	public function setSenha($dessenha)
	{
		$this->dessenha = $dessenha;
	}

	public function getDtcadastro()
	{
		return $this->dtcadastro;
	}

	public function setDtcadastro($dtcadastro)
	{
		$this->dtcadastro = $dtcadastro;
	}

	public function loadById($id)
	{
		$sql = new Sql();

		$result = $sql->select
		(
			"SELECT * FROM tb_usuario WHERE idusuario = :ID", 
			array(":ID"=>$id)
		);

		if(count($result) > 0)
		{
			$this->setData($result[0]);
		}
	}

	public function loadByTipoUsuario($id)
	{
		try
		{
			$sql = new Sql();

			$result = $sql->select
			(
				"SELECT * FROM tb_usuario WHERE idtpusu = :ID", 
				array(":ID"=>$id)
			);

			if(count($result) > 0)
			{
				$this->setData($result[0]);
			}

			return $result;
		}
		catch (Exception $e)
		{
			http_response_code(500);
			echo json_encode(['error' => 'Erro ao carregar usuários por tipo: ' . $e->getMessage()]);
			return [];
		}
	}

	public static function getList()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuario ORDER BY deslogin;");
	}

	public static function search($login)
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuario WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(':SEARCH'=>"%" . $login . "%"));
	}

	public function login($login, $password)
	{
		$sql = new Sql();

		$result = $sql->select
		(
			"SELECT * FROM tb_usuario WHERE deslogin = :LOGIN AND dessenha = :PASSWORD", 
			array
			(
				":LOGIN"=>$login,
				":PASSWORD"=>$password
			)
		);

		if(count($result) > 0)
		{
			$this->setData($result[0]);
		}
		else
		{
			throw new Exception("Login Inválido!", 1);
			
		}
	}

	public function setData($data)
	{
		$this->setIdusuario($data['idusuario']);
		$this->setLogin($data['deslogin']);
		$this->setSenha($data['dessenha']);
		$this->setDtcadastro(new DateTime($data['dtcadastro']));
	}

	public function insert($login, $senha)
	{
		$sql = new Sql();

		$result = $sql->select
		(
			"INSERT INTO tb_usuario (deslogin, dessenha) VALUES (:LOGIN, :PASSWORD)", 
			array
			(
				':LOGIN'=>$this->getLogin($login),
				':PASSWORD'=>$this->getSenha($senha)
			)
		);
	}

	public function delete()
	{
		$sql = new Sql();

		$dependentes = $sql->select(
			"SELECT COUNT(*) as total FROM tb_caixa WHERE idusuario = :ID",
			[':ID' => $this->getIdusuario()]
		);

		if ($dependentes[0]['total'] > 0) {
			throw new \Exception("Não é possível excluir o usuário. Existem caixas associadas a ele.");
		}

		$sql->execQuery("DELETE FROM tb_usuario WHERE idusuario = :ID", [
			':ID' => $this->getIdusuario()
		]);

		$this->setIdusuario(0);
		$this->setLogin("");
		$this->setSenha("");
		$this->setDtcadastro(new \DateTime());
	}

	public function update($login, $senha)
	{
		$this->setLogin($login);
		$this->setSenha($senha);

		$sql = new Sql();
		$sql->execQuery("UPDATE tb_usuario SET deslogin = :LOGIN, dessenha = :PASSWORD WHERE idusuario = :ID", array(
			':LOGIN'=>$this->getLogin(),
			':PASSWORD'=>$this->getSenha(),
			':ID'=>$this->getIdusuario()
		));
	}

	public function __construct($deslogin = "", $dessenha = "")
	{
		$this->setLogin($deslogin);
		$this->setSenha($dessenha);
	}

	public function __toString()
	{
		return json_encode(array(
			"idusuario"=>$this->getIdusuario(),
			"deslogin"=>$this->getLogin(),
			"dessenha"=>$this->getSenha(),
			"dtcadastro" => $this->getDtcadastro() instanceof DateTime
			? $this->getDtcadastro()->format("d/m/Y H:i:s")
			: null

		), JSON_UNESCAPED_SLASHES);
	}
}

?>