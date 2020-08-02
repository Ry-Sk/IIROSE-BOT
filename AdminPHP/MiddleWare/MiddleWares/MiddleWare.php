<?php
namespace MiddleWare\MiddleWares;

use Closure;
use Http\Request;
use Http\Response;

interface MiddleWare
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function hanlde(Request $request, Closure $next);
}
