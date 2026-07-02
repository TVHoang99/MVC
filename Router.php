<?php

namespace MVC;

class Router
{

    static public function parse($url, $request)
    {
        $url = trim($url);

        if ($url == "/" || $url == "/MVC/") {

            $request->controller = "tasks";
            $request->action = "index";
            $request->params = [];
        } else {

            $explode_url = array_values(array_filter(explode('/', $url), fn($s) => $s !== ''));
            $request->controller = $explode_url[0];
            $request->action = $explode_url[1] ?? 'index';
            $request->params = array_slice($explode_url, 2);
        }
    }
}
