<?php
namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Factory for the portal navigation
 */
class PortalNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @{inheritdoc}
     */
    protected function getName()
    {
        return 'portal';
    }
}