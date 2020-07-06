<?php
namespace Controllers;

use Controller\Controllers\Controller;
use Http\Request;
use Http\Response;

class WelcomeController extends Controller
{
    public function test(Request $request){
        return (new Response('23333333'));
    }
}