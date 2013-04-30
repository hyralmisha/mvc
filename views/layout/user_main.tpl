<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Гостьова книга</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/mvc/style/style.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<div id="wrapper">

<div id="header">
<div class="link">
    <form action="/mvc/guestbook/search" method="post">
        <input type="text" name="searchPhrase" value=""/> &nbsp;
        <input type="submit" value="Пошук" />
    </form> 
</div>
<div class="title">
<h1>Гостьова книга</h1>
</div>
<ul class="menu">
<li><a href="/mvc"> Головна </a></li>
<li><a href="/mvc/guestbook/add">Додати новий запис</a></li>
<li><a href="/mvc/authorization/out"> Вийти </a></li>
</ul>
</div>

<div id="content">
<div class="top"></div>
<div class="isi">
<p> 
    <?php
        echo $mitteln;
    ?>
</p>
</div>
<div id="kotakkiri"></div>

<div class="foot"></div>
</div>

<div id="middle">
    <div id="footer">&copy; <?php echo date(Y)." hyralm@mail.ru";?> </div>
</div>

</div>

</body>
</html>