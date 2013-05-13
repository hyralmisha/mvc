<?php

class Controller_tags extends Controller

{
    
    protected $_site = "http://localhost/mvc/tags/listTag/";
    
    //задаємо кількість тегів
    protected $tagCount = 7;
    //задаємо розмір шрифту відображення тегів
    protected $maxFont = 10;
    protected $minFont = 2;
    


    public function __construct()
    {
        //перегружаєм конструктор з батьківского класу
        parent :: __construct();
        // створюємо об'єкт класу Model_tags
        $this -> model = new Model_tags();
    }
    
    public function getTags()
    {
       /**
        * виводить з БД масив тегів для "хмари тегів"
        * 
        * 
        */
        
        $tags = $this -> model -> getTags( $this -> tagCount );
        
        //визначаэмо коефіцієнт розмір шрифту тегів
        $kTag =  $this -> maxFont / $tags[0]['count'];
        
        for ( $i = 0; $i < $this -> tagCount; $i++) {
            $tags[$i]['countFont'] = floor( $tags[$i]['count'] * $kTag );
            if ( $tags[$i]['countFont'] < $this -> minFont ) {
                $tags[$i]['countFont'] = $this -> minFont;
            }
        }
        
        return $tags;
    }
    
    public function pagination()
    {
        $idTag = $_GET['id'];
        
        
        if ( isset ( $_GET['id'] ) && !empty( $_GET['id'] ) ) {
            $idTag1 = $_GET['id'];
        }
        (int) $idTag1;
        
        if (is_int( $idTag1 ) ) {
            $idTag = $idTag1;
        } elseif ( isset ( $_GET['idt'] ) && !empty( $_GET['idt'] ) ) {
            $idTag = $_GET['idt'];
        }
        
        $pagedisprange = 2;//кількість додаткових посилань (номери сторінок)
        $itemsperpage = 3;//кількість записів, що виводяться 
        // якщо сторінка не задана, то будемо на 1-й
        if ( isset( $_GET['page'] ) ) { 
            $cpage = (int) $_GET['page']; 
        }else { 
            $cpage = 1; 
        }
        //визначаємо кількість записів у БД    
        if ( ! $itemscount = $this -> model -> get1($idTag) ){
            $row['error'] = "Помилка зєднання з БД!";
        }
        //повертаємо записи з БД в кількості $itemsperpage, починаючи з 
        //$cpage*$itemsperpage - $itemsperpage
        if ( $this -> model -> get( $cpage*$itemsperpage - $itemsperpage, $itemsperpage, $idTag ) ){
            $row1 = $this -> model -> get( $cpage*$itemsperpage - $itemsperpage, $itemsperpage, $idTag );
        }  else {
            $row['error'] = "Помилка зєднання з БД!";
        }
                
        $pagescount = ceil( $itemscount / $itemsperpage ); // к-ть сторінок
        $stpage = $cpage - $pagedisprange; // визначаємо, з якого номера будемо виводити сторінки
        if ( $stpage < 1 ) { 
            $stpage = 1; 
        } 
        $endpage = $cpage + $pagedisprange; // номер до якого будемо виводити
        if ( $endpage > $pagescount ) { 
            $endpage = $pagescount; 
        } 
        
        if ($cpage>1) {
            // перша
            $pag[] =  '<a href="'.$this -> _site.'page/1?idt='.$idTag.'"><<</a> ';
            // попередня
            $pag[] =  '<a href="'.$this -> _site.'page/'.($cpage-1).'?idt='.$idTag.'"><</a> ';
        }
        if ( $stpage > 1 ) $pag[] =  '... '; 
        for ($i=$stpage;$i<=$endpage;$i++) {
            if ($i==$cpage) {
                $pag[] =  '<strong>'.$i.'</strong> ';
            }else { 
                $pag[] =  '<a href="'.$this -> _site.'page/'.$i.'?idt='.$idTag.'">'.$i.'</a> ';
            }
        }
        
        if ($endpage<$pagescount) $pag[] =   '... '; 
        if ($cpage<$pagescount) {
            // наступна
            $pag[] =  '<a href="'.$this -> _site.'page/'.($cpage+1).'?idt='.$idTag.'">></a> ';
            // остання
            $pag[] =  '<a href="'.$this -> _site.'page/'.$pagescount.'?idt='.$idTag.'">>></a> ';
            
            
        }   
        $row = array ( 'row1' => $row1, 'pag' => $pag );
        
        return $row;
    }
    
    public function listTag() 
    {
        /**
        * виводить всі записи з БД для вказаного тега 
        * 
        */
        $row = $this ->pagination();
        
        //виводимо головну сторінку
        if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
            $main = parent :: $this -> _layout .'user_main.tpl';
            $mitteln = parent :: $this -> _guestbok ."user_list.tpl";
        }else{
            $main = parent :: $this -> _layout .'main.tpl';
            $mitteln = parent :: $this -> _guestbok ."list.tpl";
        }
        
        $this -> view -> actionIndex ($main, $mitteln, $row );
    }

}
?>