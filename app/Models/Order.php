<?php

namespace App\Models;

use App\Constant\Result;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 *
 * @property int    id
 * @property string acquirer
 * @property string acquirer_id
 * @property int    amount
 * @property string currency
 * @property int    status
 *
 * @package App\Models
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'acquirer',
        'acquirer_id',
        'amount',
        'currency',
        'status'
    ];

    const STATUS_CREATED = 0;
    const STATUS_OK = 1;
    const STATUS_FAIL = 2;

    const CURRENCY_USD = 'USD';
    const CURRENCY_GPB = 'GPB';

    public function setDataFromAcquirer(array $acquirer)
    {
        $orderStatus = self::STATUS_FAIL;

        if (Result::CODE_OK == $acquirer['code']) {
            $orderStatus = self::STATUS_OK;
        }

        $this->status = $orderStatus;

        if (!empty($acquirer['data']['id'])) {
            $this->acquirer_id = $acquirer['data']['id'];
        }
    }
}
