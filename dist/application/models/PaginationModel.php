<?php

class PaginationModel {
    
    protected $_db;
    public $totalresult, //Общее количество записей в базе данных
            $resultpage, // Record set from each page
            $pages, // Total number of pages required
            $openPage; // currently opened page
            
            
    public function __construct($table, $resultPerPage, $page){
        
        $this->_db = DBConnect::run();
        $sql = "SELECT COUNT(*) FROM $table";
        $this->totalresult = $this->_db->query($sql)->fetchColumn(); 
        
        $this->pages = $this->_findPages($this->totalresult, $resultPerPage);
        
        $this->openPage = $page;
        if ($this->openPage > $this->pages) {
            $this->openPage = 1;
        }
        $start = $this->openPage * $resultPerPage - $resultPerPage;
        $end = $resultPerPage;
        
        $sql="SELECT id, title AS h1, anons AS text FROM $table ORDER BY parser_id DESC LIMIT $start,$end";
        $stmt = $this->_db->query($sql); 
        $this->resultpage = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
    }
    
    private function _findPages($total, $perpage){
    
        $pages = intval($total / $perpage);
        if ($total % $perpage > 0)
            $pages++;
        return $pages;
    }
    
    public function displayPaging() {
        
         $paginator =  "<nav aria-label='Page navigation'>\n<ul class='pagination justify-content-center my-5 flex-wrap'>";
        
        
        if ($this->openPage <= 0) {
            $next = 2;
        } else {
            $next = $this->openPage + 1;
        }
        $prev = $this->openPage - 1;
        $last = $this->pages;

        if ($this->openPage > 1) {
            //$paginator .= "<a href=/article/show/page/1/>Первая</a>&nbsp&nbsp;";
            $paginator .= "<li class='page-item'>
      <a class='page-link' href='/article/show/page/$prev/' aria-label='Previous'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span></a>
    </li>";
        } else {
            //$paginator .= "First&nbsp&nbsp;";
            //$paginator .= "Prev&nbsp&nbsp;";
        }
        for ($i = 1; $i <= $this->pages; $i++) {
            if ($i == $this->openPage)
                $paginator .= "<li class='page-item active'><span class='page-link'>$i<span class='sr-only'>(current)</span></span></li>";
            else
                $paginator .= "<li class='page-item'><a  class='page-link' href='/article/show/page/$i/'>$i</a></li>";
        }
        if ($this->openPage < $this->pages) {
            $paginator .= "<li class='page-item'>
      <a  class='page-link' href='/article/show/page/$next/' aria-label='Next'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></a>
    </li>";
            //$paginator .= "<a href=/article/show/page/$last>Последняя</a>&nbsp&nbsp;";
        } else {
            //$paginator .= "Next&nbsp&nbsp;";
            //$paginator .= "Last&nbsp&nbsp;";
        }
        $paginator .= "</ul>\n</nav>";
        
        return $paginator;
        
        
    }
}