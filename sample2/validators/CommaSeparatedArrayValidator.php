<?php
namespace sample2\validators;

use sample2\exceptions\InvalidCallException;
use Yii;
use yii\validators\Validator;

/**
 * Class CommaSeparatedArrayValidator
 * @package sample2\validators
 */
class CommaSeparatedArrayValidator extends Validator
{
    /**
     * @inheritdoc
     */
    public $skipOnEmpty = false;

    public $itemTypeChecker = null;

    private const ALLOWED_TYPES = [
        'is_numeric',
        'is_string',
        'is_boolean',
    ];

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        if (!is_array($model->$attribute)) {
            $model->$attribute = explode(',', $model->$attribute);
        }

        if ($this->itemTypeChecker !== null) {
            if (!in_array($this->itemTypeChecker, self::ALLOWED_TYPES)) {
                throw new InvalidCallException('Invalid usage of ' . CommaSeparatedArrayValidator::class);
            }
            $model->$attribute = array_filter($model->$attribute, $this->itemTypeChecker);
        }

        if ($this->skipOnEmpty === false && empty($model->$attribute)) {
            $this->addError($model, $attribute, Yii::t('app', '{attribute} must be not empty.', ['attribute' => $attribute]));
        }
    }
}
