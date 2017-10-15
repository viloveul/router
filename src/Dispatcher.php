<?php

namespace Viloveul\Router;

/**
 * @email fajrulaz@gmail.com
 * @author Fajrul Akbar Zuhdi
 */

use ReflectionMethod;
use Viloveul\Core\Factory;

class Dispatcher extends Factory
{
    /**
     * @var mixed
     */
    protected $autoload = true;

    /**
     * @var mixed
     */
    protected $controllerDirectory;

    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @var string
     */
    protected $namespace = 'App\\Controllers\\';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var mixed
     */
    protected $routeCollection;

    /**
     * @param RouteCollection        $routes
     * @param $controllerDirectory
     */
    public function __construct(RouteCollection $routes, $controllerDirectory = '/')
    {
        $this->routeCollection = $routes;
        $this->controllerDirectory = realpath($controllerDirectory);
    }

    /**
     * @param  $requestUri
     * @param  $urlSuffix
     * @return mixed
     */
    public function dispatch($requestUri, $urlSuffix = null)
    {
        // Make sure the request is started using slash
        $request = '/' . ltrim($requestUri, '/');

        if (!empty($urlSuffix) && '/' != $request) {
            $len = strlen($request);
            $last = substr($request, $len - 1, 1);

            if ('/' != $last) {
                $request = preg_replace('#' . $urlSuffix . '$#i', '', $request);
            }
        }

        foreach ($this->routeCollection as $pattern => $target) {
            if (preg_match('#^' . $pattern . '$#i', $request, $matches)) {
                if (is_string($target)) {
                    $request = preg_replace('#^' . $pattern . '$#i', $target, $request);
                    continue;
                } elseif (is_object($target) && method_exists($target, '__invoke')) {
                    $param_string = implode('/', array_slice($matches, 1));
                    $this->promote(
                        function ($args = []) use ($target) {
                            return call_user_func_array($target, $args);
                        },
                        $this->segmentToArray($param_string)
                    );

                    return true;
                }
            }
        }

        return $this->validate($request);
    }

    /**
     * @return mixed
     */
    public function fetchHandler()
    {
        return $this->handler;
    }

    /**
     * @return mixed
     */
    public function fetchParams()
    {
        return $this->params;
    }

    /**
     * @param $request
     */
    protected function createSection($request)
    {
        $sections = $this->segmentToArray($request);
        $path = $this->controllerDirectory;
        $namespace = $this->namespace;

        $name = null;

        if (!empty($sections)) {
            do {
                $name = str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($sections[0]))));
                $path .= "/{$name}";

                if (is_dir($path) && !is_file("{$path}.php")) {
                    $namespace .= "{$name}\\";
                    array_shift($sections);
                }
            } while (next($sections) !== false);
        }

        if (empty($sections)) {
            $sections = is_file("{$path}/{$name}.php") ? [$name, 'index'] : ['main', 'index'];
        } elseif (!isset($sections[1])) {
            $sections[1] = 'index';
        }

        $class = $namespace . str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($sections[0]))));
        $method = 'call' . str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($sections[1]))));
        $vars = (count($sections) > 1) ? array_slice($sections, 2) : [];

        return [$class, $method, $vars];
    }

    /**
     * @param $handler
     * @param array      $params
     */
    protected function promote($handler, array $params = [])
    {
        $this->params = array_filter($params, 'trim');
        $this->handler = $handler;
    }

    /**
     * @param $string_segment
     */
    protected function segmentToArray($string_segment)
    {
        return preg_split('/\//', $string_segment, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param  $request
     * @return mixed
     */
    protected function validate($request)
    {
        list($class, $method, $vars) = $this->createSection($request);
        // Set handler if exists

        if (class_exists($class, ($this->autoload === true))) {
            $availableMethods = get_class_methods($class);
            if (in_array('__invoke', $availableMethods, true)) {
                return $this->promote(
                    function ($args = []) use ($class) {
                        return call_user_func_array(new $class(), $args);
                    },
                    array_merge(array(substr($method, 6)), $vars)
                );
            } elseif (in_array($method, $availableMethods, true)) {
                return $this->promote(
                    function ($args = []) use ($class, $method) {
                        $reflection = new ReflectionMethod($class, $method);

                        return $reflection->invokeArgs(new $class(), $args);
                    },
                    $vars
                );
            }
        }

        // Try again with 404 (if any)
        if ($this->routeCollection->has('404')) {
            // create handler from 404 route
            $e404 = $this->routeCollection->fetch('404');

            if (is_string($e404)) {
                list($eClass, $eMethod, $eVars) = $this->createSection($e404);

                if (class_exists($eClass, ($this->autoload === true))) {
                    $eAvailableMethods = get_class_methods($eClass);
                    if (in_array('__invoke', $eAvailableMethods, true)) {
                        return $this->promote(
                            function ($args = []) use ($eClass) {
                                return call_user_func_array(new $eClass(), $args);
                            },
                            preg_split('/\//', $request, -1, PREG_SPLIT_NO_EMPTY)
                        );
                    } elseif (in_array($eMethod, $eAvailableMethods, true)) {
                        return $this->promote(
                            function ($args = []) use ($eClass, $eMethod) {
                                $reflection = new ReflectionMethod($eClass, $eMethod);

                                return $reflection->invokeArgs(new $eClass(), $args);
                            },
                            $eVars
                        );
                    }
                }
            } elseif (is_object($e404) && method_exists($e404, '__invoke')) {
                return $this->promote(
                    function ($args = []) use ($e404) {
                        return call_user_func_array($e404, $args);
                    },
                    preg_split('/\//', $request, -1, PREG_SPLIT_NO_EMPTY)
                );
            }
        }

        // throw exception if 404 has no route
        throw new NoHandlerException('No Handler for request "' . $request . '"');
    }
}
