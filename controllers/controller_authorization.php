<?php

class Controller_authorization extends Controller

{
    

    
    
    public function __construct()
    {
        //перегружаєм конструктор з батьківского класу
        parent :: __construct();
        // створюємо об'єкт класу Model_guestbook
        $this -> model = new Model_authorization();
    }
    

    
    
    public function registration()
    {
        /**
        * обробляє інформацію користувача, введену при реєстрації 
        * і записує її в БД 
        * 
        */
        
        //перевіряємо, чи був викликаний метод POST
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            
            //перевіряємо чи дійсний email
            if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $_POST['email'])) {
            $row['error'] = "Ваш email не дійсний!!! Введіть інший email!";
            $_POST['email'] = "";
        } else {
            $domain = preg_replace('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', '', $_POST['email']);
            if (!checkdnsrr($domain)) {
                $row['error'] = "Ваш email не дійсний!!! Введіть інший email!";
                $_POST['email'] = "";
            }
        }


            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['last_name'] ) && !empty( $_POST['last_name'] ) &&
                 isset( $_POST['first_name'] ) && !empty( $_POST['first_name'] ) &&
                 isset( $_POST['email'] ) && !empty( $_POST['email'] ) &&
                 isset( $_POST['password'] ) && !empty( $_POST['password'] ) &&
                 isset( $_POST['password_control'] ) && !empty( $_POST['password_control'] ) ) {
                
                //перевіряємо, чи співпадають паролі
                if ( $_POST['password_control'] == $_POST['password'] ) {
                    
                    //перевіряємо, чи існує введений логін
                    $email = stripslashes( trim( htmlspecialchars( $_POST['email'] ) ) );
                    
                        //з'єднюємося з БД
                        $row = $this -> model -> confirmation($email);
                    
                        if (!$row>0){
                            //екранізуємо потенційно небезпечні символи    
                            $lastName = stripslashes( trim( htmlspecialchars( $_POST['last_name'] ) ) );
                            $firstName = stripslashes( trim( htmlspecialchars( $_POST['first_name'] ) ) );

                            //забираємо лишні пропуски і застосовуємо шифрування
                            $password =  md5( trim( $_POST['password'] ) );

                            //викликаємо метод registration() класу 
                            //Model_authorization і передаємо йому введені у форму дані
                            if ( !$this -> model -> registration( $lastName, $firstName, $email, $password ) ){
                                $row['error'] = "Помилка зєднання з БД!";
                            }
                            //переходимо на сторінку підтвердження   
                            $this -> confirmation($email);
                            exit();
                        }else{
                            $row['error'] = "Цей email уже використовується іншим користувачем!";
                            $_POST['email'] = "";
                        }
                }else {
                    $row['error'] = "Введені паролі не співпадають!";
                }
            }else{
                $row['error'] = "Будь ласка заповніть всі поля!";
            }
            
            $row['last_name'] = $_POST['last_name'];
            $row['first_name'] = $_POST['first_name'];
            $row['email'] = $_POST['email'];
        }
        //вказуємо яким методом буде оброблятися форма
        $row['action']='registration';
        
        $main = parent :: $this -> _layout .'main.tpl';
        $mitteln = parent :: $this -> _authorization."registration.tpl";
        //викликаємо метод actionIndex() класу View
        $this-> view-> actionIndex( $main, $mitteln, $row );
    }
    

    
    
    public function confirmation( $email )
    {
        /**
        * виводить підтвердження про реєстрацію і 
        * відправляє підтвердження на email 
        * 
        */
        
        if ( $this -> model -> confirmation( $email ) ){
            $row = $this -> model -> confirmation( $email );

            //відправляємо повідомлення з підтвердженням на email
            $to = $row[ 'email' ]; //email одержувача
            $subject = "Реєстрація на сайті"; //тема повідомлення
            //текст повідомлення
            $message = $row[ 'first_name' ]." Ви успішно зареєструвалися на нашому сайті \n
                        Ваш логін: ".$row[ 'email' ];

            mail($to, $subject, $message, "From: hyralm@mail.ru");
            
            //повідомлення про підтвердження, яке виведеться у вікні браузера
            $row['message'] = $message;
            $mitteln = parent :: $this -> _authorization.'message.tpl';
                    
        }  else {
            $row['error'] = "Помилка зєднання з БД!";
            $mitteln = parent :: $this -> _authorization.'error.tpl';
        }
        $main = parent :: $this -> _layout .'main.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
  
    

    
    
    public function authorization ()
    {
        /**
        * створює сесії у системі для користувача і дозволяє увійти на сайт
        * 
        */
        
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            
            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['email'] ) && !empty( $_POST['email'] ) &&
                 isset( $_POST['password'] ) && !empty( $_POST['password'] ) ) {
                
                //видаляємо потенційно-небезпечні символи
                $email = stripslashes( trim( htmlspecialchars( $_POST['email'] ) ) );
                
                if ( $this -> model -> confirmation($email) ){
                    $row = $this -> model -> confirmation($email);
                    if ($row>0 && $row['password'] == md5( trim( $_POST['password'] ) ) ) {
                        $_SESSION['email'] = $email;
                        
                        if ( $this -> model -> dateEnter( $email ) ){
                            $row['message'] = "Вітаємо, ".$row['first_name'].
                                              ", ви увійшли на сайт!";
                        }  else {
                            $row['error'] = "Помилка зєднання з БД!";
                        }
                    }else{
                        $row['error'] = "Невірний логін або пароль!";
                    }
                }  else {
                    $row['error'] = "Помилка зєднання з БД!";
                }
            }  else {
                $row['error'] = "Введіть логін і пароль!";
            }
        }
        
        if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
            $main = parent :: $this -> _layout .'user_main.tpl';
            $mitteln = parent :: $this -> _authorization."message.tpl";
        }else{
            $main = parent :: $this -> _layout .'main.tpl';
            $mitteln = parent :: $this -> _authorization.'authorization.tpl';
        }
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    


    
    
    public function out()
    {
        /**
        * видаляє сесію у системі для користувача і дозволяє вийти з сайту
        * 
        */
        session_destroy();
        $_SESSION = array();
        if ( isset( $_COOKIE[ session_name() ] ) ) {
            setcookie( session_name(), '', time() - 3600 ); 
        }
        $this -> authorization ();
    }
    
    

    
    
    protected function generateCode( $length = 6 )
    {
        /**
        * генерує випадковий код, довжиною $length
        * 
        *@param int $length --- довжина коду
        */
        $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen( $string )-1;
        while ( strlen( $code ) < $length ){
            $code .= $string[ mt_rand( 0, $clen ) ];
        }
        return $code;
    }

    

    
    
    public function passRecovery()
    {
        /**
        * виводить форму для відновлення паролю, на введений email відправляється
        * лист з hash_key (дійсний протягом 24 годин)
        * 
        */
        
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['email'] ) && !empty( $_POST['email'] ) ) {
                //видаляємо потенційно-небезпечні символи
                $email = stripslashes( trim( htmlspecialchars( $_POST['email'] ) ) );
                
                if ( $this -> model -> confirmation($email) ){
                    $row = $this -> model -> confirmation($email);
                
                    if ($row > 0){
                        
                        $id = $this -> generateCode(30);
                        $this -> model -> hashKeySave($email, $id);
                        $this -> model -> dateChange($email);
                        
                        //відправляємо повідомлення з підтвердженням на email
                        $to = $row[ 'email' ]; //email одержувача
                        $subject = "Відновлення паролю!";
                        $message = "Для відновлення паролю Вам потрібно пройти по наступному
                                    посиланню <a href = \"http://localhost/mvc/authorization/passForm/$id  \"> 
                                    http://localhost/mvc/authorization/passForm/$id </a> ";
                        mail($to, $subject, $message, "From: hyralm@mail.ru");

                        $row['message'] = "На Ваш email ".$to.
                                          "відправлено листа! Щоб відновити 
                                          пароль, слідуйте інструкціям у листі!";
                        $main = parent :: $this -> _layout .'main.tpl';
                        $mitteln = parent :: $this -> _authorization.'message.tpl';
                        $this -> view -> actionIndex( $main, $mitteln, $row );
                        exit();
                    }else {
                        $row['error'] = "Цей email незареєстрований!";
                    }
                }  else {
                    $row['error'] = "Помилка зєднання з БД!";
                }
            }else {
                $row['error'] = "Введіть email!";
            }
        }
        $main = parent :: $this -> _layout .'main.tpl';
        $mitteln = parent :: $this -> _authorization.'pass_recovery.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    
    

    
    
    public function passForm()
    {
        /**
        * виводить форму для введення нового паролю
        * 
        */
        
        if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) ) {
            $id = $_GET['id'];
            
            if ( $this -> model -> check($id) ) {
                $row = $this -> model -> check($id);
                $dateCheck = time(); 
                $dateChange = $row['date_change'];
                if ($row > 0 && $dateCheck - $dateChange < 86400 ){
                    $mitteln = parent :: $this -> _authorization."pass_form.tpl";
                }else {
                    $mitteln = parent :: $this -> _authorization."error.tpl";
                    $row['error'] = "Дане посилання неактуальне! 
                                    Спробуйте отримати інше посилання!";
                }   
            }  else {
                $row['error'] = "Помилка зєднання з БД!";
            }
        }else {
            $mitteln = parent :: $this -> _authorization."error.tpl";
        }
        $main = parent :: $this -> _layout .'main.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
     
    

    
    
    public function passChange()
    {
        /**
        * замінює пароль у БД на новий
        * 
        */
        
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            
            $row['email'] = $_POST['email'];
            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['password'] ) && !empty( $_POST['password'] ) &&
                 isset( $_POST['password_control'] ) && !empty( $_POST['password_control'] ) ) {

                if ( $_POST['password_control'] == $_POST['password'] ) {
                    $email = $_POST['email'];
                    $password = md5 ( trim( $_POST['password'] ) );
                    
                    if ( $this -> model -> passChange( $email, $password ) ) {
                        $row['message'] = "Ви успішно відновили пароль!";
                        $main = parent :: $this -> _layout .'main.tpl';
                        $mitteln = parent :: $this -> _authorization."message.tpl";

                        $this -> view -> actionIndex( $main, $mitteln, $row );
                    }  else {
                        $row['error'] = "Помилка зєднання з БД!";
                    }
                }else {
                    $row['error'] = "Введені паролі не співпадають!";
                }
            }else {
                $row['error'] = "Заповніть форму!";
            }
        }
        $mitteln = parent :: $this -> _authorization."pass_form.tpl";
        $main = parent :: $this -> _layout .'main.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    
}
?>
