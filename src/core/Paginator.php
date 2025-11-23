<?php
/**
 * Paginator - пагинация для больших списков
 */
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
     * Получить OFFSET для SQL запроса
     */
    public function getOffset() {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }
    
    /**
     * Получить LIMIT для SQL запроса
     */
    public function getLimit() {
        return $this->itemsPerPage;
    }
    
    /**
     * Получить текущую страницу
     */
    public function getCurrentPage() {
        return $this->currentPage;
    }
    
    /**
     * Получить общее количество страниц
     */
    public function getTotalPages() {
        return $this->totalPages;
    }
    
    /**
     * Получить общее количество элементов
     */
    public function getTotalItems() {
        return $this->totalItems;
    }
    
    /**
     * Есть ли предыдущая страница
     */
    public function hasPrevious() {
        return $this->currentPage > 1;
    }
    
    /**
     * Есть ли следующая страница
     */
    public function hasNext() {
        return $this->currentPage < $this->totalPages;
    }
    
    /**
     * Получить номер предыдущей страницы
     */
    public function getPreviousPage() {
        return max(1, $this->currentPage - 1);
    }
    
    /**
     * Получить номер следующей страницы
     */
    public function getNextPage() {
        return min($this->totalPages, $this->currentPage + 1);
    }
    
    /**
     * Получить массив номеров страниц для отображения
     */
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
     * Получить информацию о диапазоне элементов
     */
    public function getItemsRange() {
        if ($this->totalItems == 0) {
            return [0, 0];
        }
        
        $start = $this->getOffset() + 1;
        $end = min($this->totalItems, $start + $this->itemsPerPage - 1);
        
        return [$start, $end];
    }
    
    /**
     * Рендер HTML пагинации
     */
    public function render($baseUrl = '') {
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $pages = $this->getPages();
        [$start, $end] = $this->getItemsRange();
        
        $html = '<div class="flex items-center justify-between mt-6">';
        
        // Информация о элементах
        $html .= '<div class="text-sm text-slate-400">';
        $html .= sprintf('Показано %d-%d из %d', $start, $end, $this->totalItems);
        $html .= '</div>';
        
        // Кнопки пагинации
        $html .= '<div class="flex gap-2">';
        
        // Предыдущая
        if ($this->hasPrevious()) {
            $html .= sprintf(
                '<a href="%s?page=%d" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">←</a>',
                $baseUrl,
                $this->getPreviousPage()
            );
        }
        
        // Страницы
        foreach ($pages as $page) {
            if ($page === '...') {
                $html .= '<span class="px-3 py-2 text-slate-500">...</span>';
            } elseif ($page == $this->currentPage) {
                $html .= sprintf(
                    '<span class="px-3 py-2 bg-purple-600 text-white rounded-lg">%d</span>',
                    $page
                );
            } else {
                $html .= sprintf(
                    '<a href="%s?page=%d" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">%d</a>',
                    $baseUrl,
                    $page,
                    $page
                );
            }
        }
        
        // Следующая
        if ($this->hasNext()) {
            $html .= sprintf(
                '<a href="%s?page=%d" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">→</a>',
                $baseUrl,
                $this->getNextPage()
            );
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}
