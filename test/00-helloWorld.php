<?php

use File\Path;

class Foo {
    public function bar($sss){
        var_dump($sss());
        return 'php返回';
    }
}
$v8 = new V8Js();
$v8->setModuleLoader(function ($parms){
    return file_get_contents(Path::formt_file(ROOT.'/js/'.$parms));
});
$v8->setTimeLimit(5000);
$v8->s=new Foo();
$v8->executeString(file_get_contents(Path::formt_file(ROOT.'/js/index.js')),'V8Js::executeString()',V8Js::FLAG_FORCE_ARRAY);