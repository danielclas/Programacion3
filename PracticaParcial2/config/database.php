<?php

namespace Config;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database{

    public function __construct(){
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'=>'mysql',
            'host'=>'localhost',
            'database'=>'parcial2',
            'username'=>'root',
            'password'=>'',
            'charset'=>'utf8',
            'collation'=>'utf8_unicode_ci',
            'prefix'=>''
        ]);

        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}