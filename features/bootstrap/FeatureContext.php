<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Behat\Context\Context;
use Behat\Hook\AfterScenario;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverPlatform;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected RemoteWebDriver $driver;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        // port 4444 + /wd/hub pour une image (standalone)
        $url = 'http://selenium-server:4444/wd/hub';
        $desiredCapabilities = DesiredCapabilities::firefox();
        $desiredCapabilities->setPlatform(WebDriverPlatform::LINUX);
        $firefoxOptions = new FirefoxOptions();
        // obligatoire pour un serveur sous docker, pas de GUI !!!
        $firefoxOptions->addArguments(['-headless']);
        $desiredCapabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);
        $this->driver = RemoteWebDriver::create($url, $desiredCapabilities);
    }

    #[Given('connexion sur la page d acceuil :arg1')]
    public function homePage($arg1)
    {
        $this->driver->get($arg1);
    }

    #[When('j entre :arg1 dans le moteur')]
    public function searchSelenium($arg1)
    {
        $input = $this->driver->findElement(WebDriverBy::id('motsclefs'));
        $input->sendKeys($arg1);
    }

    #[When('j appuie sur le bouton de recherche')]
    public function clickButton()
    {
        $btnSearch = $this->driver->findElement(WebDriverBy::id('search-btn'));
        $btnSearch->click();
    }

    #[Then('les prix :arg1 sont les bons')]
    public function checkPrices($arg1)
    {
        $results = $this->driver->findElement(WebDriverBy::id('detailcursus-institutionnel'));
        $ps = $results->findElements(WebDriverBy::tagName('p'));

        Assert::assertStringContainsString(
            $arg1,
            preg_replace('/[^0-9]/', '', $ps[2]->getText()),
            'the price is not correct'
        );
    }

    #[AfterScenario]
    public function closeDriver()
    {
        $this->driver->quit();
    }
}
