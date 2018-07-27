<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;
$applicationPath = __DIR__ . '/..';
$pharFile = 'console.phar';
$pharFilename =__DIR__. '/../dist/' . $pharFile;

if (file_exists($pharFile)) {
    unlink($pharFile);
}

// run the composer
system('cd .. && composer install --no-dev -o');

$phar = new \Phar($pharFilename, 0, $pharFile);
$phar->setSignatureAlgorithm(\Phar::SHA1);
$phar->startBuffering();

$finder = new Finder();
$finder->notPath('compiler')
    ->notPath('dist')
    ->notPath('data')
    ->notPath('tests')
    ->notPath('Tests')
    ->notName('composer.*')
    ->name('*.php')
    ->ignoreUnreadableDirs()
    ->in($applicationPath);

$itemsFound = $finder->count();

foreach ($finder as $item) {
    $iteration = isset($iteration) ? $iteration + 1 : 1;
    $percentageDone = sprintf('%.2f', (100 / $itemsFound) * $iteration);
    if (is_dir($item)) {
        $phar->addEmptyDir($item->getRelativePathname());
        echo '[' . $percentageDone . '% done] Added directory ' . $item->getRelativePathname() . "\n";
    } else {
        $phar->addFile($item->getRealPath(), $item->getRelativePathname());
        echo '[' . $percentageDone . '% done] Added file ' . $item->getRelativePathname() . "\n";
    }
}
$content = file_get_contents($applicationPath . '/bin/console');
$content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
$phar->addFromString('bin/console', $content);
$stub = trim("
#!/usr/bin/env php
<?php
Phar::mapPhar('" . $pharFile . "');
require 'phar://" . $pharFile . "/bin/console';
__HALT_COMPILER();
");
$phar->setStub($stub);
$phar->stopBuffering();
unset($phar);

echo "output file in [dist/console.phar]\n";