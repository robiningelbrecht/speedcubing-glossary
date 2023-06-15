<?php

use App\ReadMe;
use App\Glossary;
use App\Term;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

// Build readme.
$pathToTerms = __DIR__ . '/terms.json';
$glossary = Glossary::fromPathToJsonFile($pathToTerms);
$template = $twig->load('glossary.html.twig');

$pathToReadMe = __DIR__ . '/README.md';
$readme = ReadMe::fromPathToReadMe($pathToReadMe);

$readme->updateGlossary($template->render([
    'glossary' => $glossary,
]));
file_put_contents($pathToReadMe, (string)$readme);

// Build HTML version.
$template = $twig->load('index.html.twig');
file_put_contents(__DIR__.'/build/index.html', $template->render([
    'glossary' => $glossary,
]));

// Sort the term.json file for convenience.
$json = json_decode(file_get_contents($pathToTerms), true);
usort($json, fn(array $a, array $b) => strtolower($a['name']) <=> strtolower($b['name']));
file_put_contents($pathToTerms, json_encode($json, JSON_PRETTY_PRINT));