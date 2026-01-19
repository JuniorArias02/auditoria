<?php

namespace App\Models;

use App\Database\Database;
use \PDO;



class CodigosVerificacion
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}


	public function generarCodigo($usuarioId, $minutosValidez = 15)
	{
		$codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
		// Usar gmdate() para UTC en lugar de date() que usa hora local
		$expiracion = gmdate('Y-m-d H:i:s', strtotime("+$minutosValidez minutes"));
		$stmt = $this->pdo->prepare("
            INSERT INTO codigos_verificacion (usuario_id, codigo, expiracion) 
            VALUES (:usuario_id, :codigo, :expiracion)
        ");

		$resultado = $stmt->execute([
			'usuario_id' => $usuarioId,
			'codigo' => $codigo,
			'expiracion' => $expiracion
		]);

		return $resultado ? $codigo : false;
	}

	public function validarCodigo($usuarioId, $codigo)
	{
		// Usar UTC_TIMESTAMP() en lugar de NOW() para comparar en UTC
		$stmt = $this->pdo->prepare("
            SELECT id FROM codigos_verificacion 
            WHERE usuario_id = :usuario_id 
            AND codigo = :codigo 
            AND expiracion > UTC_TIMESTAMP() 
            AND usado = 0
        ");
		$stmt->execute([
			'usuario_id' => $usuarioId,
			'codigo' => $codigo
		]);

		return $stmt->fetch() ? true : false;
	}

	/**
	 * Marcar código como usado
	 */
	public function marcarUsado($usuarioId, $codigo)
	{
		$stmt = $this->pdo->prepare("
            UPDATE codigos_verificacion 
            SET usado = 1 
            WHERE usuario_id = :usuario_id AND codigo = :codigo
        ");
		$stmt->execute([
			'usuario_id' => $usuarioId,
			'codigo' => $codigo
		]);
	}
}
