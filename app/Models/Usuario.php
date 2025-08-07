<?php

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

	public function getDtcadastro(){
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

	//Como não utilizou o 'this' o método pode ser estático.
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

	public function insert()
	{
		$sql = new Sql();

		$result = $sql->select
		(
			"CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", 
			array
			(
				':LOGIN'=>$this->getLogin(),
				':PASSWORD'=>$this->getSenha()
			)
		);

		if(count($result) > 0){
			$this->setData($result[0]);
		}
	}

	public function delete()
	{
		$sql = new Sql();

		$sql->execQuery("DELETE FROM tb_usuario WHERE idusuario = :ID", 
			array
			(
				':ID'=>$this->getIdusuario()
			)
		);

		$this->setIdusuario(0);
		$this->setLogin("");
		$this->setSenha("");
		$this->setDtcadastro(new DateTime());

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

	public function __construct($deslogin = "", $dessenha = ""){
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