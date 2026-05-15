<?php
// src/App/Filter/BoundsFilter.php
namespace App\Filter;
use ApiPlatform\Doctrine\Orm\Filter\FilterInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

final class BoundsFilter implements FilterInterface {
    //The `apply` method is where the filtering logic happens.
    //We retrieve the parameter definition and its value from the context.
    public function apply(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void
    {
        $value = $context['parameter']?->getValue();
        
        $minLat = $value['minLat'] ?? null;
        $maxLat = $value['maxLat'] ?? null;
        $minLng = $value['minLng'] ?? null;
        $maxLng = $value['maxLng'] ?? null;

        if (!$minLat || !$maxLat || !$minLng || !$maxLng) {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere("$alias.latitude BETWEEN :minLat and :maxLat")
            ->andWhere("$alias.longitude BETWEEN :minLng and :maxLng")
            ->setParameter('minLat', (float) $minLat)
            ->setParameter('maxLat', (float) $maxLat)
            ->setParameter('minLng', (float) $minLng)
            ->setParameter('maxLng', (float) $maxLng);
    }

    public function getDescription(string $resourceClass): array
    {
        return [];
    }
}