<?php


namespace App\Services\Api\V1\Offers;


use App\Models\Account;
use App\Models\Offer;
use App\Services\Api\V1\Offers\Resources\OfferResource;
use App\Services\Api\V1\Offers\Resources\OffersResource;
use App\Traits\BadRequestErrorsGetable;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class OfferService
{
    use CanWrapInData, BadRequestErrorsGetable;

    public function searchOffers()
    {
        return OffersResource::make($this->queryBuilder()->paginate(10));
    }

    public function findOffer(int $id)
    {
        return $this->wrapInData(OfferResource::make($this->queryBuilder()->findOrFail($id)));
    }

    public function storeOffer(array $data)
    {
        $data['user_id'] = Auth::id();
        if (!$this->checkAccount($data['account_id'], $data['user_id'])) {
            return $this->getErrorMessages();
        }
        $offer = Offer::create($data);
        return $this->wrapInData(OfferResource::make($offer));
    }

    public function updateOffer(array $data, int $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->update($data);
        return $this->wrapInData(OfferResource::make($offer));
    }

    public function destroyOffer(int $id)
    {
        return Offer::whereId($id)->delete();
    }

    public function searchOffersByAccountId(int $accountId)
    {
        return OffersResource::make(
            $this->queryBuilder()
                ->where('account_id', $accountId)
                ->paginate(10)
        );
    }

    public function searchUserOffers()
    {
        $userId = Auth::id();
        return OffersResource::make(
            $this->queryBuilder()
                ->where('user_id', $userId)
                ->paginate(10)
        );
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Offer::with('user', 'account');
    }

    /**
     * @param int $id
     * @return Offer|null
     */
    public function getOfferOnlyUserId($id): ?Offer
    {
        return Offer::findOrFail($id, ['user_id']);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getOfferOnlyUserIdAndAccount($id)
    {
        return Offer::with('account.user_id')->findOrFail($id, ['user_id']);
    }

    /**
     * @param int $accountId
     * @param int $userId
     * @return bool
     */
    protected function checkAccount(int $accountId, int $userId): bool
    {
        $account = Account::find($accountId, ['user_id']);
        $checked = $account->user_id !== $userId;
        if (!$checked) {
            $this->messageBag->add('account', 'You cannot leave offer for your accounts.');
        }
        return $checked;
    }
}
