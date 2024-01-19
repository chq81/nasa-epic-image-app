<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withCache('var/cache/ecs')
    ->withParallel()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSets([
        SetList::PSR_12,
        SetList::COMMON,
        SetList::SYMPLIFY
    ])
    // add a single rule
    ->withRules([
        NoUnusedImportsFixer::class,
    ])

    ->withSkip([
        NotOperatorWithSuccessorSpaceFixer::class,
        NoSuperfluousPhpdocTagsFixer::class,
        PhpdocNoEmptyReturnFixer::class,
        GeneralPhpdocAnnotationRemoveFixer::class,
    ])

    // add sets - group of rules
    ->withPreparedSets(
        arrays: true,
    );
