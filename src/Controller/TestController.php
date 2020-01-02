<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 14/05/2019
 * Time: 13:55
 */

namespace App\Controller;

use App\Service\Greeting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test", name="tests")
 */
class TestController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
    }

    /**
     * @Route("/{name}", name="_index")
     */
    public function index(Request $request,$name)
    {
        $number = uniqid();
        return $this->render('greet.html.twig',
            ['message' => $this->greeting->greet(
            $name." - ".$number),
            'name' => $name
    ]);
    }
}