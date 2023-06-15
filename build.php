<?php

use App\ReadMe;
use App\Glossary;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$glossary = Glossary::fromJson(file_get_contents(__DIR__ . '/terms.json'));
$template = $twig->load('glossary.html.twig');

$pathToReadMe = __DIR__ . '/README.md';
$readme = ReadMe::fromPathToReadMe($pathToReadMe);

$readme->updateGlossary($template->render([
    'glossary' => $glossary,
]));
file_put_contents($pathToReadMe, (string)$readme);