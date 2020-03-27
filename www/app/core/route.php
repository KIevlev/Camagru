<?php
class Route
{
    static function console_log($data){ // сама функция
        if(is_array($data) || is_object($data)){
            echo("<script>console.log('php_array: ".json_encode($data)."');</script>");
        } else {
            echo("<script>console.log('php_string: ".$data."');</script>");
        }
    }

    static function start()
    {

        // контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';
        $parameter = '';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
      //  echo("<script>console.log('php_array: ".json_encode($routes)."');</script>"); //delete later
        // получаем имя контроллера
        if ( !empty($routes[1]) )
        {
            $controller_name = $routes[1];
         //   Route::console_log("controller name - ".$controller_name);
        }

        // получаем имя экшена
        if ( !empty($routes[2]) )
        {
            $action_name = $routes[2];
         //   Route::console_log("action name name - ".$action_name);
        }
        if ( !empty($routes[3]))
        {
            $parameter = $routes[3];
        }
        elseif (isset($_POST))
        {
            $parameter = $_POST;
        }
        // добавляем префиксы
        $model_name = 'model_'.$controller_name;
        $controller_name = 'controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)

        $model_file = strtolower($model_name).'.php';
        $model_path = "app/models/".$model_file;
        if(file_exists($model_path))
        {
            require "app/models/".$model_file;
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "app/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            include "app/controllers/".$controller_file;
        }
        else
        {
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу сделаем редирект на страницу 404
            */
            Route::ErrorPage404();
            return ;
        }

        // создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            // вызываем действие контроллера
            $controller->$action($parameter);
        }
        else
        {
            // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();
        }
    }




    static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
        ?>
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>404 :(</title>
	<link rel="stylesheet" type="text/css" href="style/404.css">
</head>
<body>
<div class="container">
<div class="box">
<h1>404</h1>
<p>Sorry, the requested page is not found</p>
<div class="return">
<div><a href="/" class="button">Main page</a></div>
</div>
</div>
</div>
</body>
<?php
    }
}
