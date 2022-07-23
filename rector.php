<?php

declare(strict_types=1);

use \Rector\Config\RectorConfig;
use \Rector\Core\ValueObject\PhpVersion;
use \Rector\Set\ValueObject\LevelSetList;
use \Rector\Set\ValueObject\SetList;

/*---
//-- uncomment below to find the file
use \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use \Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

$a = new PhpDocInfo();

$b = new LongArrayToShortArrayRector();
// function refactor
// @dktodo report to https://getrector.org/demo/af199ed5-37ac-4a1c-b1cd-88e53dc97650 https://github.com/rectorphp/rector/issues/7304
// return null;

$c = new PhpDocNode();

$d = new \Rector\DeadCode\PhpDoc\TagRemover\ParamTagRemover(null);
return false;

$d = new \Rector\DeadCode\PhpDoc\TagRemover\ReturnTagRemover(null);
return false;

$d = new \Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover(null);
return;

$e = new \Rector\PHPStanStaticTypeMapper\TypeMapper\UnionTypeMapper();
//*/


return static function (RectorConfig $rectorConfig): void {

	$aroot = realpath(__DIR__);
	@define('ABSPATH', $aroot);
	@define('WPINC', "/wp-includes");
	@define('WPMU_PLUGIN_DIR', "$aroot/wp-content/mu-plugins");

	$rectorConfig->paths([
	]);

	$rectorConfig->autoloadPaths([
	]);

	$rectorConfig->bootstrapFiles([
	]);

	$rectorConfig->importNames();
	$rectorConfig->importShortClasses();
	$rectorConfig->phpstanConfig(__DIR__ . '/config/phpstan.neon');
	//$rectorConfig->phpVersion(PhpVersion::PHP_74);
	$rectorConfig->phpVersion(PhpVersion::PHP_81);
	$rectorConfig->parallel(PHP_INT_MAX, PHP_INT_MAX, PHP_INT_MAX);
	//$rectorConfig->disableParallel();

	$rectorConfig->skip([
		\Rector\Php54\Rector\Array_\LongArrayToShortArrayRector::class,
		\Rector\TypeDeclaration\PhpDocParser\NonInformativeReturnTagRemover::class,

		\Rector\DeadCode\PhpDoc\TagRemover\ParamTagRemover::class,
		\Rector\DeadCode\PhpDoc\TagRemover\ReturnTagRemover::class,
		\Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover::class,

		\Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector::class,

		\Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector::class,

		\Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector::class,
		\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector::class,
		\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedParamInRequiredAutowireRector::class,
		\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector::class,
		\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector::class,
		\Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector::class,
		\Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector::class,
		\Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector::class,
		\Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector::class,

		\Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector::class,
		\Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector::class,

		\Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector::class,
		\Rector\PHPUnit\Rector\ClassMethod\RemoveEmptyTestMethodRector::class,

		\Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector::class,
		\Rector\Php80\Rector\Identical\StrStartsWithRector::class,
		\Rector\Php80\Rector\Identical\StrEndsWithRector::class,
		\Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector::class,

		\Rector\Php80\MatchAndRefactor\StrStartsWithMatchAndRefactor\StrncmpMatchAndRefactor::class,
		\Rector\Php80\MatchAndRefactor\StrStartsWithMatchAndRefactor\StrposMatchAndRefactor::class,

		\Rector\Php80\Rector\Identical\StrEndsWithRector::class,
		\Rector\Php80\Rector\NotIdentical\StrContainsRector::class,
		\Rector\Php80\Rector\Identical\StrStartsWithRector::class,
		\Rector\Php80\Rector\NotIdentical\StrContainsRector::class,
		\Rector\Php80\ValueObject\StrStartsWith::class,
		\Rector\Php80\Rector\Identical\StrStartsWithRector::class,
		\Rector\Php80\MatchAndRefactor\StrStartsWithMatchAndRefactor::class,

		\Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector::class,
		\Rector\Php71\Rector\List_\ListToArrayDestructRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector::class,
		\Rector\Php81\Rector\Array_\FirstClassCallableRector::class,

		\Rector\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector::class,
		\Rector\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector::class,

		\Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector::class,

		\Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector::class,
	]);

	// define sets of rules
	$rectorConfig->sets([
		//LevelSetList::UP_TO_PHP_81,
		//SetList::TYPE_DECLARATION,
		//SetList::TYPE_DECLARATION_STRICT,
		//SetList::DEAD_CODE,
		//SetList::PHP_80,
		//SetList::PHP_81,
		//SetList::CODE_QUALITY,
		//SetList::CODING_STYLE,
		//SetList::UNWRAP_COMPAT,
	]);

	$rectorConfig->rules([
		\Rector\TypeDeclaration\Rector\FunctionLike\ParamTypeDeclarationRector::class,
		\Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector::class,

		\Rector\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector::class,

		\Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector::class,
		\Rector\TypeDeclaration\Rector\Property\PropertyTypeDeclarationRector::class,
		\Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector::class,
		\Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector::class,
		\Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector::class,

		\Rector\TypeDeclaration\Rector\ClassMethod\ParamAnnotationIncorrectNullableRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector::class,
		\Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector::class,

		\Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeFuncCallRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector::class,
		\Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector::class,
	]);
};
