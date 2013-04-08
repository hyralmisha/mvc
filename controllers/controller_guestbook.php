<?php

class Controller_guestbook extends Controller

{
    
    function add()
    {
       /**
        * записує до БД новий запис
        * 
        * @param sting $name назва запису
        * @param sting $msgShort опис запису
        * @param sting $msgFull текст запису
        *
        * @param $dateCreate, $dateEdit --- час і дата створення і редагування 
        * запису (поточні час і дата)
        */
        
        /*
         * створюємо об'єкт класу Model_add
         *          клас Model_add() описаний у файлі "models/model_add.php"
         */
        $this->model = new Model_guestbook();
        
        /*
         * перевіряємо чи був використаний для запиту сторынки метод POST
         *      --якщо так, то перевыряэмо чи у всы поля форми введені 
         *        непорожні дані
         *          --якщо так, то екранізуємо введені дані
         *          --інакше, виводиться повідомлення про помилку
         */
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            if (isset($_POST['name'])&&!empty($_POST['name'])&&
                isset($_POST['msgShort'])&&!empty($_POST['msgShort'])&&
                isset($_POST['msgFull'])&&!empty($_POST['msgFull'])){
                    $name=stripslashes(trim(htmlspecialchars($_POST['name'])));
                    $msgShort=stripslashes(trim(htmlspecialchars($_POST['msgShort'])));
                    $msgFull=stripslashes(trim(htmlspecialchars($_POST['msgFull'])));
            
                    /*
                     * викликаємо метод actionIndex() класу Model_add 
                     * і передаємо йому введені у форму дані, для збереження 
                     * до БД
                     */
                    $this->model->add($name,$msgShort,$msgFull);
                    
                    $this->main();
            }else{
                echo "Будь ласка заповніть всі поля!";
            }
        }
        $main='main.tpl';
        $mitteln="form.tpl";
        $this->view->actionIndex($main, $mitteln);
    }
       
    function main()
    {
        
        $this->model = new Model_guestbook();
        ob_start();
        $listAll = $this->model->get();
        
        include_once 'views/guestbook/list.tpl';
        
        $buffer = ob_get_contents();
        ob_end_clean();
        $mitteln=$buffer;
        include_once 'views/layout/main.tpl';
    }    
    
    function delete()
    {
     
        $this->model = new Model_guestbook();
        
        $routes = explode('/', $_SERVER['REQUEST_URI']);
        //отримуємо id
            if ( !empty($routes[3]) )
            {
                    $id = $routes[3];
            }
        
        if (isset($id)&&!empty($id)){
            $del=(int)$id*1;
    
        $this->model->delete($del);
        }else{
            
            echo 'Не видалено'.$id;
        }
        echo "<script type=\"text/javascript\">
            window.location = \"/guestbook/main\"
            </script>";
    }
    
    function edit()
    {
        $this->model = new Model_guestbook();
        ob_start();
        $routes = explode('/', $_SERVER['REQUEST_URI']);        
            //отримуємо id
		if ( !empty($routes[3]) )
		{
			$id = $routes[3];
		}
            
                if (isset($id)&&!empty($id)){
            $edit=(int)$id*1;
            
            $result=$this->model->edit($edit);
            $row=mysqli_fetch_array($result);
                $name=$row['name'];    
                $msgShort=$row['msg_short'];
                $msgFull=$row['msg_full'];
                }
            
            echo "<a href=\"/guestbook/main\">На головну</a><br/><br/>";
            include_once 'views/guestbook/edit.tpl';
            
            $buffer = ob_get_contents();
            ob_end_clean();
            $mitteln=$buffer;
            include_once 'views/layout/main.tpl';
        }
        
        function editor() 
        {
            $this->model = new Model_guestbook();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['name']) && !empty($_POST['name']) &&
                        isset($_POST['msgShort']) && !empty($_POST['msgShort']) &&
                        isset($_POST['msgFull']) && !empty($_POST['msgFull'])) {
                    $name = $_POST['name'];
                    $msgShort = $_POST['msgShort'];
                    $msgFull = $_POST['msgFull'];
                    $edit = $_POST['edit'];

                    $this->model->editor($name, $msgShort, $msgFull, $edit);
                    $this->main();
                }else{
                    echo "Будь ласка заповніть всі поля!";
                }
            }
        $main='main.tpl';
        $mitteln="form.tpl";
        $this->view->actionIndex($main, $mitteln);
    }
    
    function view()
    {
     
        $this->model = new Model_guestbook();
        
        $routes = explode('/', $_SERVER['REQUEST_URI']);        
            //отримуємо id
		if ( !empty($routes[3]) )
		{
			$id = $routes[3];
		}
            
                if (isset($id)&&!empty($id)){
            $view=(int)$id*1;
            
            $result=$this->model->view($view);
            $row=mysqli_fetch_array($result);
                $id=$row['id'];
            $dateCreate=$row['date_create'];
            $dateEdit=$row['date_edit'];
            $name=$row['name'];
            $msgShort=$row['msg_short'];
            $msgFull=$row['msg_full'];

            if($dateCreate==$dateEdit){
                $date="Дата створення: $dateCreate<br/>";
            }else{
                $date="Дата створення: $dateCreate<br/>       
                       Дата редагування: $dateEdit";    
            }
                }
    
            
            include_once 'views/guestbook/view.tpl';

        }
    
    
}

?>
