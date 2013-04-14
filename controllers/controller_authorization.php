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
    
    public function registration(){
     //перевіряємо, чи був викликаний метод POST
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){

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
                    $row = $this -> model -> confirmation($email);
                    if (!$row>0){
                        //екранізуємо потенційно небезпечні символи    
                        $lastName = stripslashes( trim( htmlspecialchars( $_POST['last_name'] ) ) );
                        $firstName = stripslashes( trim( htmlspecialchars( $_POST['first_name'] ) ) );

                        //забираємо лишні пропуски і робимо подвійне шифрування
                        $password = md5( md5( trim( $_POST['password'] ) ) );


                        //викликаємо метод registration() класу Model_authorization і передаємо йому введені у форму дані
                        $this -> model -> registration( $lastName, $firstName, $email, $password );
                        //переходимо на сторінку підтвердження   
                        $this -> confirmation($email);
                    }else{
                        $row['error'] = "Цей email уже використовується іншим користувачем!";
                        $_POST['email'] = "";
                    }
                
                }  else {
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
        $main = parent :: LAYOUT.'main.tpl';
        $mitteln = parent :: AUTHORIZATION."registration.tpl";
        //викликаємо метод actionIndex() класу View
        $this-> view-> actionIndex( $main, $mitteln, $row );
    }
    
    public function confirmation( $email )
    {
        $row = $this -> model -> confirmation( $email );
        
        //відправляємо повідомлення з підтвердженням на email
        $to = $row[ 'email' ]; //email одержувача
        $subject = "Реєстрація на сайті";
        $message = $row[ 'first_name' ]." Ви успішно зареєструвалися на нашому сайті \n
                    Ваш логін: ".$row[ 'email' ];
        
        mail($to, $subject, $message, "From: hyralm@mail.ru");
        
        $main = parent :: LAYOUT.'main.tpl';
        $mitteln = parent :: AUTHORIZATION.'confirmation_reg.tpl';
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    
    public function authorization ()
    {
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            //перевіряємо чи існують введені у форму значення, і чи вони не порожні
            if ( isset( $_POST['email'] ) && !empty( $_POST['email'] ) &&
                 isset( $_POST['password'] ) && !empty( $_POST['password'] ) ) {
                //видаляємо потенційно-небезпечні символи
                $email = stripslashes( trim( htmlspecialchars( $_POST['email'] ) ) );
                $row = $this -> model -> confirmation($email);
                if ($row>0 && $row['password'] = md5( md5( trim( $_POST['password'] ) ) ) ) {
                    $_SESSION['email'] = $email;
                }else{
                    $row['error'] = "Невірний логін або пароль!";
                }
            }  else {
                $row['error'] = "Введіть логін і пароль!";
            }
        }
        if ( isset( $_SESSION['email'] ) && !empty( $_SESSION['email'] )){
            $main = parent :: LAYOUT.'user_main.tpl';
            $mitteln = parent :: AUTHORIZATION."confirmation_auth.tpl";
        }else{
            $main = parent :: LAYOUT.'main.tpl';
            $mitteln = parent :: AUTHORIZATION.'authorization.tpl';
        }
        $this -> view -> actionIndex( $main, $mitteln, $row );
    }
    
    public function out()
    {
        session_destroy();
        $_SESSION = array();
        if ( isset( $_COOKIE[ session_name() ] ) ) {
            setcookie( session_name(), '', time() - 3600 ); 
        }
        $this -> authorization ();
    }
}
?>
