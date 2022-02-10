<?php

namespace Segwitz\ShopifyApp\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Segwitz\BasicShopifyAPI\BasicShopifyAPI;
use Segwitz\BasicShopifyAPI\Session;
use Segwitz\ShopifyApp\Contracts\ApiHelper as IApiHelper;
use Segwitz\ShopifyApp\Contracts\Objects\Values\AccessToken as AccessTokenValue;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopDomain as ShopDomainValue;
use Segwitz\ShopifyApp\Contracts\Objects\Values\ShopId as ShopIdValue;
use Segwitz\ShopifyApp\Objects\Values\AccessToken;
use Segwitz\ShopifyApp\Objects\Values\SessionContext;
use Segwitz\ShopifyApp\Objects\Values\ShopDomain;
use Segwitz\ShopifyApp\Objects\Values\ShopId;
use Segwitz\ShopifyApp\Storage\Models\Charge;
use Segwitz\ShopifyApp\Storage\Models\Plan;
use Segwitz\ShopifyApp\Storage\Scopes\Namespacing;
use Segwitz\ShopifyApp\Util;

/**
 * Responsible for representing a shop record.
 */
trait ShopModel
{
    use SoftDeletes;

    /**
     * The API helper instance.
     *
     * @var IApiHelper
     */
    public $apiHelper;

    /**
     * Session context used between requests.
     *
     * @var SessionContext
     */
    protected $sessionContext;

    /**
     * Boot the trait.
     *
     * Note that the method boot[TraitName] is automatically booted by Laravel.
     *
     * @return void
     */
    protected static function bootShopModel(): void
    {
        static::addGlobalScope(new Namespacing());
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ShopIdValue
    {
        return ShopId::fromNative($this->id);
    }

    /**
     * {@inheritdoc}
     */
    public function getDomain(): ShopDomainValue
    {
        return ShopDomain::fromNative($this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): AccessTokenValue
    {
        return AccessToken::fromNative($this->password);
    }

    /**
     * {@inheritdoc}
     */
    public function charges(): HasMany
    {
        return $this->hasMany(Util::getShopifyConfig('models.charge', Charge::class));
    }

    /**
     * {@inheritdoc}
     */
    public function hasCharges(): bool
    {
        return $this->charges->isNotEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Util::getShopifyConfig('models.plan', Plan::class));
    }

    /**
     * {@inheritdoc}
     */
    public function isGrandfathered(): bool
    {
        return (bool) $this->shopify_grandfathered === true;
    }

    /**
     * {@inheritdoc}
     */
    public function isFreemium(): bool
    {
        return (bool) $this->shopify_freemium === true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOfflineAccess(): bool
    {
        return ! $this->getAccessToken()->isNull() && ! empty($this->password);
    }

    /**
     * {@inheritDoc}
     */
    public function setSessionContext(SessionContext $session): void
    {
        $this->sessionContext = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function getSessionContext(): ?SessionContext
    {
        return $this->sessionContext;
    }

    /**
     * {@inheritdoc}
     */
    public function apiHelper(): IApiHelper
    {
        if ($this->apiHelper === null) {
            // Set the session
            $session = new Session(
                $this->getDomain()->toNative(),
                $this->getAccessToken()->toNative()
            );
            $this->apiHelper = resolve(IApiHelper::class)->make($session);
        }

        return $this->apiHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function api(): BasicShopifyAPI
    {
        if ($this->apiHelper === null) {
            $this->apiHelper();
        }

        return $this->apiHelper->getApi();
    }
}
