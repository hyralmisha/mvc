<?php

class Controller_guestbook extends Controller

{
    
    protected $_site = "http://localhost/mvc/guestbook/main/";
    
    public function __construct()
    {
        //перегружаєм конструктор з батьківского класу
        parent :: __construct();
        // створюємо об'єкт класу Model_guestbook
        $this -> model = new Model_guestbook();
    }
    
    public function pagination()
    {
        $pagedisprange = 2;//кількість додаткових посилань (номери сторінок)
        $itemsperpage = 3;//кількість записів, що виводяться 
        // якщо сторінка не задана, то будемо на 1-й
        if ( isset( $_GET['page'] ) ) { 
            $cpage = (int) $_GET['page']; 
        }else { 
            $cpage = 1; 
        }
        //визначаємо кількість записів у БД    
        if ( ! $itemscount = $this -> model -> get1() ){
            $row['error'] = "Помилка зєднання з БД!";
        }
        //повертаємо записи з БД в кількості $itemsperpage, починаючи з 
        //$cpage*$itemsperpage - $itemsperpage
        if ( $this -> model -> get( $cpage*$itemsperpage - $itemsperpage, $itemsperpage ) ){
            $row1 = $this -> model -> get( $cpage*$itemsperpage - $itemsperpage, $itemsperpage );
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
            $pag[] =  '<a href="'.$this -> _site.'page/1"><<</a> ';
            // попередня
            $pag[] =  '<a href="'.$this -> _site.'page/'.($cpage-1).'"><</a> ';
        }
        if ( $stpage > 1 ) $pag[] =  '... '; 
        for ($i=$stpage;$i<=$endpage;$i++) {
            if ($i==$cpage) {
                $pag[] =  '<strong>'.$i.'</strong> ';
            }else { 
                $pag[] =  '<a href="'.$this -> _site.'page/'.$i.'">'.$i.'</a> ';
            }
        }
        
        if ($endpage<$pagescount) $pag[] =   '... '; 
        if ($cpage<$pagescount) {
            // наступна
            $pag[] =  '<a href="'.$this -> _site.'page/'.($cpage+1).'">></a> ';
            // остання
            $pag[] =  '<a href="'.$this -> _site.'page/'.$pagescount.'">>></a> ';
            
            
        }   
        $row = array ( 'row1' => $row1, 'pag' => $pag );
        
        return $row;
    }
    
    
    public function add()
    {
        /**
        * опрацьовує дані введені у форму, і викликає метод add() класу 
        * Model_guestbook, для запису даних у БД 
        * 
        */
        
        $row['header'] = "Новий запис!";
        //перевіряємо, чи був викликаний метод POST
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){

            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['name'] ) && !empty( $_POST['name'] ) &&
                 isset( $_POST['msgShort'] ) && !empty( $_POST['msgShort'] ) &&
                 isset( $_POST['msgFull'] ) && !empty( $_POST['msgFull'] ) ){

                //екранізуємо потенційно небезпечні символи    
                $name = stripslashes( trim( htmlspecialchars( $_POST['name'] ) ) );
                $msgShort = stripslashes( trim( htmlspecialchars( $_POST['msgShort'] ) ) );
                $msgFull = stripslashes( trim( htmlspecialchars( $_POST['msgFull'] ) ) );
                $tags = stripslashes( trim( htmlspecialchars( $_POST['tags'] ) ) );
            }else{
                $row['error'] = "Будь ласка заповніть всі поля!<br/>";
            }    
            
            //викликаємо метод add() класу Model_guestbook і передаємо 
            //йому введені у форму дані
            if ( empty( $row['error'] ) && 
                $this -> model -> add( $name, $msgShort, $msgFull ) ) {
                $tags = explode(',', $tags);
                $this -> model -> addTags( $tags );
                //переходимо на головну сторінку    
                $this -> main();
                exit();
            }  else {
                $row['name'] = $_POST['name'];
                $row['msg_short'] = $_POST['msgShort'];
                $row['msg_full'] = $_POST['msgFull'];
            } 
        } 
        //вказуємо яким методом буде оброблятися форма
        $row['action'] = 'add';
        $main = self :: $this -> _layout .'user_main.tpl';
        $mitteln = self :: $this -> _guestbok ."form.tpl";
        //викликаємо метод actionIndex() класу View
        $this-> view-> actionIndex( $main, $mitteln, $row );
        
    }
       
    public function main()
    {
        /**
        * виводить всі записи з БД на головну сторінку 
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
    
    public function delete()
    {
        /**
        * видаляє запис з БД 
        * 
        */
        
        //отримуємо id запису, який треба видалити
        //якщо id існує і непоронє присвоюємо його $del і приводимо до типу int
        if ( isset ( $_GET['id'] ) && !empty( $_GET['id'] ) &&
             isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] ) ) {
            $del = $_GET['id'];
            (int) $del;
            //викликаємо метод delete() класу Model_guestbook для видалення запису з БД
            $this -> model -> delete( $del );              
        }
        //переходимо на головну сторінку
        $this -> main();
    }
    
    public function edit()
    {
        /**
        * редагує записи у БД 
        * 
        */
        
//отримуємо id запису, який треба відредагувати
        //якщо id існує і непорожнє присвоюємо його $edit і приводимо до типу int
        if ( isset ( $_GET['id'] ) && !empty( $_GET['id'] ) &&
             isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] ) ){
            $edit = $_GET['id'];
            (int) $edit;
        
            //викликаємо метод edit() класу Model_guestbook для заповнння форми 
            //редагування запису 
            if ( $this -> model -> edit( $edit ) ){
                $result = $this -> model -> edit( $edit );
                $row = mysqli_fetch_array( $result );
            }  else {
                $row['error'] = "Помилка зєднання з БД!";
            }
            $row['action']='edit';
        }else{
            $row['error'] = "Ви не можете редагувати цей запис!"; 
        }
    
        //перевіряємо, чи був викликаний метод POST
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            $row['error'] = '';
            //перевіряємо чи існують введені у формі значення, і чи вони не порожні
            if ( isset( $_POST['name'] ) && !empty( $_POST['name'] ) &&
                 isset( $_POST['msgShort'] ) && !empty( $_POST['msgShort'] ) &&
                 isset( $_POST['msgFull'] ) && !empty( $_POST['msgFull'] ) ) {
 
                //екранізуємо потенційно небезпечні символи    
                $name = stripslashes( trim( htmlspecialchars( $_POST['name'] ) ) );
                $msgShort = stripslashes( trim( htmlspecialchars( $_POST['msgShort'] ) ) );
                $msgFull = stripslashes( trim( htmlspecialchars( $_POST['msgFull'] ) ) );
                $edit = (int) $_POST['edit'];
            }else{
                $row['error'] = "Будь ласка заповніть всі поля!<br/>";
            }    
                //викликаємо метод editor() класу Model_guestbook, який редагує дані у БД
            if ( empty( $row['error'] ) ) { 
                 $this -> model -> editor( $name, $msgShort, $msgFull, $edit ); 
                 //переходимо на головну сторінку    
                 $this -> main();
                 exit();
            }  else {
                $row['id'] = $_POST['edit'];
                $row['name'] = $_POST['name'];
                $row['msg_short'] = $_POST['msgShort'];
                $row['msg_full'] = $_POST['msgFull'];
                $row['action']='edit';
            }
        }
        //викликаємо метод actionIndex() класу View
        //виводимо форму для редагування
            $row['header'] = "Редагування запису!";
        
        $main = parent :: $this -> _layout .'user_main.tpl';
        $mitteln = parent :: $this -> _guestbok .'form.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
        
    }
    
    public function view()
    {
        /**
        * виводить окремий запис у вікні браузера
        * 
        */
        
        //отримуємо id запису, який треба показати
        //якщо id існує і непоронє присвоюємо його $view і приводимо до типу int
        if ( isset ( $_GET['id'] ) && !empty( $_GET['id'] )  ){
            $view = $_GET['id'];
            (int) $view;
        
            //викликаємо метод view() класу Model_guestbook для виводу окремого запису
                $result = $this -> model -> view($view);
                $row = mysqli_fetch_array( $result );

                $dateCreate = $row['date_create'];
                $dateEdit = $row['date_edit'];

                if( $dateCreate == $dateEdit ) {
                    $row['date'] = "Дата створення: $dateCreate <br/>";
                }else{
                    $row['date'] = "Дата створення: $dateCreate <br/>       
                             Дата редагування: $dateEdit";    
                }
        }
        //викликаємо метод actionIndex() класу View
        if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
            $main = parent :: $this -> _layout .'user_main.tpl';
            $mitteln = parent :: $this -> _guestbok ."user_view.tpl";
        }else{
            $main = parent :: $this -> _layout .'main.tpl';
            $mitteln = parent :: $this -> _guestbok ."view.tpl";
        }
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    
    public function search()
    {
        
        
        //екранызуэмо потенцыйно небезпечны символи
        $searchPhrase = stripslashes( htmlspecialchars( $_POST['searchPhrase'] ) );
        //якщо є слова довжиною менше 3 символів, замінюємо їх пропусками
        $searchPhrase = trim( preg_replace( "/\s(\S{1,2})\s/", " ",$searchPhrase ) );
        //видаляємо лишні пропуски
        $searchPhrase = preg_replace( "/ +/", " ", $searchPhrase );
        
        
            //передаємо пошукову фразу функції, яка відповідає за здійснення пошуку у БД
            $row['row1'] = $this -> model -> search ( $searchPhrase );
           
       if ($row['row1']){ 
            if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
                $main = parent :: $this -> _layout .'user_main.tpl';
                $mitteln = parent :: $this -> _guestbok ."user_search.tpl";
            }else{
                $main = parent :: $this -> _layout .'main.tpl';
                $mitteln = parent :: $this -> _guestbok ."search.tpl";
            }
        }  else {
            $row['message'] = "Пошук не дав результату!";
        
            if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
                $main = parent :: $this -> _layout .'user_main.tpl';
                $mitteln = parent :: $this -> _authorization ."message.tpl";
            }else{
                $main = parent :: $this -> _layout .'main.tpl';
                $mitteln = parent :: $this -> _authorization ."message.tpl";
            }
        }
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
       
}

?>