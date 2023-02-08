<?php

namespace App\Factory;

use App\Entity\ToDo;
use App\Repository\ToDoRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ToDo>
 *
 * @method        ToDo|Proxy create(array|callable $attributes = [])
 * @method static ToDo|Proxy createOne(array $attributes = [])
 * @method static ToDo|Proxy find(object|array|mixed $criteria)
 * @method static ToDo|Proxy findOrCreate(array $attributes)
 * @method static ToDo|Proxy first(string $sortedField = 'id')
 * @method static ToDo|Proxy last(string $sortedField = 'id')
 * @method static ToDo|Proxy random(array $attributes = [])
 * @method static ToDo|Proxy randomOrCreate(array $attributes = [])
 * @method static ToDoRepository|RepositoryProxy repository()
 * @method static ToDo[]|Proxy[] all()
 * @method static ToDo[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ToDo[]|Proxy[] createSequence(array|callable $sequence)
 * @method static ToDo[]|Proxy[] findBy(array $attributes)
 * @method static ToDo[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ToDo[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ToDoFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'pinned' => self::faker()->boolean(),
            'title' => self::faker()->words(5,true),
            'description' => self::faker()->paragraph(),
            'deadline' => self::faker()->dateTimeBetween('-1 week', '+1 week')
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ToDo $toDo): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ToDo::class;
    }
}
