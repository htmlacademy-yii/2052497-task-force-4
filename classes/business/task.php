<?php

namespace taskforce\business;

use taskforce\business\actions\cancel;
use taskforce\business\actions\done;
use taskforce\business\actions\reject;
use taskforce\business\actions\take;

class Task {

  const STATUS_NEW = 'new';
  const STATUS_CANCELED = 'canceled';
  const STATUS_IN_PROGRESS = 'inprogress';
  const STATUS_DONE = 'done';
  const STATUS_FAIL = 'fail';

  const ACTION_CUSTOMER_CANCEL = 'cancel';
  const ACTION_CUSTOMER_DONE = 'done';
  const ACTION_EXECUTOR_TAKE = 'take';
  const ACTION_EXECUTOR_REJECT = 'reject';
  
  private $userId = 1;
  private $customerId;
  private $executorId;
  private $status;

  public function __construct($customerId, $executorId, $status) {
    $this->customerId = $customerId;
    $this->executorId = $executorId;
    $this->status = $status;
  }

  public function getAllStatuses () {
    return [
      self::STATUS_NEW => 'новый',
      self::STATUS_CANCELED => 'Отменено',
      self::STATUS_IN_PROGRESS => 'В работе',
      self::STATUS_DONE => 'Выполнено',
      self::STATUS_FAIL => 'Провалено',
  ];
  }

  public function getAllActions () {
    return [
      self::ACTION_CUSTOMER_CANCEL => 'Отменить',
      self::ACTION_CUSTOMER_DONE => 'Выполнено',
      self::ACTION_EXECUTOR_TAKE => 'Откликнуться',
      self::ACTION_EXECUTOR_REJECT => 'Отказаться',
  ];
  }

  public function getAvailableActions() {
    if ($this->status === self::STATUS_NEW && cancel::checkAccess($this->userId, $this->customerId, $this->executorId)) {
      return new cancel;
    }
    if ($this->status === self::STATUS_NEW && take::checkAccess($this->userId, $this->customerId, $this->executorId)) {
      return new take;
    }
    if ($this->status === self::STATUS_IN_PROGRESS && done::checkAccess($this->userId, $this->customerId, $this->executorId)) {
      return new done;
    }
    if ($this->status === self::STATUS_IN_PROGRESS && reject::checkAccess($this->userId, $this->customerId, $this->executorId)) {
      return new reject;
    }
  }

  public function getNextStatus($action) {
    $nextStatus = [
      self::ACTION_CUSTOMER_CANCEL => self::STATUS_CANCELED,
      self::ACTION_CUSTOMER_DONE => self::STATUS_DONE,
      self::ACTION_EXECUTOR_REJECT => self::STATUS_FAIL,
      self::ACTION_EXECUTOR_TAKE => self::STATUS_IN_PROGRESS
    ];
    return $nextStatus[$action];    
  }  
};