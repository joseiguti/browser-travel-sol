<?php

namespace App\Database;

use Exception;
use PDO;
use PDOException;

class DatabaseModel {
    private PDO $pdo;
    private string $lastError;

    /**
     * Crea una instancia de la clase DatabaseModel.
     *
     * @param string $databasePath Ruta del archivo de base de datos.
     */
    public function __construct(string $databasePath)
    {
        $this->lastError = '';

        try {
            $this->pdo = new PDO("sqlite:$databasePath");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTableIfNotExists();

        } catch (PDOException $e) {
            $this->lastError = 'Error al conectar a la base de datos: ' . $e->getMessage();
        }
    }

    /**
     * Obtiene el último mensaje de error.
     *
     * @return string Último mensaje de error.
     */
    public function getLastError (): string {
        return $this->lastError;
    }

    /**
     * Guarda un registro en la base de datos.
     *
     * @param string $ack     Valor "ack" del registro.
     * @param string $jsonData Datos del registro en formato JSON.
     * @return bool True si se guarda el registro correctamente, false en caso contrario.
     */
    public function saveRecord(string $ack, string $jsonData): bool
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO records (ack, json) VALUES (:ack, :json)');
            $stmt->bindParam(':ack', $ack);
            $stmt->bindParam(':json', $jsonData);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->lastError =  'Error al guardar el registro: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtiene un registro por su ID.
     *
     * @param int $id ID del registro.
     * @return array|null Arreglo asociativo con los datos del registro, o null si no se encuentra.
     */
    public function getRecordById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM records WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result === false) {
                $this->lastError = 'No se encontraron datos para el ID especificado';
                return null;
            }

            return $result;
        } catch (PDOException|Exception $e) {
            $this->lastError = 'Error al obtener el registro por ID: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Obtiene los últimos registros de la base de datos.
     *
     * @param int $limit Límite de registros a obtener.
     * @return array Arreglo con los últimos registros.
     */
    public function getLastRecords(int $limit = 10): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT id, ack FROM records ORDER BY id DESC LIMIT :limit');
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = 'Error al obtener los últimos registros: ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Crea la tabla de registros si no existe.
     */
    private function createTableIfNotExists(): void
    {
        $createTableQuery = 'CREATE TABLE IF NOT EXISTS records (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ack TEXT,
            json TEXT
        )';

        $this->pdo->exec($createTableQuery);
    }
}
