<?php

namespace AuraUI\Core;

use Exception;
use PDOException;

/**
 *  Batch Processor
 *
 * @package AuraUI\Core
 */
class BatchProcessor
{
    /**
     * Db
     *
     * @var mixed
     */
    private $db;

    /**
     * BatchSize
     *
     * @var mixed
     */
    private $batchSize = 1000;

    /**
     *   construct
     *
     * @param  $db Parameter
     */
    public function __construct($db = null)
    {
        $this->db = $db ?? getDB();
    }

    /**
     * Batch Insert
     *
     * @param mixed $table Parameter
     * @param mixed $data Data array
     * @param mixed $batchSize Parameter
     *
     * @return int Integer value
     */
    public function batchInsert(mixed $table, mixed $data, mixed $batchSize = null): int
    {
        if (empty($data)) {
            return 0;
        }

        $batchSize ??= $this->batchSize;
        $inserted = 0;

        // Разбиваем на батчи
        $batches = array_chunk($data, $batchSize);

        foreach ($batches as $batch) {
            $result = $this->insertBatch($table, $batch);
            if ($result) {
                $inserted += count($batch);
            }
        }

        Logger::info('Batch insert completed', [
            'table' => $table,
            'total_rows' => count($data),
            'batches' => count($batches),
            'inserted' => $inserted
        ]);

        return $inserted;
    }

    /**
     * Insert Batch
     *
     * @param mixed $table Parameter
     * @param array $batch Parameter
     */
    private function insertBatch(mixed $table, array $batch)
    {
        if ($batch === []) {
            return false;
        }

        try {
            // Получаем колонки из первой строки
            $columns = array_keys($batch[0]);
            $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';

            // Создаем SQL
            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES %s",
                $table,
                implode(',', $columns),
                implode(',', array_fill(0, count($batch), $placeholders))
            );

            // Собираем все значения
            $values = [];
            foreach ($batch as $row) {
                foreach ($columns as $column) {
                    $values[] = $row[$column];
                }
            }

            // Выполняем
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);

        } catch (PDOException $pdoException) {
            Logger::error('Batch insert error', [
                'table' => $table,
                'error' => $pdoException->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Batch Update
     *
     * @param mixed $table Parameter
     * @param mixed $data Data array
     * @param mixed $keyColumn Parameter
     * @param mixed $batchSize Parameter
     *
     * @return int Integer value
     */
    public function batchUpdate(mixed $table, mixed $data, mixed $keyColumn = 'id', mixed $batchSize = null): int
    {
        if (empty($data)) {
            return 0;
        }

        $batchSize ??= $this->batchSize;
        $updated = 0;

        try {
            $this->db->beginTransaction();

            // Разбиваем на батчи
            $batches = array_chunk($data, $batchSize);

            foreach ($batches as $batch) {
                foreach ($batch as $row) {
                    $key = $row[$keyColumn];
                    unset($row[$keyColumn]);

                    $sets = [];
                    $values = [];
                    foreach ($row as $column => $value) {
                        $sets[] = $column . ' = ?';
                        $values[] = $value;
                    }

                    $values[] = $key;

                    $sql = sprintf(
                        "UPDATE %s SET %s WHERE %s = ?",
                        $table,
                        implode(', ', $sets),
                        $keyColumn
                    );

                    $stmt = $this->db->prepare($sql);
                    if ($stmt->execute($values)) {
                        $updated++;
                    }
                }
            }

            $this->db->commit();

            Logger::info('Batch update completed', [
                'table' => $table,
                'total_rows' => count($data),
                'updated' => $updated
            ]);

            return $updated;

        } catch (PDOException $pdoException) {
            $this->db->rollBack();
            Logger::error('Batch update error', [
                'table' => $table,
                'error' => $pdoException->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Batch Delete
     *
     * @param mixed $table Parameter
     * @param mixed $ids Parameter
     * @param mixed $keyColumn Parameter
     * @param mixed $batchSize Parameter
     *
     * @return int|float
     */
    public function batchDelete(mixed $table, mixed $ids, mixed $keyColumn = 'id', mixed $batchSize = null): int|float
    {
        if (empty($ids)) {
            return 0;
        }

        $batchSize ??= $this->batchSize;
        $deleted = 0;

        try {
            // Разбиваем на батчи
            $batches = array_chunk($ids, $batchSize);

            foreach ($batches as $batch) {
                $placeholders = implode(',', array_fill(0, count($batch), '?'));
                $sql = sprintf(
                    "DELETE FROM %s WHERE %s IN (%s)",
                    $table,
                    $keyColumn,
                    $placeholders
                );

                $stmt = $this->db->prepare($sql);
                if ($stmt->execute($batch)) {
                    $deleted += $stmt->rowCount();
                }
            }

            Logger::info('Batch delete completed', [
                'table' => $table,
                'total_ids' => count($ids),
                'deleted' => $deleted
            ]);

            return $deleted;

        } catch (PDOException $pdoException) {
            Logger::error('Batch delete error', [
                'table' => $table,
                'error' => $pdoException->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Process
     *
     * @param mixed $data Data array
     * @param mixed $callback Parameter
     * @param mixed $batchSize Parameter
     *
     * @return int Integer value
     */
    public function process(mixed $data, mixed $callback, mixed $batchSize = null): int
    {
        if (empty($data)) {
            return 0;
        }

        $batchSize ??= $this->batchSize;
        $processed = 0;

        $batches = array_chunk($data, $batchSize);

        foreach ($batches as $batch) {
            try {
                $result = $callback($batch);
                if ($result) {
                    $processed += count($batch);
                }
            } catch (Exception $e) {
                Logger::error('Batch processing error', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $processed;
    }

    /**
     * Set Batch Size
     *
     * @return void
     */
    public function setBatchSize(): void
    {
        $this->batchSize = max(1, (int)$size);
    }
}
