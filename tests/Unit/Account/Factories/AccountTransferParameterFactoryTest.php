<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Account\Factories;

use App\Modules\Account\Exceptions\Validation\UnavailableAccountException;
use App\Modules\Account\Factories\AccountTransferParameterFactory;
use App\Modules\Account\Models\Account;
use App\Modules\Account\Repositories\AccountRepository;
use App\Modules\Account\Structures\AccountTransferParameters;
use App\Modules\Currency\Enums\Currency;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\ModelMockHelper;

class AccountTransferParameterFactoryTest extends TestCase
{
    private Mockery | AccountRepository $mockRepository;

    private AccountTransferParameterFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new AccountTransferParameterFactory(
            $this->mockRepository = Mockery::mock(AccountRepository::class),
        );
    }

    public function testCreate_withInputData_createsParameters(): void
    {
        $input = (object) ['from_account_id' => 1, 'to_account_id' => 2, 'amount' => '10.50', 'currency' => 'USD'];

        $from = ModelMockHelper::mock(Account::class, ['id' => $input->from_account_id]);
        $to = ModelMockHelper::mock(Account::class, ['id' => $input->to_account_id]);

        $this->mockRepository->expects('getById')->twice()->andReturn($from, $to);

        $result = $this->factory->create($input);
        $expected = new AccountTransferParameters($from, $to, (float) $input->amount, Currency::USD);

        $this->assertEquals($expected, $result);
    }

    public function testCreate_repositoryReturnsNull_throwsException(): void
    {
        $input = (object) ['from_account_id' => 1, 'to_account_id' => 2, 'amount' => 10.50, 'currency' => 'USD'];

        $this->mockRepository->expects('getById')->andReturnNull();

        $this->expectException(UnavailableAccountException::class);

        $this->factory->create($input);
    }
}