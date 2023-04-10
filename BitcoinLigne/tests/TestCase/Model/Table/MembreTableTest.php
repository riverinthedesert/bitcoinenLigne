<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembreTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MembreTable Test Case
 */
class MembreTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MembreTable
     */
    protected $Membre;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Membre',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Membre') ? [] : ['className' => MembreTable::class];
        $this->Membre = $this->getTableLocator()->get('Membre', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Membre);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
