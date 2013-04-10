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
                
                //викликаємо метод add() класу Model_guestbook і передаємо йому введені у форму дані
                $this -> model -> add( $name, $msgShort, $msgFull );
                //переходимо на головну сторінку    
                $this -> main();
                
            }else{
                //$error = "Будь ласка заповніть всі поля!";
            }
        }
        //викликаємо метод actionIndex() класу View
        $main = 'main.tpl';
        $mitteln = "form.tpl";
        $this-> view-> actionIndex( $main, $mitteln );
    }
       
    function main()
    {
        //викликаємо метод get() класу Model_guestbook, і передаємо $listAll масив всіх записів з БД
        $listAll = $this->model->get();
        //виводимо головну сторінку
        $main = 'main.tpl';
        $mitteln = "list.tpl";
        $this -> view -> actionIndex ($main, $mitteln );
    }    
    
    public function delete()
    {
        //отримуємо масив із рядка URL
        $routes = explode( '/', $_SERVER['REQUEST_URI'] );
        //отримуємо id запису, який треба видалити
        if ( !empty( $routes[3] ) ) {
                $id = $routes[3];
        }
        //якщо id існує і непоронє присвоюємо його $del і приводимо до типу int
        if ( isset ( $id ) && !empty( $id ) ){
            $del = (int) $id;
            //викликаємо метод delete() класу Model_guestbook для видалення запису з БД
            $this -> model -> delete( $del );
        }else{
            
            //echo $error = 'Не видалено'.$id;
        }
        //переходимо на головну сторінку
        echo    "<script type=\"text/javascript\">
                window.location = \"/guestbook/main\"
                </script>";
    }
    
    public function edit()
    {
        //отримуємо масив із рядка URL
        $routes = explode( '/', $_SERVER['REQUEST_URI'] );        
        //отримуємо id запису, який треба відредагувати
        if ( !empty( $routes[3] ) ) {
            $id = $routes[3];
        }
        //якщо id існує і непоронє присвоюємо його $del і приводимо до типу int
        if ( isset( $id ) && !empty( $id ) ) {
            $edit = (int) $id;
            //викликаємо метод edit() класу Model_guestbook для виводу форми редагування запису 
            $result = $this -> model -> edit( $edit );
            
            $row = mysqli_fetch_array( $result );
         
            $name = $row['name'];    
            $msgShort = $row['msg_short'];
            $msgFull = $row['msg_full'];
        }
        //виводимо форму для редагування
        $main = 'main.tpl';
        $mitteln = "form.tpl";
        $this-> view-> actionIndex( $main, $mitteln );
        
    }
        
    public function editor() 
    {
        //перевіряємо, чи був викликаний метод POST
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['name'] ) && !empty( $_POST['name'] ) &&
                 isset( $_POST['msgShort'] ) && !empty( $_POST['msgShort'] ) &&
                 isset( $_POST['msgFull'] ) && !empty( $_POST['msgFull'] ) ) {
 
                $name = $_POST['name'];
                $msgShort = $_POST['msgShort'];
                $msgFull = $_POST['msgFull'];
                $edit = $_POST['edit'];
                //викликаємо метод editor() класу Model_guestbook, який редагує дані у БД
                $this -> model -> editor( $name, $msgShort, $msgFull, $edit );
                //переходимо на головну сторінку    
                $this -> main();
            }else{
                    //$error = "Будь ласка заповніть всі поля!";
                }
        }
        //викликаємо метод actionIndex() класу View
        $main = 'main.tpl';
        $mitteln = 'form.tpl';
        $this -> view -> actionIndex( $main, $mitteln );
    }
    
    public function view()
    {
        //отримуємо масив із рядка URL
        $routes = explode( '/', $_SERVER['REQUEST_URI'] );        
        //отримуємо id запису, який треба відредагувати
        if ( !empty( $routes[3] ) ) {
            $id = $routes[3];
        }
        //якщо id існує і непоронє присвоюємо його $del і приводимо до типу int
        if ( isset( $id ) && !empty( $id ) ) {
            $view = (int) $id;
            //викликаємо метод view() класу Model_guestbook для виводу окремого запису
            $result = $this -> model -> view($view);
            
            $row = mysqli_fetch_array( $result );
                
            $id = $row['id'];
            $dateCreate = $row['date_create'];
            $dateEdit = $row['date_edit'];
            $name = $row['name'];
            $msgShort = $row['msg_short'];
            $msgFull = $row['msg_full'];

            if( $dateCreate == $dateEdit ) {
                $date = "Дата створення: $dateCreate <br/>";
            }else{
                $date = "Дата створення: $dateCreate <br/>       
                         Дата редагування: $dateEdit";    
            }
        }
        //викликаємо метод actionIndex() класу View
        $main = 'main.tpl';
        $mitteln = 'view.tpl';
        $this -> view -> actionIndex( $main, $mitteln );
    }
    
    
}

?>
