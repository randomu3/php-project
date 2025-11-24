# BatchProcessor

**Файл**: `/var/www/html/core/BatchProcessor.php`

**Категория**: Core

## Описание

BatchProcessor - пакетная обработка для максимальной производительности

## Методы

### `__construct($db = null)`

---

### `batchInsert($table, $data, $batchSize = null)`

BatchProcessor - пакетная обработка для максимальной производительности
/
class BatchProcessor {
    private $db;
    private $batchSize = 1000;
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Пакетная вставка данных

---

### `insertBatch($table, $batch)`

BatchProcessor - пакетная обработка для максимальной производительности
/
class BatchProcessor {
    private $db;
    private $batchSize = 1000;
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Пакетная вставка данных
    /
    public function batchInsert($table, $data, $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
    Вставить один батч

---

### `batchUpdate($table, $data, $keyColumn = 'id', $batchSize = null)`

BatchProcessor - пакетная обработка для максимальной производительности
/
class BatchProcessor {
    private $db;
    private $batchSize = 1000;
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Пакетная вставка данных
    /
    public function batchInsert($table, $data, $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
    Вставить один батч
    /
    private function insertBatch($table, $batch) {
        if (empty($batch)) {
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
            
        } catch (PDOException $e) {
            Logger::error('Batch insert error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Пакетное обновление

---

### `batchDelete($table, $ids, $keyColumn = 'id', $batchSize = null)`

BatchProcessor - пакетная обработка для максимальной производительности
/
class BatchProcessor {
    private $db;
    private $batchSize = 1000;
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Пакетная вставка данных
    /
    public function batchInsert($table, $data, $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
    Вставить один батч
    /
    private function insertBatch($table, $batch) {
        if (empty($batch)) {
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
            
        } catch (PDOException $e) {
            Logger::error('Batch insert error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Пакетное обновление
    /
    public function batchUpdate($table, $data, $keyColumn = 'id', $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
                        $sets[] = "{$column} = ?";
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
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            Logger::error('Batch update error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
    Пакетное удаление

---

### `process($data, $callback, $batchSize = null)`

BatchProcessor - пакетная обработка для максимальной производительности
/
class BatchProcessor {
    private $db;
    private $batchSize = 1000;
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Пакетная вставка данных
    /
    public function batchInsert($table, $data, $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
    Вставить один батч
    /
    private function insertBatch($table, $batch) {
        if (empty($batch)) {
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
            
        } catch (PDOException $e) {
            Logger::error('Batch insert error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Пакетное обновление
    /
    public function batchUpdate($table, $data, $keyColumn = 'id', $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
                        $sets[] = "{$column} = ?";
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
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            Logger::error('Batch update error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
    Пакетное удаление
    /
    public function batchDelete($table, $ids, $keyColumn = 'id', $batchSize = null) {
        if (empty($ids)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
            
        } catch (PDOException $e) {
            Logger::error('Batch delete error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
    Пакетная обработка с callback

---

### `setBatchSize($size)`

BatchProcessor - пакетная обработка для максимальной производительности
/
class BatchProcessor {
    private $db;
    private $batchSize = 1000;
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Пакетная вставка данных
    /
    public function batchInsert($table, $data, $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
    Вставить один батч
    /
    private function insertBatch($table, $batch) {
        if (empty($batch)) {
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
            
        } catch (PDOException $e) {
            Logger::error('Batch insert error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Пакетное обновление
    /
    public function batchUpdate($table, $data, $keyColumn = 'id', $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
                        $sets[] = "{$column} = ?";
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
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            Logger::error('Batch update error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
    Пакетное удаление
    /
    public function batchDelete($table, $ids, $keyColumn = 'id', $batchSize = null) {
        if (empty($ids)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
            
        } catch (PDOException $e) {
            Logger::error('Batch delete error', [
                'table' => $table,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
    Пакетная обработка с callback
    /
    public function process($data, $callback, $batchSize = null) {
        if (empty($data)) {
            return 0;
        }
        
        $batchSize = $batchSize ?? $this->batchSize;
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
    Установить размер батча

---

