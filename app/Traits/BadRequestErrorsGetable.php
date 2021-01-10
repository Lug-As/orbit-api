<?php


namespace App\Traits;


use App\Resources\BadRequestResource;
use Illuminate\Support\MessageBag;

trait BadRequestErrorsGetable
{
    protected $messageBag;

    public function __construct(MessageBag $messageBag)
    {
        $this->messageBag = $messageBag;
    }

    protected function getErrorMessages()
    {
        return BadRequestResource::make($this->messageBag->messages());
    }
}
