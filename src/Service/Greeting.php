<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 14/05/2019
 * Time: 14:11
 */
namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * @var LoggerInterface
     */

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function greet(string $name): string
    {
        $this->logger->info("Greeted $name");
        return "Goed gedaan ".$name."!";
    }
}