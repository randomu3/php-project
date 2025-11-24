# Paginator

**Файл**: `/var/www/html/core/Paginator.php`

**Категория**: Core

## Описание

Paginator - пагинация для больших списков

## Методы

### `__construct($totalItems, $itemsPerPage = 20, $currentPage = 1)`

---

### `getOffset()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса

---

### `getLimit()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса

---

### `getCurrentPage()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу

---

### `getTotalPages()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц

---

### `getTotalItems()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов

---

### `hasPrevious()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница

---

### `hasNext()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница
    /
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
    Есть ли следующая страница

---

### `getPreviousPage()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница
    /
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
    Есть ли следующая страница
    /
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
    Получить номер предыдущей страницы

---

### `getNextPage()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница
    /
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
    Есть ли следующая страница
    /
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
    Получить номер предыдущей страницы
    /
    public function getPreviousPage() {
        return max(1, $this->currentPage - 1);
    }
    
    /**
    Получить номер следующей страницы

---

### `getPages($maxVisible = 7)`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница
    /
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
    Есть ли следующая страница
    /
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
    Получить номер предыдущей страницы
    /
    public function getPreviousPage() {
        return max(1, $this->currentPage - 1);
    }
    
    /**
    Получить номер следующей страницы
    /
    public function getNextPage() {
        return min($this->totalPages, $this->currentPage + 1);
    }
    
    /**
    Получить массив номеров страниц для отображения

---

### `getItemsRange()`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница
    /
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
    Есть ли следующая страница
    /
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
    Получить номер предыдущей страницы
    /
    public function getPreviousPage() {
        return max(1, $this->currentPage - 1);
    }
    
    /**
    Получить номер следующей страницы
    /
    public function getNextPage() {
        return min($this->totalPages, $this->currentPage + 1);
    }
    
    /**
    Получить массив номеров страниц для отображения
    /
    public function getPages($maxVisible = 7) {
        if ($this->totalPages <= $maxVisible) {
            return range(1, $this->totalPages);
        }
        
        $pages = [];
        $half = floor($maxVisible / 2);
        
        $start = max(1, $this->currentPage - $half);
        $end = min($this->totalPages, $this->currentPage + $half);
        
        // Корректируем если упираемся в начало или конец
        if ($this->currentPage <= $half) {
            $end = min($this->totalPages, $maxVisible);
        }
        
        if ($this->currentPage >= $this->totalPages - $half) {
            $start = max(1, $this->totalPages - $maxVisible + 1);
        }
        
        // Добавляем первую страницу и многоточие
        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) {
                $pages[] = '...';
            }
        }
        
        // Добавляем страницы
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }
        
        // Добавляем многоточие и последнюю страницу
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $pages[] = '...';
            }
            $pages[] = $this->totalPages;
        }
        
        return $pages;
    }
    
    /**
    Получить информацию о диапазоне элементов

---

### `render($baseUrl = '')`

Paginator - пагинация для больших списков
/
class Paginator {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    
    public function __construct($totalItems, $itemsPerPage = 20, $currentPage = 1) {
        $this->totalItems = max(0, (int)$totalItems);
        $this->itemsPerPage = max(1, (int)$itemsPerPage);
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        // Корректируем текущую страницу если она больше максимальной
        if ($this->currentPage > $this->totalPages && $this->totalPages > 0) {
            $this->currentPage = $this->totalPages;
        }
    }
    
    /**
    Получить OFFSET для SQL запроса
    /
    public function getOffset() {
        return ($this->currentPage - 1)$this->itemsPerPage;
    }
    
    /**
    Получить LIMIT для SQL запроса
    /
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
    Получить текущую страницу
    /
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
    Получить общее количество страниц
    /
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
    Получить общее количество элементов
    /
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
    Есть ли предыдущая страница
    /
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
    Есть ли следующая страница
    /
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
    Получить номер предыдущей страницы
    /
    public function getPreviousPage() {
        return max(1, $this->currentPage - 1);
    }
    
    /**
    Получить номер следующей страницы
    /
    public function getNextPage() {
        return min($this->totalPages, $this->currentPage + 1);
    }
    
    /**
    Получить массив номеров страниц для отображения
    /
    public function getPages($maxVisible = 7) {
        if ($this->totalPages <= $maxVisible) {
            return range(1, $this->totalPages);
        }
        
        $pages = [];
        $half = floor($maxVisible / 2);
        
        $start = max(1, $this->currentPage - $half);
        $end = min($this->totalPages, $this->currentPage + $half);
        
        // Корректируем если упираемся в начало или конец
        if ($this->currentPage <= $half) {
            $end = min($this->totalPages, $maxVisible);
        }
        
        if ($this->currentPage >= $this->totalPages - $half) {
            $start = max(1, $this->totalPages - $maxVisible + 1);
        }
        
        // Добавляем первую страницу и многоточие
        if ($start > 1) {
            $pages[] = 1;
            if ($start > 2) {
                $pages[] = '...';
            }
        }
        
        // Добавляем страницы
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = $i;
        }
        
        // Добавляем многоточие и последнюю страницу
        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $pages[] = '...';
            }
            $pages[] = $this->totalPages;
        }
        
        return $pages;
    }
    
    /**
    Получить информацию о диапазоне элементов
    /
    public function getItemsRange() {
        if ($this->totalItems == 0) {
            return [0, 0];
        }
        
        $start = $this->getOffset() + 1;
        $end = min($this->totalItems, $start + $this->itemsPerPage - 1);
        
        return [$start, $end];
    }
    
    /**
    Рендер HTML пагинации

---

