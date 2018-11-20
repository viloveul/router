<?php

namespace Viloveul\Router;

/**
 * @email fajrulaz@gmail.com
 * @author Fajrul Akbar Zuhdi
 */
use Exception;
use Viloveul\Router\Contracts\NotFoundException as INotFoundException;

class NotFoundException extends Exception implements INotFoundException
{

}
