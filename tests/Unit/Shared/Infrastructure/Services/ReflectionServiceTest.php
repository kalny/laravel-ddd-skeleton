<?php

namespace Tests\Unit\Shared\Infrastructure\Services;

use App\Shared\Infrastructure\Services\ReflectionService;
use Tests\TestCase;

class ReflectionServiceTest extends TestCase
{
    private ReflectionService $reflectionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reflectionService = app(ReflectionService::class);
    }

    public function testGetValue(): void
    {
        $entity = new TestEntity('test_entity');

        $value = $this->reflectionService->getValue($entity, 'value');

        $this->assertSame($entity->getValue(), $value);
    }

    public function testGetValueRecursive(): void
    {
        $entity = new SuperEntity(
            new SubEntity(
                new TestEntity('test_entity')
            )
        );

        $value = $this->reflectionService->getValue($entity, 'sub.test.value');

        $this->assertSame('test_entity', $value);
    }

    public function testSetValue(): void
    {
        $entity = new TestEntity('test_entity');

        $this->reflectionService->setValue($entity, 'value', 'edited_entity');

        $this->assertSame('edited_entity', $entity->getValue());
    }

    public function testCreateObject(): void
    {
        $entity = $this->reflectionService->createObject(TestEntity::class, [
            'value' => 'test_entity',
        ]);

        $this->assertSame('test_entity', $entity->getValue());
    }
}

class SuperEntity
{
    public function __construct(private SubEntity $sub)
    {
    }

    public function getSub(): SubEntity
    {
        return $this->sub;
    }
}

class SubEntity
{
    public function __construct(private TestEntity $test)
    {
    }

    public function getTest(): TestEntity
    {
        return $this->test;
    }
}

class TestEntity
{
    public function __construct(private string $value)
    {
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
