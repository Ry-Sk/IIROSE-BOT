<?php


namespace Controller\Controllers;

use File\Path;
use Http\Request;
use Http\Response;
use View\View;

class ResourceController extends Controller
{
    public function handle(Request $request)
    {
        $response=new Response();
        if (file_exists(Path::public($request->getPathInfo()))
            && is_file(Path::public($request->getPathInfo()))) {
            $mine=Path::get_mine(Path::get_extension(Path::public($request->getPathInfo())));
            switch ($mine) {
                case 1:
                    $response->headers->set('Content-Type', 'text/html');
                    $response->setContent(highlight_file(Path::public($request->getPathInfo()), true));
                    break;
                default:
                    $response->headers->set('Content-Type', $mine);
                    $response->setContent(file_get_contents(Path::public($request->getPathInfo())));
            }
        } else {
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(404);
            $response->setContent(View::view('errors/404')->with('path', $request->getPathInfo())->render());
        }
        return $response;
    }
}
