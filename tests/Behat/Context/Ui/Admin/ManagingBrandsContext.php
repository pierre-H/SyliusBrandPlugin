<?php

declare(strict_types=1);

namespace Tests\Loevgaard\SyliusBrandPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Loevgaard\SyliusBrandPlugin\Entity\BrandInterface;
use Tests\Loevgaard\SyliusBrandPlugin\Behat\Page\Admin\Brand\CreateBrandPage;
use Tests\Loevgaard\SyliusBrandPlugin\Behat\Page\Admin\Brand\IndexBrandPage;
use Tests\Loevgaard\SyliusBrandPlugin\Behat\Page\Admin\Brand\UpdateBrandPage;
use Webmozart\Assert\Assert;

final class ManagingBrandsContext implements Context
{
    /**
     * @var IndexBrandPage
     */
    private $indexBrandPage;

    /**
     * @var CreateBrandPage
     */
    private $createBrandPage;

    /**
     * @var UpdateBrandPage
     */
    private $updateBrandPage;

    public function __construct(IndexBrandPage $indexBrandPage, CreateBrandPage $createBrandPage, UpdateBrandPage $updateBrandPage)
    {
        $this->indexBrandPage = $indexBrandPage;
        $this->createBrandPage = $createBrandPage;
        $this->updateBrandPage = $updateBrandPage;
    }

    /**
     * @Given I want to create a new brand
     */
    public function iWantToCreateANewBrand()
    {
        $this->createBrandPage->open();
    }

    /**
     * @When I name it :name
     */
    public function iNameIt($name)
    {
        $this->createBrandPage->nameIt($name);
    }

    /**
     * @When I set its slug to :slug
     */
    public function iSetItsSlugTo($slug)
    {
        $this->createBrandPage->specifySlug($slug);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createBrandPage->create();
    }

    /**
     * @Then the brand :brand should appear in the store
     */
    public function theBrandShouldAppearInTheStore($brand)
    {
        $this->indexBrandPage->open();

        Assert::true(
            $this->indexBrandPage->isSingleResourceOnPage(['name' => $brand]),
            sprintf('Brand %s should exist but it does not', $brand)
        );
    }

    /**
     * @Given I want to modify the :brand brand
     */
    public function iWantToModifyTheBrand(BrandInterface $brand)
    {
        $this->updateBrandPage->open([
            'id' => $brand->getId(),
        ]);
    }

    /**
     * @When I rename it to :name
     */
    public function iRenameItTo($name)
    {
        $this->updateBrandPage->nameIt($name);
    }

    /**
     * @When I save my changes
     */
    public function iSaveMyChanges()
    {
        $this->updateBrandPage->saveChanges();
    }

    /**
     * @Then this brand name should be :name
     */
    public function thisBrandNameShouldBe($name)
    {
        Assert::eq($name, $this->updateBrandPage->getName());
    }
}
