<?php

namespace App\Http\Controllers;

use App\Modules\Account\Models\Account;
use App\Modules\Account\Resources\AccountResource;
use App\Modules\Transaction\Requests\TransactionHistoryRequest;
use App\Modules\Transaction\Resources\TransactionResource;
use App\Modules\Transaction\Services\TransactionService;

class AccountTransactionsController extends Controller
{
    public function __construct(
        readonly private TransactionService $service,
    ) {}

    public function index(TransactionHistoryRequest $request, Account $account)
    {
        $query = (object) $request->validated();
        $transactions = $this->service->getAccountTransactions($account, $query->limit ?? 100, $query->offset ?? 0);

        return response()->json([
            'account' => new AccountResource($account),
            'transactions' => TransactionResource::collection($transactions),
        ]);
    }
}
