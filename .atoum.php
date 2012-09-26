<?php
use \mageekguy\atoum;

define('COVERAGE_TITLE', 'MetaYaml');
define('COVERAGE_DIRECTORY', './web/coverage');
define('COVERAGE_WEB_PATH', 'http://localhost/meta-yaml/coverage');

if(false === is_dir(COVERAGE_DIRECTORY))
{
    mkdir(COVERAGE_DIRECTORY, 0777, true);
}

$script->addTestAllDirectory(__DIR__ . '/test');

$stdOutWriter = new atoum\writers\std\out();

$coverageField = new atoum\report\fields\runner\coverage\html(COVERAGE_TITLE, COVERAGE_DIRECTORY);
$coverageField->setRootUrl(COVERAGE_WEB_PATH);

$cliReport = new atoum\reports\realtime\cli();
$cliReport
    ->addWriter($stdOutWriter)
    ->addField($coverageField, array(atoum\runner::runStop))
;

$runner->setBootstrapFile('test/bootstrap.php');

$runner->addReport($cliReport);
