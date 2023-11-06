<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Route;

class HomeController extends Controller
{
    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function __invoke(): string
    {
        return '<h1>Hello world!! Im home page</h1>';
    }
}
