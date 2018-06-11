<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 5/8/2018
 * Time: 11:15 PM
 */

namespace App\src\Interfaces;


interface RequestBuilder
{
    public function __construct($subject);
    public function buildRequest();
    public function getBuild();
}