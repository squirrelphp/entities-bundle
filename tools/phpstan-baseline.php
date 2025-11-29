<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\EntitiesBundle\\\\DependencyInjection\\\\Configuration\\:\\:getConfigTreeBuilder\\(\\) return type with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\TreeBuilder does not specify its types\\: T$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between \'squirrel\\.connection\\.\' and mixed results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between \'squirrel…\' and mixed results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between mixed and \'\\\\\\\\\' results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between non\\-falsy\\-string and mixed results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset 0 on mixed\\.$#',
	'identifier' => 'offsetAccess.nonOffsetAccessible',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset 1 on mixed\\.$#',
	'identifier' => 'offsetAccess.nonOffsetAccessible',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset class\\-string on mixed\\.$#',
	'identifier' => 'offsetAccess.nonOffsetAccessible',
	'count' => 2,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$directories of method Squirrel\\\\EntitiesBundle\\\\DependencyInjection\\\\SquirrelEntitiesExtension\\:\\:findAllEntityClassesInFilesystem\\(\\) expects array, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$directory of method Squirrel\\\\EntitiesBundle\\\\DependencyInjection\\\\SquirrelEntitiesExtension\\:\\:findNextFileAndReturnContentsGenerator\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$fileContents of method Squirrel\\\\Entities\\\\Generate\\\\FindClassesWithAttribute\\:\\:__invoke\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$string of function strlen expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$value of function count expects array\\|Countable, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$overrideConnectionName of method Squirrel\\\\EntitiesBundle\\\\DependencyInjection\\\\SquirrelEntitiesExtension\\:\\:createRepositoryServicesForEntity\\(\\) expects string\\|null, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#4 \\$overrideTableName of method Squirrel\\\\EntitiesBundle\\\\DependencyInjection\\\\SquirrelEntitiesExtension\\:\\:createRepositoryServicesForEntity\\(\\) expects string\\|null, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/DependencyInjection/SquirrelEntitiesExtension.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
