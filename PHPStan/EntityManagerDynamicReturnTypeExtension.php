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
        return in_array($methodReflection->getName(), ['getManager', 'getCache'], true);
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
        if ($arg instanceof \PhpParser\Node\Expr\Variable) {
            throw new \Exception('Cannot analyze variable passed to '.$this->getClass().'::' . $methodReflection->getName() . '($'.$arg->name.')');
        }
        if ($arg instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            throw new \Exception('Cannot analyze variable passed to '.$this->getClass().'::' . $methodReflection->getName() . '()');
        }
        var_dump($arg);
        throw new \Exception('PHPSTAN helper error ');
    }
}
