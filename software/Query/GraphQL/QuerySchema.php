<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

$queryType = new ObjectType([
    'name'      =>  'Query',
    'fields'    =>  [
        'hello'         =>  Type::string()
    ]
]);
