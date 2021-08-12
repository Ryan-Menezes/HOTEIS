<?php

namespace core\classes;

use PDO;
use PDOException;
use Exception;

class Database{
	private static $conexao;

	// =====================================================================
	// MÉTODO PARA CONECTAR COM O BANCO DE DADOS
	// =====================================================================

	private static function conectar(){
		try{
			$config = [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
				PDO::ATTR_CASE => PDO::CASE_NATURAL
			];

			self::$conexao = new PDO('mysql:host=' . DB_SERVER . '; dbname=' . DB_DATABASE . '; charset=' . DB_CHARSET, DB_USER, DB_PASSWORD, $config);
			self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			self::desconectar();
			echo '<h3>OCORREU UM ERRO NA CONEXÃO[' . $e->getCode() . ']: ' . $e->getMessage() . '</h3>';
		}
	}

	// =====================================================================
	// MÉTODO PARA DESCONECTAR COM O BANCO DE DADOS
	// =====================================================================

	private static function desconectar(){
		self::$conexao = null;
	}

	// =====================================================================
	// MÉTODO PARA EXECUTAR QUERIES INSERT, DELETE, UPDATE E CALL
	// =====================================================================

	public static function EXECUTE_NON_QUERY(string $sql, array $parametros = []) : bool{
		if(!preg_match('/^INSERT|UPDATE|DELETE|CALL/i', $sql)):
			return false;
		endif;

		self::conectar();

		if(!is_null(self::$conexao)):
			self::$conexao->beginTransaction();

			try{
				$comando = self::$conexao->prepare($sql);

				if(is_array($parametros) && !empty($parametros)) $comando->execute($parametros);
				else $comando->execute();

				self::$conexao->commit();
				self::desconectar();

				return true;
			}catch(PDOException $e){
				self::$conexao->rollBack();
				self::desconectar();

				return false;
			}
		endif;

		self::desconectar();
		return false;
	}

	// =====================================================================
	// MÉTODO PARA EXECUTAR QUERIES DE BUSCA SELECT E CALL
	// =====================================================================

	public static function EXECUTE_QUERY(string $sql, array $parametros = []) : array{
		if(!preg_match('/^SELECT|CALL/i', $sql)):
			return array();
		endif;

		self::conectar();

		if(!is_null(self::$conexao)):
			self::$conexao->beginTransaction();

			try{
				$comando = self::$conexao->prepare($sql);

				if(is_array($parametros) && !empty($parametros)) $comando->execute($parametros);
				else $comando->execute();

				self::$conexao->commit();
				self::desconectar();

				return $comando->fetchAll();
			}catch(PDOException $e){
				self::$conexao->rollBack();
				self::desconectar();

				return array();
			}
		endif;

		self::desconectar();
		return array();
	}
}