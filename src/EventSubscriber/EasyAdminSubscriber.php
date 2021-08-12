<?php

namespace App\EventSubscriber;

use App\Entity\Formation;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setFormationSlugAndEtat']
        ];
    }

    public function setFormationSlugAndEtat(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Formation)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getNomFormation());
        $entity->setSlug($slug);
        $entity->setEtat('programmé');
    }
}
