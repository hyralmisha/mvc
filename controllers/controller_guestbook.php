<?php

class Controller_guestbook extends Controller

{
    
    public function __construct()
    {
        //перегружаєм конструктор з батьківского класу
        parent :: __construct();
        // створюємо об'єкт класу Model_guestbook
        $this -> model = new Model_guestbook();
    }
    
    public function add()
    {
        /**
        * опрацьовує дані введені у форму, і викликає метод add() класу 
        * Model_guestbook, для запису даних у БД 
        * 
        */
        
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
                
                //викликаємо метод add() класу Model_guestbook і передаємо 
                //йому введені у форму дані
                if ($this -> model -> add( $name, $msgShort, $msgFull ) ){
                    //переходимо на головну сторінку    
                    $this -> main();
                    exit();
                }  else {
                    $row['error'] = "Помилка зєднання з БД!";
                    
                }
            }else{
                $row['error'] = "Будь ласка заповніть всі поля!";
            }
        } 
        //вказуємо яким методом буде оброблятися форма
        $row['action'] = 'add';
        $main = self :: LAYOUT.'main.tpl';
        $mitteln = self :: GUESTBOOK."form.tpl";
        //викликаємо метод actionIndex() класу View
        $this-> view-> actionIndex( $main, $mitteln, $row );
        
    }
       
    function main()
    {
        /**
        * виводить всі записи з БД на головну сторінку 
        * 
        */
        
        //викликаємо метод get() класу Model_guestbook, і передаємо 
        //$row масив всіх записів з БД
        if ( $this -> model -> get() ){
            $row = $this -> model -> get();
        }  else {
            $row['error'] = "Помилка зєднання з БД!";
        }
        //виводимо головну сторінку
        if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
            $main = parent :: LAYOUT.'user_main.tpl';
            $mitteln = parent :: GUESTBOOK."user_list.tpl";
        }else{
            $main = parent :: LAYOUT.'main.tpl';
            $mitteln = parent :: GUESTBOOK."list.tpl";
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
        if ( isset ( $_GET['id'] ) && !empty( $_GET['id'] ) ){
            $del = $_GET['id'];
            (int) $del;
            //викликаємо метод delete() класу Model_guestbook для видалення запису з БД
            if ( !$this -> model -> delete( $del ) ){
                $row['error'] = "Помилка зєднання з БД!";              
            }
        }else{
            $row['error'] = "Ви не можете видалити цей запис!"; 
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
             isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
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
            //перевіряємо чи існують введені у формі значення, і чи вони не порожні
            if ( isset( $_POST['name'] ) && !empty( $_POST['name'] ) &&
                 isset( $_POST['msgShort'] ) && !empty( $_POST['msgShort'] ) &&
                 isset( $_POST['msgFull'] ) && !empty( $_POST['msgFull'] ) ) {
 
                $name = $_POST['name'];
                $msgShort = $_POST['msgShort'];
                $msgFull = $_POST['msgFull'];
                $edit = (int) $_POST['edit'];
                //викликаємо метод editor() класу Model_guestbook, який редагує дані у БД
                if ( ! $this -> model -> editor( $name, $msgShort, $msgFull, $edit ) ){
                    $row['error'] = "Помилка зєднання з БД!";
                }
                
                //переходимо на головну сторінку    
                $this -> main();
                exit();
            }
        }  else {
        //викликаємо метод actionIndex() класу View
        //виводимо форму для редагування
        $main = parent :: LAYOUT.'main.tpl';
        $mitteln = parent :: GUESTBOOK.'form.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
        }
    }
    
    public function view()
    {
        /**
        * виводить окремий запис у вікні браузера
        * 
        */
        
        //отримуємо id запису, який треба показати
        //якщо id існує і непоронє присвоюємо його $view і приводимо до типу int
        if ( isset ( $_GET['id'] ) && !empty( $_GET['id'] ) && 
             isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] ) ){
            $view = $_GET['id'];
            (int) $view;
        
            //викликаємо метод view() класу Model_guestbook для виводу окремого запису
            if ( $this -> model -> view($view) ){
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
            }  else {
                $row['error'] = "Помилка зєднання з БД!";
            }
        }else{
            $row['error'] = "Ви не можете переглянути цей запис!"; 
        }
        //викликаємо метод actionIndex() класу View
        if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
            $main = parent :: LAYOUT.'user_main.tpl';
            $mitteln = parent :: GUESTBOOK."user_view.tpl";
        }else{
            $main = parent :: LAYOUT.'main.tpl';
            $mitteln = parent :: GUESTBOOK."view.tpl";
        }
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    
    
}

?>
