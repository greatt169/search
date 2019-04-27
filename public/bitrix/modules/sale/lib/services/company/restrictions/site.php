<?php
namespace Bitrix\Sale\Services\Company\Restrictions;

use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaymentCollection;
use Bitrix\Sale\Services;
use Bitrix\Sale\Internals;
use Bitrix\Sale\Shipment;
use Bitrix\Sale\ShipmentCollection;

/**
 * Class Site
 * @package Bitrix\Sale\Services\Company\Restrictions
 */
class Site extends Services\PaySystem\Restrictions\Site
{
	protected static function extractParams(Internals\Entity $entity)
	{
		if (!($entity instanceof Payment) && !($entity instanceof Shipment) && !($entity instanceof Order))
			return false;


		if ($entity instanceof Order)
		{
			$order = $entity;
		}
		else
		{
			/** @var PaymentCollection|ShipmentCollection $collection */
			$collection = $entity->getCollection();

			/** @var Order $order */
			$order = $collection->getOrder();
		}

		return $order->getSiteId();
	}
}