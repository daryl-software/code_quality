<?php


namespace PHPStan;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class EntityManagerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return \Manager_Container::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'getManager';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
    {
        $arg = $methodCall->args[0]->value;

        if ($arg instanceof ClassConstFetch) {
            /** @var \PhpParser\Node\Name\FullyQualified $va */
            $va = $arg->class;
            return new ObjectType($va);
        }
        if ($arg instanceof \PhpParser\Node\Scalar\MagicConst\Class_) {
            return new ObjectType($scope->getClassReflection()->getName());
        }
//        var_dump($arg);
        throw new \Exception('PHPSTAN helper error');
    }
}
