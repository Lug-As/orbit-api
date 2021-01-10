<?php


namespace App\Services\Api\V1\Offers;


use App\Models\Offer;
use App\Services\Api\V1\Offers\Resources\OfferResource;
use App\Services\Api\V1\Offers\Resources\OffersResource;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class OfferService
{
    use CanWrapInData;

    public function searchOffers()
    {
        return OffersResource::make($this->requestBuilder()->paginate(10));
    }

    public function findOffer(int $id)
    {
        return $this->wrapInData(OfferResource::make($this->requestBuilder()->findOrFail($id)));
    }

    public function storeOffer(array $data)
    {
        $data['user_id'] = Auth::id();
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
            $this->requestBuilder()
                ->where('account_id', $accountId)
                ->paginate(10)
        );
    }

    public function searchUserOffers()
    {
        $userId = Auth::id();
        return OffersResource::make(
            $this->requestBuilder()
                ->where('user_id', $userId)
                ->paginate(10)
        );
    }

    /**
     * Return builder with Request model's relations
     *
     * @return Builder
     */
    protected function requestBuilder(): Builder
    {
        return Offer::with('user', 'account');
    }

    /**
     * @param int $id
     * @return Offer|null
     */
    public function getOfferOnlyUserId(int $id): ?Offer
    {
        return Offer::findOrFail($id, ['user_id']);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getOfferOnlyUserIdAndAccount(int $id)
    {
        return Offer::with('account.user_id')->findOrFail($id, ['user_id']);
    }
}
