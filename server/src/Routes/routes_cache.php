<?php return array (
  'GET' => 
  array (
    'generator/routes' => 
    array (
      0 => 'App\\Controllers\\GeneratorController',
      1 => 'checkCurrentRoutes',
    ),
    'swagger/json' => 
    array (
      0 => 'App\\Controllers\\SwaggerController',
      1 => 'json',
    ),
    'user/selectAll' => 
    array (
      0 => '\\App\\Controllers\\UserController',
      1 => 'selectAll',
    ),
    'user/select' => 
    array (
      0 => '\\App\\Controllers\\UserController',
      1 => 'select',
    ),
  ),
  'POST' => 
  array (
    'generator/generate' => 
    array (
      0 => 'App\\Controllers\\GeneratorController',
      1 => 'generate',
    ),
  ),
  'PUT' => 
  array (
  ),
  'DELETE' => 
  array (
    'user/delete' => 
    array (
      0 => '\\App\\Controllers\\UserController',
      1 => 'delete',
    ),
  ),
);