<?php

namespace App\Http\Controllers;

use App\Modules\Account\Exceptions\Validation\AccountValidationException;
use App\Modules\Account\Factories\AccountTransferParameterFactory;
use App\Modules\Account\Requests\TransferAccountRequest;
use App\Modules\Account\Services\AccountProcessorService;

class AccountTransferController extends Controller
{
    public function __construct(
        private readonly AccountProcessorService $processor,
        private readonly AccountTransferParameterFactory $factory,
    ) {}

    public function __invoke(TransferAccountRequest $request)
    {
        try {
            $parameters = $this->factory->create((object) $request->validated());
            $this->processor->execute($parameters);

            return response()->json([
               'message' => 'We have successfully queued the requested transaction.',
            ]);
        } catch (AccountValidationException $t) {
            return response()->json(['error' =>  $t->getMessage()]);
        }
    }
}
