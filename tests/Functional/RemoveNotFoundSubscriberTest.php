<?php

namespace Zenstruck\RedirectBundle\Tests\Functional;

use Zenstruck\RedirectBundle\Tests\Fixture\Bundle\Entity\DummyNotFound;
use Zenstruck\RedirectBundle\Tests\Fixture\Bundle\Entity\DummyRedirect;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RemoveNotFoundSubscriberTest extends FunctionalTest
{
    public function addTestData()
    {
        parent::addTestData();

        $this->em->persist(new DummyNotFound('/foo', 'http://example.com/foo'));
        $this->em->persist(new DummyNotFound('/foo', 'http://example.com/foo?bar=foo'));
        $this->em->persist(new DummyNotFound('/bar', 'http://example.com/bar'));

        $this->em->flush();
    }

    public function testDeleteNotFoundOnCreateRedirect()
    {
        $this->assertCount(3, $this->getNotFounds());

        $this->em->persist(new DummyRedirect('/foo', '/bar'));
        $this->em->flush();

        $this->assertCount(1, $this->getNotFounds());
    }

    public function testDeleteNotFoundOnUpdateRedirect()
    {
        $this->assertCount(3, $this->getNotFounds());

        $redirect = $this->getRedirect('/301-redirect');
        $redirect->setSource('/foo');
        $this->em->flush();

        $this->assertCount(1, $this->getNotFounds());
    }
}
